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

        foreach ($hotels as $hotel) {
            Room::create([
                'hotel_id'        => $hotel->id,
                'name'            => 'Phòng Standard',
                'description'     => 'Phòng tiêu chuẩn thoải mái với đầy đủ tiện nghi cơ bản.',
                'room_type'       => 'Single',
                'max_guests'      => 2,
                'size_sqm'        => 25.00,
                'bed_type'        => 'Giường đơn',
                'price_per_night' => 600000,
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
                'price_per_night' => 1200000,
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
                'price_per_night' => 2500000,
                'total_rooms'     => 2,
                'is_active'       => true,
            ]);
        }
    }
}