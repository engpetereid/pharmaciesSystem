@extends('layouts.admin')

@section('header', 'Edit Drug')

@section('content')
<div class="bg-white shadow rounded-lg p-6 max-w-2xl mx-auto">
    <form action="{{ url('admin/drugs/'.$drug->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-5">
            <label for="name" class="block text-sm font-medium text-gray-700">Drug Name</label>
            <input type="text" name="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" value="{{ old('name', $drug->name) }}" required>
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div class="mb-5">
            <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
            <select name="category_id" id="category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" required>
                <option value="" disabled>Select Category...</option>
                @foreach($categories ?? [] as $cat)
                    <option value="{{ $cat->id }}" @selected(old('category_id', $drug->category_id) == $cat->id)>{{ $cat->name }}</option>
                @endforeach
            </select>
            @error('category_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div class="mb-5">
            <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
            <input type="number" step="0.01" name="price" id="price" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" value="{{ old('price', $drug->price) }}" required>
            @error('price')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div class="flex justify-end">
            <a href="{{ url('admin/drugs') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded mr-3 hover:bg-gray-300 transition">Cancel</a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">Update Drug</button>
        </div>
    </form>
</div>
@endsection
