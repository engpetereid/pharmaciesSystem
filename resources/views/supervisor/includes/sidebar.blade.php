<aside class="w-64 bg-indigo-800 text-white min-h-screen flex flex-col overflow-y-auto">
    <div class="h-16 flex flex-shrink-0 items-center justify-center font-bold text-xl border-b border-indigo-700">
        {{ config('app.name') }} Supervisor
    </div>
    <nav class="flex-1 px-2 py-4 space-y-1">
        <a href="{{ route('supervisor.dashboard') }}" class="block px-4 py-2 rounded text-sm hover:bg-indigo-700 hover:text-white transition">Dashboard</a>

        <div class="text-xs uppercase tracking-wider text-indigo-300 font-semibold px-4 mt-6 mb-2">Operations</div>
        <a href="{{ route('supervisor.invoices.index') }}" class="block px-4 py-2 rounded text-sm hover:bg-indigo-700 hover:text-white transition">Invoices</a>
        <a href="{{ route('supervisor.warehouses') }}" class="block px-4 py-2 rounded text-sm hover:bg-indigo-700 hover:text-white transition">Warehouse</a>
    </nav>
</aside>
