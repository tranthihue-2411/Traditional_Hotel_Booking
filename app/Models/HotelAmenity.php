<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HotelAmenity extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'amenity_id'
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function amenity()
    {
        return $this->belongsTo(Amenity::class);
    }
}