@extends('admin.layouts.app')

@section('title', 'Quản lý đặt phòng')
@section('page-title', 'Quản lý đặt phòng')

@section('content')

@php
$statusLabels = [
    'pending'   => ['label' => 'Chờ duyệt',  'class' => 'bg-yellow-100 text-yellow-700'],
    'confirmed' => ['label' => 'Đã duyệt',   'class' => 'bg-green-100 text-green-700'],
    'cancelled' => ['label' => 'Đã hủy',     'class' => 'bg-red-100 text-red-700'],
    'completed' => ['label' => 'Hoàn thành', 'class' => 'bg-blue-100 text-blue-700'],
];
@endphp

<div class="bg-white rounded-lg shadow p-5 mb-6">
    <form action="{{ route('admin.bookings.index') }}" method="GET">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-3">
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1.5">Tìm kiếm</label>
                <input type="text" name="search" value="{{ request('search') }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-teal-500"
                    placeholder="Mã, tên, email...">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1.5">Trạng thái</label>
                <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-teal-500 bg-white">
                    <option value="">Tất cả trạng thái</option>
                    @foreach($statusLabels as $value => $status)
                    <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>{{ $status['label'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white px-5 py-2 rounded-lg text-sm font-semibold">Tìm kiếm</button>
            <a href="{{ route('admin.bookings.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-5 py-2 rounded-lg text-sm font-semibold">Xóa lọc</a>
        </div>
    </form>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full min-w-max">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50">
                    <th class="text-left px-4 py-3.5 text-xs font-semibold text-gray-500 uppercase whitespace-nowrap">Mã đặt phòng</th>
                    <th class="text-left px-4 py-3.5 text-xs font-semibold text-gray-500 uppercase whitespace-nowrap">Khách hàng</th>
                    <th class="text-left px-4 py-3.5 text-xs font-semibold text-gray-500 uppercase whitespace-nowrap">Khách sạn / Phòng</th>
                    <th class="text-left px-4 py-3.5 text-xs font-semibold text-gray-500 uppercase whitespace-nowrap">Ngày</th>
                    <th class="text-left px-4 py-3.5 text-xs font-semibold text-gray-500 uppercase whitespace-nowrap">Trạng thái</th>
                    <th class="text-left px-4 py-3.5 text-xs font-semibold text-gray-500 uppercase whitespace-nowrap">Thanh toán</th>
                    <th class="text-right px-4 py-3.5 text-xs font-semibold text-gray-500 uppercase whitespace-nowrap">Tổng tiền</th>
                    <th class="text-center px-4 py-3.5 text-xs font-semibold text-gray-500 uppercase whitespace-nowrap">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($bookings as $booking)
                @php $s = $statusLabels[$booking->status] ?? ['label' => $booking->status, 'class' => 'bg-gray-100 text-gray-700']; @endphp
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3">
                        <a href="{{ route('admin.bookings.show', $booking) }}"
                            class="text-teal-600 hover:text-teal-700 font-semibold text-sm whitespace-nowrap">
                            {{ $booking->booking_reference }}
                        </a>
                    </td>
                    <td class="px-4 py-3">
                        <p class="font-medium text-gray-700 text-sm whitespace-nowrap">{{ $booking->guest_name }}</p>
                        <p class="text-gray-400 text-xs">{{ $booking->guest_email }}</p>
                    </td>
                    <td class="px-4 py-3">
                        <p class="text-gray-600 text-sm max-w-[180px] truncate">{{ $booking->hotel->name }}</p>
                        <p class="text-gray-400 text-xs max-w-[180px] truncate">
                            {{ $booking->details->map(fn($d) => $d->room_name . ($d->quantity > 1 ? " ×{$d->quantity}" : ''))->join(', ') }}
                        </p>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <p class="text-gray-600 text-sm">{{ $booking->check_in_date->format('d/m/Y') }}</p>
                        <p class="text-gray-400 text-xs">→ {{ $booking->check_out_date->format('d/m/Y') }}</p>
                    </td>
                    <td class="px-4 py-3">
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold whitespace-nowrap {{ $s['class'] }}">{{ $s['label'] }}</span>
                    </td>
                    <td class="px-4 py-3">
                        @if($booking->is_paid)
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold whitespace-nowrap bg-green-100 text-green-700">✅ Đã thanh toán</span>
                        @else
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold whitespace-nowrap bg-gray-100 text-gray-500">⏳ Chưa thanh toán</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-right whitespace-nowrap">
                        <span class="font-bold text-gray-700 text-sm">{{ number_format($booking->total_amount) }}đ</span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <a href="{{ route('admin.bookings.show', $booking) }}"
                            class="px-3 py-1 bg-teal-50 hover:bg-teal-100 text-teal-600 rounded text-xs font-semibold">Xem</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-16 text-center">
                        <p class="text-gray-400 text-sm">Không có đặt phòng nào</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-50">{{ $bookings->links() }}</div>
</div>

@endsection