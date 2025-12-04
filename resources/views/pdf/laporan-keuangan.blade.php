<!doctype html>
<html>
<head>
    <meta charset="utf-8"/>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #333; padding: 6px; }
        th { background: #f2f2f2; }
    </style>
</head>
<body>

<h2 style="text-align:center">
    LAPORAN KEUANGAN<br/>TAHUN {{ $tahun }}
</h2>

<table>
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Keterangan</th>
            <th>Rupiah</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $row)
        <tr>
            <td>{{ $row->tanggal }}</td>
            <td>{{ $row->keterangan }}</td>
            <td>{{ number_format($row->rupiah) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>