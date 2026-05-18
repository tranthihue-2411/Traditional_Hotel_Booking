@extends('layouts.main')
@section('title', 'Chi Tiết Đặt Phòng - HotelHub')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="bg-gradient-to-r from-teal-500 to-teal-700 text-white p-6">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-bold mb-2">Chi Tiết Đặt Phòng</h1>
                    <p class="text-teal-100">Mã đặt phòng: <span class="font-mono font-bold">{{ $booking->booking_reference }}</span></p>
                </div>
                <div class="px-4 py-2 bg-white bg-opacity-20 rounded-lg">
                    @if($booking->status == 'confirmed') <span class="text-green-200">✅ Đã xác nhận</span>
                    @elseif($booking->status == 'pending') <span class="text-yellow-200">⏳ Đang chờ</span>
                    @elseif($booking->status == 'cancelled') <span class="text-red-200">❌ Đã hủy</span>
                    @else <span class="text-blue-200">🏁 Hoàn thành</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="p-6">
            <div class="border-b pb-6 mb-6">
                <h2 class="text-2xl font-semibold mb-4">{{ $booking->hotel->name }}</h2>
                <div class="flex items-center gap-4 mb-4">
                    <img src="{{ $booking->hotel->main_image ?? 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=200&h=150&fit=crop' }}"
                         alt="{{ $booking->hotel->name }}" class="w-32 h-24 object-cover rounded-lg">
                    <div>
                        <p class="text-gray-600">📍 {{ $booking->hotel->address }}, {{ $booking->hotel->city }}</p>
                        <div class="flex items-center mt-2">
                            <span class="text-yellow-500">⭐ {{ number_format($booking->hotel->rating, 1) }}</span>
                            <span class="text-gray-500 text-sm ml-2">({{ $booking->hotel->review_count }} đánh giá)</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="border-b pb-6 mb-6">
                <h2 class="text-2xl font-semibold mb-4">Chi Tiết Đặt Phòng</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Phòng</p>
                        <p class="font-semibold text-lg">{{ $booking->room->name }}</p>
                        <p class="text-sm text-gray-600 mt-1">{{ $booking->room->bed_type }} • Tối đa {{ $booking->room->max_guests }} khách</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Số đêm</p>
                        <p class="font-semibold text-lg">{{ $booking->number_of_nights }} đêm</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Nhận phòng</p>
                        <p class="font-semibold text-lg">{{ $booking->check_in_date->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Trả phòng</p>
                        <p class="font-semibold text-lg">{{ $booking->check_out_date->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Số khách</p>
                        <p class="font-semibold text-lg">{{ $booking->number_of_guests }} người</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Ngày đặt</p>
                        <p class="font-semibold text-lg">{{ $booking->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <div class="border-b pb-6 mb-6">
                <h2 class="text-2xl font-semibold mb-4">Thông Tin Khách Hàng</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Họ và tên</p>
                        <p class="font-semibold">{{ $booking->guest_name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Email</p>
                        <p class="font-semibold">{{ $booking->guest_email }}</p>
                    </div>
                    @if($booking->guest_phone)
                    <div>
                        <p class="text-sm text-gray-600">Số điện thoại</p>
                        <p class="font-semibold">{{ $booking->guest_phone }}</p>
                    </div>
                    @endif
                </div>
                @if($booking->special_requests)
                <div class="mt-4">
                    <p class="text-sm text-gray-600">Ghi chú đặc biệt</p>
                    <p class="font-semibold">{{ $booking->special_requests }}</p>
                </div>
                @endif
            </div>

            <div class="bg-gray-50 p-6 rounded-lg mb-6">
                <h2 class="text-2xl font-semibold mb-4">Tóm Tắt Giá</h2>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span>{{ number_format($booking->room_price_per_night) }}đ × {{ $booking->number_of_nights }} đêm</span>
                        <span>{{ number_format($booking->subtotal) }}đ</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Thuế và phí</span>
                        <span>{{ number_format($booking->taxes) }}đ</span>
                    </div>
                    @if($booking->service_fee > 0)
                    <div class="flex justify-between text-gray-600">
                        <span>Phí dịch vụ</span>
                        <span>{{ number_format($booking->service_fee) }}đ</span>
                    </div>
                    @endif
                    @if($booking->discount > 0)
                    <div class="flex justify-between text-green-600">
                        <span>Giảm giá</span>
                        <span>-{{ number_format($booking->discount) }}đ</span>
                    </div>
                    @endif
                    <div class="flex justify-between font-bold text-2xl pt-4 border-t border-gray-300">
                        <span>Tổng cộng</span>
                        <span class="text-teal-600">{{ number_format($booking->total_amount) }}đ</span>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ route('bookings.index') }}"
                   class="flex-1 bg-teal-600 text-white py-3 rounded-lg text-center hover:bg-teal-700 font-semibold">
                    Xem Tất Cả Đặt Phòng
                </a>
                <a href="{{ route('hotels.show', $booking->hotel) }}"
                   class="flex-1 bg-gray-200 text-gray-700 py-3 rounded-lg text-center hover:bg-gray-300 font-semibold">
                    Xem Khách Sạn
                </a>
                @if($booking->status == 'confirmed' || $booking->status == 'pending')
                <form action="{{ route('bookings.cancel', $booking) }}" method="POST"
                      onsubmit="return confirm('Bạn có chắc muốn hủy đặt phòng này?');" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full bg-red-600 text-white py-3 rounded-lg hover:bg-red-700 font-semibold">
                        Hủy Đặt Phòng
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>

    @if($booking->hotel->phone || $booking->hotel->email)
    <div class="bg-white rounded-lg shadow p-6 mt-6">
        <h3 class="text-xl font-semibold mb-4">Liên Hệ Khách Sạn</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @if($booking->hotel->phone)
            <div>
                <p class="text-sm text-gray-600">Điện thoại</p>
                <p class="font-semibold">{{ $booking->hotel->phone }}</p>
            </div>
            @endif
            @if($booking->hotel->email)
            <div>
                <p class="text-sm text-gray-600">Email</p>
                <p class="font-semibold">{{ $booking->hotel->email }}</p>
            </div>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection