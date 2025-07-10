<table border="1" cellpadding="10">
    <thead>
        <tr>
            <th>Year</th>
            <th>Month</th>
            <th>Customer</th>
            <th>Phone</th>
            <th>Address</th>
            <th>Product</th>
            <th>Total Quantity Sold</th>
            <th>Total Amount (₹)</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($reports as $report)
            <tr>
                <td>{{ $report->year }}</td>
                <td>{{ $report->month }}</td>
                <td>{{ $report->customer_name }}</td>
                <td>{{ $report->phone }}</td>
                <td>{{ $report->address }}</td>
                <td>{{ $report->product }}</td>
                <td>{{ $report->total_quantity }}</td>
                <td>₹{{ number_format($report->total_amount, 2) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="8">No data found.</td>
            </tr>
        @endforelse
    </tbody>
</table>
