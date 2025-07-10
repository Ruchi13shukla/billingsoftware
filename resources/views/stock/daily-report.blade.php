@extends('backend.layouts.app')

@section('content')
<div class="container">
    <h3>Daily Stock Report</h3>

    <form method="GET" class="mb-3">
        <input type="date" name="date" value="{{ $date }}">
        <button type="submit" class="btn btn-primary">Filter</button>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Product</th>
                <th>Opening Stock</th>
                <th>Stock Out</th>
                <th>Closing Stock</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reports as $report)
            <tr>
                <td>{{ $report->product->name }}</td>
                <td>{{ $report->opening_stock }}</td>
                <td>{{ $report->stock_out }}</td>
                <td>{{ $report->closing_stock }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
