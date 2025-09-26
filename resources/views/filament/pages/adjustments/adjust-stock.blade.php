<x-filament::page>
    <div class="mb-4">
        {{ $this->form }}
    </div>

    <table class="table-auto w-full mt-2 border border-gray-300 text-sm md:border-spacing-2 outline-none">
        <thead>
            <tr>
                <th class="border px-3 py-2">Product Name</th>
                <th class="border px-3 py-2">Product Code</th>
                <th class="border px-3 py-2">Stock</th>
                <th class="border px-3 py-2">Quantity</th>
                <th class="border px-3 py-2">Adjust</th>
                <th class="border px-3 py-2">Action</th>
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
                            class="w-full h-10 dark:bg-gray-800 dark:border-gray-600 rounded-lg">
                    </td>
                    <td class="border p-2 text-center">
                        <select wire:model="items.{{ $index }}.type"
                            class="w-full h-10 dark:bg-gray-800 dark:border-gray-600 rounded-lg">
                            <option value="add">(+) Addition</option>
                            <option value="sub">(-) Subtract</option>
                        </select>
                    </td>
                    <td class="border border-gray-300 p-2 text-center">
                        <button type="button" wire:click="removeItem({{ $index }})" title="Remove"
                            class="bg-red-600 hover:bg-red-700 text-white p-2 rounded-lg">
                            @svg('heroicon-o-trash', 'w-5 h-5')
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-gray-500 dark:text-gray-400">
                        No products added yet.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if (count($items) > 0)
        <div class="mt-4">
            <textarea wire:model="note" rows="2" class="w-full dark:bg-gray-800 dark:border-gray-600 rounded-md"
                placeholder="Note (optional)"></textarea>
        </div>

        <div class="mt-4">
            <x-filament::button wire:click="save">
                Create Adjustment
            </x-filament::button>
        </div>
    @endif
</x-filament::page>
