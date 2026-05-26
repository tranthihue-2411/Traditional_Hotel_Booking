@extends('admin.layouts.app')
@section('title', 'Chi tiết khách sạn')
@section('page-title', 'Chi tiết khách sạn')

@section('content')

<div class="mb-4 flex items-center justify-between">
    <a href="{{ route('admin.hotels.index') }}" class="text-teal-600 hover:text-teal-700 text-sm">
        ← Quay lại danh sách
    </a>
    <a href="{{ route('admin.hotels.edit', $hotel) }}"
        class="bg-yellow-100 text-yellow-700 hover:bg-yellow-200 px-4 py-2 rounded text-sm font-semibold">
        Chỉnh sửa khách sạn
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <div class="lg:col-span-2 space-y-5">

        {{-- Thông tin khách sạn --}}
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">{{ $hotel->name }}</h2>
            @if($hotel->main_image)
            <img src="{{ $hotel->main_image }}" alt="{{ $hotel->name }}"
                 class="w-full h-48 object-cover rounded-lg mb-4">
            @endif
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-500">Địa chỉ</p>
                    <p class="font-medium">{{ $hotel->address }}, {{ $hotel->city }}, {{ $hotel->province }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Điện thoại</p>
                    <p class="font-medium">{{ $hotel->phone ?? 'Chưa có' }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Email</p>
                    <p class="font-medium">{{ $hotel->email ?? 'Chưa có' }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Trạng thái</p>
                    <span class="px-2 py-1 rounded text-xs font-semibold
                        {{ $hotel->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600' }}">
                        {{ $hotel->is_active ? 'Hoạt động' : 'Tạm dừng' }}
                    </span>
                </div>
                <div>
                    <p class="text-gray-500">Đánh giá</p>
                    <p class="font-medium">⭐ {{ number_format($hotel->rating, 1) }} ({{ $hotel->review_count }} đánh giá)</p>
                </div>
            </div>
            @if($hotel->description)
            <div class="mt-4">
                <p class="text-gray-500 text-sm mb-1">Mô tả</p>
                <p class="text-gray-700 text-sm">{{ $hotel->description }}</p>
            </div>
            @endif
        </div>

        {{-- Danh sách loại phòng --}}
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-gray-800">
                    Danh sách loại phòng
                    <span class="ml-2 text-xs bg-teal-100 text-teal-700 px-2 py-0.5 rounded-full font-medium">
                        {{ $hotel->rooms->count() }} loại
                    </span>
                </h3>
                <a href="{{ route('admin.rooms.create', $hotel) }}"
                    class="bg-teal-600 text-white px-4 py-2 rounded text-sm hover:bg-teal-700 font-semibold">
                    + Thêm loại phòng
                </a>
            </div>

            @forelse($hotel->rooms as $room)
            <div class="border border-gray-100 rounded-lg p-4 mb-3 last:mb-0 hover:border-teal-200 transition">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        @if($room->image)
                        <img src="{{ $room->image }}" alt="{{ $room->name }}"
                             class="w-14 h-10 object-cover rounded flex-shrink-0">
                        @else
                        <div class="w-14 h-10 bg-gray-100 rounded flex items-center justify-center flex-shrink-0">
                            <span class="text-lg">🛏️</span>
                        </div>
                        @endif
                        <div>
                            <p class="font-semibold text-gray-800 text-sm">{{ $room->name }}</p>
                            <p class="text-gray-500 text-xs mt-0.5">
                                {{ $room->room_type }}
                                @if($room->bed_type) · {{ $room->bed_type }} @endif
                                · Tối đa {{ $room->max_guests }} khách
                                @if($room->size_sqm) · {{ $room->size_sqm }}m² @endif
                            </p>
                            <p class="text-xs mt-1">
                                <span class="{{ $room->is_active ? 'text-green-600' : 'text-red-500' }}">
                                    {{ $room->is_active ? '✓ Đang hoạt động' : '✗ Tạm dừng' }}
                                </span>
                                <span class="text-gray-300 mx-1">•</span>
                                <span class="text-gray-400">{{ $room->total_rooms }} phòng sẵn có</span>
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 ml-3 flex-shrink-0">
                        <div class="text-right mr-2">
                            <p class="font-bold text-teal-600 text-sm">{{ number_format($room->price_per_night) }}đ</p>
                            <p class="text-gray-400 text-xs">/đêm</p>
                        </div>
                        <a href="{{ route('admin.rooms.edit', [$hotel, $room]) }}"
                            class="px-3 py-1.5 bg-yellow-100 text-yellow-700 rounded text-xs hover:bg-yellow-200 font-medium">
                            Sửa
                        </a>
                        <form action="{{ route('admin.rooms.destroy', [$hotel, $room]) }}" method="POST"
                            onsubmit="return confirm('Bạn chắc muốn xóa loại phòng này?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="px-3 py-1.5 bg-red-100 text-red-600 rounded text-xs hover:bg-red-200 font-medium">
                                Xóa
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-8">
                <p class="text-gray-400 text-sm mb-3">Chưa có loại phòng nào.</p>
                <a href="{{ route('admin.rooms.create', $hotel) }}"
                    class="bg-teal-600 text-white px-5 py-2 rounded text-sm hover:bg-teal-700">
                    Thêm loại phòng đầu tiên
                </a>
            </div>
            @endforelse
        </div>

        {{-- Đánh giá --}}
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="font-bold text-gray-800 mb-4">Đánh giá gần đây ({{ $hotel->reviews->count() }})</h3>
            @forelse($hotel->reviews->take(5) as $review)
            <div class="border-b border-gray-100 pb-3 mb-3 last:border-0 last:mb-0">
                <div class="flex justify-between mb-1">
                    <p class="font-medium text-sm">{{ $review->user->name ?? 'Khách' }}</p>
                    <span class="text-yellow-500 text-sm">{{ str_repeat('⭐', $review->rating) }}</span>
                </div>
                @if($review->comment)
                <p class="text-gray-600 text-sm">{{ $review->comment }}</p>
                @endif
                <p class="text-gray-400 text-xs mt-1">{{ $review->created_at->format('d/m/Y') }}</p>
            </div>
            @empty
            <p class="text-gray-400 text-sm">Chưa có đánh giá nào.</p>
            @endforelse
        </div>

    </div>

    {{-- Cột phải --}}
    <div class="space-y-5">

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="font-bold text-gray-800 mb-4">Thống kê</h3>
            <div class="space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Tổng loại phòng</span>
                    <span class="font-semibold">{{ $hotel->rooms->count() }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Tổng đặt phòng</span>
                    <span class="font-semibold">{{ $hotel->bookings->count() }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Đã duyệt</span>
                    <span class="font-semibold text-green-600">
                        {{ $hotel->bookings->where('status', 'confirmed')->count() }}
                    </span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Đã hủy</span>
                    <span class="font-semibold text-red-500">
                        {{ $hotel->bookings->where('status', 'cancelled')->count() }}
                    </span>
                </div>
                <div class="flex justify-between text-sm border-t pt-3 mt-3">
                    <span class="text-gray-500">Doanh thu</span>
                    <span class="font-bold text-teal-600">
                        {{ number_format($hotel->bookings->where('status', 'confirmed')->sum('total_amount')) }}đ
                    </span>
                </div>
            </div>
        </div>

        @if($hotel->amenities->count() > 0)
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="font-bold text-gray-800 mb-4">Tiện ích</h3>
            <div class="flex flex-wrap gap-2">
                @foreach($hotel->amenities as $amenity)
                <span class="bg-teal-50 text-teal-700 text-xs px-3 py-1 rounded-full border border-teal-100">
                    {{ $amenity->name }}
                </span>
                @endforeach
            </div>
        </div>
        @endif

    </div>

</div>

@endsection