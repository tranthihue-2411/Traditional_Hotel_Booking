<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_reference',
        'user_id',
        'hotel_id',
        'room_id',
        'check_in_date',
        'check_out_date',
        'number_of_guests',
        'number_of_nights',
        'room_price_per_night',
        'subtotal',
        'service_fee',
        'discount',
        'total_amount',
        'guest_name',
        'guest_email',
        'guest_phone',
        'special_requests',
        'status',
        'cancelled_at',
        'cancellation_reason'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }
}