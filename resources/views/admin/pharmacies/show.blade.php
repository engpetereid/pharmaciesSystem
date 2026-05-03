@extends('layouts.admin')

@section('header', 'Pharmacy Details')

@section('content')
<div class="bg-white shadow rounded-lg p-6 max-w-2xl mx-auto">
    <div class="mb-6 border-b pb-4">
        <h4 class="text-sm uppercase tracking-wider text-gray-500 mb-1">Pharmacy Name</h4>
        <p class="text-lg font-medium text-gray-900">{{ $pharmacy->name }}</p>
    </div>

    <div class="flex justify-end space-x-3">
        <a href="{{ route('admin.pharmacies.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300 transition">Back</a>
        <a href="{{ route('admin.pharmacies.edit',$pharmacy->id) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">Edit Pharmacy</a>
    </div>

    <div class="flex justify-end space-x-3">
        <a href="{{ route('admin.pharmacies.add',$pharmacy->id) }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300 transition">add</a>
    </div>


    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
        <tr>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">drug</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">quantity</th>
            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
        </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
        @foreach($items ?? [] as $item)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->drug->name ?? 'N/A' }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->quantity ?? 'N/A' }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
