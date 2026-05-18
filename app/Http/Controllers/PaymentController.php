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

        if ($booking->status !== 'pending') {
            return redirect()->route('bookings.show', $booking)
                ->with('success', 'Đặt phòng này đã được xử lý rồi.');
        }

        return view('payment.show', compact('booking'));
    }

    public function process(Request $request, Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'payment_method'  => 'required|in:credit_card,bank_transfer,cash',
            'card_name'       => 'required_if:payment_method,credit_card|nullable|string',
            'card_number'     => 'required_if:payment_method,credit_card|nullable|string',
            'card_expiry'     => 'required_if:payment_method,credit_card|nullable|string',
            'card_cvv'        => 'required_if:payment_method,credit_card|nullable|string',
        ]);

        // Mockup: giả lập xử lý thanh toán thành công
        $booking->update(['status' => 'confirmed']);

        return redirect()->route('bookings.show', $booking)
            ->with('success', '🎉 Thanh toán thành công! Đặt phòng của bạn đã được xác nhận.');
    }
}