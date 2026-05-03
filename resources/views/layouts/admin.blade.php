<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard - {{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-900 bg-gray-100 flex h-screen overflow-hidden">
    <!-- Sidebar -->
    @include('admin.includes.sidebar')

    <!-- Main Wrapper -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Header -->
        @include('admin.includes.header')

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto bg-gray-100 p-6">
            @include('admin.includes.alerts.success')
            @include('admin.includes.alerts.errors')
            
            @yield('content')
        </main>

        <!-- Footer -->
        @include('admin.includes.footer')
    </div>
</body>
</html>
