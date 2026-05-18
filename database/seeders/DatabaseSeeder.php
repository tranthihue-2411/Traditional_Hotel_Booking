<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AmenitySeeder::class,
            UserSeeder::class,
            HotelSeeder::class,
            RoomSeeder::class,
            BookingSeeder::class,
        ]);
    }
}