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

    <table border="1" cellpadding="10" width="100%" style="margin-bottom: 10px;">
        <thead style="background-color: #f8f9fa;">
            <tr>
                <th>Date</th>
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

    
    @php
        $monthStart = \Carbon\Carbon::parse($monthlyReports->first()->sale_date)->startOfMonth()->format('Y-m-d');
        $monthEnd = \Carbon\Carbon::parse($monthlyReports->first()->sale_date)->endOfMonth()->format('Y-m-d');
    @endphp

    <form method="GET" action="{{ route('reports.monthly_pdf') }}" target="_blank" style="margin-bottom: 30px;">
        <input type="hidden" name="from" value="{{ $monthStart }}">
        <input type="hidden" name="to" value="{{ $monthEnd }}">
        <button type="submit" style="padding: 8px 16px; background-color: #0d6efd; color: #fff; border: none; border-radius: 5px;">
            Download PDF for {{ $month }}
        </button>
    </form>
@endforeach
