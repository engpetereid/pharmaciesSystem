@extends('layouts.supervisor')

@section('header', 'Invoice Details')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row justify-between items-center">
    <div class="flex items-center">
        <a href="{{ route('supervisor.invoices.index') }}" class="mr-4 text-gray-400 hover:text-indigo-600 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <h3 class="text-2xl font-bold text-gray-800">Invoice <span class="text-indigo-600">#INV-{{ str_pad($invoice->id ?? 0, 5, '0', STR_PAD_LEFT) }}</span></h3>
    </div>
    
    <div class="mt-4 sm:mt-0 flex space-x-3">
        <a href="{{ route('supervisor.invoices.edit', $invoice->id) }}" class="px-4 py-2 bg-blue-50 text-blue-700 hover:bg-blue-100 font-medium rounded-lg shadow-sm transition flex items-center border border-blue-200">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            Edit
        </a>
        <form action="{{ route('supervisor.invoices.destroy', $invoice->id) }}" method="POST" class="inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-4 py-2 bg-red-50 text-red-700 hover:bg-red-100 font-medium rounded-lg shadow-sm transition flex items-center border border-red-200" onclick="return confirm('Are you sure you want to delete this invoice?')">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                Delete
            </button>
        </form>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <!-- Invoice Summary -->
    <div class="bg-white shadow-md rounded-xl p-6 border border-gray-100 flex items-center">
        <div class="p-3 rounded-full bg-indigo-100 text-indigo-600 mr-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Date</p>
            <h4 class="text-lg font-bold text-gray-900">{{ $invoice->date ? \Carbon\Carbon::parse($invoice->date)->format('F d, Y') : 'N/A' }}</h4>
        </div>
    </div>

    <div class="bg-white shadow-md rounded-xl p-6 border border-gray-100 flex items-center">
        <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Pharmacy</p>
            <h4 class="text-lg font-bold text-gray-900">{{ $invoice->pharmacy->name ?? ($invoice->pharmacy_id ?? 'N/A') }}</h4>
        </div>
    </div>

    <div class="bg-white shadow-md rounded-xl p-6 border border-gray-100 flex items-center">
        <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Price</p>
            <h4 class="text-lg font-bold text-gray-900">${{ number_format($invoice->price ?? 0, 2) }}</h4>
        </div>
    </div>
</div>

<!-- Invoice Items -->
<div class="bg-white shadow-md rounded-xl overflow-hidden border border-gray-100">
    <div class="bg-gray-50 border-b border-gray-100 px-6 py-4">
        <h4 class="text-lg font-semibold text-gray-700 flex items-center">
            <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
            Invoice Items
        </h4>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Drug Name</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Quantity</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Unit Price</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Total</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @if(isset($invoice->items) && count($invoice->items) > 0)
                    @foreach($invoice->items as $item)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $item->drug->name ?? 'Unknown Drug' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 text-center font-semibold">
                            {{ $item->quantity ?? 0 }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 text-right">
                            ${{ number_format($item->drug->price ?? 0, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 text-right">
                            ${{ number_format(($item->drug->price ?? 0) * ($item->quantity ?? 0), 2) }}
                        </td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                            <p class="text-sm">No items found for this invoice.</p>
                        </td>
                    </tr>
                @endif
            </tbody>
            @if(isset($invoice->items) && count($invoice->items) > 0)
            <tfoot class="bg-gray-50 border-t border-gray-200">
                <tr>
                    <th colspan="3" class="px-6 py-4 text-right text-sm font-bold text-gray-700 uppercase">Grand Total</th>
                    <th class="px-6 py-4 text-right text-lg font-bold text-indigo-700">${{ number_format($invoice->price ?? 0, 2) }}</th>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>
@endsection
