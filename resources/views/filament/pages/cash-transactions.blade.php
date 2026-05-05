<x-filament-panels::page>
    @if(!auth()->user()->hasRole(\App\Enums\RoleEnum::STAFF))
        <form wire:submit="save" x-data="{}" @scroll-to-top.window="window.scrollTo({ top: 0, behavior: 'smooth' })">
            
            <x-filament::section>
                <x-slot name="heading">
                    {{ $editId ? 'Edit Transaksi Kas' : 'Input Transaksi Kas' }}
                </x-slot>

                {{ $this->form }}

                <x-slot name="footer">
                    <div class="flex items-center justify-end gap-x-3">
                        @if($editId)
                            <x-filament::button type="button" color="gray" wire:click="cancelEdit">
                                Batal
                            </x-filament::button>
                            <x-filament::button type="submit" color="warning">
                                Perbarui
                            </x-filament::button>
                        @else
                            <x-filament::button type="submit">
                                Simpan
                            </x-filament::button>
                        @endif
                    </div>
                </x-slot>
                
            </x-filament::section>
            
        </form>
    @endif

    <div class="mt-8">
        {{ $this->table }}
    </div>
</x-filament-panels::page>
