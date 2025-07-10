<h3>Daily Sales Report</h3>

@if ($from && $to)
    <p>From {{ $from }} to {{ $to }}</p>
@endif

@php
    use Carbon\Carbon;
    $grouped = $reports->groupBy(function ($item) {
        return Carbon::parse($item->sale_date)->format('F Y'); 
    });
@endphp

@foreach ($grouped as $month => $monthlyReports)
    <h4 style="background-color: #d1e7dd; padding: 10px; color: #0f5132;">{{ $month }}</h4>

    <table border="1" cellpadding="10" width="100%" style="margin-bottom: 30px;">
        <thead style="background-color: #f8f9fa;">
            <tr>
                <th>Sale Date</th>
                <th>Customer Name</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Product</th>
                <th>Total Quantity</th>
                <th>Total Amount (₹)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($monthlyReports as $report)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($report->sale_date)->format('d-m-Y') }}</td>
                    <td>{{ $report->customer_name }}</td>
                    <td>{{ $report->phone }}</td>
                    <td>{{ $report->address }}</td>
                    <td>{{ $report->product }}</td>
                    <td>{{ $report->total_quantity }}</td>
                    <td>₹{{ number_format($report->total_amount, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Monthly PDF Button --}}
    @php
        $monthDate = Carbon::parse($monthlyReports->first()->sale_date);
        $monthStart = $monthDate->copy()->startOfMonth()->format('Y-m-d');
        $monthEnd = $monthDate->copy()->endOfMonth()->format('Y-m-d');
    @endphp

@endforeach
