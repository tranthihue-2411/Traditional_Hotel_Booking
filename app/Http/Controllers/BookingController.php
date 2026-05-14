<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('bookings.index', compact('bookings'));
    }

    public function show($id)
    {
        $booking = Booking::findOrFail($id);

        return view('bookings.show', compact('booking'));
    }

    public function store(Request $request)
    {
        return redirect()->back()
            ->with('success', 'Booking created successfully.');
    }
}