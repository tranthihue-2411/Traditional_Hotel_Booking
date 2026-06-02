<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingDetail extends Model
{
    protected $fillable = [
        'booking_id', 'room_id', 'room_name', 'room_type',
        'price_per_night', 'quantity', 'number_of_nights', 'subtotal',
    ];

    protected $casts = [
        'price_per_night' => 'decimal:2',
        'subtotal'        => 'decimal:2',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class)->withTrashed();
    }
}