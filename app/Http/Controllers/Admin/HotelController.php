<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Amenity;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    public function index()
    {
        $hotels = Hotel::withTrashed()->with('user')->latest()->paginate(15);
        return view('admin.hotels.index', compact('hotels'));
    }

    public function create()
    {
        $amenities = Amenity::all();
        return view('admin.hotels.create', compact('amenities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'address'     => 'required|string',
            'city'        => 'required|string',
            'province'    => 'required|string',
            'country'     => 'nullable|string',
            'phone'       => 'nullable|string',
            'email'       => 'nullable|email',
            'website'     => 'nullable|url',
            'latitude'    => 'nullable|numeric',
            'longitude'   => 'nullable|numeric',
            'main_image'  => 'nullable|url',
            'is_active'   => 'boolean',
            'amenities'   => 'nullable|array',
        ]);

        $hotel = Hotel::create($validated);

        if ($request->has('amenities')) {
            $hotel->amenities()->sync($request->amenities);
        }

        return redirect()->route('admin.hotels.index')->with('success', 'Đã tạo khách sạn thành công!');
    }

    public function show(Hotel $hotel)
    {
        $hotel->load(['amenities', 'reviews', 'bookings']);
        $rooms = $hotel->rooms()->withTrashed()->get();
        return view('admin.hotels.show', compact('hotel', 'rooms'));
    }

    public function edit(Hotel $hotel)
    {
        $amenities = Amenity::all();
        $hotel->load('amenities');
        return view('admin.hotels.edit', compact('hotel', 'amenities'));
    }

    public function update(Request $request, Hotel $hotel)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'address'     => 'required|string',
            'city'        => 'required|string',
            'province'    => 'required|string',
            'country'     => 'nullable|string',
            'phone'       => 'nullable|string',
            'email'       => 'nullable|email',
            'website'     => 'nullable|url',
            'latitude'    => 'nullable|numeric',
            'longitude'   => 'nullable|numeric',
            'main_image'  => 'nullable|url',
            'is_active'   => 'boolean',
            'amenities'   => 'nullable|array',
        ]);

        $hotel->update($validated);

        if ($request->has('amenities')) {
            $hotel->amenities()->sync($request->amenities);
        } else {
            $hotel->amenities()->detach();
        }

        return redirect()->route('admin.hotels.index')->with('success', 'Đã cập nhật khách sạn thành công!');
    }

    public function destroy(Hotel $hotel)
    {
        $hotel->delete();
        return redirect()->route('admin.hotels.index')->with('success', 'Đã ẩn khách sạn thành công!');
    }

    public function restore(int $id)
    {
        Hotel::withTrashed()->findOrFail($id)->restore();
        return redirect()->route('admin.hotels.index')->with('success', 'Đã khôi phục khách sạn thành công!');
    }
}