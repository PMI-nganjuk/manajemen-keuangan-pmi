<div class="p-4 bg-white rounded shadow-sm mb-4">
    {{ $form }}

    <x-filament::button
        class="mt-4"
        color="primary"
        wire:click="createRecord"
    >
        Simpan
    </x-filament::button>
</div>
