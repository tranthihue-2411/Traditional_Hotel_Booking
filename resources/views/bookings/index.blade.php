@extends('layouts.main')
@section('title', 'Lịch sử đặt phòng - HotelHub')

@section('content')

@php
$statusLabels = [
    'pending'   => ['label' => 'Chờ duyệt',  'class' => 'bg-yellow-100 text-yellow-700'],
    'confirmed' => ['label' => 'Đã duyệt',   'class' => 'bg-green-100 text-green-700'],
    'cancelled' => ['label' => 'Đã hủy',     'class' => 'bg-red-100 text-red-700'],
    'completed' => ['label' => 'Hoàn thành', 'class' => 'bg-blue-100 text-blue-700'],
];
@endphp

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Lịch sử đặt phòng</h1>

    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <form action="{{ route('bookings.index') }}" method="GET" class="flex gap-3">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Tìm theo tên khách sạn, mã đặt phòng..."
                   class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 text-sm">
            <select name="status"
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 text-sm bg-white">
                <option value="">Tất cả trạng thái</option>
                <option value="pending"   {{ request('status') == 'pending'   ? 'selected' : '' }}>⏳ Chờ duyệt</option>
                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>✅ Đã duyệt</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>🏁 Hoàn thành</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>❌ Đã hủy</option>
            </select>
            <button type="submit" class="bg-teal-600 text-white px-5 py-2 rounded-lg hover:bg-teal-700 text-sm font-semibold">Tìm</button>
            @if(request('search') || request('status'))
            <a href="{{ route('bookings.index') }}" class="bg-gray-200 text-gray-700 px-5 py-2 rounded-lg hover:bg-gray-300 text-sm font-semibold">Xóa lọc</a>
            @endif
        </form>
    </div>

    @forelse($bookings as $booking)
    @php $s = $statusLabels[$booking->status] ?? ['label' => $booking->status, 'class' => 'bg-gray-100 text-gray-700']; @endphp
    <div class="bg-white rounded-lg shadow overflow-hidden hover:shadow-lg transition mb-4">
        <div class="flex flex-col md:flex-row">
            <img src="{{ $booking->hotel->main_image ?? 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=400&h=300&fit=crop' }}"
                 alt="{{ $booking->hotel->name }}"
                 class="w-full md:w-48 h-40 object-cover flex-shrink-0">
            <div class="p-5 flex-1">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-800">{{ $booking->hotel->name }}</h3>
                        <p class="text-gray-500 text-sm">
                            🛏️ {{ $booking->details->map(fn($d) => $d->room_name . ($d->quantity > 1 ? " ×{$d->quantity}" : ''))->join(', ') }}
                        </p>
                        <p class="text-gray-400 text-xs mt-0.5"># {{ $booking->booking_reference }}</p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold whitespace-nowrap {{ $s['class'] }}">
                        {{ $s['label'] }}
                    </span>
                </div>

                <div class="grid grid-cols-3 gap-3 mb-4 p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="text-gray-500 text-xs">Nhận phòng</p>
                        <p class="font-semibold text-sm">{{ $booking->check_in_date->format('d/m/Y') }}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-gray-500 text-xs">Số đêm</p>
                        <p class="font-semibold text-sm">{{ $booking->details->first()?->number_of_nights ?? 0 }} đêm</p>
                    </div>
                    <div class="text-right">
                        <p class="text-gray-500 text-xs">Trả phòng</p>
                        <p class="font-semibold text-sm">{{ $booking->check_out_date->format('d/m/Y') }}</p>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-gray-500 text-xs">Tổng tiền</span>
                        <span class="text-teal-600 font-bold text-xl ml-2">{{ number_format($booking->total_amount) }}đ</span>
                    </div>
                    <div class="flex gap-2 flex-wrap">
                        <a href="{{ route('bookings.show', $booking) }}"
                           class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 text-sm font-semibold">Chi tiết</a>

                        @if($booking->status === 'confirmed' && !$booking->is_paid)
                        <a href="{{ route('payment.show', $booking) }}"
                           class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm font-semibold">💳 Thanh toán</a>
                        @elseif($booking->status === 'confirmed' && $booking->is_paid)
                        <span class="px-4 py-2 bg-green-100 text-green-700 rounded-lg text-sm font-semibold">✅ Đã thanh toán</span>
                        @elseif($booking->status === 'pending')
                        <span class="px-4 py-2 bg-yellow-100 text-yellow-700 rounded-lg text-sm font-semibold">⏳ Chờ duyệt</span>
                        @endif

                        @if($booking->status !== 'cancelled' && $booking->status !== 'completed')
                        <form action="{{ route('bookings.cancel', $booking) }}" method="POST"
                              onsubmit="return confirm('Bạn có chắc muốn hủy?')">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 text-sm font-semibold">Hủy</button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="bg-white p-12 rounded-lg shadow text-center">
        <div class="text-6xl mb-4">📋</div>
        <p class="text-xl text-gray-600 mb-4">Bạn chưa có đặt phòng nào</p>
        <a href="{{ route('hotels.search') }}" class="bg-teal-600 text-white px-6 py-2 rounded-lg hover:bg-teal-700 inline-block">Tìm khách sạn</a>
    </div>
    @endforelse

    <div class="mt-6">{{ $bookings->links() }}</div>
</div>

@endsection