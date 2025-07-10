<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $sale->invoice_number }}</title>
    <style>
        @page {
            margin: 25px;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 14px;
            color: #333;
        }
        h2, h3, h4 {
            margin: 4px 0;
        }
        p {
            margin: 2px 0;
        }

        .invoice-header {
            background-color: #f7f7f7;
            border: 1px solid #333;
            padding: 10px;
            margin-bottom: 10px;
            text-align: center;
        }

        .invoice-info {
            margin-bottom: 10px;
            text-align: right;
        }

        .section {
            margin-bottom: 12px;
            page-break-inside: avoid;
        }

        .customer-box {
            border: 1px solid #ccc;
            padding: 10px;
            background-color: #f9f9f9;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }

        th, td {
            border: 1px solid #555;
            padding: 4px;
            text-align: center;
        }

        th {
            background-color: #eaeaea;
        }

        .totals {
            font-weight: bold;
        }

        .terms {
            font-size: 10px;
            margin-top: 20px;
            padding-top: 5px;
            border-top: 1px dashed #999;
        }

        .footer {
            text-align: center;
            font-size: 10px;
            margin-top: 20px;
            color: #777;
        }

        .signature {
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            align-items: center;
        }

        .signature div {
            width: 48%;
            text-align: center;
            line-height: 2em;
        }

        .btn-wrapper {
            text-align: right;
            margin-bottom: 20px;
        }

        .btn-wrapper a,
        .btn-wrapper button {
            padding: 6px 12px;
            font-size: 14px;
            margin-right: 10px;
            text-decoration: none;
            border-radius: 3px;
            border: none;
            cursor: pointer;
        }

        .btn-print {
            background-color: #ddd;
        }

        .btn-download {
            background-color: #4CAF50;
            color: white;
        }

        .btn-email {
            background-color: #2196F3;
            color: white;
        }

        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>


    <div class="invoice-header">
        <h2>Company Name Pvt. Ltd.</h2>
        <p><strong>Address:</strong> 123 Main Street, City, State, ZIP Code</p>
        <p><strong>Phone:</strong> +91-9876543210 | <strong>Email:</strong> contact@company.com</p>
    </div>


    <div class="invoice-info">
        <h3>Invoice #: {{ $sale->invoice_number }}</h3>
        <p><strong>Date:</strong> {{ $sale->created_at->format('d-m-Y') }}</p>
    </div>

    
    <div class="section customer-box">
        <h3>Customer Details</h3>
        <p><strong>Name:</strong> {{ $sale->customer_name }}</p>
        <p><strong>Phone:</strong> {{ $sale->phone }}</p>
        <p><strong>Address:</strong> {{ $sale->address }}</p>
        <p><strong>GST Type:</strong> {{ $sale->gst_type }}</p>
    </div>

    
    <div class="section">
        <h3>Sale Items</h3>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Cost Price</th>
                    <th>Subtotal</th>
                    <th>CGST%</th>
                    <th>CGST Amt</th>
                    <th>SGST%</th>
                    <th>SGST Amt</th>
                    <th>Total</th>
                    <th>Profit</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sale->items as $item)
                <tr>
                    <td>{{ $item->product->name ?? 'N/A' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->price, 2) }}</td>
                    <td>{{ number_format($item->cost_price, 2) }}</td>
                    <td>{{ number_format($item->subtotal, 2) }}</td>
                    <td>{{ $item->cgst_percentage }}%</td>
                    <td>{{ number_format($item->cgst_amount, 2) }}</td>
                    <td>{{ $item->sgst_percentage }}%</td>
                    <td>{{ number_format($item->sgst_amount, 2) }}</td>
                    <td>{{ number_format($item->total, 2) }}</td>
                    <td>{{ number_format($item->profit, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    
    @php $totalProfit = $sale->items->sum('profit'); @endphp

    <div class="section">
        <h3>Summary</h3>
        <p><strong>Total CGST:</strong> ₹{{ number_format($sale->total_cgst_amount, 2) }}</p>
        <p><strong>Total SGST:</strong> ₹{{ number_format($sale->total_sgst_amount, 2) }}</p>
        <p><strong>Total Profit:</strong> ₹{{ number_format($totalProfit, 2) }}</p>
        <h3>Grand Total: ₹{{ number_format($sale->total, 2) }}</h3>
    </div>


    <div class="signature">
        <div>
            ______________________<br><strong>Customer Signature</strong>
        </div>
        <div>
            ______________________<br><strong>Authorized Signature</strong>
        </div>
    </div>


    <div class="terms">
        <h4>Terms & Conditions</h4>
        <ul style="padding-left: 15px;">
            <li>Goods once sold will not be taken back or exchanged.</li>
            <li>Payment is due within 7 days from the invoice date unless otherwise agreed.</li>
            <li>All disputes are subject to the jurisdiction of your local court.</li>
            <li>Inspect goods upon delivery.</li>
            <li>Warranty & service are per manufacturer terms.</li>
        </ul>
    </div>


    <div class="footer">
        <p>Thank you for your business!</p>
        <p>Company Name Pvt. Ltd. | www.companywebsite.com | contact@company.com</p>
    </div>


    <div class="btn-wrapper no-print">
        <button onclick="window.print()" class="btn-print">🖨️ Print</button>

        <a href="{{ route('sale.invoice.pdf', $sale->id) }}" class="btn-download" target="_blank">⬇️ Download PDF</a>

    </div>

</body>
</html>
