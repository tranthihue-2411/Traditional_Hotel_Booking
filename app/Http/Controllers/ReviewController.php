<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        return redirect()->back()
            ->with('success', 'Review submitted successfully.');
    }
}