@extends('admin.layouts.app')
@section('title', 'Sửa người dùng - Admin')
@section('page-title', 'Sửa thông tin người dùng')

@section('content')

<div class="mb-4">
    <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:text-blue-700 text-sm flex items-center gap-1.5">
        <i class="fas fa-arrow-left text-xs"></i> Quay lại danh sách
    </a>
</div>

<div class="max-w-xl">
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-50">
            <h2 class="font-bold text-slate-800 flex items-center gap-2">
                <i class="fas fa-user text-blue-500 text-sm"></i>
                {{ $user->name }}
            </h2>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Họ tên</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                            class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-300 @enderror">
                        @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}"
                            class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-300 @enderror">
                        @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex gap-3 mt-6 pt-6 border-t border-slate-50">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl text-sm font-semibold flex items-center gap-2">
                        <i class="fas fa-save"></i> Cập nhật
                    </button>
                    <a href="{{ route('admin.users.index') }}"
                        class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-6 py-2.5 rounded-xl text-sm font-semibold flex items-center gap-2">
                        <i class="fas fa-times"></i> Hủy
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection