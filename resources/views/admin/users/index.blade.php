@extends('admin.layouts.app')
@section('title', 'Quản lý người dùng - Admin')
@section('page-title', 'Quản lý người dùng')

@section('content')

<div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 mb-6">
    <form action="{{ route('admin.users.index') }}" method="GET">
        <div class="flex gap-3">
            <div class="flex-1">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-2.5 text-slate-300 text-xs"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="w-full border border-slate-200 rounded-xl pl-8 pr-4 py-2 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Tìm theo tên, email...">
                </div>
            </div>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-xl text-sm font-semibold flex items-center gap-2">
                <i class="fas fa-search"></i> Tìm
            </button>
            <a href="{{ route('admin.users.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-5 py-2 rounded-xl text-sm font-semibold flex items-center gap-2">
                <i class="fas fa-times"></i> Xóa lọc
            </a>
        </div>
    </form>
</div>

<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    <table class="w-full">
        <thead>
            <tr class="border-b border-slate-100 bg-slate-50">
                <th class="text-left px-6 py-3.5 text-xs font-semibold text-slate-400 uppercase tracking-wider">Người dùng</th>
                <th class="text-left px-6 py-3.5 text-xs font-semibold text-slate-400 uppercase tracking-wider">Email</th>
                <th class="text-left px-6 py-3.5 text-xs font-semibold text-slate-400 uppercase tracking-wider">Ngày đăng ký</th>
                <th class="text-left px-6 py-3.5 text-xs font-semibold text-slate-400 uppercase tracking-wider">Trạng thái</th>
                <th class="text-right px-6 py-3.5 text-xs font-semibold text-slate-400 uppercase tracking-wider">Thao tác</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-50">
            @forelse($users as $user)
            <tr class="hover:bg-slate-50 transition-colors">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 bg-blue-600 rounded-full flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <p class="font-semibold text-slate-800 text-sm">{{ $user->name }}</p>
                    </div>
                </td>
                <td class="px-6 py-4 text-slate-600 text-sm">{{ $user->email }}</td>
                <td class="px-6 py-4 text-slate-400 text-sm">{{ $user->created_at->format('d/m/Y') }}</td>
                <td class="px-6 py-4">
                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                        {{ $user->is_locked ? 'bg-red-50 text-red-500 border border-red-100' : 'bg-emerald-50 text-emerald-600 border border-emerald-100' }}">
                        <i class="fas fa-circle text-xs mr-1"></i>
                        {{ $user->is_locked ? 'Đã khóa' : 'Hoạt động' }}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.users.edit', $user) }}"
                            class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded text-sm hover:bg-yellow-200">
                            Sửa
                        </a>
                        <form action="{{ route('admin.users.toggle-lock', $user) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="px-3 py-1 {{ $user->is_locked ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-red-100 text-red-600 hover:bg-red-200' }} rounded text-sm">
                                {{ $user->is_locked ? 'Mở khóa' : 'Khóa' }}
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-16 text-center">
                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-users text-slate-300 text-2xl"></i>
                    </div>
                    <p class="text-slate-400 text-sm">Không có người dùng nào</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="px-6 py-4 border-t border-slate-50">
        {{ $users->links() }}
    </div>
</div>
@endsection