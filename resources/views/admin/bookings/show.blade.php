@extends('admin.layouts.app')
@section('title', 'Chi tiết đặt phòng - Admin')
@section('page-title', 'Chi tiết đặt phòng')

@section('content')

<div class="mb-4">
    <a href="{{ route('admin.bookings.index') }}" class="text-blue-600 hover:text-blue-700 text-sm flex items-center gap-1.5">
        <i class="fas fa-arrow-left text-xs"></i> Quay lại danh sách
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <div class="lg:col-span-2 space-y-5">

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-50 flex items-center justify-between">
                <div>
                    <h2 class="font-bold text-slate-800 flex items-center gap-2">
                        <i class="fas fa-calendar-check text-blue-500 text-sm"></i>
                        {{ $booking->booking_reference }}
                    </h2>
                    <p class="text-slate-400 text-xs mt-0.5">Đặt lúc: {{ $booking->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <span class="px-3 py-1.5 rounded-full text-xs font-semibold
                    {{ $booking->status === 'confirmed' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : '' }}
                    {{ $booking->status === 'pending' ? 'bg-amber-50 text-amber-600 border border-amber-100' : '' }}
                    {{ $booking->status === 'cancelled' ? 'bg-red-50 text-red-500 border border-red-100' : '' }}
                    {{ $booking->status === 'completed' ? 'bg-blue-50 text-blue-600 border border-blue-100' : '' }}">
                    {{ ucfirst($booking->status) }}
                </span>
            </div>

            <div class="p-6">
                <div class="flex gap-4 mb-5 pb-5 border-b border-slate-50">
                    <img src="{{ $booking->hotel->main_image ?? 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=200&h=150&fit=crop' }}"
                        alt="{{ $booking->hotel->name }}"
                        class="w-20 h-16 rounded-xl object-cover flex-shrink-0">
                    <div>
                        <h3 class="font-bold text-slate-800">{{ $booking->hotel->name }}</h3>
                        <p class="text-slate-400 text-sm mt-0.5">
                            <i class="fas fa-bed mr-1 text-slate-300"></i>{{ $booking->room->name }}
                        </p>
                        <p class="text-slate-400 text-xs mt-1">
                            <i class="fas fa-map-marker-alt mr-1 text-slate-300"></i>
                            {{ $booking->hotel->city }}, {{ $booking->hotel->province }}
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                        <p class="text-slate-400 text-xs mb-1"><i class="fas fa-sign-in-alt mr-1 text-blue-400"></i>Nhận phòng</p>
                        <p class="font-bold text-slate-800">{{ $booking->check_in_date->format('d/m/Y') }}</p>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                        <p class="text-slate-400 text-xs mb-1"><i class="fas fa-sign-out-alt mr-1 text-blue-400"></i>Trả phòng</p>
                        <p class="font-bold text-slate-800">{{ $booking->check_out_date->format('d/m/Y') }}</p>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                        <p class="text-slate-400 text-xs mb-1"><i class="fas fa-moon mr-1 text-blue-400"></i>Số đêm</p>
                        <p class="font-bold text-slate-800">{{ $booking->number_of_nights }} đêm</p>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                        <p class="text-slate-400 text-xs mb-1"><i class="fas fa-user mr-1 text-blue-400"></i>Số khách</p>
                        <p class="font-bold text-slate-800">{{ $booking->number_of_guests }} khách</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                <i class="fas fa-user text-blue-500 text-sm"></i> Thông tin khách hàng
            </h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-slate-400 text-xs mb-1">Họ tên</p>
                    <p class="font-semibold text-slate-700 text-sm">{{ $booking->guest_name }}</p>
                </div>
                <div>
                    <p class="text-slate-400 text-xs mb-1">Email</p>
                    <p class="font-semibold text-slate-700 text-sm">{{ $booking->guest_email }}</p>
                </div>
                @if($booking->guest_phone)
                <div>
                    <p class="text-slate-400 text-xs mb-1">Điện thoại</p>
                    <p class="font-semibold text-slate-700 text-sm">{{ $booking->guest_phone }}</p>
                </div>
                @endif
                @if($booking->special_requests)
                <div class="col-span-2">
                    <p class="text-slate-400 text-xs mb-1">Yêu cầu đặc biệt</p>
                    <p class="font-semibold text-slate-700 text-sm">{{ $booking->special_requests }}</p>
                </div>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                <i class="fas fa-edit text-blue-500 text-sm"></i> Cập nhật trạng thái
            </h3>
            <form action="{{ route('admin.bookings.update-status', $booking) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="flex gap-3">
                    <select name="status"
                        class="flex-1 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="pending" {{ $booking->status === 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                        <option value="confirmed" {{ $booking->status === 'confirmed' ? 'selected' : '' }}>✅ Confirmed</option>
                        <option value="cancelled" {{ $booking->status === 'cancelled' ? 'selected' : '' }}>❌ Cancelled</option>
                        <option value="completed" {{ $booking->status === 'completed' ? 'selected' : '' }}>🏁 Completed</option>
                    </select>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold flex items-center gap-2">
                        <i class="fas fa-save"></i> Cập nhật
                    </button>
                </div>
            </form>
        </div>

    </div>

    <div class="space-y-5">
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="bg-blue-600 px-6 py-5">
                <p class="text-blue-200 text-xs font-medium mb-1">Tổng thanh toán</p>
                <p class="text-white font-bold text-3xl">{{ number_format($booking->total_amount) }}đ</p>
            </div>
            <div class="p-5 space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500">{{ number_format($booking->room_price_per_night) }}đ × {{ $booking->number_of_nights }} đêm</span>
                    <span class="font-medium text-slate-700">{{ number_format($booking->subtotal) }}đ</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500">Thuế (10%)</span>
                    <span class="font-medium text-slate-700">{{ number_format($booking->taxes) }}đ</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500">Phí dịch vụ</span>
                    <span class="font-medium text-slate-700">{{ number_format($booking->service_fee) }}đ</span>
                </div>
                <div class="flex justify-between font-bold text-slate-800 pt-3 border-t border-slate-100">
                    <span>Tổng cộng</span>
                    <span class="text-blue-600 text-lg">{{ number_format($booking->total_amount) }}đ</span>
                </div>
            </div>
        </div>

        @if($booking->status === 'cancelled')
        <div class="bg-red-50 rounded-2xl border border-red-100 p-5">
            <h3 class="font-bold text-red-600 mb-3 flex items-center gap-2">
                <i class="fas fa-times-circle text-sm"></i> Thông tin hủy
            </h3>
            @if($booking->cancelled_at)
            <div class="mb-2">
                <p class="text-red-400 text-xs mb-0.5">Thời gian hủy</p>
                <p class="text-red-600 text-sm font-medium">{{ $booking->cancelled_at->format('d/m/Y H:i') }}</p>
            </div>
            @endif
            @if($booking->cancellation_reason)
            <div>
                <p class="text-red-400 text-xs mb-0.5">Lý do</p>
                <p class="text-red-600 text-sm font-medium">{{ $booking->cancellation_reason }}</p>
            </div>
            @endif
        </div>
        @endif
    </div>

</div>

@endsection