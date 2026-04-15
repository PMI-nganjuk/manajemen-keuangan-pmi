<x-filament-panels::page>
    @php
        $rows = collect($laporan);
        $totalData = $rows->count();
        $totalSaldoAkhir = $rows->sum(fn($row) => (float) ($row->saldo_akhir ?? 0));
        $jumlahDraft = $rows->filter(fn($row) => strtolower((string) ($row->status ?? '')) === 'draft')->count();
    @endphp

    <div style="display: grid; gap: 16px;">
        <form wire:submit.prevent="downloadPdf">
            <x-filament::section>
                <x-slot name="heading">Laporan Keuangan Tahunan</x-slot>

                <p style="margin: 0 0 12px 0; color: #6b7280; font-size: 14px;">
                    Pilih tahun, lihat ringkasan data, lalu unduh laporan PDF.
                </p>

                <div style="max-width: 420px;">
                    {{ $this->form }}
                </div>

                <x-slot name="footer">
                    <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                        <x-filament::button type="submit" icon="heroicon-o-arrow-down-tray">
                            Download PDF
                        </x-filament::button>

                        <span style="font-size: 13px; color: #6b7280;">
                            Tahun aktif: {{ $tahun ?? '-' }}
                        </span>
                    </div>
                </x-slot>
            </x-filament::section>
        </form>

        <x-filament::section>
            <x-slot name="heading">Ringkasan</x-slot>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 12px;">
                <div style="border: 1px solid #e5e7eb; border-radius: 10px; padding: 12px;">
                    <div style="font-size: 12px; color: #6b7280; margin-bottom: 6px;">Total Data</div>
                    <div style="font-size: 24px; font-weight: 700; line-height: 1;">
                        {{ number_format($totalData, 0, ',', '.') }}
                    </div>
                </div>

                <div style="border: 1px solid #e5e7eb; border-radius: 10px; padding: 12px;">
                    <div style="font-size: 12px; color: #6b7280; margin-bottom: 6px;">Total Saldo Akhir</div>
                    <div style="font-size: 24px; font-weight: 700; line-height: 1;">
                        Rp {{ number_format($totalSaldoAkhir, 0, ',', '.') }}
                    </div>
                </div>

                <div style="border: 1px solid #e5e7eb; border-radius: 10px; padding: 12px;">
                    <div style="font-size: 12px; color: #6b7280; margin-bottom: 6px;">Status Draft</div>
                    <div style="font-size: 24px; font-weight: 700; line-height: 1;">
                        {{ number_format($jumlahDraft, 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">Daftar Laporan Tahun {{ $tahun ?? '-' }}</x-slot>

            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                    <thead>
                        <tr style="background: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                            <th style="text-align: left; padding: 10px; white-space: nowrap;">Periode</th>
                            <th style="text-align: left; padding: 10px; white-space: nowrap;">Tahun</th>
                            <th style="text-align: left; padding: 10px; white-space: nowrap;">Status</th>
                            <th style="text-align: right; padding: 10px; white-space: nowrap;">Kas Tahun 1</th>
                            <th style="text-align: right; padding: 10px; white-space: nowrap;">Kas Tahun 2</th>
                            <th style="text-align: right; padding: 10px; white-space: nowrap;">Saldo Akhir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($laporan as $row)
                            @php
                                $status = strtolower((string) ($row->status ?? '-'));
                                $statusColor = match ($status) {
                                    'final', 'selesai' => '#15803d',
                                    'draft' => '#b45309',
                                    default => '#6b7280',
                                };
                            @endphp
                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                <td style="padding: 10px;">{{ $row->periode ?? '-' }}</td>
                                <td style="padding: 10px;">{{ $row->tahun ?? '-' }}</td>
                                <td style="padding: 10px; color: {{ $statusColor }}; font-weight: 600;">
                                    {{ ucfirst((string) ($row->status ?? '-')) }}
                                </td>
                                <td style="padding: 10px; text-align: right;">
                                    {{ number_format((float) ($row->kas_tahun1 ?? 0), 0, ',', '.') }}
                                </td>
                                <td style="padding: 10px; text-align: right;">
                                    {{ number_format((float) ($row->kas_tahun2 ?? 0), 0, ',', '.') }}
                                </td>
                                <td style="padding: 10px; text-align: right; font-weight: 700;">
                                    {{ number_format((float) ($row->saldo_akhir ?? 0), 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="padding: 20px; text-align: center; color: #6b7280;">
                                    Tidak ada data laporan keuangan untuk tahun ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>
