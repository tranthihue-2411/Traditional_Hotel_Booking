@extends('admin.layouts.app')
@section('title', 'Quản lý đặt phòng - Admin')
@section('page-title', 'Quản lý đặt phòng')

@section('content')

<div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 mb-6">
    <form action="{{ route('admin.bookings.index') }}" method="GET">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-3">
            <div>
                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1.5">Tìm kiếm</label>
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-2.5 text-slate-300 text-xs"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="w-full border border-slate-200 rounded-xl pl-8 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Mã, tên, email...">
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1.5">Trạng thái</label>
                <select name="status"
                    class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                    <option value="">Tất cả</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-xl text-sm font-semibold flex items-center gap-2">
                    <i class="fas fa-search"></i> Tìm
                </button>
                <a href="{{ route('admin.bookings.index') }}"
                    class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-5 py-2 rounded-xl text-sm font-semibold flex items-center gap-2">
                    <i class="fas fa-times"></i> Xóa lọc
                </a>
            </div>
        </div>
    </form>
</div>

<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    <table class="w-full">
        <thead>
            <tr class="border-b border-slate-100 bg-slate-50">
                <th class="text-left px-6 py-3.5 text-xs font-semibold text-slate-400 uppercase tracking-wider">Mã đặt phòng</th>
                <th class="text-left px-6 py-3.5 text-xs font-semibold text-slate-400 uppercase tracking-wider">Khách hàng</th>
                <th class="text-left px-6 py-3.5 text-xs font-semibold text-slate-400 uppercase tracking-wider">Khách sạn</th>
                <th class="text-left px-6 py-3.5 text-xs font-semibold text-slate-400 uppercase tracking-wider">Ngày</th>
                <th class="text-left px-6 py-3.5 text-xs font-semibold text-slate-400 uppercase tracking-wider">Trạng thái</th>
                <th class="text-right px-6 py-3.5 text-xs font-semibold text-slate-400 uppercase tracking-wider">Tổng tiền</th>
                <th class="text-center px-6 py-3.5 text-xs font-semibold text-slate-400 uppercase tracking-wider">Thao tác</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-50">
            @forelse($bookings as $booking)
            <tr class="hover:bg-slate-50 transition-colors">
                <td class="px-6 py-4">
                    <a href="{{ route('admin.bookings.show', $booking) }}"
                        class="text-blue-600 hover:text-blue-700 font-semibold text-sm">
                        {{ $booking->booking_reference }}
                    </a>
                </td>
                <td class="px-6 py-4">
                    <p class="font-medium text-slate-700 text-sm">{{ $booking->guest_name }}</p>
                    <p class="text-slate-400 text-xs mt-0.5">{{ $booking->guest_email }}</p>
                </td>
                <td class="px-6 py-4">
                    <p class="text-slate-600 text-sm">{{ $booking->hotel->name }}</p>
                    <p class="text-slate-400 text-xs mt-0.5">{{ $booking->room->name }}</p>
                </td>
                <td class="px-6 py-4">
                    <p class="text-slate-600 text-sm">{{ $booking->check_in_date->format('d/m/Y') }}</p>
                    <p class="text-slate-400 text-xs">→ {{ $booking->check_out_date->format('d/m/Y') }}</p>
                </td>
                <td class="px-6 py-4">
                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                        {{ $booking->status === 'confirmed' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : '' }}
                        {{ $booking->status === 'pending' ? 'bg-amber-50 text-amber-600 border border-amber-100' : '' }}
                        {{ $booking->status === 'cancelled' ? 'bg-red-50 text-red-500 border border-red-100' : '' }}
                        {{ $booking->status === 'completed' ? 'bg-blue-50 text-blue-600 border border-blue-100' : '' }}">
                        {{ ucfirst($booking->status) }}
                    </span>
                </td>
                <td class="px-6 py-4 text-right">
                    <span class="font-bold text-slate-700 text-sm">{{ number_format($booking->total_amount) }}đ</span>
                </td>
                <td class="px-6 py-4 text-center">
                    <a href="{{ route('admin.bookings.show', $booking) }}"
                        class="px-3 py-1 bg-blue-100 text-blue-700 rounded text-sm hover:bg-blue-200">
                        Chi tiết
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-16 text-center">
                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-calendar-times text-slate-300 text-2xl"></i>
                    </div>
                    <p class="text-slate-400 text-sm">Không có đặt phòng nào</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-slate-50">
        {{ $bookings->links() }}
    </div>
</div>

@endsection