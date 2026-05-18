<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $fillable = [
        'user_id', 'hotel_id', 'booking_id',
        'rating', 'comment', 'is_verified', 'is_published',
    ];

    protected $casts = [
        'is_verified'  => 'boolean',
        'is_published' => 'boolean',
    ];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function hotel(): BelongsTo { return $this->belongsTo(Hotel::class); }
    public function booking(): BelongsTo { return $this->belongsTo(Booking::class); }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }
}