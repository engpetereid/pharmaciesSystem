@extends('layouts.admin')

@section('header', 'Create Pharmacy')

@section('content')
<div class="bg-white shadow rounded-lg p-6 max-w-2xl mx-auto">
    <form action="{{ url('admin/pharmacies') }}" method="POST">
        @csrf
        <div class="mb-5">
            <label for="name" class="block text-sm font-medium text-gray-700">Pharmacy Name</label>
            <input type="text" name="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" value="{{ old('name') }}" required>
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div class="mb-5">
            <label for="supervisor_id" class="block text-sm font-medium text-gray-700">user</label>
            <select name="user_id" id="supervisor_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" >
                <option value="" selected>Select user...</option>
                @foreach($supervisors as $supervisor)
                    <option value="{{ $supervisor->id }}" @selected(old('user_id') == $supervisor->id)>{{ $supervisor->name }}</option>
                @endforeach
            </select>
            @error('supervisor_id')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div class="flex justify-end">
            <a href="{{ url('admin/pharmacies') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded mr-3 hover:bg-gray-300 transition">Cancel</a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">Save Pharmacy</button>
        </div>
    </form>
</div>
@endsection
