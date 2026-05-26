<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'HotelHub - Đặt Phòng Khách Sạn')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @stack('styles')
</head>
<body class="bg-gray-50">

    <nav class="bg-white shadow-sm">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <a href="{{ route('home') }}" class="text-2xl font-bold text-teal-600">
                    🏨 HotelHub
                </a>
                <div class="flex items-center space-x-6">
                    <a href="{{ route('home') }}" class="text-gray-700 hover:text-teal-600">Trang chủ</a>
                    <a href="{{ route('hotels.search') }}" class="text-gray-700 hover:text-teal-600">Khách sạn</a>
                    @auth
                        @if(auth()->user()->is_admin)
                            <a href="{{ route('admin.dashboard') }}" class="text-teal-600 hover:text-teal-700 font-semibold">Trang quản trị</a>
                        @endif
                        <a href="{{ route('bookings.index') }}" class="text-gray-700 hover:text-teal-600">Lịch sử đặt phòng</a>
                        <a href="{{ route('profile.edit') }}" class="text-gray-700 hover:text-teal-600">Tài khoản</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-700 hover:text-teal-600">Đăng xuất</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-teal-600">Đăng nhập</a>
                        <a href="{{ route('register') }}" class="bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700">Đăng ký</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    @if(session('success'))
    <div class="container mx-auto px-4 mt-4">
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="container mx-auto px-4 mt-4">
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            {{ session('error') }}
        </div>
    </div>
    @endif

    <main>@yield('content')</main>

    <footer class="bg-gray-800 text-white py-8 mt-12">
        <div class="container mx-auto px-4 text-center">
            <p>&copy; {{ date('Y') }} HotelHub. Tất cả quyền được bảo lưu.</p>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>