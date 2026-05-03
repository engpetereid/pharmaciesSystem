@extends('layouts.supervisor')

@section('header', 'Create Invoice')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h3 class="text-2xl font-bold text-gray-800">New Invoice</h3>
    <a href="{{ route('supervisor.invoices.index') }}" class="text-indigo-600 hover:text-indigo-800 font-medium flex items-center transition">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Back to Invoices
    </a>
</div>

<div class="bg-white shadow-md rounded-xl overflow-hidden border border-gray-100">
    <form action="{{ route('supervisor.invoices.store') }}" method="POST" id="invoiceForm">
        @csrf
        <input type="number" name="pharmacy_id" value="{{auth()->user()->pharmacy->id}}" hidden >
        <div class="p-6 md:p-8 border-b border-gray-100 bg-gray-50">
            <h4 class="text-lg font-semibold text-gray-700 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Invoice Details
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Invoice Date <span class="text-red-500">*</span></label>
                    <input type="date" name="date" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition" required value="{{ old('date', date('Y-m-d')) }}">
                    @error('date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="p-6 md:p-8">
            <div class="flex justify-between items-center mb-4">
                <h4 class="text-lg font-semibold text-gray-700 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    Invoice Items
                </h4>
                <button type="button" id="addItemBtn" class="px-4 py-2 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 rounded-lg text-sm font-medium transition flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Add Item
                </button>
            </div>

            @error('items')
                <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
                    {{ $message }}
                </div>
            @enderror

            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200" id="itemsTable">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Drug <span class="text-red-500">*</span></th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-32">Quantity <span class="text-red-500">*</span></th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-20">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100" id="itemsContainer">
                        <!-- Initial Row -->
                        <tr class="item-row hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <select name="items[0][drug_id]" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                    <option value="" disabled selected>Select a drug</option>
                                    @foreach($drugs ?? [] as $drug)
                                        <option value="{{ $drug->id }}">{{ $drug->name }} (Price: ${{ number_format($drug->price, 2) }})</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="number" name="items[0][quantity]" min="1" value="1" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <button type="button" class="text-gray-400 cursor-not-allowed" disabled title="Cannot remove the only item">
                                    <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="px-6 py-5 bg-gray-50 border-t border-gray-100 flex items-center justify-end rounded-b-xl">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-6 rounded-lg focus:outline-none focus:shadow-outline transition flex items-center shadow-md">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Save Invoice
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('itemsContainer');
        const addItemBtn = document.getElementById('addItemBtn');
        let itemIndex = 1;

        const drugsOptions = `@foreach($drugs ?? [] as $drug)<option value="{{ $drug->id }}">{{ addslashes($drug->name) }} (Price: ${{ number_format($drug->price, 2) }})</option>@endforeach`;

        addItemBtn.addEventListener('click', function() {
            const tr = document.createElement('tr');
            tr.className = 'item-row hover:bg-gray-50 transition';
            tr.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap">
                    <select name="items[${itemIndex}][drug_id]" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                        <option value="" disabled selected>Select a drug</option>
                        ${drugsOptions}
                    </select>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <input type="number" name="items[${itemIndex}][quantity]" min="1" value="1" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                    <button type="button" class="remove-item-btn text-red-500 hover:text-red-700 transition">
                        <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </td>
            `;
            container.appendChild(tr);
            itemIndex++;
        });

        container.addEventListener('click', function(e) {
            const btn = e.target.closest('.remove-item-btn');
            if (btn) {
                const rows = container.querySelectorAll('.item-row');
                if (rows.length > 1) {
                    btn.closest('tr').remove();
                }
            }
        });
    });
</script>
@endsection
