@extends('layouts.main')
@section('title', 'Thanh toán - HotelHub')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <h1 class="text-3xl font-bold mb-6">Thanh Toán</h1>

    @if(session('info'))
    <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-4">
        {{ session('info') }}
    </div>
    @endif

    @if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-6">Chọn phương thức thanh toán</h2>

                <form action="{{ route('payment.process', $booking) }}" method="POST">
                    @csrf

                    <div class="space-y-3 mb-6">
                        <label class="flex items-center gap-3 border border-gray-200 rounded-lg p-4 cursor-pointer hover:border-teal-500 transition">
                            <input type="radio" name="payment_method" value="credit_card"
                                   class="text-teal-600" checked onchange="showPaymentForm(this.value)">
                            <div class="flex items-center gap-3">
                                <span class="text-2xl">💳</span>
                                <div>
                                    <p class="font-semibold">Thẻ tín dụng / Ghi nợ</p>
                                    <p class="text-sm text-gray-500">Visa, Mastercard, JCB</p>
                                </div>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 border border-gray-200 rounded-lg p-4 cursor-pointer hover:border-teal-500 transition">
                            <input type="radio" name="payment_method" value="bank_transfer"
                                   class="text-teal-600" onchange="showPaymentForm(this.value)">
                            <div class="flex items-center gap-3">
                                <span class="text-2xl">🏦</span>
                                <div>
                                    <p class="font-semibold">Chuyển khoản ngân hàng</p>
                                    <p class="text-sm text-gray-500">Vietcombank, BIDV, Techcombank...</p>
                                </div>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 border border-gray-200 rounded-lg p-4 cursor-pointer hover:border-teal-500 transition">
                            <input type="radio" name="payment_method" value="cash"
                                   class="text-teal-600" onchange="showPaymentForm(this.value)">
                            <div class="flex items-center gap-3">
                                <span class="text-2xl">💵</span>
                                <div>
                                    <p class="font-semibold">Thanh toán tại khách sạn</p>
                                    <p class="text-sm text-gray-500">Trả tiền mặt khi nhận phòng</p>
                                </div>
                            </div>
                        </label>
                    </div>

                    <div id="credit_card_form" class="border border-gray-200 rounded-lg p-5 mb-6">
                        <h3 class="font-semibold mb-4 text-gray-700">Thông tin thẻ</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tên chủ thẻ</label>
                                <input type="text" name="card_name" value="{{ auth()->user()->name }}"
                                       placeholder="NGUYEN VAN A"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 uppercase">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Số thẻ</label>
                                <input type="text" name="card_number"
                                       placeholder="1234 5678 9012 3456" maxlength="19"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 font-mono"
                                       oninput="formatCardNumber(this)">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Ngày hết hạn</label>
                                    <input type="text" name="card_expiry"
                                           placeholder="MM/YY" maxlength="5"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 font-mono"
                                           oninput="formatExpiry(this)">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">CVV</label>
                                    <input type="text" name="card_cvv"
                                           placeholder="123" maxlength="3"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 font-mono">
                                </div>
                            </div>
                        </div>
                        <p class="text-xs text-gray-400 mt-3">🔒 Thông tin thẻ được bảo mật theo tiêu chuẩn PCI DSS</p>
                    </div>

                    <div id="bank_transfer_form" class="border border-gray-200 rounded-lg p-5 mb-6 hidden">
                        <h3 class="font-semibold mb-4 text-gray-700">Thông tin chuyển khoản</h3>
                        <div class="bg-gray-50 rounded-lg p-4 space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Ngân hàng:</span>
                                <span class="font-semibold">Vietcombank</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Số tài khoản:</span>
                                <span class="font-semibold font-mono">1234 5678 9012</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Chủ tài khoản:</span>
                                <span class="font-semibold">CONG TY HOTELHUB</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Nội dung CK:</span>
                                <span class="font-semibold text-teal-600">{{ $booking->booking_reference }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Số tiền:</span>
                                <span class="font-semibold text-teal-600">{{ number_format($booking->total_amount) }}đ</span>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-3">⚠️ Vui lòng chuyển khoản đúng nội dung để hệ thống tự động xác nhận.</p>
                    </div>

                    <div id="cash_form" class="border border-gray-200 rounded-lg p-5 mb-6 hidden">
                        <h3 class="font-semibold mb-3 text-gray-700">Thanh toán tại khách sạn</h3>
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <p class="text-yellow-800 text-sm">
                                ⚠️ Đặt phòng sẽ được giữ trong <strong>24 giờ</strong>. Vui lòng đến khách sạn và thanh toán khi nhận phòng.
                            </p>
                        </div>
                    </div>

                    <button type="submit"
                            class="w-full bg-teal-600 text-white py-3 rounded-lg font-semibold hover:bg-teal-700 transition text-lg">
                        ✅ Xác nhận thanh toán {{ number_format($booking->total_amount) }}đ
                    </button>
                </form>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow p-6 sticky top-4">
                <h3 class="font-semibold text-lg mb-4 pb-3 border-b">Tóm tắt đơn hàng</h3>

                <img src="{{ $booking->hotel->main_image ?? 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=400&h=300&fit=crop' }}"
                     alt="{{ $booking->hotel->name }}"
                     class="w-full h-32 object-cover rounded-lg mb-4">

                <h4 class="font-semibold text-gray-800 mb-1">{{ $booking->hotel->name }}</h4>
                <p class="text-gray-500 text-sm mb-3">📍 {{ $booking->hotel->city }}</p>

                <div class="space-y-2 text-sm mb-4 pb-4 border-b">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Phòng</span>
                        <span class="font-medium">{{ $booking->room->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Nhận phòng</span>
                        <span class="font-medium">{{ $booking->check_in_date->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Trả phòng</span>
                        <span class="font-medium">{{ $booking->check_out_date->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Số đêm</span>
                        <span class="font-medium">{{ $booking->number_of_nights }} đêm</span>
                    </div>
                </div>

                <div class="space-y-2 text-sm mb-4">
                    <div class="flex justify-between">
                        <span class="text-gray-600">{{ number_format($booking->room_price_per_night) }}đ × {{ $booking->number_of_nights }} đêm</span>
                        <span>{{ number_format($booking->subtotal) }}đ</span>
                    </div>
                    <div class="flex justify-between text-gray-500">
                        <span>Thuế (10%)</span>
                        <span>{{ number_format($booking->taxes) }}đ</span>
                    </div>
                    <div class="flex justify-between text-gray-500">
                        <span>Phí dịch vụ</span>
                        <span>{{ number_format($booking->service_fee) }}đ</span>
                    </div>
                </div>

                <div class="flex justify-between font-bold text-lg pt-3 border-t">
                    <span>Tổng cộng</span>
                    <span class="text-teal-600">{{ number_format($booking->total_amount) }}đ</span>
                </div>

                <div class="mt-4 space-y-2">
                    <div class="flex items-center gap-2 text-xs text-gray-400">
                        🔒 <span>Thanh toán an toàn & bảo mật</span>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-gray-400">
                        🔄 <span>Hủy miễn phí trước ngày nhận phòng</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
function showPaymentForm(method) {
    document.getElementById('credit_card_form').classList.add('hidden');
    document.getElementById('bank_transfer_form').classList.add('hidden');
    document.getElementById('cash_form').classList.add('hidden');
    document.getElementById(method + '_form').classList.remove('hidden');
}

function formatCardNumber(input) {
    let value = input.value.replace(/\D/g, '').substring(0, 16);
    input.value = value.replace(/(.{4})/g, '$1 ').trim();
}

function formatExpiry(input) {
    let value = input.value.replace(/\D/g, '').substring(0, 4);
    if (value.length >= 2) value = value.substring(0, 2) + '/' + value.substring(2);
    input.value = value;
}
</script>
@endpush

@endsection