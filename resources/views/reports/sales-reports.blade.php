@extends('backend.layouts.app')

@section('content')

    <h1>Monthly Sales Report</h1>

    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>Year</th>
                <th>Month</th>
                <th>Total Items Sold</th>
                <th>Total Sales (₹)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reports as $report)
                <tr>
                    <td>{{ $report->year }}</td>
                    <td>{{ $report->month }}</td>
                    <td>{{ $report->total_items }}</td>
                    <td>₹{{ number_format($report->total_sales, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
@endsection
