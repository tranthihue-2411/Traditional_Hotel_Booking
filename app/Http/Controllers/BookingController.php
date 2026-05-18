<?php
namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'hotel_id'         => 'required|exists:hotels,id',
            'room_id'          => 'required|exists:rooms,id',
            'check_in_date'    => 'required|date|after_or_equal:today',
            'check_out_date'   => 'required|date|after:check_in_date',
            'number_of_guests' => 'required|integer|min:1|max:10',
            'guest_name'       => 'required|string|max:255',
            'guest_email'      => 'required|email|max:255',
            'guest_phone'      => 'nullable|string|max:20',
            'special_requests' => 'nullable|string|max:1000',
        ]);

        $room = Room::findOrFail($validated['room_id']);

        if (!$room->isAvailable($validated['check_in_date'], $validated['check_out_date'])) {
            return back()->withErrors(['room' => 'Phòng không còn trống trong khoảng thời gian này.']);
        }

        $checkIn  = new \DateTime($validated['check_in_date']);
        $checkOut = new \DateTime($validated['check_out_date']);
        $nights   = $checkIn->diff($checkOut)->days;

        $subtotal   = $room->price_per_night * $nights;
        $taxes      = $subtotal * 0.1;
        $serviceFee = 15;
        $total      = $subtotal + $taxes + $serviceFee;

        $booking = Booking::create([
            'booking_reference'    => 'BK-' . date('Y') . '-' . strtoupper(substr(uniqid(), -6)),
            'user_id'              => Auth::id(),
            'hotel_id'             => $validated['hotel_id'],
            'room_id'              => $validated['room_id'],
            'check_in_date'        => $validated['check_in_date'],
            'check_out_date'       => $validated['check_out_date'],
            'number_of_guests'     => $validated['number_of_guests'],
            'number_of_nights'     => $nights,
            'room_price_per_night' => $room->price_per_night,
            'subtotal'             => $subtotal,
            'taxes'                => $taxes,
            'service_fee'          => $serviceFee,
            'discount'             => 0,
            'total_amount'         => $total,
            'guest_name'           => $validated['guest_name'],
            'guest_email'          => $validated['guest_email'],
            'guest_phone'          => $validated['guest_phone'] ?? null,
            'special_requests'     => $validated['special_requests'] ?? null,
            'status'               => 'confirmed',
        ]);

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Đặt phòng thành công!');
    }

    public function show(Booking $booking)
    {
        if ($booking->user_id !== Auth::id() && !Auth::user()->is_admin) {
            abort(403);
        }
        $booking->load(['hotel', 'room', 'user']);
        return view('bookings.show', compact('booking'));
    }

    public function myBookings()
    {
        $bookings = Booking::where('user_id', Auth::id())
            ->with(['hotel', 'room'])
            ->latest()
            ->paginate(10);

        return view('bookings.index', compact('bookings'));
    }

    public function cancel(Booking $booking)
    {
        if ($booking->user_id !== Auth::id() && !Auth::user()->is_admin) {
            abort(403);
        }

        if ($booking->status === 'cancelled') {
            return back()->withErrors(['booking' => 'Đặt phòng đã bị hủy rồi.']);
        }

        $booking->cancel('Cancelled by user');

        return back()->with('success', 'Đã hủy đặt phòng thành công.');
    }
}