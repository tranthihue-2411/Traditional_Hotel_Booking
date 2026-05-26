@extends('admin.layouts.app')
@section('title', 'Sửa loại phòng')
@section('page-title', 'Chỉnh sửa loại phòng')

@section('content')

<div class="mb-4">
    <a href="{{ route('admin.hotels.show', $hotel) }}" class="text-teal-600 hover:text-teal-700 text-sm">
        ← Quay lại {{ $hotel->name }}
    </a>
</div>

<div class="max-w-2xl bg-white rounded-lg shadow p-6">
    <form action="{{ route('admin.rooms.update', [$hotel, $room]) }}" method="POST">
        @csrf
        @method('PATCH')
        <div class="grid grid-cols-2 gap-4">

            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Tên loại phòng <span class="text-red-400">*</span></label>
                <input type="text" name="name" value="{{ old('name', $room->name) }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-teal-500">
            </div>

            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Mô tả</label>
                <textarea name="description" rows="3"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-teal-500 resize-none">{{ old('description', $room->description) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Loại phòng <span class="text-red-400">*</span></label>
                <select name="room_type" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-teal-500 bg-white">
                    @foreach(['Single', 'Double', 'Suite', 'Villa'] as $type)
                    <option value="{{ $type }}" {{ old('room_type', $room->room_type) == $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Loại giường</label>
                <input type="text" name="bed_type" value="{{ old('bed_type', $room->bed_type) }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-teal-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Số khách tối đa <span class="text-red-400">*</span></label>
                <input type="number" name="max_guests" value="{{ old('max_guests', $room->max_guests) }}" min="1"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-teal-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Diện tích (m²)</label>
                <input type="number" name="size_sqm" value="{{ old('size_sqm', $room->size_sqm) }}" step="0.1"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-teal-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Giá/đêm (đ) <span class="text-red-400">*</span></label>
                <input type="number" name="price_per_night" value="{{ old('price_per_night', $room->price_per_night) }}" min="0"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-teal-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Số lượng phòng <span class="text-red-400">*</span></label>
                <input type="number" name="total_rooms" value="{{ old('total_rooms', $room->total_rooms) }}" min="1"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-teal-500">
            </div>

            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">URL hình ảnh</label>
                @if($room->image)
                <img src="{{ $room->image }}" alt="{{ $room->name }}"
                     class="w-24 h-16 object-cover rounded mb-2">
                @endif
                <input type="url" name="image" value="{{ old('image', $room->image) }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-teal-500">
            </div>

            <div class="col-span-2">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1"
                        {{ old('is_active', $room->is_active) ? 'checked' : '' }}
                        class="w-4 h-4 accent-teal-600">
                    <span class="text-sm font-medium text-gray-700">Kích hoạt loại phòng này</span>
                </label>
            </div>

        </div>

        <div class="flex gap-3 mt-6 pt-4 border-t">
            <button type="submit"
                class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-2 rounded-lg text-sm font-semibold">
                Cập nhật loại phòng
            </button>
            <a href="{{ route('admin.hotels.show', $hotel) }}"
                class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2 rounded-lg text-sm font-semibold">
                Hủy
            </a>
        </div>
    </form>
</div>

@endsection