<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard - HotelHub')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <aside class="w-64 bg-gray-800 text-white">
            <div class="p-4">
                <h1 class="text-2xl font-bold mb-6">🏨 Admin Panel</h1>
                <nav class="space-y-2">
                    <a href="{{ route('admin.dashboard') }}"
                       class="block px-4 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700' : '' }}">
                        📊 Dashboard
                    </a>
                    <a href="{{ route('admin.hotels.index') }}"
                       class="block px-4 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.hotels.*') ? 'bg-gray-700' : '' }}">
                        🏨 Quản lý Khách sạn
                    </a>
                    <a href="{{ route('admin.bookings.index') }}"
                       class="block px-4 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.bookings.*') ? 'bg-gray-700' : '' }}">
                        📋 Quản lý Đặt phòng
                    </a>
                    <a href="{{ route('admin.users.index') }}"
                       class="block px-4 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.users.*') ? 'bg-gray-700' : '' }}">
                        👥 Quản lý Người dùng
                    </a>
                    <a href="{{ route('home') }}"
                       class="block px-4 py-2 rounded hover:bg-gray-700">
                        🌐 Về Website
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="mt-4">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 rounded hover:bg-gray-700">
                            🚪 Đăng xuất
                        </button>
                    </form>
                </nav>
            </div>
        </aside>

        <main class="flex-1 overflow-y-auto">
            <header class="bg-white shadow-sm px-6 py-4">
                <div class="flex justify-between items-center">
                    <h2 class="text-2xl font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h2>
                    <div class="text-sm text-gray-600">
                        Xin chào, <span class="font-semibold">{{ auth()->user()->name }}</span>
                    </div>
                </div>
            </header>

            <div class="p-6">
                @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
                @endif
                @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>