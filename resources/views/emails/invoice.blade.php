<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Your Invoice</title>
</head>
<body>
    <h2>Hello {{ $sale->customer_name }},</h2>
    <p>Thank you for your purchase. Please find your invoice attached.</p>
    
    <p><strong>Invoice #:</strong> {{ $sale->invoice_number }}</p>
    <p><strong>Total Amount:</strong> ₹{{ number_format($sale->total, 2) }}</p>

    <p>Regards,<br>Your Company</p>
</body>
</html>
