<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Barcode PDF</title>
    <style>
        body {
            font-family: sans-serif;
        }

        .barcode {
            display: inline-block;
            margin: 10px;
            padding: 5px 10px;
            text-align: center;
            background-color: #0be3ff;
        }

        .product-section {
            page-break-inside: avoid;
            margin-bottom: 30px;
        }
    </style>
</head>

<body>
    @foreach ($products as $item)
        @for ($i = 0; $i < $item['barcode_qty']; $i++)
            <div class="barcode">
                <div>{{ $item['product']->product_name }}</div>
                {!! DNS1D::getBarcodeHTML($item['product']->product_code, 'EAN13', 2, 50) !!}
                <div>{{ $item['product']->product_code }}</div>
            </div>
        @endfor
    @endforeach
</body>

</html>
