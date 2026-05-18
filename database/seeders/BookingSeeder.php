<?php
namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $customers = User::where('is_admin', false)->get();
        $rooms     = Room::with('hotel')->get();

        $bookings = [
            ['status' => 'completed',  'days_ago' => 30,  'nights' => 3],
            ['status' => 'completed',  'days_ago' => 20,  'nights' => 2],
            ['status' => 'confirmed',  'days_ago' => -5,  'nights' => 4],
            ['status' => 'confirmed',  'days_ago' => -3,  'nights' => 2],
            ['status' => 'pending',    'days_ago' => -7,  'nights' => 3],
            ['status' => 'pending',    'days_ago' => -10, 'nights' => 5],
            ['status' => 'cancelled',  'days_ago' => 15,  'nights' => 2],
            ['status' => 'cancelled',  'days_ago' => 10,  'nights' => 3],
            ['status' => 'completed',  'days_ago' => 45,  'nights' => 1],
            ['status' => 'confirmed',  'days_ago' => -2,  'nights' => 7],
        ];

        foreach ($bookings as $index => $data) {
            $room     = $rooms[$index % $rooms->count()];
            $customer = $customers[$index % $customers->count()];

            $checkIn  = now()->subDays($data['days_ago'])->format('Y-m-d');
            $checkOut = now()->subDays($data['days_ago'] - $data['nights'])->format('Y-m-d');
            $nights   = $data['nights'];

            $subtotal   = $room->price_per_night * $nights;
            $taxes      = $subtotal * 0.08;
            $serviceFee = $subtotal * 0.05;
            $total      = $subtotal + $taxes + $serviceFee;

            Booking::create([
                'booking_reference'    => 'BK-' . date('Y') . '-' . strtoupper(substr(uniqid(), -6)),
                'user_id'              => $customer->id,
                'hotel_id'             => $room->hotel->id,
                'room_id'              => $room->id,
                'check_in_date'        => $checkIn,
                'check_out_date'       => $checkOut,
                'number_of_guests'     => rand(1, $room->max_guests),
                'number_of_nights'     => $nights,
                'room_price_per_night' => $room->price_per_night,
                'subtotal'             => $subtotal,
                'taxes'                => $taxes,
                'service_fee'          => $serviceFee,
                'discount'             => 0,
                'total_amount'         => $total,
                'guest_name'           => $customer->name,
                'guest_email'          => $customer->email,
                'guest_phone'          => '090' . rand(1000000, 9999999),
                'status'               => $data['status'],
                'cancelled_at'         => $data['status'] === 'cancelled' ? now() : null,
            ]);
        }
    }
}