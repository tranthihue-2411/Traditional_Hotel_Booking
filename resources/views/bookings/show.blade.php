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
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">{{ session('error') }}</div>
    @endif
    @if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
        @foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach
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
                    {{-- Khách sạn --}}
                    <div class="flex gap-4 mb-5 pb-5 border-b">
                        <img src="{{ $booking->hotel->main_image ?? 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=200&h=150&fit=crop' }}"
                             alt="{{ $booking->hotel->name }}"
                             class="w-24 h-16 object-cover rounded-lg flex-shrink-0">
                        <div>
                            <h3 class="font-bold text-gray-800 text-lg">{{ $booking->hotel->name }}</h3>
                            <p class="text-gray-400 text-xs mt-1">📍 {{ $booking->hotel->address }}, {{ $booking->hotel->city }}</p>
                        </div>
                    </div>

                    {{-- Ngày --}}
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
                            <p class="text-gray-500 text-xs mb-1">Số khách</p>
                            <p class="font-bold text-gray-800">{{ $booking->number_of_guests }} khách</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-gray-500 text-xs mb-1">Số đêm</p>
                            <p class="font-bold text-gray-800">{{ $booking->details->first()?->number_of_nights ?? 0 }} đêm</p>
                        </div>
                    </div>

                    {{-- Chi tiết phòng --}}
                    <div class="mb-5 pb-5 border-b">
                        <p class="text-gray-500 text-xs mb-3 font-semibold uppercase">Danh sách phòng</p>
                        <div class="space-y-2">
                            @foreach($booking->details as $detail)
                            <div class="flex justify-between items-center bg-gray-50 rounded-lg p-3">
                                <div>
                                    <p class="font-semibold text-gray-700 text-sm">{{ $detail->room_name }}</p>
                                    <p class="text-gray-400 text-xs">{{ number_format($detail->price_per_night) }}đ × {{ $detail->quantity }} phòng × {{ $detail->number_of_nights }} đêm</p>
                                </div>
                                <p class="font-bold text-teal-600 text-sm">{{ number_format($detail->subtotal) }}đ</p>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Thông tin khách --}}
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

                @php
                    $canCancel = false;
                    $cancelNote = '';
                    if ($booking->status === 'pending') {
                        $canCancel = true;
                    } elseif ($booking->status === 'confirmed') {
                        if (!$booking->is_paid) {
                            $canCancel = true;
                        } else {
                            $hoursLeft = now()->diffInHours($booking->check_in_date, false);
                            if ($hoursLeft >= 24) {
                                $canCancel = true;
                            } else {
                                $cancelNote = 'Không thể hủy: chỉ còn dưới 24 giờ trước khi nhận phòng và đã thanh toán.';
                            }
                        }
                    }
                @endphp

                @if($canCancel)
                <form action="{{ route('bookings.cancel', $booking) }}" method="POST"
                      onsubmit="return confirm('Bạn có chắc muốn hủy đặt phòng này?')">
                    @csrf
                    <button type="submit"
                            class="px-6 py-3 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 text-sm font-semibold">
                        Hủy đặt phòng
                    </button>
                </form>
                @elseif($cancelNote)
                <div class="flex-1 bg-gray-50 text-gray-400 py-3 px-4 rounded-lg text-xs text-center border border-gray-200">
                    🔒 {{ $cancelNote }}
                </div>
                @endif
            </div>

            {{-- Chính sách hủy --}}
            @if(!in_array($booking->status, ['cancelled', 'completed']))
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 text-sm">
                <p class="font-semibold text-amber-800 mb-2">📋 Chính sách hủy phòng</p>
                <ul class="text-amber-700 space-y-1 text-xs list-disc list-inside">
                    <li>Đặt phòng <strong>chờ duyệt</strong>: hủy tự do, không phí.</li>
                    <li>Đặt phòng <strong>đã duyệt chưa thanh toán</strong>: hủy tự do.</li>
                    <li>Đặt phòng <strong>đã thanh toán</strong>, hủy trước 24h check-in: hoàn tiền.</li>
                    <li>Đặt phòng <strong>đã thanh toán</strong>, hủy trong 24h trước check-in: <span class="font-semibold text-red-600">không thể hủy</span>.</li>
                </ul>
            </div>
            @endif

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
                    @foreach($booking->details as $detail)
                    <div class="flex justify-between">
                        <span class="text-gray-500">{{ $detail->room_name }} × {{ $detail->quantity }}</span>
                        <span class="font-medium">{{ number_format($detail->subtotal) }}đ</span>
                    </div>
                    @endforeach
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
                            @else 💵 Tiền mặt tại quầy
                            @endif
                        </p>
                    </div>
                    @endif

                    @if($booking->refund_status === 'pending')
                    <div class="bg-yellow-50 border border-yellow-300 rounded-lg p-3 mt-2">
                        <p class="text-yellow-700 text-xs font-semibold">🔄 Đang xử lý hoàn tiền</p>
                        <p class="text-yellow-600 text-xs mt-0.5">Chúng tôi sẽ liên hệ trong 3–5 ngày làm việc.</p>
                    </div>
                    @elseif($booking->refund_status === 'completed')
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mt-2">
                        <p class="text-blue-700 text-xs font-semibold">✅ Đã hoàn tiền</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>

@endsection