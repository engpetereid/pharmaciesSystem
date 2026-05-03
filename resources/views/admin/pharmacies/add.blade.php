@extends('layouts.admin')

@section('header', 'Invoices')

@section('content')
    <form action="{{ route('admin.pharmacies.storeDrugs') }}" method="POST">
        @csrf

        <input type="hidden" name="pharmacy_id" value="{{ $pharmacy->id }}">

        <div id="items">
            <div class="item flex gap-2 mb-2">
                <select name="items[0][drug_id]" class="border p-2">
                    @foreach($drugs as $drug)
                        <option value="{{ $drug->id }}">
                            {{ $drug->name }}
                        </option>
                    @endforeach
                </select>

                <input type="number" name="items[0][quantity]" placeholder="Quantity" class="border p-2">
            </div>
        </div>

        <button type="button" onclick="addItem()">+ Add Drug</button>

        <button type="submit">Save</button>
    </form>
    <script>
        let index = 1;

        function addItem() {
            let html = `
    <div class="item flex gap-2 mb-2">
        <select name="items[${index}][drug_id]" class="border p-2">
            @foreach($drugs as $drug)
            <option value="{{ $drug->id }}">
                    {{ $drug->name }}
            </option>
@endforeach
            </select>

            <input type="number" name="items[${index}][quantity]" placeholder="Quantity" class="border p-2">
    </div>
    `;
            document.getElementById('items').insertAdjacentHTML('beforeend', html);
            index++;
        }
    </script>
@endsection
