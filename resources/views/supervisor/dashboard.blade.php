@extends('layouts.supervisor')

@section('header', 'Supervisor Dashboard')

@section('content')
<div class="bg-white shadow rounded-lg p-6">
    <h3 class="text-lg font-semibold text-gray-700 mb-6">Welcome to the Supervisor Dashboard</h3>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Stat Cards -->
        <div class="bg-indigo-50 rounded-lg p-5 flex items-center justify-between border border-indigo-100 shadow-sm hover:shadow-md transition">
            <div>
                <h4 class="text-indigo-600 font-semibold mb-1">Invoices</h4>
                <div class="text-3xl font-bold text-gray-800">{{ $todayInvoices }}</div>
            </div>
            <div class="text-indigo-400">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
        </div>
    </div>
</div>
@endsection
