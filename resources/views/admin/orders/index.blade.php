@extends('layouts.admin')

@section('header', 'Orders Management')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row justify-between items-center">
    <div>
        <h3 class="text-2xl font-bold text-gray-800">All Orders</h3>
        <p class="text-sm text-gray-500 mt-1">Manage and review all incoming drug orders from pharmacies.</p>
    </div>
</div>

<div class="bg-white shadow-md rounded-xl overflow-hidden border border-gray-100">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Order ID</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pharmacy</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Drug</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Quantity</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($orders ?? [] as $order)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                        {{ $order->pharmacy->name ?? ($order->pharmacy_id ?? 'Unknown Pharmacy') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-indigo-600">
                        {{ $order->drug->name ?? 'Unknown Drug' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center font-medium">
                        {{ $order->quantity ?? 0 }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        @if($order->accepted)
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Accepted
                            </span>
                        @else
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Pending
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $order->created_at ? $order->created_at->format('Y-m-d H:i') : 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        @if(!$order->accepted)
                            <form action="{{ route('admin.orders.accept', $order->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 rounded-md transition border border-indigo-200">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Accept
                                </button>
                            </form>
                        @else
                            <span class="inline-flex items-center text-gray-400 cursor-not-allowed">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Accepted
                            </span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-10 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        <p class="mt-2 text-sm text-gray-500 font-medium">No orders found.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if(isset($orders) && method_exists($orders, 'links'))
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
