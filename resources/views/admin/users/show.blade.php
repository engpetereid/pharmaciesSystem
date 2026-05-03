@extends('layouts.admin')

@section('header', 'User Details')

@section('content')
<div class="bg-white shadow rounded-lg p-6 max-w-2xl mx-auto">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 pb-4 border-b">
        <div>
            <h4 class="text-sm uppercase tracking-wider text-gray-500 mb-1">Name</h4>
            <p class="text-lg font-medium text-gray-900">{{ $user->name }}</p>
        </div>
        <div>
            <h4 class="text-sm uppercase tracking-wider text-gray-500 mb-1">Email</h4>
            <p class="text-lg font-medium text-gray-900">{{ $user->email }}</p>
        </div>
        <div>
            <h4 class="text-sm uppercase tracking-wider text-gray-500 mb-1">Role</h4>
            <p class="text-lg font-medium text-gray-900">
                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                    {{ ucfirst($user->role) }}
                </span>
            </p>
        </div>
        <div>
            <h4 class="text-sm uppercase tracking-wider text-gray-500 mb-1">Joined</h4>
            <p class="text-lg font-medium text-gray-900">{{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }}</p>
        </div>
    </div>
    
    <div class="flex justify-end space-x-3">
        <a href="{{ url('admin/users') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300 transition">Back</a>
        <a href="{{ url('admin/users/'.$user->id.'/edit') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">Edit User</a>
    </div>
</div>
@endsection
