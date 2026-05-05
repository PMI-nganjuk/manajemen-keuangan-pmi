<x-filament-panels::page>
    <form wire:submit.prevent="save">
        {{ $this->form }}

        @if(auth()->user()->hasRole(\App\Enums\RoleEnum::ADMIN))
            <div style="margin-top: 2rem; display: flex; align-items: center; justify-content: flex-start;">
                <x-filament::button type="submit" color="danger">
                    Simpan Perubahan
                </x-filament::button>                
            </div>
        @endif
    </form>
</x-filament-panels::page>