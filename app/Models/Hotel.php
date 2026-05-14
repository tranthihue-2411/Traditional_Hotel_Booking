<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Hotel extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'address',
        'city',
        'province',
        'country',
        'phone',
        'email',
        'website',
        'latitude',
        'longitude',
        'rating',
        'review_count',
        'main_image',
        'is_active'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function hotelAmenities()
    {
        return $this->hasMany(HotelAmenity::class);
    }
}