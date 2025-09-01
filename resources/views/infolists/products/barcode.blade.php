@php
    use Picqer\Barcode\BarcodeGeneratorPNG;

    $generator = new BarcodeGeneratorPNG();
    $barcode = base64_encode($generator->getBarcode($state->product_code, $generator::TYPE_EAN_13));
@endphp

<div class="flex flex-col items-center mb-4">
    <img src="data:image/png;base64,{{ $barcode }}" alt="Barcode" class="mb-2">
    <button onclick="printBarcode()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
        Print Barcode
    </button>
</div>

<script>
    function printBarcode() {
        const printWindow = window.open('', 'Print Barcode', 'height=400,width=600');
        printWindow.document.write('<img src="data:image/png;base64,{{ $barcode }}" />');
        printWindow.document.close();
        printWindow.print();
    }
</script>
