<?php
namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\Amenity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Room;

class HotelController extends Controller
{
    public function index()
    {
        $hotels = Hotel::active()
            ->with('rooms')
            ->orderBy('rating', 'desc')
            ->take(6)
            ->get();

        return view('hotels.index', compact('hotels'));
    }

    public function search(Request $request)
    {
        $query = Hotel::active()->with('rooms', 'amenities');

        if ($request->filled('location')) {
            $location = $request->location;
            $query->where(function ($q) use ($location) {
                $q->where('city', 'like', "%{$location}%")
                  ->orWhere('province', 'like', "%{$location}%")
                  ->orWhere('address', 'like', "%{$location}%");
            });
        }

        if ($request->filled('min_price') || $request->filled('max_price')) {
            $minPrice = $request->min_price ?? 0;
            $maxPrice = $request->max_price ?? 9999999;
            $query->whereHas('rooms', function ($q) use ($minPrice, $maxPrice) {
                $q->whereBetween('price_per_night', [$minPrice, $maxPrice]);
            });
        }

        if ($request->filled('rating')) {
            $ratings = is_array($request->rating) ? $request->rating : [$request->rating];
            $query->whereIn(DB::raw('FLOOR(rating)'), $ratings);
        }

        if ($request->filled('amenities')) {
            $amenities = is_array($request->amenities) ? $request->amenities : [$request->amenities];
            $query->whereHas('amenities', function ($q) use ($amenities) {
                $q->whereIn('amenities.id', $amenities);
            });
        }

        $sortBy = $request->get('sort', 'rating-desc');

        switch ($sortBy) {
            case 'price-asc':
                $query->orderByRaw('(SELECT MIN(price_per_night) FROM rooms WHERE rooms.hotel_id = hotels.id AND rooms.is_active = 1) ASC');
                break;
            case 'price-desc':
                $query->orderByRaw('(SELECT MIN(price_per_night) FROM rooms WHERE rooms.hotel_id = hotels.id AND rooms.is_active = 1) DESC');
                break;
            case 'rating-desc':
            default:
                $query->orderBy('hotels.rating', 'desc');
                break;
        }

        $hotels    = $query->paginate(12);
        $amenities = Amenity::all();

        return view('hotels.search', compact('hotels', 'amenities'));
    }

    public function show(Hotel $hotel)
    {
        $hotel->load([
            'rooms'    => fn($q) => $q->active(),
            'amenities',
            'reviews'  => fn($q) => $q->published()->latest()->take(10),
        ]);

        $checkIn  = request('checkin',  now()->addDay()->format('Y-m-d'));
        $checkOut = request('checkout', now()->addDays(2)->format('Y-m-d'));

        $availableRooms = $hotel->rooms->filter(
            fn($room) => $room->isAvailable($checkIn, $checkOut)
        );

        return view('hotels.detail', compact('hotel', 'availableRooms', 'checkIn', 'checkOut'));
    }

    public function roomAvailableCount(Room $room, Request $request)
    {
        $checkIn  = $request->checkin;
        $checkOut = $request->checkout;

        if (!$checkIn || !$checkOut) {
            return response()->json(['count' => $room->total_rooms]);
        }

        return response()->json([
            'count' => $room->availableCount($checkIn, $checkOut)
        ]);
    }
}