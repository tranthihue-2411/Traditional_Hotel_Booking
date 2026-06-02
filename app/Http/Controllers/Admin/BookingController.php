<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['hotel', 'details.room', 'user'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('booking_reference', 'like', "%{$search}%")
                  ->orWhere('guest_name', 'like', "%{$search}%")
                  ->orWhere('guest_email', 'like', "%{$search}%");
            });
        }

        $bookings = $query->paginate(20);
        return view('admin.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['hotel', 'details.room', 'user']);
        return view('admin.bookings.show', compact('booking'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        if (in_array($booking->status, ['completed', 'cancelled'])) {
            return back()->withErrors(['status' => 'Không thể thay đổi trạng thái đặt phòng đã hoàn thành hoặc đã hủy.']);
        }

        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed',
            'reason' => 'required_if:status,cancelled|nullable|string|max:500',
        ]);

        $newStatus = $request->status;
        $updates   = ['status' => $newStatus];

        if ($newStatus === 'confirmed' && !$booking->payment_deadline) {
            $deadline = $booking->check_in_date->subDay();
            $updates['payment_deadline'] = $deadline->isPast()
                ? now()->addHours(2)
                : $deadline;
        }

        if ($newStatus === 'cancelled') {
            $updates['cancelled_at']        = now();
            $updates['cancellation_reason'] = $request->reason;
            if ($booking->is_paid) {
                // Admin tự chọn có hoàn tiền hay không
                $updates['refund_status'] = $request->boolean('refund') ? 'pending' : 'none';
            }
        }

        $booking->update($updates);

        return back()->with('success', 'Đã cập nhật trạng thái đặt phòng!');
    }

    public function confirmRefund(Booking $booking)
    {
        if ($booking->refund_status !== 'pending') {
            return back()->withErrors(['refund' => 'Không thể xác nhận hoàn tiền.']);
        }

        $booking->update(['refund_status' => 'completed']);

        return back()->with('success', 'Đã xác nhận hoàn tiền cho khách!');
    }
}