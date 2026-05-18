@extends('admin.layouts.app')
@section('title', 'Thêm khách sạn - Admin')
@section('page-title', 'Thêm khách sạn mới')

@section('content')

<div class="mb-4">
    <a href="{{ route('admin.hotels.index') }}" class="text-blue-600 hover:text-blue-700 text-sm flex items-center gap-1.5">
        <i class="fas fa-arrow-left text-xs"></i> Quay lại danh sách
    </a>
</div>

<div class="max-w-3xl">
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-50">
            <h2 class="font-bold text-slate-800 flex items-center gap-2">
                <i class="fas fa-hotel text-blue-500 text-sm"></i> Thông tin khách sạn
            </h2>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.hotels.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-2 gap-4">

                    <div class="col-span-2">
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Tên khách sạn <span class="text-red-400">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}"
                            class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-300 @enderror"
                            placeholder="Grand Hotel...">
                        @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="col-span-2">
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Mô tả</label>
                        <textarea name="description" rows="3"
                            class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                            placeholder="Mô tả về khách sạn...">{{ old('description') }}</textarea>
                    </div>

                    <div class="col-span-2">
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Địa chỉ <span class="text-red-400">*</span></label>
                        <input type="text" name="address" value="{{ old('address') }}"
                            class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('address') border-red-300 @enderror"
                            placeholder="123 Nguyễn Huệ...">
                        @error('address')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Thành phố <span class="text-red-400">*</span></label>
                        <input type="text" name="city" value="{{ old('city') }}"
                            class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('city') border-red-300 @enderror"
                            placeholder="Hà Nội">
                        @error('city')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Tỉnh/Thành <span class="text-red-400">*</span></label>
                        <input type="text" name="province" value="{{ old('province') }}"
                            class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('province') border-red-300 @enderror"
                            placeholder="Hà Nội">
                        @error('province')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Số điện thoại</label>
                        <input type="text" name="phone" value="{{ old('phone') }}"
                            class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="+84 24 1234 5678">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                            class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="hotel@example.com">
                    </div>

                    <div class="col-span-2">
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">URL Hình ảnh chính</label>
                        <input type="url" name="main_image" value="{{ old('main_image') }}"
                            class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="https://...">
                    </div>

                    <div class="col-span-2">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <div class="relative">
                                <input type="checkbox" name="is_active" value="1"
                                    {{ old('is_active', true) ? 'checked' : '' }}
                                    class="sr-only peer">
                                <div class="w-10 h-6 bg-slate-200 peer-checked:bg-blue-600 rounded-full transition-colors"></div>
                                <div class="absolute top-1 left-1 w-4 h-4 bg-white rounded-full transition-transform peer-checked:translate-x-4"></div>
                            </div>
                            <span class="text-sm font-medium text-slate-700">Kích hoạt khách sạn</span>
                        </label>
                    </div>

                    <div class="col-span-2">
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-3">Tiện nghi</label>
                        <div class="grid grid-cols-3 gap-2">
                            @foreach($amenities as $amenity)
                            <label class="flex items-center gap-2 bg-slate-50 border border-slate-100 rounded-xl px-3 py-2.5 cursor-pointer hover:border-blue-200 hover:bg-blue-50 transition-colors">
                                <input type="checkbox" name="amenities[]" value="{{ $amenity->id }}"
                                    {{ in_array($amenity->id, (array)old('amenities', [])) ? 'checked' : '' }}
                                    class="w-4 h-4 accent-blue-600">
                                <span class="text-slate-600 text-sm">{{ $amenity->name }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                </div>

                <div class="flex gap-3 mt-6 pt-6 border-t border-slate-50">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl text-sm font-semibold flex items-center gap-2">
                        <i class="fas fa-save"></i> Lưu khách sạn
                    </button>
                    <a href="{{ route('admin.hotels.index') }}"
                        class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-6 py-2.5 rounded-xl text-sm font-semibold flex items-center gap-2">
                        <i class="fas fa-times"></i> Hủy
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection