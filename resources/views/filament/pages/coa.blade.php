<x-filament-panels::page>
    {{ $this->form }}
    @if(auth()->user()->hasRole('admin'))
        <x-filament::button 
            wire:click="save"
            class="mt-4"
            color="primary"
        >
            Simpan Perubahan
        </x-filament::button>
    @endif

</x-filament-panels::page>
