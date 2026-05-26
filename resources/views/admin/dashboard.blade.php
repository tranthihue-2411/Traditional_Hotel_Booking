@extends('admin.layouts.app')

@section('title', 'Bảng điều khiển')
@section('page-title', 'Bảng điều khiển')

@section('content')

@php
$statusLabels = [
    'pending'   => ['label' => 'Chờ duyệt',  'class' => 'bg-yellow-100 text-yellow-700'],
    'confirmed' => ['label' => 'Đã duyệt',   'class' => 'bg-green-100 text-green-700'],
    'cancelled' => ['label' => 'Đã hủy',     'class' => 'bg-red-100 text-red-700'],
    'completed' => ['label' => 'Hoàn thành', 'class' => 'bg-blue-100 text-blue-700'],
];
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Tổng doanh thu</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($totalRevenue) }}đ</p>
                <p class="text-sm mt-1 {{ $revenueTrend >= 0 ? 'text-green-500' : 'text-red-500' }}">
                    {{ $revenueTrend >= 0 ? '↑' : '↓' }} {{ abs($revenueTrend) }}% so với tháng trước
                </p>
            </div>
            <div class="text-3xl">💰</div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Tổng đặt phòng</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ $totalBookings }}</p>
                <p class="text-sm mt-1 {{ $bookingTrend >= 0 ? 'text-green-500' : 'text-red-500' }}">
                    {{ $bookingTrend >= 0 ? '↑' : '↓' }} {{ abs($bookingTrend) }} so với tháng trước
                </p>
            </div>
            <div class="text-3xl">📋</div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Tổng người dùng</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ $totalUsers }}</p>
                <p class="text-sm text-gray-400 mt-1">Tài khoản đã đăng ký</p>
            </div>
            <div class="text-3xl">👥</div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Tổng khách sạn</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ $totalHotels }}</p>
                <p class="text-sm text-gray-400 mt-1">Đang hoạt động</p>
            </div>
            <div class="text-3xl">🏨</div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b flex items-center justify-between">
            <h3 class="text-lg font-semibold">Đặt phòng gần đây</h3>
            <a href="{{ route('admin.bookings.index') }}" class="text-teal-600 hover:underline text-sm">Xem tất cả</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mã</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Khách sạn</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Trạng thái</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tổng</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($recentBookings as $booking)
                    @php $s = $statusLabels[$booking->status] ?? ['label' => $booking->status, 'class' => 'bg-gray-100 text-gray-700']; @endphp
                    <tr>
                        <td class="px-4 py-3 text-sm">
                            <a href="{{ route('admin.bookings.show', $booking) }}"
                               class="text-teal-600 hover:underline font-semibold">
                                {{ $booking->booking_reference }}
                            </a>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-700">{{ $booking->hotel->name }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded-full text-xs font-medium whitespace-nowrap {{ $s['class'] }}">
                                {{ $s['label'] }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm font-semibold">{{ number_format($booking->total_amount) }}đ</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-gray-400 text-sm">Chưa có đặt phòng nào</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h3 class="text-lg font-semibold">Khách sạn nổi bật</h3>
        </div>
        <div class="p-6">
            <div class="space-y-4 mb-6">
                @forelse($topHotels as $index => $hotel)
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-teal-600 rounded-full flex items-center justify-center text-white text-sm font-bold">
                            {{ $index + 1 }}
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800 text-sm">{{ $hotel->name }}</p>
                            <p class="text-gray-400 text-xs">{{ $hotel->city }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-semibold">{{ $hotel->bookings_count }} đặt phòng</p>
                        <p class="text-xs text-gray-400">⭐ {{ number_format($hotel->rating, 1) }}</p>
                    </div>
                </div>
                @empty
                <p class="text-gray-400 text-sm">Chưa có dữ liệu</p>
                @endforelse
            </div>

            <div class="border-t pt-4">
                <h4 class="font-semibold text-gray-700 text-sm mb-3">Theo trạng thái</h4>
                <div class="space-y-2">
                    @foreach($statusLabels as $key => $status)
                    <div class="flex justify-between items-center">
                        <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $status['class'] }}">
                            {{ $status['label'] }}
                        </span>
                        <span class="font-semibold text-gray-700 text-sm">{{ $bookingsByStatus[$key] ?? 0 }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

</div>

@endsection