@extends('backend.layouts.app')

@section('content')
<div class="container mt-4">
    <h4>Create Sale</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('sales.store') }}">
        @csrf

        {{-- Customer Info --}}
        <div class="card mb-3">
            <div class="card-header">Customer Information</div>
            <div class="card-body">
                <div class="form-group mb-2">
                    <label for="customer_name">Name</label>
                    <input type="text" name="customer_name" class="form-control" required>
                </div>

                <div class="form-group mb-2">
                    <label for="customer_phone">Phone</label>
                    <input type="text" name="customer_phone" class="form-control" required>
                </div>

                <div class="form-group mb-2">
                    <label for="customer_address">Address</label>
                    <textarea name="customer_address" class="form-control" rows="2" required></textarea>
                </div>

                <div class="form-group mb-2">
                    <label for="customer_gstin">GSTIN (optional)</label>
                    <input type="text" name="customer_gstin" class="form-control">
                </div>
            </div>
        </div>

        {{-- Products Selection --}}
        <div class="card mb-3">
            <div class="card-header">Products</div>
            <div class="card-body">
                <table class="table table-bordered" id="products_table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th width="100">Price</th>
                            <th width="100">Quantity</th>
                            <th width="50">Remove</th>
                        </tr>
                    </thead>
<tbody>
    <tr>
        <td>
            <select name="products[0][product_id]" class="form-control product-select" required>
                <option value="">-- Select Product --</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                        {{ $product->name }} (₹{{ $product->price }})
                    </option>
                @endforeach
            </select>
        </td>
        <td>
            <input type="text" class="form-control price_display" readonly>
            <input type="hidden" name="products[0][price]" class="price">
        </td>
        <td><input type="number" name="products[0][quantity]" class="form-control" min="1" required></td>
        <td><button type="button" class="btn btn-danger btn-sm remove-row">X</button></td>
    </tr>
</tbody>
                </table>
                <button type="button" id="add_row" class="btn btn-secondary btn-sm">Add Product</button>

            </div>
        </div>

        {{-- Submit --}}
        <button type="submit" class="btn btn-primary">Save Sale</button>
    </form>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    let rowIndex = 1;

    document.getElementById('add_row').addEventListener('click', function () {
        const table = document.querySelector('#products_table tbody');
        const newRow = table.rows[0].cloneNode(true);

        // Reset input values and update name attributes
        newRow.querySelectorAll('select, input').forEach((el) => {
            if (el.name) {
                el.name = el.name.replace(/\[\d+\]/, '[' + rowIndex + ']');
            }
            if (el.classList.contains('price_display')) {
                el.value = '';
            } else if (el.tagName === 'SELECT') {
                el.selectedIndex = 0;
            } else {
                el.value = '';
            }
        });

        table.appendChild(newRow);
        rowIndex++;
    });

    // Remove row
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-row')) {
            const row = e.target.closest('tr');
            const table = row.closest('tbody');
            if (table.rows.length > 1) {
                row.remove();
            }
        }
    });

    // Set price on product select
    document.addEventListener('change', function (e) {
        if (e.target.classList.contains('product-select')) {
            const price = e.target.options[e.target.selectedIndex].dataset.price || '';
            const row = e.target.closest('tr');

            row.querySelector('.price_display').value = price;
            row.querySelector('.price').value = price;
        }
    });
});
</script>
@endsection



