<x-filament::page>
    <div class="mb-6">
        {{ $this->form }}
    </div>

    <div>
        <table class="table-auto w-full mt-2 border border-gray-300 text-sm">
            <thead>
                <tr>
                    <th class="border px-4 py-2">Nama Produk</th>
                    <th class="border px-4 py-2">Kode Produk</th>
                    <th class="border px-4 py-2">Jumlah Barcode</th>
                    <th class="border px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($items as $item)
                    <tr>
                        <td class="border px-4 py-2">{{ $item['name'] }}</td>
                        <td class="border px-4 py-2">{{ $item['code'] }}</td>
                        <td class="border px-4 py-2">
                            <input type="number" wire:model.defer="items.{{ $loop->index }}.quantity" min="1"
                                class="w-full h-10 rounded-lg" />
                        </td>
                        <td class="border px-4 py-2 text-center">
                            <button type="button"
                                wire:click="removeItem({{ $item['id'] }})"
                                title="Remove"
                                class="bg-transparent hover:bg-red-50 p-2 rounded-lg">
                                
                                @svg('heroicon-o-trash', 'w-5 h-5 text-red-600')
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-gray-500">
                            Belum ada produk yang ditambahkan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if (count($items) > 0)
        <div class="mt-4">
            <form wire:submit.prevent="generateBarcode">
                <x-filament::button type="submit" icon="heroicon-o-printer">
                    Buat PDF Barcode
                </x-filament::button>
            </form>
        </div>
    @endif
</x-filament::page>
