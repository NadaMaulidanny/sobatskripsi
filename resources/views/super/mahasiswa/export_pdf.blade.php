<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Data Mahasiswa</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; line-height: 1.5; }
        .title { font-size: 18px; font-weight: bold; margin: 0; color: #111827; text-align: center; }
        .subtitle { font-size: 11px; color: #6b7280; text-align: center; margin: 5px 0 25px 0; border-bottom: 2px solid #333; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th { background-color: #0f766e; color: white; padding: 10px; font-weight: bold; text-align: left; font-size: 11px; text-transform: uppercase; }
        td { padding: 10px; border-bottom: 1px solid #e5e7eb; }
        tr:nth-child(even) { background-color: #f9fafb; }
        .center { text-align: center; }
        .muted { color: #9ca3af; }
    </style>
</head>
<body>

    <div>
        <div class="title">DATA INDUK MAHASISWA</div>
        <div class="subtitle">
            @if($startDate && $endDate)
                Periode: {{ date('d-m-Y', strtotime($startDate)) }} s/d {{ date('d-m-Y', strtotime($endDate)) }}
            @else
                Riwayat Seluruh Data Master Mahasiswa
            @endif
            | Diunduh pada: {{ date('d-m-Y H:i') }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="8%" class="center">No</th>
                <th width="22%">NIM</th>
                <th width="35%">Nama Mahasiswa</th>
                <th width="35%">Program Studi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($mahasiswas as $index => $m)
                <tr>
                    <td class="center muted">{{ $index + 1 }}</td>
                    <td>{{ $m->nim }}</td>
                    <td style="font-weight: bold;">{{ $m->user->name ?? '-' }}</td>
                    <td>{{ $m->prodi->nama_prodi ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>