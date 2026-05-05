<x-filament-panels::page>

    {{-- FORM DI ATAS --}}
    <div class="mb-4 rounded bg-white p-4 shadow-sm">
        @if ($this->editingId)
            <div class="mb-3 border-l-4 border-yellow-400 bg-yellow-50 p-3 text-sm text-yellow-700">
                <strong>Mode Edit</strong> - Mengubah data Penyesuaian ID: {{ $this->editingId }}
            </div>
        @endif

        {{ $this->form }}

        <div class="mt-4 flex gap-2">
            <x-filament::button wire:click="createRecord" color="primary">
                @if ($this->editingId)
                    Perbarui Data
                @else
                    Simpan Data Baru
                @endif
            </x-filament::button>

            @if ($this->editingId)
                <x-filament::button wire:click="resetForm" color="gray">
                    Batal Edit
                </x-filament::button>
            @endif
        </div>
    </div>

    {{-- TABLE DI BAWAH --}}
    {{ $this->table }}

</x-filament-panels::page>
