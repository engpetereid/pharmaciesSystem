@extends('layouts.admin')

@section('header', 'Category Details')

@section('content')
<div class="bg-white shadow rounded-lg p-6 max-w-2xl mx-auto">
    <div class="mb-6 border-b pb-4">
        <h4 class="text-sm uppercase tracking-wider text-gray-500 mb-1">Name</h4>
        <p class="text-lg font-medium text-gray-900">{{ $category->name }}</p>
    </div>
    
    <div class="flex justify-end space-x-3">
        <a href="{{ url('admin/categories') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300 transition">Back</a>
        <a href="{{ url('admin/categories/'.$category->id.'/edit') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">Edit Category</a>
    </div>
</div>
@endsection
