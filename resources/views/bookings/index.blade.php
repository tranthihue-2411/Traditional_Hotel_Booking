@extends('layouts.main')
@section('title', 'Đặt Phòng Của Tôi - HotelHub')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Đặt Phòng Của Tôi</h1>

    @if($bookings->count() > 0)
    <div class="space-y-4">
        @foreach($bookings as $booking)
        <div class="bg-white rounded-lg shadow overflow-hidden hover:shadow-lg transition">
            <div class="p-6">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-1">{{ $booking->hotel->name }}</h3>
                        <p class="text-gray-600 text-sm">📍 {{ $booking->hotel->city }}</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="px-3 py-1 rounded-full text-sm font-medium
                            @if($booking->status == 'confirmed') bg-green-100 text-green-800
                            @elseif($booking->status == 'pending') bg-yellow-100 text-yellow-800
                            @elseif($booking->status == 'cancelled') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800 @endif">
                            @if($booking->status == 'confirmed') Đã xác nhận
                            @elseif($booking->status == 'pending') Đang chờ
                            @elseif($booking->status == 'cancelled') Đã hủy
                            @else Hoàn thành @endif
                        </span>
                        <span class="text-sm text-gray-500">Mã: {{ $booking->booking_reference }}</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <p class="text-sm text-gray-600">Phòng</p>
                        <p class="font-semibold">{{ $booking->room->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Nhận phòng</p>
                        <p class="font-semibold">{{ $booking->check_in_date->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Trả phòng</p>
                        <p class="font-semibold">{{ $booking->check_out_date->format('d/m/Y') }}</p>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 pt-4 border-t">
                    <div>
                        <p class="text-sm text-gray-600">Khách</p>
                        <p class="font-semibold">{{ $booking->number_of_guests }} người • {{ $booking->number_of_nights }} đêm</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-600">Tổng cộng</p>
                        <p class="text-2xl font-bold text-teal-600">{{ number_format($booking->total_amount) }}đ</p>
                    </div>
                </div>

                <div class="flex gap-2 mt-4">
                    <a href="{{ route('bookings.show', $booking) }}"
                       class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700">
                        Xem chi tiết
                    </a>
                    @if($booking->status == 'confirmed' || $booking->status == 'pending')
                    <form action="{{ route('bookings.cancel', $booking) }}" method="POST"
                          onsubmit="return confirm('Bạn có chắc muốn hủy đặt phòng này?');">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                            Hủy đặt phòng
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="mt-6">{{ $bookings->links() }}</div>
    @else
    <div class="bg-white p-12 rounded-lg shadow text-center">
        <div class="text-6xl mb-4">📋</div>
        <p class="text-xl text-gray-600 mb-4">Bạn chưa có đặt phòng nào</p>
        <a href="{{ route('hotels.search') }}" class="bg-teal-600 text-white px-6 py-2 rounded-lg hover:bg-teal-700 inline-block">
            Tìm kiếm khách sạn
        </a>
    </div>
    @endif
</div>
@endsection