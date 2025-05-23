@extends('backend.layouts.app')

@section('content')
<div class="container mt-4">
    <h4>Create Sale (GST and Non-GST)</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('sales.store') }}">
        @csrf

      
        <div class="card mb-3">
            <div class="card-header">Customer Information</div>
            <div class="card-body row">
                <div class="form-group col-md-3">
                    <label>Name</label>
                    <input type="text" name="customer_name" class="form-control" required>
                </div>
                <div class="form-group col-md-3">
                    <label>Phone</label>
                    <input type="text" name="customer_phone" class="form-control" required>
                </div>
                <div class="form-group col-md-3">
                    <label>Address</label>
                    <input type="text" name="customer_address" class="form-control" required>
                </div>
                <div class="form-group col-md-3">
                    <label>GSTIN (optional)</label>
                    <input type="text" name="customer_gstin" class="form-control">
                </div>
            </div>
        </div>

       
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between">
                <span>Products</span>
                <button type="button" id="add_row" class="btn btn-sm btn-secondary">+ Add Product</button>
            </div>
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
                                    <option value="">-- Select --</option>
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
                            <td>
                                <input type="number" name="products[0][quantity]" class="form-control quantity" min="1" value="1" required>
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm remove-row">X</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        
        <div class="card mb-3">
            <div class="card-body row align-items-center">
                <div class="col-md-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="gst_toggle" checked>
                        <label class="form-check-label" for="gst_toggle">Apply GST</label>
                    </div>
                </div>

                <div class="col-md-3" id="gst_section">
                    <label for="gst_percentage">GST %</label>
                    <select id="gst_percentage" name="gst_percentage" class="form-control">
                        <option value="0">0%</option>
                        <option value="5">5%</option>
                        <option value="12">12%</option>
                        <option value="18" selected>18%</option>
                        <option value="28">28%</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label>Total (Before GST)</label>
                    <input type="text" id="subtotal" class="form-control" readonly>
                </div>

                <div class="col-md-3">
                    <label>Total Payable</label>
                    <input type="text" id="total" class="form-control" readonly>
                    <input type="hidden" name="total_amount" id="total_amount">
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end mb-5">
            <button type="submit" class="btn btn-primary px-4">Save Sale</button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    let rowIndex = 1;

    function updateTotals() {
        let subtotal = 0;

        document.querySelectorAll('#products_table tbody tr').forEach((row) => {
            const price = parseFloat(row.querySelector('.price')?.value || 0);
            const qty = parseInt(row.querySelector('.quantity')?.value || 1);
            subtotal += price * qty;
        });

        const gstEnabled = document.getElementById('gst_toggle').checked;
        let total = subtotal;

        if (gstEnabled) {
            const gstRate = parseFloat(document.getElementById('gst_percentage')?.value || 0);
            const gstAmount = subtotal * gstRate / 100;
            total = subtotal + gstAmount;
            document.getElementById('gst_section').style.display = 'block';
        } else {
            document.getElementById('gst_section').style.display = 'none';
        }

        document.getElementById('subtotal').value = subtotal.toFixed(2);
        document.getElementById('total').value = total.toFixed(2);
        document.getElementById('total_amount').value = total.toFixed(2);
    }

    document.addEventListener('DOMContentLoaded', function () {
        const tableBody = document.querySelector('#products_table tbody');

        document.getElementById('add_row').addEventListener('click', function () {
            const firstRow = tableBody.querySelector('tr');
            const newRow = firstRow.cloneNode(true);

            newRow.querySelectorAll('select, input').forEach((el) => {
                if (el.name) el.name = el.name.replace(/\[\d+\]/, `[${rowIndex}]`);
                if (el.classList.contains('price_display') || el.tagName === 'INPUT') el.value = '';
                if (el.tagName === 'SELECT') el.selectedIndex = 0;
            });

            tableBody.appendChild(newRow);
            rowIndex++;
        });

        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-row')) {
                const row = e.target.closest('tr');
                if (tableBody.rows.length > 1) row.remove();
                updateTotals();
            }
        });

        document.addEventListener('change', function (e) {
            if (e.target.classList.contains('product-select')) {
                const price = e.target.selectedOptions[0].dataset.price || 0;
                const row = e.target.closest('tr');
                row.querySelector('.price_display').value = price;
                row.querySelector('.price').value = price;
                updateTotals();
            }

            if (e.target.classList.contains('quantity') || e.target.id === 'gst_percentage' || e.target.id === 'gst_toggle') {
                updateTotals();
            }
        });

        
        updateTotals();
    });
</script>
@endsection
