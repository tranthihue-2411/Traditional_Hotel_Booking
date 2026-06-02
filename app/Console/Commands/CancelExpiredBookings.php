<?php

namespace App\Console\Commands;

use App\Models\Booking;
use Illuminate\Console\Command;

class CancelExpiredBookings extends Command
{
    protected $signature   = 'bookings:cancel-expired';
    protected $description = 'Tự động hủy booking confirmed đã quá payment_deadline mà chưa thanh toán';

    public function handle(): void
    {
        $expired = Booking::where('status', 'confirmed')
            ->where('is_paid', false)
            ->whereNotNull('payment_deadline')
            ->where('payment_deadline', '<', now())
            ->get();

        foreach ($expired as $booking) {
            $booking->update([
                'status'              => 'cancelled',
                'cancelled_at'        => now(),
                'cancellation_reason' => 'Hệ thống tự động hủy do quá thời hạn thanh toán.',
            ]);
        }

        $this->info("Đã hủy {$expired->count()} booking hết hạn thanh toán.");
    }
}