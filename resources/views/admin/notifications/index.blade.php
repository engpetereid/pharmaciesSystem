@extends('layouts.admin')

@section('header', 'System Notifications')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row justify-between items-center">
    <div>
        <h3 class="text-2xl font-bold text-gray-800">Alerts & Notifications</h3>
        <p class="text-sm text-gray-500 mt-1">Review system alerts, low stock warnings, and automated messages.</p>
    </div>
</div>

<div class="bg-white shadow-md rounded-xl overflow-hidden border border-gray-100">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Alert Type / ID</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-1/2">Message Content</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Related Context</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Time</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($notifications ?? [] as $notification)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 bg-yellow-50 rounded-full flex items-center justify-center text-yellow-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                            </div>
                            <div class="ml-4 text-sm font-bold text-gray-900">
                                #{{ str_pad($notification->id, 5, '0', STR_PAD_LEFT) }}
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">{{ $notification->message ?? 'No Message Provided' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($notification->pharmacy_id || $notification->drug_id)
                            <div class="flex flex-col space-y-1">
                                @if($notification->pharmacy_id)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                        Pharmacy: {{ $notification->pharmacy->name ?? $notification->pharmacy_id }}
                                    </span>
                                @endif
                                @if($notification->drug_id)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Drug: {{ $notification->drug->name ?? $notification->drug_id }}
                                    </span>
                                @endif
                            </div>
                        @else
                            <span class="text-sm text-gray-400 italic">System Alert</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500 font-medium">
                        {{ $notification->created_at ? $notification->created_at->diffForHumans() : 'Unknown Date' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center">
                        <div class="mx-auto h-16 w-16 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                            <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        </div>
                        <h3 class="text-sm font-medium text-gray-900">All clear!</h3>
                        <p class="mt-1 text-sm text-gray-500">There are no new system notifications at this time.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        @if(isset($notifications) && method_exists($notifications, 'links'))
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
