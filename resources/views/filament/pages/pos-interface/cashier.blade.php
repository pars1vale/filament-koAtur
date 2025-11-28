<x-filament::page>
    <div class="flex h-[80vh] gap-4">

        <!-- LEFT SIDE -->
        <div class="w-1/2 h-full border-r dark:bg-gray-900 rounded-xl shadow p-4 flex flex-col">

            <!-- SEARCH + CATEGORY -->
            <div class="p-4 border-b">
                {{ $this->form }}
            </div>

            <!-- PRODUCT GRID -->
            <div class="flex-1 overflow-y-auto p-4">
                <div
                    class="grid gap-4
                    grid-cols-2
                    sm:grid-cols-3
                    lg:grid-cols-4">

                    @forelse ($this->products as $product)
                        <button wire:click="selectProduct({{ $product->id }})"
                            class="border rounded-xl p-3 shadow hover:bg-blue-50 transition">

                            <img src="{{ $product->product_image ?? 'https://via.placeholder.com/150' }}"
                                class="w-full h-24 object-cover rounded mb-2" />

                            <div class="font-semibold text-sm">{{ $product->product_name }}</div>
                            <div class="text-xs text-gray-600">{{ $product->product_note ?? '-' }}</div>
                            <div class="mt-1 font-bold text-blue-600">
                                Rp {{ number_format($product->product_price) }}
                            </div>

                        </button>
                    @empty
                        <p class="text-center text-gray-500 col-span-full">
                            No products found.
                        </p>
                    @endforelse

                </div>

                <!-- PAGINATION -->
                <div class="mt-4">
                    {{ $this->products->links() }}
                </div>

            </div>
        </div>

        <!-- RIGHT SIDE: CART -->
        <div class="w-1/2 h-auto border-l dark:bg-gray-900 rounded-xl shadow p-4 flex flex-col">
            <div class="flex justify-between items-center mb-3">
                <h2 class="font-bold text-lg">Current Order</h2>

                <x-filament::button color="danger" size="sm" wire:click="clearAll">
                    Clear All
                </x-filament::button>
            </div>

            <div class="flex-1 overflow-y-auto space-y-3 border-b py-3">
                @forelse($cart as $id => $item)
                    <div class="flex justify-between items-center">

                        <div>
                            <p class="font-semibold">{{ $item['name'] }}</p>
                            <p class="text-xs text-gray-600">
                                Rp {{ number_format($item['price']) }} × {{ $item['qty'] }}
                            </p>
                        </div>

                        <div class="flex items-center space-x-1">
                            <x-filament::button icon="heroicon-o-minus" size="xs" color="gray"
                                wire:click="decrease({{ $id }})" />

                            <span class="mx-3 text-sm">{{ $item['qty'] }}</span>

                            <x-filament::button icon="heroicon-o-plus" size="xs" color="primary"
                                wire:click="increase({{ $id }})" />
                        </div>

                        <div class="font-bold text-right">
                            Rp{{ number_format($item['price'] * $item['qty']) }}
                        </div>

                    </div>
                @empty
                    <p class="text-gray-500 text-center py-10">Cart is empty.</p>
                @endforelse
            </div>

            <!-- SUMMARY -->
            <div class="mt-3 space-y-2">

                <div class="flex justify-between text-sm">
                    <span>Subtotal</span>
                    <span>Rp{{ number_format($this->subtotal) }}</span>
                </div>

                <div class="flex justify-between text-sm">
                    <span>Discount ({{ $this->discount_percent }}%)</span>
                    <span>- Rp {{ number_format($this->discountValue) }}</span>
                </div>

                <div class="flex justify-between text-lg font-bold border-t pt-2">
                    <span>Total</span>
                    <span>Rp{{ number_format($this->total) }}</span>
                </div>

                <!-- Customer Name Display (Optional) -->
                @if ($this->customer_name)
                    <div class="text-xs text-gray-500 mt-1">
                        Customer: <strong>{{ $this->customer_name }}</strong>
                    </div>
                @endif

                <x-filament::button color="success" class="w-full mt-2" wire:click="printBill">
                    Print Bill
                </x-filament::button>
            </div>
        </div>

    </div>
</x-filament::page>
