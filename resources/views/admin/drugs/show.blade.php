@extends('layouts.admin')

@section('header', 'Drug Details')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div class="flex items-center">
        <a href="{{ route('admin.drugs.index') }}" class="mr-4 text-gray-400 hover:text-indigo-600 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <h3 class="text-2xl font-bold text-gray-800">Drug: <span class="text-indigo-600">{{ $drug->name }}</span></h3>
    </div>
</div>

<div class="bg-white shadow-md rounded-xl overflow-hidden border border-gray-100 max-w-3xl mx-auto">
    <div class="bg-indigo-50 border-b border-indigo-100 px-8 py-6">
        <div class="flex items-center">
            <div class="flex-shrink-0 h-16 w-16 bg-white rounded-2xl shadow-sm flex items-center justify-center text-indigo-600 border border-indigo-50">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
            </div>
            <div class="ml-6">
                <h4 class="text-xl font-bold text-gray-900">{{ $drug->name }}</h4>
                <p class="text-sm text-indigo-600 font-medium mt-1">System ID: #{{ str_pad($drug->id, 5, '0', STR_PAD_LEFT) }}</p>
            </div>
        </div>
    </div>
    
    <div class="p-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <div class="bg-gray-50 rounded-lg p-5 border border-gray-100">
                <h4 class="text-xs uppercase tracking-wider text-gray-500 font-semibold mb-2">Category Assignment</h4>
                <div class="flex items-center">
                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                        {{ $drug->category ? $drug->category->name : 'Uncategorized' }}
                    </span>
                </div>
            </div>
            
            <div class="bg-gray-50 rounded-lg p-5 border border-gray-100">
                <h4 class="text-xs uppercase tracking-wider text-gray-500 font-semibold mb-2">Unit Price</h4>
                <div class="flex items-baseline text-2xl font-bold text-gray-900">
                    <span class="text-gray-500 mr-1 text-lg">$</span>
                    {{ number_format($drug->price, 2) }}
                </div>
            </div>
        </div>
        
        <div class="flex justify-end space-x-4 border-t border-gray-100 pt-6 mt-2">
            <a href="{{ route('admin.drugs.index') }}" class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition shadow-sm">
                Return to List
            </a>
            <a href="{{ route('admin.drugs.edit', $drug->id) }}" class="px-5 py-2.5 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition shadow-sm flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                Edit Drug
            </a>
        </div>
    </div>
</div>
@endsection
