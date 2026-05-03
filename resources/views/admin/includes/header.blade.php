<header class="bg-white shadow h-16 flex-shrink-0 flex items-center justify-between px-6">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        @yield('header')
    </h2>
    <div class="flex items-center space-x-4">
        <!-- User Menu -->
        <span class="text-gray-600 text-sm font-medium">{{ auth()->check() ? auth()->user()->name : 'Admin' }}</span>
        <form method="POST" action="{{ url('logout') }}" class="inline">
            @csrf
            <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-800 transition">Logout</button>
        </form>
    </div>
</header>
