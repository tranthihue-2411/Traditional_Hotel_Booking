<?php

namespace App\Http\Controllers;

use App\Models\Hotel;

class HotelController extends Controller
{
    public function index()
    {
        $hotels = Hotel::paginate(9);

        return view('hotels.index', compact('hotels'));
    }

    public function show($id)
    {
        $hotel = Hotel::with([
            'rooms',
            'reviews'
        ])->findOrFail($id);

        return view('hotels.show', compact('hotel'));
    }
}