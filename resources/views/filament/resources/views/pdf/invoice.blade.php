<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Invoice</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 13px;
            color: #333;
        }

        .title {
            text-align: center;
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .subtitle {
            text-align: center;
            font-size: 14px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }

        table,
        th,
        td {
            border: 1px solid #bbb;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        .right {
            text-align: right !important;
        }

        .totals {
            width: 40%;
            margin-left: auto;
            border-collapse: collapse;
        }

        .totals td {
            padding: 6px;
            border: 1px solid #ccc;
        }

        .footer-text {
            font-size: 12px;
            text-align: center;
            margin-top: 25px;
        }
    </style>
</head>

<body>

    <div class="title">INVOICE</div>
    <div class="subtitle">Generated Automatically by POS System</div>

    <table>
        <tr>
            <td><strong>Customer:</strong> {{ $customerName ?: '-' }}</td>
            <td class="right"><strong>Date:</strong> {{ $invoiceTime->format('d/m/Y H:i') }}</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th width="40%">Product</th>
                <th width="10%" class="right">Qty</th>
                <th width="25%" class="right">Price</th>
                <th width="25%" class="right">Total</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($cart as $item)
                <tr>
                    <td>{{ $item['name'] }}</td>
                    <td class="right">{{ $item['qty'] }}</td>
                    <td class="right">Rp {{ number_format($item['price']) }}</td>
                    <td class="right">Rp {{ number_format($item['price'] * $item['qty']) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals">
        <tr>
            <td><strong>Subtotal</strong></td>
            <td class="right">Rp {{ number_format($subtotal) }}</td>
        </tr>

        <tr>
            <td>
                <strong>Discount ({{ $discountPercent }}%)</strong>
            </td>
            <td class="right">- Rp {{ number_format($discountAmount) }}</td>
        </tr>

        <tr>
            <td><strong>Grand Total</strong></td>
            <td class="right"><strong>Rp {{ number_format($total) }}</strong></td>
        </tr>
    </table>

    <div class="footer-text">
        Thank you for your purchase!
        <br> Powered by Laravel + Filament POS
    </div>

</body>

</html>
