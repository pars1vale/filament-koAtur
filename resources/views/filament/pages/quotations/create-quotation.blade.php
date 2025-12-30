<x-filament::page>
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow p-4">
            {{ $this->form }}
        </div>

        <div class="mt-4">
            <table class="table-auto w-full border border-gray-300 text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-3 py-2">Produk</th>
                        <th class="border px-3 py-2">Harga Bersih per Unit</th>
                        <th class="border px-3 py-2">Stok</th>
                        <th class="border px-3 py-2">Jumlah</th>
                        <th class="border px-3 py-2">Pajak</th>
                        <th class="border px-3 py-2">Subtotal</th>
                        <th class="border px-3 py-2">Aksi</th>
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
                                    class="w-20 h-8 rounded-md border-gray-500 text-center" />
                            </td>
                            <td class="border px-3 py-2 text-center">
                                Rp {{ number_format($item['tax'], 0, ',', '.') }}
                            </td>
                            <td class="border px-3 py-2 text-center">
                                Rp {{ number_format($item['sub_total'], 0, ',', '.') }}
                            </td>
                            <td class="border px-3 py-2 text-center">
                                <button
                                    type="button"
                                    wire:click="removeItem({{ $index }})"
                                    class="rounded-md px-2 py-1 hover:bg-red-100"
                                >
                                    @svg('heroicon-o-trash', 'w-4 h-4 text-red-600')
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-gray-500 py-4">
                                Belum ada produk yang ditambahkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 flex justify-end">
            <div class="text-right">
                <p class="font-semibold text-md">
                    Pajak ({{ $tax_percentage }}%): Rp {{ number_format($tax_amount, 0, ',', '.') }}
                </p>
                <p class="font-semibold text-md">
                    Diskon ({{ $discount_percentage }}%): Rp {{ number_format($discount_amount, 0, ',', '.') }}
                </p>
                <p class="font-semibold text-md">
                    Pengiriman: Rp {{ number_format($shipping_amount, 0, ',', '.') }}
                </p>
                <p class="font-semibold text-md">
                    Total Keseluruhan: Rp {{ number_format($grand_total, 0, ',', '.') }}
                </p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-4">
            <div class="flex flex-wrap items-center gap-4 w-full">

                <div class="flex flex-col flex-1 min-w-[150px]">
                    <label class="text-sm font-medium mb-1">Pajak (%)</label>
                    <input type="number" min="0" wire:model.live="tax_percentage"
                        class="w-full border border-gray-600 rounded-md h-9 px-2 focus:ring-primary-500 focus:border-primary-500" />
                </div>

                <div class="flex flex-col flex-1 min-w-[150px]">
                    <label class="text-sm font-medium mb-1">Diskon (%)</label>
                    <input type="number" min="0" wire:model.live="discount_percentage"
                        class="w-full border border-gray-600 rounded-md h-9 px-2 focus:ring-primary-500 focus:border-primary-500" />
                </div>

                <div class="flex flex-col flex-1 min-w-[150px]">
                    <label class="text-sm font-medium mb-1">Pengiriman</label>
                    <input type="number" min="0" wire:model.live="shipping_amount"
                        class="w-full border border-gray-600 rounded-md h-9 px-2 focus:ring-primary-500 focus:border-primary-500" />
                </div>

                <div class="flex flex-col flex-1 min-w-[150px]">
                    <label class="text-sm font-medium mb-1">Status</label>
                    <select wire:model="status"
                        class="w-full border border-gray-600 rounded-md h-9 px-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="Pending">Tertunda</option>
                        <option value="Sent">Terkirim</option>
                    </select>
                </div>
            </div>

            <div class="pt-4">
                <textarea wire:model="note" rows="2" class="w-full rounded-md"
                    placeholder="Catatan (opsional)"></textarea>
            </div>

            <div class="pt-2">
                <x-filament::button wire:click="save" color="primary" class="mt-2 md:mt-0">
                    Simpan Penawaran
                </x-filament::button>
            </div>
        </div>
    </div>
</x-filament::page>
