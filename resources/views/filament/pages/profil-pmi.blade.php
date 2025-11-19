<x-filament-panels::page>
    {{ $this->form }}
    @if(auth()->user()->hasRole('admin'))
        <x-filament::button wire:click="update" class="mt-4">
            Simpan Perubahan
        </x-filament::button>
    @endif
</x-filament-panels::page>
