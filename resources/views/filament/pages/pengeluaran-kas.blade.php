<x-filament-panels::page>

    {{-- FORM DI ATAS --}}
    <div class="p-4 bg-white rounded shadow-sm mb-4">
        {{ $this->form }}

        <x-filament::button
            wire:click="createRecord"
            color="primary"
            class="mt-3 mt-4"
        >
            Simpan
        </x-filament::button>
    </div>

    {{-- TABLE DI BAWAH --}}
    {{ $this->table }}

</x-filament-panels::page>
