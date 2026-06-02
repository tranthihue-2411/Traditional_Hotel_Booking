<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function show(Booking $booking)
    {
        if ($booking->user_id !== Auth::id() && !Auth::user()->is_admin) {
            abort(403);
        }

        if ($booking->status !== 'confirmed') {
            return redirect()->route('bookings.show', $booking)
                ->with('error', $booking->status === 'pending'
                    ? 'Đặt phòng đang chờ khách sạn xác nhận.'
                    : 'Đặt phòng này không thể thanh toán.');
        }

        if ($booking->is_paid) {
            return redirect()->route('bookings.show', $booking)
                ->with('error', 'Đặt phòng này đã được thanh toán rồi.');
        }

        if ($booking->payment_deadline && now()->isAfter($booking->payment_deadline)) {
            return redirect()->route('bookings.show', $booking)
                ->with('error', 'Đã hết thời hạn thanh toán. Đặt phòng sẽ bị hủy tự động.');
        }

        $booking->load(['hotel', 'details.room']);
        return view('payment.show', compact('booking'));
    }

    public function process(Request $request, Booking $booking)
    {
        if ($booking->user_id !== Auth::id() && !Auth::user()->is_admin) {
            abort(403);
        }

        if ($booking->status !== 'confirmed') {
            return redirect()->route('bookings.show', $booking)
                ->with('error', 'Đặt phòng này không thể thanh toán.');
        }

        if ($booking->is_paid) {
            return redirect()->route('bookings.show', $booking)
                ->with('error', 'Đặt phòng này đã được thanh toán rồi.');
        }

        if ($booking->payment_deadline && now()->isAfter($booking->payment_deadline)) {
            return redirect()->route('bookings.show', $booking)
                ->with('error', 'Đã hết thời hạn thanh toán.');
        }

        $allowedMethods = Auth::user()->is_admin
            ? ['credit_card', 'bank_transfer', 'cash']
            : ['credit_card', 'bank_transfer'];

        $request->validate([
            'payment_method' => ['required', 'in:' . implode(',', $allowedMethods)],
            'card_name'      => 'required_if:payment_method,credit_card|nullable|string',
            'card_number'    => 'required_if:payment_method,credit_card|nullable|string',
            'card_expiry'    => 'required_if:payment_method,credit_card|nullable|string',
            'card_cvv'       => 'required_if:payment_method,credit_card|nullable|string',
        ]);

        // Tất cả phương thức đều giữ confirmed sau khi thanh toán
        // Admin chuyển completed thủ công sau khi khách check-out thật sự
        $booking->update([
            'is_paid'        => true,
            'paid_at'        => now(),
            'payment_method' => $request->payment_method,
        ]);

        $message = $request->payment_method === 'cash'
            ? '✅ Đã thu tiền mặt thành công! Khách sạn sẽ chuyển hoàn thành sau khi khách check-out.'
            : '🎉 Thanh toán thành công! Chúc bạn có kỳ nghỉ tuyệt vời.';

        return redirect()->route('bookings.show', $booking)
            ->with('success', $message);
    }
}