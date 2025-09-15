<x-filament::page>
    <div class="mb-6">
        {{ $this->form }}
    </div>

    @if ($this->product_id)
        <div>
            <table class="table-auto w-full mt-2 border border-gray-300 text-sm md:border-spacing-2 outline-none">
                <thead>
                    <tr>
                        <th class="border px-4 py-2"> Product Name</th>
                        <th class="border px-4 py-2"> Product Code</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $product = \App\Models\Products\Product::find($product_id);
                    @endphp

                    @if ($product)
                        <td class="border px-4 py-2">{{ $product->product_name }}</td>
                        <td class="border px-4 py-2">{{ $product->product_code }}</td>
                    @endif
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            <form wire:submit.prevent="generateBarcode">
                <x-filament::button type="submit" icon="heroicon-o-printer">
                    Generate Barcode PDF
                </x-filament::button>
            </form>
        </div>
    @endif
</x-filament::page>
