@extends('admin.layouts.app')

@section('title', 'Chi tiết đặt phòng')
@section('page-title', 'Chi tiết đặt phòng')

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

<div class="mb-4">
    <a href="{{ route('admin.bookings.index') }}" class="text-teal-600 hover:text-teal-700 text-sm">← Quay lại danh sách</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <div class="lg:col-span-2 space-y-5">

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b flex items-center justify-between">
                <div>
                    <h2 class="font-bold text-gray-800">{{ $booking->booking_reference }}</h2>
                    <p class="text-gray-400 text-xs mt-0.5">Đặt lúc: {{ $booking->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <span class="px-3 py-1.5 rounded-full text-xs font-semibold whitespace-nowrap {{ $s['class'] }}">{{ $s['label'] }}</span>
            </div>

            <div class="p-6">
                {{-- Khách sạn --}}
                <div class="flex gap-4 mb-5 pb-5 border-b">
                    <img src="{{ $booking->hotel->main_image ?? 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=200&h=150&fit=crop' }}"
                        alt="{{ $booking->hotel->name }}"
                        class="w-20 h-16 rounded-lg object-cover flex-shrink-0">
                    <div>
                        <h3 class="font-bold text-gray-800">{{ $booking->hotel->name }}</h3>
                        <p class="text-gray-400 text-xs mt-1">📍 {{ $booking->hotel->city }}, {{ $booking->hotel->province }}</p>
                    </div>
                </div>

                {{-- Ngày --}}
                <div class="grid grid-cols-2 gap-3 mb-5 pb-5 border-b">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-400 text-xs mb-1">Nhận phòng</p>
                        <p class="font-bold text-gray-800">{{ $booking->check_in_date->format('d/m/Y') }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-400 text-xs mb-1">Trả phòng</p>
                        <p class="font-bold text-gray-800">{{ $booking->check_out_date->format('d/m/Y') }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-400 text-xs mb-1">Số đêm</p>
                        <p class="font-bold text-gray-800">{{ $booking->details->first()?->number_of_nights ?? 0 }} đêm</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-400 text-xs mb-1">Số khách</p>
                        <p class="font-bold text-gray-800">{{ $booking->number_of_guests }} khách</p>
                    </div>
                </div>

                {{-- Chi tiết phòng --}}
                <div class="mb-5 pb-5 border-b">
                    <p class="text-gray-400 text-xs mb-3 font-semibold uppercase">Danh sách phòng</p>
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

                {{-- Thanh toán --}}
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <p class="text-gray-400 text-xs mb-2">💳 Trạng thái thanh toán</p>
                    @if($booking->is_paid)
                    <p class="font-bold text-green-600 text-sm">✅ Đã thanh toán</p>
                    <p class="text-gray-400 text-xs mt-1">{{ $booking->paid_at->format('d/m/Y H:i') }}</p>
                    <p class="text-gray-400 text-xs mt-0.5">
                        @if($booking->payment_method === 'credit_card') 💳 Thẻ tín dụng
                        @elseif($booking->payment_method === 'bank_transfer') 🏦 Chuyển khoản ngân hàng
                        @else 💵 Tiền mặt tại quầy
                        @endif
                    </p>
                    @else
                    <p class="font-bold text-yellow-600 text-sm">⏳ Chưa thanh toán</p>
                    @if($booking->payment_deadline)
                    <p class="text-gray-400 text-xs mt-1">Hạn thanh toán: <strong>{{ $booking->payment_deadline->format('H:i d/m/Y') }}</strong></p>
                    @endif
                    @endif
                </div>
            </div>
        </div>

        {{-- Thông tin khách hàng --}}
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="font-bold text-gray-800 mb-4">Thông tin khách hàng</h3>
            <div class="grid grid-cols-2 gap-4">
                <div><p class="text-gray-400 text-xs mb-1">Họ tên</p><p class="font-semibold text-gray-700 text-sm">{{ $booking->guest_name }}</p></div>
                <div><p class="text-gray-400 text-xs mb-1">Email</p><p class="font-semibold text-gray-700 text-sm">{{ $booking->guest_email }}</p></div>
                @if($booking->guest_phone)
                <div><p class="text-gray-400 text-xs mb-1">Điện thoại</p><p class="font-semibold text-gray-700 text-sm">{{ $booking->guest_phone }}</p></div>
                @endif
                @if($booking->special_requests)
                <div class="col-span-2"><p class="text-gray-400 text-xs mb-1">Yêu cầu đặc biệt</p><p class="font-semibold text-gray-700 text-sm">{{ $booking->special_requests }}</p></div>
                @endif
            </div>
        </div>

        {{-- Cập nhật trạng thái --}}
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="font-bold text-gray-800 mb-4">Cập nhật trạng thái</h3>

            @if(in_array($booking->status, ['completed', 'cancelled']))
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-sm text-gray-500">
                🔒 Không thể thay đổi trạng thái đặt phòng đã <strong>{{ $booking->status === 'completed' ? 'hoàn thành' : 'hủy' }}</strong>.
            </div>
            @else

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-4 text-red-600 text-sm">
                @foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach
            </div>
            @endif

            <form action="{{ route('admin.bookings.update-status', $booking) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="flex gap-3 mb-3">
                    <select name="status" id="statusSelect"
                        class="flex-1 border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-500 bg-white"
                        onchange="toggleReasonField(this.value)">
                        <option value="pending"   {{ old('status', $booking->status) === 'pending'   ? 'selected' : '' }}>⏳ Chờ duyệt</option>
                        <option value="confirmed" {{ old('status', $booking->status) === 'confirmed' ? 'selected' : '' }}>✅ Đã duyệt</option>
                        <option value="cancelled" {{ old('status', $booking->status) === 'cancelled' ? 'selected' : '' }}>❌ Đã hủy</option>
                        <option value="completed" {{ old('status', $booking->status) === 'completed' ? 'selected' : '' }}>🏁 Hoàn thành</option>
                    </select>
                    <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white px-5 py-2.5 rounded-lg text-sm font-semibold">Cập nhật</button>
                </div>
                <div id="reasonField" class="hidden mb-3 space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Lý do hủy <span class="text-red-500">*</span></label>
                        <textarea id="reasonTextarea" name="reason" rows="3"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-teal-500"
                            placeholder="Nhập lý do hủy đặt phòng...">{{ old('reason') }}</textarea>
                    </div>
                    @if($booking->is_paid)
                    <div class="flex items-center gap-2 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <input type="checkbox" name="refund" id="refundCheckbox" value="1"
                               class="w-4 h-4 text-teal-600 rounded">
                        <label for="refundCheckbox" class="text-sm text-gray-700">
                            Hoàn tiền cho khách
                            <span class="text-gray-400 text-xs ml-1">(booking đã thanh toán {{ number_format($booking->total_amount) }}đ)</span>
                        </label>
                    </div>
                    @endif
                </div>
                <p class="text-gray-400 text-xs">ℹ️ Chỉ chuyển sang <strong>Hoàn thành</strong> khi khách đã thực sự check-out.</p>
            </form>
            @endif
        </div>

    </div>

    <div class="space-y-5">

        {{-- Tổng tiền --}}
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="bg-gradient-to-r from-teal-500 to-teal-700 px-6 py-5">
                <p class="text-teal-100 text-xs mb-1">Tổng thanh toán</p>
                <p class="text-white font-bold text-3xl">{{ number_format($booking->total_amount) }}đ</p>
            </div>
            <div class="p-5 space-y-3 text-sm">
                @foreach($booking->details as $detail)
                <div class="flex justify-between">
                    <span class="text-gray-500">{{ $detail->room_name }} × {{ $detail->quantity }}</span>
                    <span class="font-medium text-gray-700">{{ number_format($detail->subtotal) }}đ</span>
                </div>
                @endforeach
                <div class="flex justify-between"><span class="text-gray-500">Thuế (10%)</span><span class="font-medium text-gray-700">{{ number_format($booking->taxes) }}đ</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Phí dịch vụ</span><span class="font-medium text-gray-700">{{ number_format($booking->service_fee) }}đ</span></div>
                @if($booking->discount > 0)
                <div class="flex justify-between"><span class="text-green-500">Giảm giá</span><span class="text-green-500 font-medium">-{{ number_format($booking->discount) }}đ</span></div>
                @endif
                <div class="flex justify-between font-bold text-gray-800 pt-3 border-t">
                    <span>Tổng cộng</span>
                    <span class="text-teal-600 text-lg">{{ number_format($booking->total_amount) }}đ</span>
                </div>
            </div>
        </div>

        {{-- Thông tin hủy --}}
        @if($booking->status === 'cancelled')
        <div class="bg-red-50 border border-red-200 rounded-lg p-5">
            <h3 class="font-bold text-red-600 mb-3">❌ Thông tin hủy</h3>
            @if($booking->cancelled_at)
            <div class="mb-2">
                <p class="text-red-400 text-xs mb-0.5">Thời gian hủy</p>
                <p class="text-red-600 text-sm font-medium">{{ $booking->cancelled_at->format('d/m/Y H:i') }}</p>
            </div>
            @endif
            @if($booking->cancellation_reason)
            <div class="mb-2">
                <p class="text-red-400 text-xs mb-0.5">Lý do</p>
                <p class="text-red-600 text-sm font-medium">{{ $booking->cancellation_reason }}</p>
            </div>
            @endif
            <div>
                <p class="text-red-400 text-xs mb-0.5">Hoàn tiền</p>
                @if($booking->refund_status === 'none' || !$booking->refund_status)
                    <span class="text-gray-500 text-sm">Không áp dụng</span>
                @elseif($booking->refund_status === 'pending')
                    <div class="space-y-2">
                        <span class="inline-flex items-center gap-1 text-yellow-700 bg-yellow-100 px-2 py-0.5 rounded text-xs font-semibold">🔄 Đang xử lý</span>
                        <form action="{{ route('admin.bookings.confirm-refund', $booking) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                onclick="return confirm('Xác nhận đã hoàn tiền cho khách?')"
                                class="w-full mt-1 px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white rounded text-xs font-semibold">
                                ✅ Xác nhận đã hoàn tiền
                            </button>
                        </form>
                    </div>
                @elseif($booking->refund_status === 'completed')
                    <span class="inline-flex items-center gap-1 text-green-700 bg-green-100 px-2 py-0.5 rounded text-xs font-semibold">✅ Đã hoàn tiền</span>
                @endif
            </div>
        </div>
        @endif

    </div>

</div>

@push('scripts')
<script>
function toggleReasonField(status) {
    const field = document.getElementById('reasonField');
    const textarea = document.getElementById('reasonTextarea');
    if (!field || !textarea) return;
    if (status === 'cancelled') {
        field.classList.remove('hidden');
        textarea.setAttribute('required', 'required');
    } else {
        field.classList.add('hidden');
        textarea.removeAttribute('required');
    }
}
document.addEventListener('DOMContentLoaded', function () {
    const sel = document.getElementById('statusSelect');
    if (sel) toggleReasonField(sel.value);
});
</script>
@endpush

@endsection