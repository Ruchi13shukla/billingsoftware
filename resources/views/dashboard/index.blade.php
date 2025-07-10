@extends('backend.layouts.app')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Dashboard</h2>

    {{-- Summary Cards --}}
    <div class="row mb-4 justify-content-center">
    <div class="col-md-3">
        <div class="card text-white bg-primary">
            <div class="card-body text-center">
                <h5>Total Sales</h5>
                <h3>₹{{ number_format($totalSales, 2) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body text-center">
                <h5>Total Profit</h5>
                <h3>₹{{ number_format($totalProfit, 2) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-secondary">
            <div class="card-body text-center">
                <h5>Total Stock</h5>
                <h3>{{ $stockCount }}</h3>
            </div>
        </div>
    </div>
</div>

    {{-- Monthly Chart --}}

<div class="card mb-4">
<div class="card-body overflow-auto">
        Monthly Sales & Profit</div>
    <div class="card-body" style="position: relative; height: 400px;">
        <canvas id="salesChart" style="width: 100%; height: 100%;"></canvas>
    </div>
</div>


    {{-- Recent Activities --}}
    <div class="card">
        <div class="card-header">Recent Sales</div>
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentActivities as $sale)
                        <tr>
                            <td>{{ $sale->id }}</td>
                            <td>{{ $sale->customer_name ?? 'N/A' }}</td>
                            <td>{{ \Carbon\Carbon::parse($sale->created_at)->format('d M Y') }}</td>
                            <td>₹{{ number_format($sale->total, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center">No recent sales found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($months) !!},
            datasets: [
                {
                    label: 'Revenue',
                    backgroundColor: 'rgba(0, 123, 255, 0.7)',
                    data: {!! json_encode($revenue) !!}
                },
                {
                    label: 'Profit',
                    backgroundColor: 'rgba(40, 167, 69, 0.7)',
                    data: {!! json_encode($profit) !!}
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Monthly Sales and Profit ({{ now()->year }})'
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endsection
