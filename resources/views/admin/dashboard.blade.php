@extends('admin.layouts.app')
@section('title', 'Admin Dashboard - HotelHub')
@section('page-title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm mb-1">Tổng Doanh Thu</p>
                <p class="text-2xl font-bold text-gray-800">{{ number_format($totalRevenue) }}đ</p>
                <p class="text-xs text-gray-500 mt-1">Tháng này: {{ number_format($monthlyRevenue) }}đ</p>
            </div>
            <div class="text-3xl">💰</div>
        </div>
        @if($revenueTrend != 0)
        <p class="text-xs mt-2 {{ $revenueTrend > 0 ? 'text-green-600' : 'text-red-600' }}">
            {{ $revenueTrend > 0 ? '↑' : '↓' }} {{ abs($revenueTrend) }}% so với tháng trước
        </p>
        @endif
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm mb-1">Tổng Đặt Phòng</p>
                <p class="text-2xl font-bold text-gray-800">{{ $totalBookings }}</p>
                <p class="text-xs text-gray-500 mt-1">Đã xác nhận: {{ $confirmedBookings }}</p>
            </div>
            <div class="text-3xl">📋</div>
        </div>
        @if($bookingTrend != 0)
        <p class="text-xs mt-2 {{ $bookingTrend > 0 ? 'text-green-600' : 'text-red-600' }}">
            {{ $bookingTrend > 0 ? '↑' : '↓' }} {{ abs($bookingTrend) }} đặt phòng
        </p>
        @endif
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm mb-1">Tổng Người Dùng</p>
                <p class="text-2xl font-bold text-gray-800">{{ $totalUsers }}</p>
            </div>
            <div class="text-3xl">👥</div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm mb-1">Tổng Khách Sạn</p>
                <p class="text-2xl font-bold text-gray-800">{{ $totalHotels }}</p>
            </div>
            <div class="text-3xl">🏨</div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h3 class="text-lg font-semibold">Đặt Phòng Gần Đây</h3>
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
                <tbody class="divide-y divide-gray-200">
                    @forelse($recentBookings as $booking)
                    <tr>
                        <td class="px-4 py-3 text-sm">
                            <a href="{{ route('admin.bookings.show', $booking) }}" class="text-teal-600 hover:underline">
                                {{ $booking->booking_reference }}
                            </a>
                        </td>
                        <td class="px-4 py-3 text-sm">{{ $booking->hotel->name }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs rounded
                                @if($booking->status == 'confirmed') bg-green-100 text-green-800
                                @elseif($booking->status == 'pending') bg-yellow-100 text-yellow-800
                                @elseif($booking->status == 'cancelled') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm font-semibold">{{ number_format($booking->total_amount) }}đ</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-4 py-3 text-center text-gray-500">Chưa có đặt phòng nào</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t">
            <a href="{{ route('admin.bookings.index') }}" class="text-teal-600 hover:underline text-sm">Xem tất cả →</a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h3 class="text-lg font-semibold">Khách Sạn Phổ Biến</h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                @forelse($topHotels as $hotel)
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-semibold">{{ $hotel->name }}</p>
                        <p class="text-sm text-gray-600">{{ $hotel->city }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-semibold">{{ $hotel->bookings_count }} đặt phòng</p>
                        <p class="text-xs text-gray-500">⭐ {{ number_format($hotel->rating, 1) }}</p>
                    </div>
                </div>
                @empty
                <p class="text-gray-500">Chưa có dữ liệu</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection