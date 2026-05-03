<aside class="w-64 bg-gray-800 text-white min-h-screen flex flex-col overflow-y-auto">
    <div class="h-16 flex flex-shrink-0 items-center justify-center font-bold text-xl border-b border-gray-700">
        {{ config('app.name') }} Admin
    </div>
    <nav class="flex-1 px-2 py-4 space-y-1">
        <a href="{{ route('dashboard') }}" class="block px-4 py-2 rounded text-sm hover:bg-gray-700 hover:text-white transition">Dashboard</a>

        <div class="text-xs uppercase tracking-wider text-gray-500 font-semibold px-4 mt-6 mb-2">Management</div>
        <a href="{{ url('admin/categories') }}" class="block px-4 py-2 rounded text-sm hover:bg-gray-700 hover:text-white transition">Categories</a>
        <a href="{{ url('admin/drugs') }}" class="block px-4 py-2 rounded text-sm hover:bg-gray-700 hover:text-white transition">Drugs</a>
        <a href="{{ url('admin/pharmacies') }}" class="block px-4 py-2 rounded text-sm hover:bg-gray-700 hover:text-white transition">Pharmacies</a>
        <a href="{{ url('admin/users') }}" class="block px-4 py-2 rounded text-sm hover:bg-gray-700 hover:text-white transition">Users</a>

        <div class="text-xs uppercase tracking-wider text-gray-500 font-semibold px-4 mt-6 mb-2">Logs & Operations</div>
        <a href="{{ url('admin/orders') }}" class="block px-4 py-2 rounded text-sm hover:bg-gray-700 hover:text-white transition">Orders</a>
        <a href="{{ url('admin/invoices') }}" class="block px-4 py-2 rounded text-sm hover:bg-gray-700 hover:text-white transition">Invoices</a>
        <a href="{{ url('admin/notifications') }}" class="block px-4 py-2 rounded text-sm hover:bg-gray-700 hover:text-white transition">Notifications</a>
    </nav>
</aside>
