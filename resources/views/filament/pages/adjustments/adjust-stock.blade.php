<x-filament::page>
    <div class="mb-4">
        {{ $this->form }}
    </div>

    <table class="table-auto w-full mt-2 border border-gray-300 text-sm md:border-spacing-2 outline-none">
        <thead>
            <tr>
                <th class="border px-3 py-2">Nama Produk</th>
                <th class="border px-3 py-2">Kode Produk</th>
                <th class="border px-3 py-2">Stok</th>
                <th class="border px-3 py-2">Jumlah</th>
                <th class="border px-3 py-2">Sesuaikan</th>
                <th class="border px-3 py-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($items as $index => $item)
                <tr>
                    <td class="border px-3 py-2">{{ $item['name'] }}</td>
                    <td class="border px-3 py-2">{{ $item['code'] }}</td>
                    <td class="border px-3 py-2 text-center">{{ $item['stock'] }}</td>
                    <td class="border p-2 text-center">
                        <input type="number" wire:model="items.{{ $index }}.quantity"
                            class="w-full h-10 rounded-lg">
                    </td>
                    <td class="border p-2 text-center">
                        <select wire:model="items.{{ $index }}.type"
                            class="w-full h-10 rounded-lg">
                            <option value="add">(+) Tambah</option>
                            <option value="sub">(-) Kurang</option>
                        </select>
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
                    <td colspan="5" class="text-center py-4 text-gray-500">
                        Belum ada produk yang ditambahkan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if (count($items) > 0)
        <div class="mt-4">
            <textarea wire:model="note" rows="2" class="w-full rounded-md"
                placeholder="Catatan (opsional)"></textarea>
        </div>

        <div class="mt-4">
            <x-filament::button wire:click="save">
                Buat Penyesuaian
            </x-filament::button>
        </div>
    @endif
</x-filament::page>
