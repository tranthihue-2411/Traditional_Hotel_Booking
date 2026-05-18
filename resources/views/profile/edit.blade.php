@extends('layouts.main')
@section('title', 'Tài Khoản - HotelHub')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <h1 class="text-3xl font-bold mb-6">Cài Đặt Tài Khoản</h1>

    @if(session('status'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('status') }}
    </div>
    @endif

    @if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
    </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-6 border-b">
            <h2 class="text-xl font-semibold mb-4">Thông Tin Cá Nhân</h2>
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('patch')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Họ và tên</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    </div>
                </div>
                <button type="submit" class="bg-teal-600 text-white px-6 py-2 rounded-lg hover:bg-teal-700">
                    Cập nhật thông tin
                </button>
            </form>
        </div>

        <div class="p-6">
            <h2 class="text-xl font-semibold mb-4">Đổi Mật Khẩu</h2>
            <form action="{{ route('profile.password.update') }}" method="POST">
                @csrf
                @method('put')
                <div class="space-y-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Mật khẩu hiện tại</label>
                        <input type="password" name="current_password" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Mật khẩu mới</label>
                        <input type="password" name="password" required minlength="8"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Xác nhận mật khẩu mới</label>
                        <input type="password" name="password_confirmation" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    </div>
                </div>
                <button type="submit" class="bg-teal-600 text-white px-6 py-2 rounded-lg hover:bg-teal-700">
                    Đổi mật khẩu
                </button>
            </form>
        </div>
    </div>
</div>
@endsection