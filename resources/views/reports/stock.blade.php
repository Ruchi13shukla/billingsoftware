@extends('backend.layouts.app')

@section('content')
<div class="container">
    <h3>Daily Stock Report</h3>

<form method="GET" action="{{ route('stock.report') }}" class="mb-4">
    <div class="row">
        <div class="col">
            <input type="date" name="from" class="form-control" value="{{ request('from') }}">
        </div>
        <div class="col">
            <input type="date" name="to" class="form-control" value="{{ request('to') }}">
        </div>
        <div class="col">
            <button class="btn btn-primary" type="submit">Filter</button>
        </div>
    </div>
</form>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Product</th>
            <th>Opening Stock</th>
            <th>Stock Out</th>
            <th>Closing Stock</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($stockReports as $report)
            <tr>
                <td>{{ $report->product->name }}</td>
                <td>{{ $report->opening_stock }}</td>
                <td>{{ $report->stock_out }}</td>
                <td>{{ $report->closing_stock }}</td>
                <td>{{ $report->created_at->format('Y-m-d') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5">No data found.</td>
            </tr>
        @endforelse
    </tbody>
</table>
