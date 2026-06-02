@extends('layouts.main')
@section('title', 'Chờ xác nhận - HotelHub')

@section('content')
<div class="container mx-auto px-4 py-16 max-w-2xl">

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="bg-yellow-500 px-6 py-8 text-center">
            <div class="text-6xl mb-4">⏳</div>
            <h1 class="text-2xl font-bold text-white mb-2">Đang chờ xác nhận</h1>
            <p class="text-yellow-100 text-sm">Khách sạn đang kiểm tra phòng trống và sẽ xác nhận trong thời gian sớm nhất</p>
        </div>

        <div class="p-6">
            <div class="bg-gray-50 rounded-lg p-5 mb-6 border border-gray-200">
                <div class="flex items-center gap-4 mb-4 pb-4 border-b border-gray-200">
                    <img src="{{ $booking->hotel->main_image ?? 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=200&h=150&fit=crop' }}"
                        alt="{{ $booking->hotel->name }}"
                        class="w-16 h-12 object-cover rounded-lg flex-shrink-0">
                    <div>
                        <p class="font-bold text-gray-800">{{ $booking->hotel->name }}</p>
                        <p class="text-yellow-600 text-xs font-semibold mt-0.5">Mã: {{ $booking->booking_reference }}</p>
                    </div>
                </div>

                {{-- Danh sách phòng --}}
                <div class="mb-4 pb-4 border-b border-gray-200">
                    <p class="text-gray-500 text-xs mb-2 font-semibold uppercase">Phòng đã đặt</p>
                    @foreach($booking->details as $detail)
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-700">{{ $detail->room_name }} × {{ $detail->quantity }}</span>
                        <span class="font-semibold text-teal-600">{{ number_format($detail->subtotal) }}đ</span>
                    </div>
                    @endforeach
                </div>

                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500 text-xs mb-0.5">Nhận phòng</p>
                        <p class="font-semibold text-gray-700">{{ $booking->check_in_date->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs mb-0.5">Trả phòng</p>
                        <p class="font-semibold text-gray-700">{{ $booking->check_out_date->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs mb-0.5">Số đêm</p>
                        <p class="font-semibold text-gray-700">{{ $booking->details->first()?->number_of_nights ?? 0 }} đêm</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs mb-0.5">Tổng tiền</p>
                        <p class="font-bold text-teal-600">{{ number_format($booking->total_amount) }}đ</p>
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <h3 class="font-bold text-gray-700 text-sm mb-4">Quy trình đặt phòng</h3>
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <div class="w-7 h-7 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0 text-white text-xs">✓</div>
                        <div>
                            <p class="text-sm font-semibold text-gray-700">Đặt phòng thành công</p>
                            <p class="text-xs text-gray-400">Yêu cầu đặt phòng đã được ghi nhận</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-7 h-7 bg-yellow-400 rounded-full flex items-center justify-center flex-shrink-0 text-white text-xs animate-pulse">⏳</div>
                        <div>
                            <p class="text-sm font-semibold text-yellow-600">Khách sạn đang xác nhận</p>
                            <p class="text-xs text-gray-400">Khách sạn kiểm tra phòng trống thực tế</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-7 h-7 bg-gray-200 rounded-full flex items-center justify-center flex-shrink-0 text-gray-400 text-xs">💳</div>
                        <div>
                            <p class="text-sm font-semibold text-gray-400">Thanh toán</p>
                            <p class="text-xs text-gray-400">Có thể thanh toán sau khi được xác nhận</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-7 h-7 bg-gray-200 rounded-full flex items-center justify-center flex-shrink-0 text-gray-400 text-xs">🏨</div>
                        <div>
                            <p class="text-sm font-semibold text-gray-400">Nhận phòng</p>
                            <p class="text-xs text-gray-400">Mang mã đặt phòng đến khách sạn</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('bookings.show', $booking) }}"
                    class="flex-1 bg-teal-600 hover:bg-teal-700 text-white py-3 rounded-lg text-sm font-semibold text-center">
                    Xem chi tiết đặt phòng
                </a>
                <a href="{{ route('bookings.index') }}"
                    class="flex-1 border border-gray-300 text-gray-600 hover:bg-gray-50 py-3 rounded-lg text-sm font-semibold text-center">
                    Lịch sử đặt phòng
                </a>
            </div>
        </div>
    </div>
</div>
@endsection