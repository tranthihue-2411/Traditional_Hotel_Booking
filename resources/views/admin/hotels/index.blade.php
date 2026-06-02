@extends('admin.layouts.app')
@section('title', 'Quản lý khách sạn - Admin')
@section('page-title', 'Quản lý khách sạn')

@section('content')

<div class="flex items-center justify-between mb-6">
    <p class="text-sm text-slate-400">Tổng cộng <span class="font-semibold text-slate-600">{{ $hotels->total() }}</span> khách sạn</p>
    <a href="{{ route('admin.hotels.create') }}"
        class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold flex items-center gap-2">
        <i class="fas fa-plus"></i> Thêm khách sạn
    </a>
</div>

<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    <table class="w-full">
        <thead>
            <tr class="border-b border-slate-100 bg-slate-50">
                <th class="text-left px-6 py-3.5 text-xs font-semibold text-slate-400 uppercase tracking-wider">Khách sạn</th>
                <th class="text-left px-6 py-3.5 text-xs font-semibold text-slate-400 uppercase tracking-wider">Địa điểm</th>
                <th class="text-left px-6 py-3.5 text-xs font-semibold text-slate-400 uppercase tracking-wider">Đánh giá</th>
                <th class="text-left px-6 py-3.5 text-xs font-semibold text-slate-400 uppercase tracking-wider">Trạng thái</th>
                <th class="text-right px-6 py-3.5 text-xs font-semibold text-slate-400 uppercase tracking-wider">Thao tác</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-50">
            @forelse($hotels as $hotel)
            <tr class="hover:bg-slate-50 transition-colors">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <img src="{{ $hotel->main_image ?? 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=100&h=100&fit=crop' }}"
                            alt="{{ $hotel->name }}"
                            class="w-11 h-11 rounded-xl object-cover flex-shrink-0">
                        <div>
                            <p class="font-semibold text-slate-800 text-sm">{{ $hotel->name }}</p>
                            <p class="text-slate-400 text-xs mt-0.5">{{ $hotel->email ?? 'Chưa có email' }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <p class="text-slate-600 text-sm">{{ $hotel->city }}</p>
                    <p class="text-slate-400 text-xs">{{ $hotel->province }}</p>
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-1.5">
                        <i class="fas fa-star text-amber-400 text-xs"></i>
                        <span class="font-semibold text-slate-700 text-sm">{{ number_format($hotel->rating, 1) }}</span>
                        <span class="text-slate-300 text-xs">({{ $hotel->review_count }})</span>
                    </div>
                </td>
                <td class="px-6 py-4">
                    @if($hotel->trashed())
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-500 border border-gray-200">
                            <i class="fas fa-eye-slash text-xs mr-1"></i>Đã ẩn
                        </span>
                    @elseif($hotel->is_active)
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-600 border border-emerald-100">
                            <i class="fas fa-circle text-xs mr-1"></i>Hoạt động
                        </span>
                    @else
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-500 border border-red-100">
                            <i class="fas fa-circle text-xs mr-1"></i>Tạm dừng
                        </span>
                    @endif
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center justify-end gap-2">
                        @if($hotel->trashed())
                            <form action="{{ route('admin.hotels.restore', $hotel->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="px-3 py-1 bg-green-100 text-green-700 rounded text-sm hover:bg-green-200">
                                    Khôi phục
                                </button>
                            </form>
                        @else
                            <a href="{{ route('admin.hotels.show', $hotel) }}"
                                class="px-3 py-1 bg-blue-100 text-blue-700 rounded text-sm hover:bg-blue-200">
                                Xem
                            </a>
                            <a href="{{ route('admin.hotels.edit', $hotel) }}"
                                class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded text-sm hover:bg-yellow-200">
                                Sửa
                            </a>
                            <form action="{{ route('admin.hotels.destroy', $hotel) }}" method="POST"
                                onsubmit="return confirm('Bạn có chắc muốn ẩn khách sạn này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="px-3 py-1 bg-red-100 text-red-600 rounded text-sm hover:bg-red-200">
                                    Xóa
                                </button>
                            </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-16 text-center">
                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-hotel text-slate-300 text-2xl"></i>
                    </div>
                    <p class="text-slate-400 text-sm">Chưa có khách sạn nào</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-slate-50">
        {{ $hotels->links() }}
    </div>
</div>

@endsection