<?php

namespace App\Http\Controllers;

use App\Models\Hotel;

class HomeController extends Controller
{
    public function index()
    {
        $hotels = Hotel::latest()->take(6)->get();

        return view('hotels.home', compact('hotels'));
    }
}