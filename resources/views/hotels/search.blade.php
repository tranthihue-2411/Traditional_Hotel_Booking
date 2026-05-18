@extends('layouts.main')
@section('title', 'Tìm Kiếm Khách Sạn - HotelHub')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col lg:flex-row gap-6">

        <aside class="lg:w-1/4 bg-white p-6 rounded-lg shadow">
            <h3 class="text-xl font-bold mb-4">Bộ lọc</h3>
            <form action="{{ route('hotels.search') }}" method="GET" id="filterForm">
                @if(request('location'))<input type="hidden" name="location" value="{{ request('location') }}">@endif
                @if(request('checkin'))<input type="hidden" name="checkin" value="{{ request('checkin') }}">@endif
                @if(request('checkout'))<input type="hidden" name="checkout" value="{{ request('checkout') }}">@endif
                @if(request('guests'))<input type="hidden" name="guests" value="{{ request('guests') }}">@endif

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Giá mỗi đêm (đ)</label>
                    <div class="flex gap-2">
                        <input type="number" name="min_price" placeholder="Min" value="{{ request('min_price') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                        <input type="number" name="max_price" placeholder="Max" value="{{ request('max_price') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Xếp hạng</label>
                    <div class="space-y-2">
                        @foreach([5, 4, 3] as $rating)
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="rating[]" value="{{ $rating }}"
                                   {{ in_array($rating, (array)request('rating', [])) ? 'checked' : '' }}
                                   class="mr-2 rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                            <span class="text-yellow-500">{{ str_repeat('⭐', $rating) }} {{ $rating }} sao</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tiện ích</label>
                    <div class="space-y-2 max-h-48 overflow-y-auto">
                        @foreach($amenities ?? [] as $amenity)
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="amenities[]" value="{{ $amenity->id }}"
                                   {{ in_array($amenity->id, (array)request('amenities', [])) ? 'checked' : '' }}
                                   class="mr-2 rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                            <span>{{ $amenity->name }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <button type="submit" class="w-full bg-teal-600 text-white py-2 rounded-lg hover:bg-teal-700 mb-2">
                    Áp dụng bộ lọc
                </button>
                <a href="{{ route('hotels.search') }}" class="block w-full bg-gray-200 text-gray-700 py-2 rounded-lg text-center hover:bg-gray-300">
                    Đặt lại
                </a>
            </form>
        </aside>

        <main class="lg:w-3/4">
            <div class="bg-white p-4 rounded-lg shadow mb-4">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div>
                        <h2 class="text-xl font-bold">Kết quả tìm kiếm</h2>
                        <p class="text-gray-600">
                            @if(request('location'))Tìm kiếm: {{ request('location') }} — @endif
                            Tìm thấy <span class="font-semibold">{{ $hotels->total() }}</span> khách sạn
                        </p>
                    </div>
                    <form action="{{ route('hotels.search') }}" method="GET" class="flex items-center gap-2">
                        @foreach(request()->except('sort') as $key => $value)
                            @if(is_array($value))
                                @foreach($value as $item)<input type="hidden" name="{{ $key }}[]" value="{{ $item }}">@endforeach
                            @else
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endif
                        @endforeach
                        <select name="sort" onchange="this.form.submit()"
                                class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                            <option value="rating-desc" {{ request('sort') == 'rating-desc' ? 'selected' : '' }}>Đánh giá: Cao → Thấp</option>
                            <option value="price-asc" {{ request('sort') == 'price-asc' ? 'selected' : '' }}>Giá: Thấp → Cao</option>
                            <option value="price-desc" {{ request('sort') == 'price-desc' ? 'selected' : '' }}>Giá: Cao → Thấp</option>
                        </select>
                    </form>
                </div>
            </div>

            @if($hotels->count() > 0)
            <div class="space-y-4">
                @foreach($hotels as $hotel)
                <div class="bg-white rounded-lg shadow overflow-hidden hover:shadow-lg transition">
                    <div class="flex flex-col md:flex-row">
                        <img src="{{ $hotel->main_image ?? 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800&h=600&fit=crop' }}"
                             alt="{{ $hotel->name }}" class="w-full md:w-64 h-48 object-cover">
                        <div class="p-6 flex-1">
                            <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $hotel->name }}</h3>
                            <p class="text-gray-600 text-sm mb-3">📍 {{ $hotel->address }}, {{ $hotel->city }}</p>
                            <div class="flex items-center mb-3">
                                <span class="text-yellow-500">⭐ {{ number_format($hotel->rating, 1) }}</span>
                                <span class="text-gray-500 text-sm ml-2">({{ $hotel->review_count }} đánh giá)</span>
                            </div>
                            @if($hotel->amenities->count() > 0)
                            <div class="flex flex-wrap gap-2 mb-3">
                                @foreach($hotel->amenities->take(4) as $amenity)
                                <span class="text-xs bg-gray-100 px-2 py-1 rounded">{{ $amenity->name }}</span>
                                @endforeach
                                @if($hotel->amenities->count() > 4)
                                <span class="text-xs text-gray-500">+{{ $hotel->amenities->count() - 4 }} tiện ích khác</span>
                                @endif
                            </div>
                            @endif
                            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                                <div>
                                    <span class="text-2xl font-bold text-teal-600">{{ number_format($hotel->rooms->min('price_per_night')) }}đ</span>
                                    <span class="text-gray-500 text-sm">/đêm</span>
                                </div>
                                <a href="{{ route('hotels.show', $hotel) }}"
                                   class="bg-teal-600 text-white px-6 py-2 rounded-lg hover:bg-teal-700 transition">
                                    Xem chi tiết
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-6">{{ $hotels->withQueryString()->links() }}</div>
            @else
            <div class="bg-white p-12 rounded-lg shadow text-center">
                <div class="text-6xl mb-4">🔍</div>
                <p class="text-xl text-gray-600 mb-4">Không tìm thấy khách sạn nào</p>
                <a href="{{ route('hotels.search') }}" class="bg-teal-600 text-white px-6 py-2 rounded-lg hover:bg-teal-700 inline-block">
                    Đặt lại bộ lọc
                </a>
            </div>
            @endif
        </main>
    </div>
</div>
@endsection