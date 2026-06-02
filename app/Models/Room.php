<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'hotel_id', 'name', 'description', 'room_type', 'max_guests',
        'size_sqm', 'bed_type', 'price_per_night', 'total_rooms',
        'amenities', 'image', 'is_active',
    ];

    protected $casts = [
        'amenities'       => 'array',
        'is_active'       => 'boolean',
        'price_per_night' => 'decimal:2',
    ];

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function bookingDetails(): HasMany
    {
        return $this->hasMany(BookingDetail::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function isAvailable(string $checkIn, string $checkOut): bool
    {
        return $this->availableCount($checkIn, $checkOut) > 0;
    }

    public function availableCount(string $checkIn, string $checkOut): int
    {
        $bookedCount = BookingDetail::where('room_id', $this->id)
            ->whereHas('booking', function ($q) use ($checkIn, $checkOut) {
                $q->whereNotIn('status', ['cancelled', 'completed'])
                  ->where(function ($q2) use ($checkIn, $checkOut) {
                      $q2->whereBetween('check_in_date', [$checkIn, $checkOut])
                         ->orWhereBetween('check_out_date', [$checkIn, $checkOut])
                         ->orWhere(function ($q3) use ($checkIn, $checkOut) {
                             $q3->where('check_in_date', '<=', $checkIn)
                                ->where('check_out_date', '>=', $checkOut);
                         });
                  });
            })
            ->sum('quantity');

        return max(0, $this->total_rooms - $bookedCount);
    }
}