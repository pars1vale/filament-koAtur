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
            padding: 5px;
            padding-left: 10px;
            padding-right: 10px;
            text-align: center;
            background-color: aqua
        }
    </style>
</head>

<body>
    <div>
        @for ($i = 0; $i < $qty; $i++)
            <div class="barcode">
                <div>{{ $product->product_name }}</div>
                {!! DNS1D::getBarcodeHTML($product->product_code, 'EAN13', 2, 50) !!}
                <div>{{ $product->product_code }}</div>
            </div>
        @endfor
    </div>
</body>

</html>
