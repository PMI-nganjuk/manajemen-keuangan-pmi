<x-filament-panels::page>
    {{ $this->form }}

    <x-filament::button wire:click="update" class="mt-4">
        Simpan Perubahan
    </x-filament::button>
</x-filament-panels::page>