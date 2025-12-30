<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Penawaran - {{ $quotation->reference }}</title>
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
        <h2>Penawaran</h2>
        <p>No. Referensi: <strong>{{ $quotation->reference }}</strong></p>
    </div>

    <div class="info">
        <div class="company-info">
            <h4>Info Perusahaan</h4>
            <p><strong>My Company</strong></p>
            <p>123 Business Street</p>
            <p>Email: info@company.com</p>
            <p>Telepon: +62 812 3456 7890</p>
        </div>

        <div class="customer-info">
            <h4>Info Pelanggan</h4>
            <p><strong>{{ $quotation->customer?->customer_name ?? '-' }}</strong></p>
            <p>{{ $quotation->customer?->address ?? '-' }}</p>
            <p>{{ $quotation->customer?->customer_email ?? '-' }}</p>
            <p>{{ $quotation->customer?->customer_phone ?? '-' }}</p>
        </div>

        <div class="invoice-info">
            <h4>Rincian Faktur</h4>
            <p>Penawaran: <strong>{{ $quotation->reference }}</strong></p>
            <p>Tanggal: {{ $quotation->date ? \Carbon\Carbon::parse($quotation->date)->format('d M Y') : '-' }}</p>
            <p>Status: <strong>{{ ucfirst($quotation->status ?? 'Pending') }}</strong></p>
            <p>Status Pembayaran:</p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Produk</th>
                <th>Harga Satuan Bersih</th>
                <th>Jumlah</th>
                <th>Diskon</th>
                <th>Pajak</th>
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
                <td>Diskon ({{ $quotation->discount_percentage ?? 0 }}%)</td>
                <td>Rp {{ number_format($quotation->discount_amount, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Pajak ({{ $quotation->tax_percentage ?? 0 }}%)</td>
                <td>Rp {{ number_format($quotation->tax_amount, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Pengiriman</td>
                <td>Rp {{ number_format($quotation->shipping_amount, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td><strong>Total Keseluruhan</strong></td>
                <td><strong>Rp {{ number_format($quotation->total_amount, 0, ',', '.') }}</strong></td>
            </tr>
        </table>
    </div>
</body>

</html>
