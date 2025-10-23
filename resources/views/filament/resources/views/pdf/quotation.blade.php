<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Quotation - {{ $quotation->reference }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
        }

        .header,
        .footer {
            text-align: center;
        }

        .company-info,
        .customer-info,
        .invoice-info {
            width: 32%;
            display: inline-block;
            vertical-align: top;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f3f3f3;
        }

        .totals {
            width: 40%;
            float: right;
            margin-top: 15px;
        }

        .totals table {
            border: none;
        }

        .totals td {
            border: none;
            padding: 4px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>Quotation</h2>
        <p>Reference: <strong>{{ $quotation->reference }}</strong></p>
    </div>

    <div class="info">
        <div class="company-info">
            <h4>Company Info</h4>
            <p><strong>My Company</strong></p>
            <p>123 Business Street</p>
            <p>Email: info@company.com</p>
            <p>Phone: +62 812 3456 7890</p>
        </div>

        <div class="customer-info">
            <h4>Customer Info</h4>
            <p><strong>{{ $quotation->customer?->customer_name ?? '-' }}</strong></p>
            <p>{{ $quotation->customer?->address ?? '-' }}</p>
            <p>{{ $quotation->customer?->customer_email ?? '-' }}</p>
            <p>{{ $quotation->customer?->customer_phone ?? '-' }}</p>
        </div>

        <div class="invoice-info">
            <h4>Invoice Info</h4>
            <p>Reference: <strong>{{ $quotation->reference }}</strong></p>
            <p>Date: {{ $quotation->date ? \Carbon\Carbon::parse($quotation->date)->format('d M Y') : '-' }}</p>
            <p>Status: <strong>{{ ucfirst($quotation->status ?? 'Pending') }}</strong></p>
            <p>Payment Status:</p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Product</th>
                <th>Net Unit Price</th>
                <th>Quantity</th>
                <th>Discount</th>
                <th>Tax</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($quotation->details as $index => $detail)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $detail->product_name }}</td>
                    <td>{{ number_format($detail->unit_price, 0, ',', '.') }}</td>
                    <td>{{ $detail->quantity }}</td>
                    <td>{{ number_format($detail->product_discount_amount, 0, ',', '.') }}</td>
                    <td>{{ number_format($detail->product_tax_amount, 0, ',', '.') }}</td>
                    <td>{{ number_format($detail->sub_total, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <table>
            <tr>
                <td>Discount ({{ $quotation->discount_percentage ?? 0 }}%)</td>
                <td>Rp {{ number_format($quotation->discount_amount, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Tax ({{ $quotation->tax_percentage ?? 0 }}%)</td>
                <td>Rp {{ number_format($quotation->tax_amount, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Shipping</td>
                <td>Rp {{ number_format($quotation->shipping_amount, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td><strong>Grand Total</strong></td>
                <td><strong>Rp {{ number_format($quotation->total_amount, 0, ',', '.') }}</strong></td>
            </tr>
        </table>
    </div>
</body>

</html>
