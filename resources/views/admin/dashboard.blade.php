@extends('layouts.admin')

@section('header', 'Dashboard')

@section('content')
<div class="mb-6">
    <h3 class="text-2xl font-bold text-gray-800">Admin Overview</h3>
    <p class="text-sm text-gray-500 mt-1">System-wide statistics and operations summary.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Stat Cards: data passed from AdminDashboard controller -->
    <div class="bg-white rounded-xl p-6 border-b-4 border-indigo-500 shadow-sm hover:shadow-md transition flex items-center justify-between">
        <div>
            <h4 class="text-indigo-600 font-bold uppercase tracking-wider text-xs mb-2">Pharmacies</h4>
            <div class="text-4xl font-extrabold text-gray-800">{{ $pharmacyCount ?? 0 }}</div>
        </div>
        <div class="p-4 bg-indigo-50 rounded-full text-indigo-500">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
        </div>
    </div>

    <div class="bg-white rounded-xl p-6 border-b-4 border-purple-500 shadow-sm hover:shadow-md transition flex items-center justify-between">
        <div>
            <h4 class="text-purple-600 font-bold uppercase tracking-wider text-xs mb-2">Users</h4>
            <div class="text-4xl font-extrabold text-gray-800">{{ $userCount ?? 0 }}</div>
        </div>
        <div class="p-4 bg-purple-50 rounded-full text-purple-500">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        </div>
    </div>

    <div class="bg-white rounded-xl p-6 border-b-4 border-yellow-500 shadow-sm hover:shadow-md transition flex items-center justify-between">
        <div>
            <h4 class="text-yellow-600 font-bold uppercase tracking-wider text-xs mb-2">Pending Orders</h4>
            <div class="text-4xl font-extrabold text-gray-800">{{ $pendingOrderCount ?? 0 }}</div>
        </div>
        <div class="p-4 bg-yellow-50 rounded-full text-yellow-500">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
        </div>
    </div>

    <div class="bg-white rounded-xl p-6 border-b-4 border-green-500 shadow-sm hover:shadow-md transition flex items-center justify-between">
        <div>
            <h4 class="text-green-600 font-bold uppercase tracking-wider text-xs mb-2">Today's Invoices</h4>
            <div class="text-4xl font-extrabold text-gray-800">{{ $todayInvoiceCount ?? 0 }}</div>
        </div>
        <div class="p-4 bg-green-50 rounded-full text-green-500">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h4 class="text-lg font-bold text-gray-800 border-b border-gray-100 pb-4 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            Quick Actions
        </h4>
        <div class="grid grid-cols-2 gap-4">
            <a href="{{ route('admin.orders') }}" class="p-4 bg-gray-50 rounded-lg border border-gray-100 hover:bg-indigo-50 hover:border-indigo-100 transition group">
                <div class="text-indigo-600 font-semibold mb-1 group-hover:text-indigo-700">Review Orders</div>
                <div class="text-xs text-gray-500">Process incoming pharmacy requests</div>
            </a>
            <a href="{{ route('admin.invoices.index') }}" class="p-4 bg-gray-50 rounded-lg border border-gray-100 hover:bg-green-50 hover:border-green-100 transition group">
                <div class="text-green-600 font-semibold mb-1 group-hover:text-green-700">Manage Invoices</div>
                <div class="text-xs text-gray-500">View and track all billing</div>
            </a>
            <a href="{{ route('admin.drugs.create') }}" class="p-4 bg-gray-50 rounded-lg border border-gray-100 hover:bg-blue-50 hover:border-blue-100 transition group">
                <div class="text-blue-600 font-semibold mb-1 group-hover:text-blue-700">Add New Drug</div>
                <div class="text-xs text-gray-500">Expand central inventory</div>
            </a>
            <a href="{{ route('admin.pharmacies.create') }}" class="p-4 bg-gray-50 rounded-lg border border-gray-100 hover:bg-purple-50 hover:border-purple-100 transition group">
                <div class="text-purple-600 font-semibold mb-1 group-hover:text-purple-700">Register Pharmacy</div>
                <div class="text-xs text-gray-500">Onboard a new location</div>
            </a>
        </div>
    </div>
</div>
@endsection
