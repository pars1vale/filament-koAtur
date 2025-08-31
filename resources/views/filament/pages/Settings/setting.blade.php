<x-filament-panels::page>
    <form wire:submit.prevent="save" class="space-y-6">
        <x-filament::card>
            <x-slot name="header">
                General
            </x-slot>

            <div class="grid grid-cols-3 gap-6">
                {{ $this->form }}
            </div>
        </x-filament::card>

        <div class="flex items-center gap-4">
            <x-filament::button type="submit" color="primary">
                Update
            </x-filament::button>
            <x-filament::button type="button" color="secondary" onclick="location.reload()">
                Cancel
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
