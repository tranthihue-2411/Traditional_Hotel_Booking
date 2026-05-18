<?php
namespace Database\Seeders;

use App\Models\Amenity;
use App\Models\Hotel;
use App\Models\User;
use Illuminate\Database\Seeder;

class HotelSeeder extends Seeder
{
    public function run(): void
    {
        $admin     = User::where('is_admin', true)->first();
        $amenities = Amenity::pluck('id')->toArray();

        $hotels = [
            [
                'name'        => 'Grand Hotel Hanoi',
                'description' => 'Khách sạn 5 sao sang trọng tại trung tâm Hà Nội với tầm nhìn ra Hồ Hoàn Kiếm.',
                'address'     => '123 Đinh Tiên Hoàng, Hoàn Kiếm',
                'city'        => 'Hà Nội',
                'province'    => 'Hà Nội',
                'phone'       => '024-3825-1234',
                'email'       => 'info@grandhotelhanoi.com',
                'rating'      => 4.50,
            ],
            [
                'name'        => 'Saigon Riverside Hotel',
                'description' => 'Khách sạn hiện đại bên bờ sông Sài Gòn, trung tâm TP.HCM.',
                'address'     => '456 Bến Vân Đồn, Quận 4',
                'city'        => 'TP. Hồ Chí Minh',
                'province'    => 'TP. Hồ Chí Minh',
                'phone'       => '028-3823-5678',
                'email'       => 'info@saigonriverside.com',
                'rating'      => 4.30,
            ],
            [
                'name'        => 'Da Nang Beach Resort',
                'description' => 'Khu nghỉ dưỡng ven biển Mỹ Khê, bãi biển đẹp nhất miền Trung.',
                'address'     => '789 Võ Nguyên Giáp, Sơn Trà',
                'city'        => 'Đà Nẵng',
                'province'    => 'Đà Nẵng',
                'phone'       => '0236-3958-123',
                'email'       => 'info@danangbeachresort.com',
                'rating'      => 4.70,
            ],
            [
                'name'        => 'Hoi An Ancient Town Hotel',
                'description' => 'Khách sạn boutique phong cách cổ kính giữa lòng phố cổ Hội An.',
                'address'     => '12 Trần Phú, Minh An',
                'city'        => 'Hội An',
                'province'    => 'Quảng Nam',
                'phone'       => '0235-3861-456',
                'email'       => 'info@hoianancienthotel.com',
                'rating'      => 4.60,
            ],
            [
                'name'        => 'Nha Trang Ocean Hotel',
                'description' => 'Khách sạn view biển tuyệt đẹp tại thành phố biển Nha Trang.',
                'address'     => '234 Trần Phú, Lộc Thọ',
                'city'        => 'Nha Trang',
                'province'    => 'Khánh Hòa',
                'phone'       => '0258-3521-789',
                'email'       => 'info@nhatrangocean.com',
                'rating'      => 4.20,
            ],
            [
                'name'        => 'Phu Quoc Paradise Resort',
                'description' => 'Khu nghỉ dưỡng thiên đường tại đảo ngọc Phú Quốc.',
                'address'     => '567 Đường Trần Hưng Đạo, Dương Đông',
                'city'        => 'Phú Quốc',
                'province'    => 'Kiên Giang',
                'phone'       => '0297-3990-012',
                'email'       => 'info@phuquocparadise.com',
                'rating'      => 4.80,
            ],
        ];

        foreach ($hotels as $hotelData) {
            $hotel = Hotel::create([
                ...$hotelData,
                'country'   => 'Vietnam',
                'is_active' => true,
                'user_id'   => $admin->id,
            ]);

            // Gán 3 tiện nghi đầu tiên (truyền thống: không dùng shuffle random)
            $hotel->amenities()->attach(array_slice($amenities, 0, 3));
        }
    }
}