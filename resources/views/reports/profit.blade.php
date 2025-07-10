@extends('backend.layouts.app')

@section('content')
<div class="container">
    <h2>Monthly Product Profit Report</h2>

    <form method="GET" action="{{ route('reports.profit') }}" class="mb-4">
        <div class="row g-3 align-items-end">
            <div class="col-auto">
                <label for="month">Month:</label>
                <select name="month" class="form-select" id="month">
                    @for ($m = 1; $m <= 12; $m++)
                        <option value="{{ sprintf('%02d', $m) }}" {{ $month == sprintf('%02d', $m) ? 'selected' : '' }}>
                            {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                        </option>
                    @endfor
                </select>
            </div>
            <div class="col-auto">
                <label for="year">Year:</label>
                <select name="year" class="form-select" id="year">
                    @for ($y = now()->year; $y >= 2022; $y--)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </div>
    </form>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Product Name</th>
                <th>Quantity Sold</th>
                <th>Total Sales Amount</th>
                <th>Total Cost</th>
                <th>Total Profit</th>
            </tr>
        </thead>
        <tbody>
            @forelse($profits as $profit)
                <tr>
                    <td>{{ $profit->product_name }}</td>
                    <td>{{ $profit->quantity_sold }}</td>
                    <td>₹{{ number_format($profit->total_revenue, 2) }}</td>
                    <td>₹{{ number_format($profit->total_cost, 2) }}</td>
                    <td>₹{{ number_format($profit->total_profit, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No sales found for selected month.</td>
                </tr>
            @endforelse
        </tbody>
<tfoot class="table-secondary">
    <tr>
        <th>Total</th>
        <th>{{ $profits->sum('quantity_sold') }}</th>
        <th>₹{{ number_format($profits->sum('total_revenue'), 2) }}</th>
        <th>₹{{ number_format($profits->sum('total_cost'), 2) }}</th>
        <th>₹{{ number_format($profits->sum('total_profit'), 2) }}</th>
    </tr>
</tfoot>
    </table>
</div>
@endsection
