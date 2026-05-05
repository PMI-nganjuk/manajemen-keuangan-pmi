<x-filament-panels::page>
    <form wire:submit="create">
        
        <x-filament::section>
            <x-slot name="heading">
                Input Akun Pengguna
            </x-slot>

            {{ $this->form }}

            <x-slot name="footer">
                <div class="flex items-center justify-end gap-x-3">

                    <x-filament::button type="submit">
                        Simpan
                    </x-filament::button>

                </div>
            </x-slot>
            
        </x-filament::section>
        
    </form>

    <div class="mt-8">
        {{ $this->table }}
    </div>
</x-filament-panels::page>