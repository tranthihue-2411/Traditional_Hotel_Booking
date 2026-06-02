<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingDetail;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'hotel_id'         => 'required|exists:hotels,id',
            'rooms'            => 'required|array|min:1',
            'rooms.*.room_id'  => 'required|exists:rooms,id',
            'rooms.*.quantity' => 'required|integer|min:1',
            'check_in_date'    => 'required|date|after_or_equal:today',
            'check_out_date'   => 'required|date|after:check_in_date',
            'number_of_guests' => 'required|integer|min:1|max:20',
            'guest_name'       => 'required|string|max:255',
            'guest_email'      => 'required|email|max:255',
            'guest_phone'      => 'nullable|string|max:20',
            'special_requests' => 'nullable|string|max:1000',
        ]);

        $checkIn  = new \DateTime($validated['check_in_date']);
        $checkOut = new \DateTime($validated['check_out_date']);
        $nights   = $checkIn->diff($checkOut)->days;
        $isAdmin  = Auth::user()->is_admin;

        // Kiểm tra tất cả phòng còn trống
        $roomDetails = [];
        foreach ($validated['rooms'] as $item) {
            $room = Room::findOrFail($item['room_id']);
            $qty  = $item['quantity'];
            if ($room->availableCount($validated['check_in_date'], $validated['check_out_date']) < $qty) {
                return back()->withErrors(['rooms' => "Phòng {$room->name} không đủ số lượng trống."]);
            }
            $roomDetails[] = ['room' => $room, 'quantity' => $qty];
        }

        // Tính tổng tiền
        $subtotal = 0;
        foreach ($roomDetails as $item) {
            $subtotal += $item['room']->price_per_night * $nights * $item['quantity'];
        }
        $taxes      = $subtotal * 0.1;
        $serviceFee = 15;
        $total      = $subtotal + $taxes + $serviceFee;

        // Admin → confirmed + payment_deadline luôn
        // Khách → pending, chờ admin duyệt
        $status = $isAdmin ? 'confirmed' : 'pending';

        $paymentDeadline = null;
        if ($isAdmin) {
            $deadline        = Carbon::parse($validated['check_in_date'])->subDay();
            $paymentDeadline = $deadline->isPast() ? now()->addHours(2) : $deadline;
        }

        DB::transaction(function () use ($validated, $roomDetails, $nights, $subtotal, $taxes, $serviceFee, $total, $status, $paymentDeadline) {
            $booking = Booking::create([
                'booking_reference' => 'BK-' . date('Y') . '-' . strtoupper(substr(uniqid(), -6)),
                'user_id'           => Auth::id(),
                'hotel_id'          => $validated['hotel_id'],
                'check_in_date'     => $validated['check_in_date'],
                'check_out_date'    => $validated['check_out_date'],
                'number_of_guests'  => $validated['number_of_guests'],
                'subtotal'          => $subtotal,
                'taxes'             => $taxes,
                'service_fee'       => $serviceFee,
                'discount'          => 0,
                'total_amount'      => $total,
                'guest_name'        => $validated['guest_name'],
                'guest_email'       => $validated['guest_email'],
                'guest_phone'       => $validated['guest_phone'] ?? null,
                'special_requests'  => $validated['special_requests'] ?? null,
                'status'            => $status,
                'payment_deadline'  => $paymentDeadline,
            ]);

            foreach ($roomDetails as $item) {
                BookingDetail::create([
                    'booking_id'       => $booking->id,
                    'room_id'          => $item['room']->id,
                    'room_name'        => $item['room']->name,
                    'room_type'        => $item['room']->room_type,
                    'price_per_night'  => $item['room']->price_per_night,
                    'quantity'         => $item['quantity'],
                    'number_of_nights' => $nights,
                    'subtotal'         => $item['room']->price_per_night * $nights * $item['quantity'],
                ]);
            }

            $this->lastBooking = $booking;
        });

        // Admin → redirect thẳng sang trang thanh toán
        // Khách → redirect sang trang chờ xác nhận
        if (Auth::user()->is_admin) {
            return redirect()->route('payment.show', $this->lastBooking)
                ->with('success', 'Đã tạo booking thành công! Tiến hành thu tiền.');
        }

        return redirect()->route('bookings.pending', $this->lastBooking)
            ->with('success', 'Đặt phòng thành công! Vui lòng chờ khách sạn xác nhận.');
    }

    public function show(Booking $booking)
    {
        if ($booking->user_id !== Auth::id() && !Auth::user()->is_admin) {
            abort(403);
        }
        $booking->load(['hotel', 'details.room', 'user', 'review']);
        return view('bookings.show', compact('booking'));
    }

    public function myBookings(Request $request)
    {
        $query = Booking::where('user_id', Auth::id())
            ->with(['hotel', 'details.room'])
            ->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('booking_reference', 'like', "%{$search}%")
                  ->orWhereHas('hotel', function ($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $bookings = $query->paginate(10);
        return view('bookings.index', compact('bookings'));
    }

    public function cancel(Booking $booking)
    {
        if ($booking->user_id !== Auth::id() && !Auth::user()->is_admin) {
            abort(403);
        }

        if (in_array($booking->status, ['cancelled', 'completed'])) {
            return back()->withErrors(['booking' => 'Không thể hủy đặt phòng này.']);
        }

        if ($booking->status === 'confirmed') {
            $hoursUntilCheckIn = now()->diffInHours($booking->check_in_date, false);
            if ($hoursUntilCheckIn < 24 && $booking->is_paid) {
                return back()->withErrors(['booking' => 'Không thể hủy: chỉ còn dưới 24 giờ trước khi nhận phòng và đã thanh toán.']);
            }
            $updates = [
                'status'              => 'cancelled',
                'cancelled_at'        => now(),
                'cancellation_reason' => 'Khách hủy đặt phòng.',
            ];
            if ($booking->is_paid) {
                $updates['refund_status'] = 'pending';
            }
            $booking->update($updates);
        } else {
            $booking->cancel('Khách hủy đặt phòng.');
        }

        return back()->with('success', 'Đã hủy đặt phòng thành công.' .
            ($booking->refund_status === 'pending' ? ' Yêu cầu hoàn tiền đã được ghi nhận.' : ''));
    }

    public function pending(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }
        $booking->load(['hotel', 'details.room']);
        return view('bookings.pending', compact('booking'));
    }
}