<table class="table-auto border-collapse border border-gray-600 w-full text-sm">
    <tbody>
        <tr class="border-b">
            <td class="font-semibold px-4 py-2 w-40">Product</td>
            <td class="px-4 py-2">{{ $record->product_name }}</td>
        </tr>
        <tr class="border-b">
            <td class="font-semibold px-4 py-2">Category</td>
            <td class="px-4 py-2">{{ $record->category->category_name ?? '-' }}</td>
        </tr>
        <tr class="border-b">
            <td class="font-semibold px-4 py-2">Unit</td>
            <td class="px-4 py-2">{{ $record->unit->name ?? '-' }}</td>
        </tr>
        <tr class="border-b">
            <td class="font-semibold px-4 py-2">SKU</td>
            <td class="px-4 py-2">{{ $record->product_code }}</td>
        </tr>
        <tr class="border-b">
            <td class="font-semibold px-4 py-2">Minimum Qty</td>
            <td class="px-4 py-2">{{ $record->product_stock_alert }}</td>
        </tr>
        <tr class="border-b">
            <td class="font-semibold px-4 py-2">Quantity</td>
            <td class="px-4 py-2">{{ $record->product_quantity }}</td>
        </tr>
        <tr class="border-b">
            <td class="font-semibold px-4 py-2">Tax</td>
            <td class="px-4 py-2">{{ $record->product_order_tax }}%</td>
        </tr>
        <tr class="border-b">
            <td class="font-semibold px-4 py-2">Discount Type</td>
            <td class="px-4 py-2">{{ $record->product_tax_type }}</td>
        </tr>
        <tr class="border-b">
            <td class="font-semibold px-4 py-2">Price</td>
            <td class="px-4 py-2">IDR {{ number_format($record->product_price, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="font-semibold px-4 py-2">Description</td>
            <td class="px-4 py-2">{{ $record->product_note }}</td>
        </tr>
    </tbody>
</table>
