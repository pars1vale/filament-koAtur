<x-filament::page>
    <div class="bg-white dark:bg-gray-900 shadow rounded-xl p-6">
        {{-- Header --}}

        <div class="flex justify-between items-start mb-2">
            <div>
                <h2 class="text-l text-gray-800 dark:text-gray-200">
                    Reference: <b>{{ $quotation->reference }}<b>
                </h2>
            </div>

            <button wire:click="downloadPdf"
                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                <x-heroicon-o-printer class="w-5 h-5 mr-2" /> Print PDF
            </button>
        </div>

        {{-- Company / Customer / Invoice Info --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-2">
            <div>
                <h3 class="font-bold mb-2">Company Info</h3>
                <p class="w-full sm:w-1/3 border-t border-gray-300 pt-4">My Company</p>
                <p class="font-normal">123 Business Street</p>
                <p class="font-normal">Email: info@company.com</p>
                <p class="font-normal">Phone: +62 812 3456 7890</p>
            </div>

            <div>
                <h3 class="font-bold mb-2">Customer Info</h3>
                <p class="w-full sm:w-1/3 border-t border-gray-300 pt-4">
                    {{ $quotation->customer?->customer_name ?? '-' }}</p>
                <p class="font-normal">{{ $quotation->customer?->address ?? '-' }}</p>
                <p class="font-normal">{{ $quotation->customer?->customer_email ?? '-' }}</p>
                <p class="font-normal">{{ $quotation->customer?->customer_phone ?? '-' }}</p>
            </div>

            <div>
                <h3 class="font-bold mb-2">Invoice Info</h3>
                <p class="w-full font-normal sm:w-1/3 border-t border-gray-300 pt-4">Quotation:
                    <strong>{{ $quotation->reference }}</<strong>>
                </p>
                <p class="font-normal">Date:
                    {{ $quotation->date ? \Illuminate\Support\Carbon::parse($quotation->date)->format('d M Y') : '-' }}
                </p>
                <p class="font-normal">Status: <strong>{{ ucfirst($quotation->status) }}</strong></p>
                <p class="font-normal">Payment Status:</p>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto w-full sm:w-1/3 border-t border-gray-300 pt-4">
            <table class="table-auto w-full border border-gray-300 text-sm">
                <thead class="bg-gray-100 dark:bg-gray-800">
                    <tr>
                        <th class="border px-3 py-2">#</th>
                        <th class="border px-3 py-2">Product</th>
                        <th class="border px-3 py-2">Net Unit Price</th>
                        <th class="border px-3 py-2">Quantity</th>
                        <th class="border px-3 py-2">Discount</th>
                        <th class="border px-3 py-2">Tax</th>
                        <th class="border px-3 py-2">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($quotation->details as $index => $detail)
                        <tr>
                            <td class="border p-2">{{ $index + 1 }}</td>
                            <td class="border p-2">{{ $detail->product_name }}</td>
                            <td class="border p-2">{{ $detail->unit_price }}</td>
                            <td class="border p-2 text-center">{{ $detail->quantity }}</td>
                            <td class="border p-2 text-left">Rp
                                {{ number_format($detail->product_discount_amount, 0, ',', '.') }}
                            </td>
                            <td class="border p-2 text-left">Rp
                                {{ number_format($detail->product_tax_amount, 0, ',', '.') }}</td>
                            <td class="border p-2 text-left">Rp {{ number_format($detail->sub_total, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Totals --}}
        <div class="mt-6 flex justify-end">
            <div class="w-full sm:w-1/3 border-t border-gray-300 pt-4">
                <div class="flex justify-between mb-2">
                    <span>Discount ({{ $quotation->discount_percentage ?? 0 }}%)</span>
                    <span>Rp {{ number_format($quotation->discount_amount, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between mb-2">
                    <span>Tax ({{ $quotation->tax_percentage ?? 0 }}%)</span>
                    <span>Rp {{ number_format($quotation->tax_amount, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between mb-2">
                    <span>Shipping</span>
                    <span>Rp {{ number_format($quotation->shipping_amount, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between font-bold text-lg border-t border-gray-300 pt-2">
                    <span>Grand Total</span>
                    <span>Rp {{ number_format($quotation->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>
</x-filament::page>
