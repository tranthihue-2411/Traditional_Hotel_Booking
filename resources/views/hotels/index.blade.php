@extends('layouts.main')
@section('title', 'Trang Chủ - HotelHub')

@section('content')
<section class="bg-gradient-to-r from-teal-500 to-teal-700 text-white py-16">
    <div class="container mx-auto px-4">
        <div class="text-center mb-8">
            <h2 class="text-4xl font-bold mb-4">Tìm Khách Sạn Hoàn Hảo Cho Bạn</h2>
            <p class="text-xl">Hơn 1,000 khách sạn trên toàn Việt Nam</p>
        </div>
        <form action="{{ route('hotels.search') }}" method="GET" class="max-w-4xl mx-auto bg-white rounded-lg shadow-lg p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Địa điểm</label>
                    <input type="text" name="location" placeholder="Hà Nội, Việt Nam"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 text-gray-900">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nhận phòng</label>
                    <input type="date" name="checkin" value="{{ now()->addDay()->format('Y-m-d') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 text-gray-900">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Trả phòng</label>
                    <input type="date" name="checkout" value="{{ now()->addDays(2)->format('Y-m-d') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 text-gray-900">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Khách</label>
                    <input type="number" name="guests" value="2" min="1" max="10"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 text-gray-900">
                </div>
                <div class="md:col-span-4">
                    <button type="submit" class="w-full bg-teal-600 text-white py-3 rounded-lg font-semibold hover:bg-teal-700 transition">
                        🔍 Tìm kiếm
                    </button>
                </div>
            </div>
        </form>
    </div>
</section>

<section class="container mx-auto px-4 py-12">
    <h2 class="text-3xl font-bold mb-8 text-gray-800">Khách Sạn Nổi Bật</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($hotels as $hotel)
        <div class="bg-white rounded-lg shadow overflow-hidden hover:shadow-lg transition">
            <img src="{{ $hotel->main_image ?? 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800&h=600&fit=crop' }}"
                 alt="{{ $hotel->name }}" class="w-full h-48 object-cover">
            <div class="p-4">
                <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $hotel->name }}</h3>
                <p class="text-gray-600 text-sm mb-2">📍 {{ $hotel->city }}</p>
                <div class="flex items-center mb-3">
                    <span class="text-yellow-500">⭐ {{ number_format($hotel->rating, 1) }}</span>
                    <span class="text-gray-500 text-sm ml-2">({{ $hotel->review_count }} đánh giá)</span>
                </div>
                <div class="flex justify-between items-center">
                    <div>
                        <span class="text-2xl font-bold text-teal-600">{{ number_format($hotel->rooms->min('price_per_night')) }}đ</span>
                        <span class="text-gray-500 text-sm">/đêm</span>
                    </div>
                    <a href="{{ route('hotels.show', $hotel) }}" class="bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700">
                        Xem chi tiết
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>

<section class="bg-teal-50 py-12">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="text-4xl font-bold text-teal-600 mb-2">1,234</div>
                <div class="text-gray-600">Khách sạn</div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="text-4xl font-bold text-teal-600 mb-2">45,678</div>
                <div class="text-gray-600">Đặt phòng</div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="text-4xl font-bold text-teal-600 mb-2">4.5⭐</div>
                <div class="text-gray-600">Đánh giá trung bình</div>
            </div>
        </div>
    </div>
</section>
@endsection