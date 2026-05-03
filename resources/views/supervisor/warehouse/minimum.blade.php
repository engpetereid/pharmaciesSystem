@extends('layouts.supervisor')

@section('header', 'Require minimum')

@section('content')
<div class="mb-4 flex items-center justify-between">
    <h3 class="text-lg font-semibold text-gray-700">minimum</h3>
    <a href="{{ route('supervisor.warehouses') }}" class="text-indigo-600 hover:underline">Back to Warehouse</a>
</div>

<div class="bg-white shadow rounded-lg p-6">
    <form action="{{ route('supervisor.minimum') }}" method="POST">
        @csrf

        <input type="hidden" name="pharmacy_id" value="{{ auth()->user()->pharmacy->id ?? '' }}">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div>
                <input type="text" value="{{$drug->name}}" disabled>
                <input type="hidden" name="drug_id" value="{{ $drug->id }}">
            </div>

            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">minimum</label>
                <input type="number" name="minimum" value="{{$warehouse->minimum}}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="minimum" required min="1">
            </div>
        </div>

        <div class="flex items-center justify-end mt-6 border-t pt-4">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline transition">
                Submit Order
            </button>
        </div>
    </form>
</div>
@endsection
