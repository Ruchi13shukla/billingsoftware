@extends('backend.layouts.app')

@section('content')
<div class="container">
    <h2>Product-wise Monthly Sales Report</h2>

    <form method="GET" action="{{ route('sales.monthly_report') }}" class="mb-4">
        <div class="row">
            <div class="col-md-3">
                <label>Customer</label>
                <select name="customer_id" class="form-control">
                    <option value="">-- Select Customer --</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label>Product</label>
                <select name="product_id" class="form-control">
                    <option value="">All</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label>From</label>
                <input type="date" name="from" class="form-control">
            </div>
            <div class="col-md-2">
                <label>To</label>
                <input type="date" name="to" class="form-control">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </div>
    </form>

    @foreach($report as $month => $data)
        <h4>{{ \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y') }}</h4>

        <table class="table table-bordered">
            <thead class="table-secondary">
                <tr>
                    <th>Product</th>
                    <th>Total Quantity Sold</th>
                    <th>Total Amount (₹)</th>
                    {{-- <th>Customers</th> --}}
                </tr>
            </thead>
            <tbody>
                @foreach($data['products'] as $product)
                    <tr>
                        <td>{{ $product['product'] }}</td>
                        <td>{{ $product['total_quantity'] }}</td>
                        <td>₹{{ number_format($product['total_amount'], 2) }}</td>
                        <td>
                            @foreach($product['customers'] as $customer)
                                Name: {{ $customer['name'] }}<br>
                                Phone: {{ $customer['phone'] }}<br>
                                Address: {{ $customer[''] }}<br><hr>
                            @endforeach
                        </td>
                    </tr>
                @endforeach
                <tr class="table-success font-weight-bold">
                    <td colspan="2">Monthly Total:</td>
                    <td colspan="2">₹{{ number_format($data['month_total'], 2) }}</td>
                </tr>
            </tbody>
        </table>
    @endforeach
</div>
@endsection
