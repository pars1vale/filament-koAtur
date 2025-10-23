<x-filament::page>
    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow p-4">
            {{ $this->form }}
        </div>

        <div class="mt-4">
            <table class="table-auto w-full border border-gray-300 text-sm">
                <thead class="bg-gray-100 dark:bg-gray-800">
                    <tr>
                        <th class="border px-3 py-2">Product</th>
                        <th class="border px-3 py-2">Net Unit Price</th>
                        <th class="border px-3 py-2">Stock</th>
                        <th class="border px-3 py-2">Quantity</th>
                        <th class="border px-3 py-2">Tax</th>
                        <th class="border px-3 py-2">Sub Total</th>
                        <th class="border px-3 py-2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $index => $item)
                        <tr>
                            <td class="border px-3 py-2 text-center">{{ $item['name'] }}</td>
                            <td class="border px-3 py-2 text-center">
                                Rp {{ number_format($item['net_unit_price'], 0, ',', '.') }}
                            </td>
                            <td class="border px-3 py-2 text-center">{{ $item['stock'] }}</td>
                            <td class="border px-3 py-2 text-center">
                                <input type="number" min="1"
                                    wire:model.live="items.{{ $index }}.quantity"
                                    class="w-20 h-8 rounded-md border-gray-500 dark:bg-gray-800 text-center" />
                            </td>
                            <td class="border px-3 py-2 text-center">
                                Rp {{ number_format($item['tax'], 0, ',', '.') }}
                            </td>
                            <td class="border px-3 py-2 text-center">
                                Rp {{ number_format($item['sub_total'], 0, ',', '.') }}
                            </td>
                            <td class="border px-3 py-2 text-center">
                                <button type="button" wire:click="removeItem({{ $index }})"
                                    class="bg-red-600 text-white rounded-md px-2 py-1 hover:bg-red-700">
                                    @svg('heroicon-o-trash', 'w-4 h-4')
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-gray-500 py-4">
                                No products added yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 flex justify-end">
            <div class="text-right">
                <p class="font-semibold text-md">
                    Tax ({{ $tax_percentage }}%): Rp {{ number_format($tax_amount, 0, ',', '.') }}
                </p>
                <p class="font-semibold text-md">
                    Discount ({{ $discount_percentage }}%): Rp {{ number_format($discount_amount, 0, ',', '.') }}
                </p>
                <p class="font-semibold text-md">
                    Shipping: Rp {{ number_format($shipping_amount, 0, ',', '.') }}
                </p>
                <p class="font-semibold text-md">
                    Grand Total: Rp {{ number_format($grand_total, 0, ',', '.') }}
                </p>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl shadow p-4">
            <div class="flex flex-wrap items-center gap-4 w-full">

                <div class="flex flex-col flex-1 min-w-[150px]">
                    <label class="text-sm font-medium dark:text-gray-300 mb-1">Tax (%)</label>
                    <input type="number" min="0" wire:model.live="tax_percentage"
                        class="w-full border border-gray-600 dark:bg-gray-800 rounded-md h-9 px-2 focus:ring-primary-500 focus:border-primary-500" />
                </div>

                <div class="flex flex-col flex-1 min-w-[150px]">
                    <label class="text-sm font-medium dark:text-gray-300 mb-1">Discount (%)</label>
                    <input type="number" min="0" wire:model.live="discount_percentage"
                        class="w-full border border-gray-600 dark:bg-gray-800 rounded-md h-9 px-2 focus:ring-primary-500 focus:border-primary-500" />
                </div>

                <div class="flex flex-col flex-1 min-w-[150px]">
                    <label class="text-sm font-medium dark:text-gray-300 mb-1">Shipping</label>
                    <input type="number" min="0" wire:model.live="shipping_amount"
                        class="w-full border border-gray-600 dark:bg-gray-800 rounded-md h-9 px-2 focus:ring-primary-500 focus:border-primary-500" />
                </div>

                <div class="flex flex-col flex-1 min-w-[150px]">
                    <label class="text-sm font-medium dark:text-gray-300 mb-1">Status</label>
                    <select wire:model="status"
                        class="w-full border border-gray-600 dark:bg-gray-800 rounded-md h-9 px-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="Pending">Pending</option>
                        <option value="Sent">Sent</option>
                    </select>
                </div>
            </div>

            <div class="pt-4">
                <textarea wire:model="note" rows="2" class="w-full dark:bg-gray-800 dark:border-gray-600 rounded-md"
                    placeholder="Note (optional)"></textarea>
            </div>

            <div class="pt-2">
                <x-filament::button wire:click="save" color="primary" class="mt-2 md:mt-0">
                    Save Quotation
                </x-filament::button>
            </div>
        </div>
    </div>
</x-filament::page>
