<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class HotelController extends Controller
{
    public function index()
    {
        return view('admin.hotels.index');
    }
}