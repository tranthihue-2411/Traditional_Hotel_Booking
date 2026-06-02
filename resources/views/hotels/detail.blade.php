@extends('layouts.main')
@section('title', $hotel->name . ' - HotelHub')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <img src="{{ $hotel->main_image ?? 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=1600&h=600&fit=crop' }}"
             alt="{{ $hotel->name }}" class="w-full h-96 object-cover rounded-lg shadow-lg">
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <div class="lg:col-span-2">

            <div class="bg-white p-6 rounded-lg shadow mb-6">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $hotel->name }}</h1>
                <p class="text-gray-600 mb-4">📍 {{ $hotel->address }}, {{ $hotel->city }}, {{ $hotel->province }}</p>
                <div class="flex items-center mb-4">
                    <span class="text-yellow-500 text-2xl">⭐ {{ number_format($hotel->rating, 1) }}</span>
                    <span class="text-gray-600 ml-2">({{ $hotel->review_count }} đánh giá)</span>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow mb-6">
                <h2 class="text-2xl font-bold mb-4">Mô tả</h2>
                <p class="text-gray-700 leading-relaxed">{{ $hotel->description }}</p>
            </div>

            @if($hotel->amenities->count() > 0)
            <div class="bg-white p-6 rounded-lg shadow mb-6">
                <h2 class="text-2xl font-bold mb-4">Tiện ích</h2>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($hotel->amenities as $amenity)
                    <div class="flex items-center">
                        <span class="mr-2">•</span>
                        <span>{{ $amenity->name }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="bg-white p-6 rounded-lg shadow mb-6">
                <h2 class="text-2xl font-bold mb-4">Phòng có sẵn</h2>

                @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
                </div>
                @endif

                <div class="space-y-4">
                    @forelse($availableRooms as $room)
                    <div class="border border-gray-200 rounded-lg p-4 hover:border-teal-500 transition"
                         id="room-{{ $room->id }}">
                        <div class="flex flex-col md:flex-row gap-4">
                            @if($room->image)
                            <img src="{{ $room->image }}" alt="{{ $room->name }}"
                                 class="w-full md:w-48 h-32 object-cover rounded-lg">
                            @else
                            <div class="w-full md:w-48 h-32 bg-gray-100 rounded-lg flex items-center justify-center">
                                <span class="text-3xl">🛏️</span>
                            </div>
                            @endif
                            <div class="flex-1">
                                <h3 class="text-xl font-semibold mb-2">{{ $room->name }}</h3>
                                <p class="text-gray-600 text-sm mb-2">{{ $room->description }}</p>
                                <div class="flex flex-wrap gap-4 text-sm text-gray-600 mb-3">
                                    <span>🛏️ {{ $room->bed_type }}</span>
                                    <span>👥 Tối đa {{ $room->max_guests }} khách</span>
                                    @if($room->size_sqm)<span>📐 {{ $room->size_sqm }} m²</span>@endif
                                </div>
                                <div class="flex justify-between items-center">
                                    <div>
                                        <span class="text-2xl font-bold text-teal-600">{{ number_format($room->price_per_night) }}đ</span>
                                        <span class="text-gray-500 text-sm">/đêm</span>
                                        <p class="text-sm mt-1 room-available-count"
                                           data-room-id="{{ $room->id }}"
                                           data-initial="{{ $room->availableCount($checkIn, $checkOut) }}">
                                            {{ $room->availableCount($checkIn, $checkOut) }} phòng có sẵn
                                        </p>
                                    </div>
                                    @auth
                                    <div class="flex items-center gap-2">
                                        <label class="text-sm text-gray-600">Số lượng:</label>
                                        <input type="number"
                                               id="qty-{{ $room->id }}"
                                               min="0"
                                               max="{{ $room->availableCount($checkIn, $checkOut) }}"
                                               value="0"
                                               data-room-id="{{ $room->id }}"
                                               data-price="{{ $room->price_per_night }}"
                                               data-name="{{ $room->name }}"
                                               class="w-16 border border-gray-300 rounded-lg px-2 py-1 text-center text-sm focus:ring-2 focus:ring-teal-500"
                                               onchange="updateCart()">
                                    </div>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-600">Không có phòng nào khả dụng trong khoảng thời gian này.</p>
                    @endforelse
                </div>
            </div>

            @if($hotel->reviews->count() > 0)
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-2xl font-bold mb-4">Đánh giá ({{ $hotel->review_count }})</h2>
                <div class="space-y-4">
                    @foreach($hotel->reviews as $review)
                    <div class="border-b pb-4 last:border-b-0">
                        <div class="flex justify-between mb-2">
                            <div>
                                <span class="font-semibold">{{ $review->user->name ?? 'Khách' }}</span>
                                <span class="text-yellow-500 ml-2">{{ str_repeat('⭐', $review->rating) }}</span>
                            </div>
                            <span class="text-gray-500 text-sm">{{ $review->created_at->diffForHumans() }}</span>
                        </div>
                        @if($review->comment)<p class="text-gray-700">{{ $review->comment }}</p>@endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        {{-- BOOKING CARD --}}
        <div class="lg:col-span-1">
            <div class="bg-white p-6 rounded-lg shadow sticky top-4">
                <h2 class="text-2xl font-bold mb-4">Đặt phòng</h2>

                @if($availableRooms->count() > 0)
                @auth
                <form action="{{ route('bookings.store') }}" method="POST" id="bookingForm">
                    @csrf
                    <input type="hidden" name="hotel_id" value="{{ $hotel->id }}">
                    <input type="hidden" name="guest_name" value="{{ auth()->user()->name }}">
                    <input type="hidden" name="guest_email" value="{{ auth()->user()->email }}">

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nhận phòng</label>
                            <input type="date" name="check_in_date" id="checkIn"
                                   value="{{ $checkIn }}" required min="{{ date('Y-m-d') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500"
                                   onchange="updatePrice()">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Trả phòng</label>
                            <input type="date" name="check_out_date" id="checkOut"
                                   value="{{ $checkOut }}" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500"
                                   onchange="updatePrice()">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Số khách</label>
                            <input type="number" name="number_of_guests" id="numGuests" value="2" min="1" max="20" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Số điện thoại</label>
                            <input type="text" name="guest_phone" placeholder="090..."
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                        </div>
                    </div>

                    {{-- Phòng đã chọn --}}
                    <div id="cartSummary" class="hidden mt-4 border-t pt-4">
                        <p class="text-sm font-semibold text-gray-700 mb-2">Phòng đã chọn:</p>
                        <div id="cartItems" class="space-y-1 text-sm text-gray-600 mb-3"></div>
                        <div id="hiddenRooms"></div>

                        <div class="pt-3 border-t space-y-1 text-sm">
                            <div class="flex justify-between text-gray-500">
                                <span>Tạm tính</span>
                                <span id="subtotalVal">0đ</span>
                            </div>
                            <div class="flex justify-between text-gray-500">
                                <span>Thuế (10%)</span>
                                <span id="taxVal">0đ</span>
                            </div>
                            <div class="flex justify-between font-bold text-lg pt-2 border-t">
                                <span>Tổng cộng</span>
                                <span id="totalVal" class="text-teal-600">0đ</span>
                            </div>
                        </div>
                    </div>

                    <button type="submit" id="bookBtn"
                            class="w-full bg-gray-300 text-gray-500 py-3 rounded-lg font-semibold mt-4 cursor-not-allowed"
                            disabled>
                        Chọn phòng để đặt
                    </button>
                </form>
                @else
                <a href="{{ route('login') }}" class="block w-full bg-teal-600 text-white py-3 rounded-lg font-semibold hover:bg-teal-700 mt-4 text-center">
                    Đăng nhập để đặt phòng
                </a>
                @endauth
                @else
                <p class="text-gray-600">Không có phòng nào khả dụng trong khoảng thời gian này.</p>
                @endif
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
let ajaxTimer = null;

function updateAvailableCount(checkIn, checkOut) {
    document.querySelectorAll('.room-available-count').forEach(function(el) {
        const roomId = el.dataset.roomId;
        el.textContent = '...';
        el.className = 'text-gray-400 text-sm mt-1 room-available-count';
        fetch(`/rooms/${roomId}/available-count?checkin=${checkIn}&checkout=${checkOut}`)
            .then(r => r.json())
            .then(data => {
                el.textContent = data.count + ' phòng có sẵn';
                el.className = data.count > 0
                    ? 'text-green-600 text-sm mt-1 room-available-count'
                    : 'text-red-500 text-sm mt-1 room-available-count';
                // Cập nhật max của input số lượng
                const qtyInput = document.getElementById('qty-' + roomId);
                if (qtyInput) qtyInput.max = data.count;
            })
            .catch(() => {
                el.textContent = el.dataset.initial + ' phòng có sẵn';
                el.className = 'text-green-600 text-sm mt-1 room-available-count';
            });
    });
}

function updatePrice() {
    const checkIn  = document.getElementById('checkIn').value;
    const checkOut = document.getElementById('checkOut').value;
    if (!checkIn || !checkOut) return;
    const d1 = new Date(checkIn);
    const d2 = new Date(checkOut);
    if (d2 <= d1) return;
    clearTimeout(ajaxTimer);
    ajaxTimer = setTimeout(() => updateAvailableCount(checkIn, checkOut), 500);
    updateCart();
}

function updateCart() {
    const checkIn  = document.getElementById('checkIn').value;
    const checkOut = document.getElementById('checkOut').value;
    if (!checkIn || !checkOut) return;
    const nights = Math.max(1, Math.round((new Date(checkOut) - new Date(checkIn)) / 86400000));

    const inputs = document.querySelectorAll('input[id^="qty-"]');
    let subtotal = 0;
    let cartHTML = '';
    let hiddenHTML = '';
    let roomCount = 0;

    inputs.forEach(input => {
        const qty = parseInt(input.value) || 0;
        if (qty > 0) {
            const price = parseFloat(input.dataset.price);
            const name  = input.dataset.name;
            const roomId = input.dataset.roomId;
            const lineTotal = price * qty * nights;
            subtotal += lineTotal;
            cartHTML += `<div class="flex justify-between"><span>${name} × ${qty}</span><span>${lineTotal.toLocaleString('vi-VN')}đ</span></div>`;
            hiddenHTML += `<input type="hidden" name="rooms[${roomCount}][room_id]" value="${roomId}">`;
            hiddenHTML += `<input type="hidden" name="rooms[${roomCount}][quantity]" value="${qty}">`;
            roomCount++;
        }
    });

    const tax   = Math.round(subtotal * 0.1) + 15;
    const total = subtotal + tax;

    const cartSummary = document.getElementById('cartSummary');
    const bookBtn = document.getElementById('bookBtn');

    if (roomCount > 0) {
        document.getElementById('cartItems').innerHTML = cartHTML;
        document.getElementById('hiddenRooms').innerHTML = hiddenHTML;
        document.getElementById('subtotalVal').textContent = subtotal.toLocaleString('vi-VN') + 'đ';
        document.getElementById('taxVal').textContent = tax.toLocaleString('vi-VN') + 'đ';
        document.getElementById('totalVal').textContent = total.toLocaleString('vi-VN') + 'đ';
        cartSummary.classList.remove('hidden');
        bookBtn.disabled = false;
        bookBtn.className = 'w-full bg-teal-600 text-white py-3 rounded-lg font-semibold hover:bg-teal-700 mt-4 cursor-pointer';
        bookBtn.textContent = `Đặt phòng — ${total.toLocaleString('vi-VN')}đ`;
    } else {
        cartSummary.classList.add('hidden');
        bookBtn.disabled = true;
        bookBtn.className = 'w-full bg-gray-300 text-gray-500 py-3 rounded-lg font-semibold mt-4 cursor-not-allowed';
        bookBtn.textContent = 'Chọn phòng để đặt';
    }
}
</script>
@endpush
@endsection