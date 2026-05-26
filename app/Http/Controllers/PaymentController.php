<?php
namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function show(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
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

        $booking->load(['hotel', 'room']);
        return view('payment.show', compact('booking'));
    }

    public function process(Request $request, Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
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

        $request->validate([
            'payment_method' => 'required|in:credit_card,bank_transfer,cash',
            'card_name'      => 'required_if:payment_method,credit_card|nullable|string',
            'card_number'    => 'required_if:payment_method,credit_card|nullable|string',
            'card_expiry'    => 'required_if:payment_method,credit_card|nullable|string',
            'card_cvv'       => 'required_if:payment_method,credit_card|nullable|string',
        ]);

        $booking->update([
            'is_paid'        => true,
            'paid_at'        => now(),
            'payment_method' => $request->payment_method,
        ]);

        return redirect()->route('bookings.show', $booking)
            ->with('success', '🎉 Thanh toán thành công! Chúc bạn có kỳ nghỉ tuyệt vời.');
    }
}