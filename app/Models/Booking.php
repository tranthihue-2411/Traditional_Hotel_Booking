<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    protected $fillable = [
        'booking_reference', 'user_id', 'hotel_id',
        'check_in_date', 'check_out_date', 'number_of_guests',
        'subtotal', 'taxes', 'service_fee', 'discount', 'total_amount',
        'guest_name', 'guest_email', 'guest_phone', 'special_requests',
        'status', 'cancelled_at', 'cancellation_reason',
        'is_paid', 'paid_at', 'payment_method', 'payment_deadline', 'refund_status',
    ];

    protected $casts = [
        'check_in_date'    => 'date',
        'check_out_date'   => 'date',
        'cancelled_at'     => 'datetime',
        'total_amount'     => 'decimal:2',
        'is_paid'          => 'boolean',
        'paid_at'          => 'datetime',
        'payment_deadline' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class)->withTrashed();
    }

    public function details(): HasMany
    {
        return $this->hasMany(BookingDetail::class);
    }

    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }

    public function cancel(?string $reason = null): void
    {
        $this->update([
            'status'              => 'cancelled',
            'cancelled_at'        => now(),
            'cancellation_reason' => $reason,
        ]);
    }

    // Helper: lấy số đêm từ details
    public function getNumberOfNightsAttribute(): int
    {
        return $this->details->first()?->number_of_nights ?? 0;
    }

    // Helper: lấy tên phòng đầu tiên (tương thích view cũ)
    public function getRoomNameAttribute(): string
    {
        return $this->details->map(fn($d) => $d->room_name)->join(', ');
    }
}