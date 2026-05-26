@extends('layouts.main')
@section('title', 'Chi tiết đặt phòng - HotelHub')

@section('content')

@php
$statusLabels = [
    'pending'   => ['label' => 'Chờ duyệt',  'class' => 'bg-yellow-100 text-yellow-700'],
    'confirmed' => ['label' => 'Đã duyệt',   'class' => 'bg-green-100 text-green-700'],
    'cancelled' => ['label' => 'Đã hủy',     'class' => 'bg-red-100 text-red-700'],
    'completed' => ['label' => 'Hoàn thành', 'class' => 'bg-blue-100 text-blue-700'],
];
$s = $statusLabels[$booking->status] ?? ['label' => $booking->status, 'class' => 'bg-gray-100 text-gray-700'];
@endphp

<div class="container mx-auto px-4 py-8 max-w-4xl">

    <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
        <a href="{{ route('home') }}" class="hover:text-teal-600">Trang chủ</a>
        <span>›</span>
        <a href="{{ route('bookings.index') }}" class="hover:text-teal-600">Lịch sử đặt phòng</a>
        <span>›</span>
        <span class="text-gray-700">{{ $booking->booking_reference }}</span>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
        {{ session('error') }}
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2 space-y-5">

            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="bg-gradient-to-r from-teal-500 to-teal-700 text-white p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h1 class="text-2xl font-bold mb-1">Chi tiết đặt phòng</h1>
                            <p class="text-teal-100 text-sm">Mã: <span class="font-mono font-bold">{{ $booking->booking_reference }}</span></p>
                        </div>
                        <span class="px-3 py-1.5 rounded-full text-sm font-semibold whitespace-nowrap {{ $s['class'] }}">
                            {{ $s['label'] }}
                        </span>
                    </div>
                </div>

                <div class="p-6">
                    <div class="flex gap-4 mb-5 pb-5 border-b">
                        <img src="{{ $booking->hotel->main_image ?? 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=200&h=150&fit=crop' }}"
                             alt="{{ $booking->hotel->name }}"
                             class="w-24 h-16 object-cover rounded-lg flex-shrink-0">
                        <div>
                            <h3 class="font-bold text-gray-800 text-lg">{{ $booking->hotel->name }}</h3>
                            <p class="text-gray-500 text-sm">🛏️ {{ $booking->room->name }}</p>
                            <p class="text-gray-400 text-xs mt-1">📍 {{ $booking->hotel->address }}, {{ $booking->hotel->city }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-5 pb-5 border-b">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-gray-500 text-xs mb-1">Nhận phòng</p>
                            <p class="font-bold text-gray-800">{{ $booking->check_in_date->format('d/m/Y') }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-gray-500 text-xs mb-1">Trả phòng</p>
                            <p class="font-bold text-gray-800">{{ $booking->check_out_date->format('d/m/Y') }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-gray-500 text-xs mb-1">Số đêm</p>
                            <p class="font-bold text-gray-800">{{ $booking->number_of_nights }} đêm</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-gray-500 text-xs mb-1">Số khách</p>
                            <p class="font-bold text-gray-800">{{ $booking->number_of_guests }} khách</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-gray-500 text-xs mb-1">Tên khách</p>
                            <p class="font-semibold text-gray-700 text-sm">{{ $booking->guest_name }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 text-xs mb-1">Email</p>
                            <p class="font-semibold text-gray-700 text-sm">{{ $booking->guest_email }}</p>
                        </div>
                        @if($booking->guest_phone)
                        <div>
                            <p class="text-gray-500 text-xs mb-1">Điện thoại</p>
                            <p class="font-semibold text-gray-700 text-sm">{{ $booking->guest_phone }}</p>
                        </div>
                        @endif
                        @if($booking->special_requests)
                        <div class="col-span-2">
                            <p class="text-gray-500 text-xs mb-1">Yêu cầu đặc biệt</p>
                            <p class="font-semibold text-gray-700 text-sm">{{ $booking->special_requests }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Nút thao tác --}}
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('bookings.index') }}"
                   class="flex-1 text-center border border-gray-300 text-gray-600 hover:bg-gray-50 py-3 rounded-lg text-sm font-semibold">
                    ← Lịch sử đặt phòng
                </a>

                @if($booking->status === 'confirmed' && !$booking->is_paid)
                <a href="{{ route('payment.show', $booking) }}"
                   class="flex-1 bg-teal-600 hover:bg-teal-700 text-white py-3 rounded-lg text-sm font-semibold text-center">
                    💳 Thanh toán ngay
                </a>
                @elseif($booking->status === 'confirmed' && $booking->is_paid)
                <div class="flex-1 bg-green-100 text-green-700 py-3 rounded-lg text-sm font-semibold text-center">
                    ✅ Đã thanh toán lúc {{ $booking->paid_at->format('d/m/Y H:i') }}
                </div>
                @elseif($booking->status === 'pending')
                <a href="{{ route('bookings.pending', $booking) }}"
                   class="flex-1 bg-yellow-100 text-yellow-700 py-3 rounded-lg text-sm font-semibold text-center">
                    ⏳ Đang chờ xác nhận
                </a>
                @endif

                @if($booking->status !== 'cancelled' && $booking->status !== 'completed')
                <form action="{{ route('bookings.cancel', $booking) }}" method="POST"
                      onsubmit="return confirm('Bạn có chắc muốn hủy đặt phòng này?')">
                    @csrf
                    <button type="submit"
                            class="px-6 py-3 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 text-sm font-semibold">
                        Hủy đặt phòng
                    </button>
                </form>
                @endif
            </div>

            {{-- Đánh giá --}}
            @php
                $userReview = \App\Models\Review::where('user_id', auth()->id())
                    ->where('hotel_id', $booking->hotel_id)->first();
            @endphp

            @if($booking->status === 'completed' && !$userReview)
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-bold text-gray-800 mb-4">⭐ Đánh giá chuyến đi</h3>
                <form action="{{ route('reviews.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="hotel_id" value="{{ $booking->hotel_id }}">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Điểm đánh giá</label>
                        <select name="rating" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                            @foreach([5,4,3,2,1] as $star)
                            <option value="{{ $star }}">{{ $star }} sao {{ str_repeat('⭐', $star) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nhận xét</label>
                        <textarea name="comment" rows="4"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500"
                            placeholder="Chia sẻ trải nghiệm của bạn..."></textarea>
                    </div>
                    <button type="submit" class="bg-teal-600 text-white px-6 py-2 rounded-lg hover:bg-teal-700 font-semibold">
                        Gửi đánh giá
                    </button>
                </form>
            </div>

            @elseif($booking->status === 'completed' && $userReview)
            <div class="bg-green-50 border border-green-200 rounded-lg p-5">
                <p class="font-bold text-green-700">✅ Bạn đã đánh giá chuyến đi này!</p>
                <div class="mt-1">{{ str_repeat('⭐', $userReview->rating) }}</div>
                @if($userReview->comment)
                <p class="text-green-600 text-sm mt-1 italic">"{{ $userReview->comment }}"</p>
                @endif
            </div>

            @elseif($booking->status === 'cancelled')
            <div class="bg-red-50 border border-red-200 rounded-lg p-5">
                <p class="font-semibold text-red-600">❌ Đặt phòng đã hủy — không thể đánh giá</p>
            </div>

            @else
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-5">
                <p class="font-semibold text-gray-600">🕐 Chưa thể đánh giá</p>
                <p class="text-gray-400 text-sm mt-0.5">Bạn có thể đánh giá sau khi hoàn thành chuyến đi</p>
            </div>
            @endif

        </div>

        {{-- Cột phải --}}
        <div>
            <div class="bg-white rounded-lg shadow overflow-hidden sticky top-4">
                <div class="bg-gradient-to-r from-teal-500 to-teal-700 px-6 py-5">
                    <p class="text-teal-100 text-xs mb-1">Tổng thanh toán</p>
                    <p class="text-white font-bold text-3xl">{{ number_format($booking->total_amount) }}đ</p>
                </div>
                <div class="p-5 space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">{{ number_format($booking->room_price_per_night) }}đ × {{ $booking->number_of_nights }} đêm</span>
                        <span class="font-medium">{{ number_format($booking->subtotal) }}đ</span>
                    </div>
                    <div class="flex justify-between text-gray-500">
                        <span>Thuế (10%)</span>
                        <span>{{ number_format($booking->taxes) }}đ</span>
                    </div>
                    <div class="flex justify-between text-gray-500">
                        <span>Phí dịch vụ</span>
                        <span>{{ number_format($booking->service_fee) }}đ</span>
                    </div>
                    <div class="flex justify-between font-bold text-lg pt-3 border-t">
                        <span>Tổng cộng</span>
                        <span class="text-teal-600">{{ number_format($booking->total_amount) }}đ</span>
                    </div>

                    @if($booking->is_paid)
                    <div class="bg-green-50 border border-green-200 rounded-lg p-3 mt-2">
                        <p class="text-green-700 text-xs font-semibold">✅ Đã thanh toán</p>
                        <p class="text-green-600 text-xs mt-0.5">{{ $booking->paid_at->format('d/m/Y H:i') }}</p>
                        <p class="text-green-600 text-xs mt-0.5">
                            @if($booking->payment_method === 'credit_card') 💳 Thẻ tín dụng
                            @elseif($booking->payment_method === 'bank_transfer') 🏦 Chuyển khoản
                            @else 💵 Tiền mặt tại khách sạn
                            @endif
                        </p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>

@endsection