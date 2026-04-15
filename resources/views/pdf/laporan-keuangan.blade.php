<!doctype html>
<html>

<head>
    <meta charset="utf-8" />
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 6px;
        }

        th {
            background: #f2f2f2;
        }
    </style>
</head>

<body>

    <h2 style="text-align:center">
        LAPORAN KEUANGAN<br />TAHUN {{ $tahun }}
    </h2>

    <table>
        <thead>
            <tr>
                <th>Periode</th>
                <th>Status</th>
                <th>Saldo Akhir</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $row)
                <tr>
                    <td>{{ $row->periode ?? '-' }}</td>
                    <td>{{ $row->status ?? '-' }}</td>
                    <td>{{ number_format((float) ($row->saldo_akhir ?? 0), 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" style="text-align:center; color:#666;">
                        Tidak ada data laporan keuangan untuk tahun ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>

</html>
