<x-filament-panels::page>

    <form wire:submit.prevent="downloadPdf">
        {{ $this->form }}

        <div class="mt-4">
            <x-filament::button type="submit">
                Download PDF
            </x-filament::button>
        </div>
    </form>

    <hr class="my-6">

    <h2 class="text-xl font-bold mb-4">Data Laporan Tahun {{ $tahun }}</h2>

    <table class="w-full text-sm border">
        <thead class="bg-gray-100">
            <tr class="border">
                <th class="p-2 border">Tanggal</th>
                <th class="p-2 border">Keterangan</th>
                <th class="p-2 border">Rupiah</th>
            </tr>
        </thead>
        <tbody>
            @foreach($laporan as $row)
                <tr class="border">
                    <td class="p-2 border">{{ $row->tanggal }}</td>
                    <td class="p-2 border">{{ $row->keterangan }}</td>
                    <td class="p-2 border">{{ number_format($row->rupiah) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</x-filament-panels::page>