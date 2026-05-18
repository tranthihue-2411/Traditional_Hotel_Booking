<?php
namespace Database\Seeders;

use App\Models\Amenity;
use Illuminate\Database\Seeder;

class AmenitySeeder extends Seeder
{
    public function run(): void
    {
        $amenities = [
            ['name' => 'WiFi miễn phí',       'icon' => 'wifi',         'category' => 'Basic'],
            ['name' => 'Hồ bơi',              'icon' => 'pool',         'category' => 'Luxury'],
            ['name' => 'Bãi đỗ xe',           'icon' => 'parking',      'category' => 'Basic'],
            ['name' => 'Nhà hàng',            'icon' => 'restaurant',   'category' => 'Service'],
            ['name' => 'Spa & Wellness',      'icon' => 'spa',          'category' => 'Luxury'],
            ['name' => 'Phòng gym',           'icon' => 'gym',          'category' => 'Sport'],
            ['name' => 'Điều hòa',            'icon' => 'ac',           'category' => 'Basic'],
            ['name' => 'Bar & Lounge',        'icon' => 'bar',          'category' => 'Service'],
            ['name' => 'Dịch vụ phòng 24/7',  'icon' => 'room-service', 'category' => 'Service'],
            ['name' => 'Bãi biển riêng',      'icon' => 'beach',        'category' => 'Luxury'],
        ];

        foreach ($amenities as $amenity) {
            Amenity::create($amenity);
        }
    }
}