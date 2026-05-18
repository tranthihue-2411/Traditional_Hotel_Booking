<?php
namespace Database\Seeders;

use App\Models\Hotel;
use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $hotels = Hotel::all();

        $roomConfigs = [
            // [standard_price, deluxe_price, suite_price]
            [500000,  900000,  2000000],
            [650000,  1200000, 2800000],
            [400000,  750000,  1500000],
            [700000,  1400000, 3500000],
            [550000,  1000000, 2200000],
            [900000,  1800000, 4500000],
        ];

        foreach ($hotels as $index => $hotel) {
            $prices = $roomConfigs[$index % count($roomConfigs)];

            Room::create([
                'hotel_id'        => $hotel->id,
                'name'            => 'Phòng Standard',
                'description'     => 'Phòng tiêu chuẩn thoải mái với đầy đủ tiện nghi cơ bản.',
                'room_type'       => 'Single',
                'max_guests'      => 2,
                'size_sqm'        => 25.00,
                'bed_type'        => 'Giường đơn',
                'price_per_night' => $prices[0],
                'total_rooms'     => 10,
                'is_active'       => true,
            ]);

            Room::create([
                'hotel_id'        => $hotel->id,
                'name'            => 'Phòng Deluxe',
                'description'     => 'Phòng cao cấp rộng rãi với view đẹp và tiện nghi sang trọng.',
                'room_type'       => 'Double',
                'max_guests'      => 3,
                'size_sqm'        => 40.00,
                'bed_type'        => 'Giường đôi',
                'price_per_night' => $prices[1],
                'total_rooms'     => 5,
                'is_active'       => true,
            ]);

            Room::create([
                'hotel_id'        => $hotel->id,
                'name'            => 'Phòng Suite',
                'description'     => 'Suite hạng sang với phòng khách riêng và tầm nhìn toàn cảnh.',
                'room_type'       => 'Suite',
                'max_guests'      => 4,
                'size_sqm'        => 80.00,
                'bed_type'        => 'Giường King',
                'price_per_night' => $prices[2],
                'total_rooms'     => 2,
                'is_active'       => true,
            ]);
        }
    }
}