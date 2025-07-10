@extends('backend.layouts.app')

@section('content')
    <h2>Monthly Sales Report</h2>
    <a href="{{ route('reports.download') }}" class="btn btn-success">Download PDF</a>

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Year</th>
                <th>Month</th>
                <th>Total Items Sold</th>
                <th>Total Sales (₹)</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($reports as $report)
                <tr>
                    <td>{{ $report->year }}</td>
                    <td>{{ \Carbon\Carbon::create()->month($report->month)->format('F') }}</td>
                    <td>{{ $report->total_items }}</td>
                    <td>₹{{ number_format($report->total_sales, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No sales data found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
