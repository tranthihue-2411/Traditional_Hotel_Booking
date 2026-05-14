<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ReviewController;

use App\Http\Controllers\Admin\HotelController as AdminHotelController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\UserController as AdminUserController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/hotels', [HotelController::class, 'index'])
    ->name('hotels.index');

Route::get('/hotels/{id}', [HotelController::class, 'show'])
    ->name('hotels.show');

/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | Booking
    |--------------------------------------------------------------------------
    */

    Route::get('/bookings', [BookingController::class, 'index'])
        ->name('bookings.index');

    Route::post('/bookings', [BookingController::class, 'store'])
        ->name('bookings.store');

    Route::get('/bookings/{id}', [BookingController::class, 'show'])
        ->name('bookings.show');

    /*
    |--------------------------------------------------------------------------
    | Reviews
    |--------------------------------------------------------------------------
    */

    Route::post('/reviews', [ReviewController::class, 'store'])
        ->name('reviews.store');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->prefix('admin')->group(function () {

    Route::get('/hotels', [AdminHotelController::class, 'index'])
        ->name('admin.hotels.index');

    Route::get('/bookings', [AdminBookingController::class, 'index'])
        ->name('admin.bookings.index');

    Route::get('/users', [AdminUserController::class, 'index'])
        ->name('admin.users.index');
});

require __DIR__.'/auth.php';