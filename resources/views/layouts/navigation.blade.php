<nav class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="text-xl font-bold text-gray-800">
                        🏨 Hotel Booking
                    </a>
                </div>
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <a href="{{ route('home') }}"
                       class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-500 hover:text-gray-700">
                        Trang chủ
                    </a>
                    <a href="{{ route('hotels.search') }}"
                       class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-500 hover:text-gray-700">
                        Tìm khách sạn
                    </a>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ml-6 space-x-4">
                @auth
                    @if(auth()->user()->is_admin)
                        <a href="{{ route('admin.dashboard') }}"
                        class="text-sm text-indigo-600 font-medium hover:text-indigo-800">
                            Admin
                        </a>
                    @endif

                    <a href="{{ route('bookings.index') }}"
                    class="text-sm text-gray-600 hover:text-gray-800">
                        Đặt phòng của tôi
                    </a>

                    <a href="{{ route('profile.edit') }}"
                    class="text-sm text-gray-600 hover:text-gray-800">
                        {{ auth()->user()->name }}
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-gray-600 hover:text-gray-800">
                            Đăng xuất
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                    class="text-sm text-gray-600 hover:text-gray-800">Đăng nhập</a>
                    <a href="{{ route('register') }}"
                    class="text-sm bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                        Đăng ký
                    </a>
@endauth
            </div>
        </div>
    </div>
</nav>