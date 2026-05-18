<?php
namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Hotel;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'rating'   => 'required|integer|min:1|max:5',
            'comment'  => 'nullable|string|max:1000',
        ]);

        $booking = Booking::where('user_id', Auth::id())
            ->where('hotel_id', $validated['hotel_id'])
            ->where('status', 'completed')
            ->first();

        if (!$booking) {
            return back()->withErrors(['review' => 'Bạn cần hoàn thành chuyến đi trước khi đánh giá.']);
        }

        $existing = Review::where('user_id', Auth::id())
            ->where('hotel_id', $validated['hotel_id'])
            ->first();

        if ($existing) {
            return back()->withErrors(['review' => 'Bạn đã đánh giá khách sạn này rồi.']);
        }

        Review::create([
            'user_id'      => Auth::id(),
            'hotel_id'     => $validated['hotel_id'],
            'booking_id'   => $booking->id,
            'rating'       => $validated['rating'],
            'comment'      => $validated['comment'],
            'is_verified'  => true,
            'is_published' => true,
        ]);

        Hotel::find($validated['hotel_id'])->updateRating();

        return back()->with('success', 'Cảm ơn bạn đã đánh giá khách sạn!');
    }
}