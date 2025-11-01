{{-- filepath: /c:/laragon/www/Tugas_Akhir/resources/views/export/buku_acara_pdf.blade.php --}}
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Buku Acara Kompetisi</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10.5px;
            line-height: 1.05;
            color: #111;
            margin: 0;
            padding: 0 8px;
        }

        /* Header / logo */
        .header {
            text-align: center;
            margin-top: 6px;
            margin-bottom: 6px;
            page-break-inside: avoid;
        }

        .logos {
            display: inline-block;
            text-align: center;
            margin-bottom: 4px;
        }

        .logos img {
            height: 30px;
            width: auto;
            margin: 0 6px;
            object-fit: contain;
            vertical-align: middle;
        }

        .title {
            font-weight: 700;
            font-size: 12px;
            margin-top: 4px;
            margin-bottom: 4px;
        }

        .subtitle,
        .small {
            font-size: 9px;
            color: #444;
            margin-bottom: 4px;
        }

        /* Compact table */
        table {
            width: 100%;
            border-collapse: collapse;
            page-break-inside: avoid;
            margin-bottom: 8px;
            table-layout: fixed;
            word-wrap: break-word;
        }

        thead th {
            background: #f5f5f5;
            border-bottom: 1px solid #bbb;
            padding: 4px 6px;
            font-weight: 700;
            font-size: 9px;
            text-align: left;
            vertical-align: middle;
        }

        tbody td {
            padding: 3px 6px;
            font-size: 9px;
            border-bottom: 1px solid #eee;
            vertical-align: middle;
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        .seri-title {
            font-size: 10px;
            font-weight: 600;
            margin: 4px 0;
        }

        .no-peserta {
            padding: 6px 0;
            font-size: 9px;
            color: #666;
            text-align: center;
        }

        /* column widths */
        th.col-no { width: 6%; }
        th.col-lint { width: 8%; }
        th.col-nama { width: 34%; }
        th.col-lahir { width: 12%; }
        th.col-klub { width: 18%; }
        th.col-limit { width: 10%; }
        th.col-hasil { width: 12%; }
        th.col-ket { width: 8%; }

        /* small adjustments for printing */
        @media print {
            body { padding: 0 6px; font-size: 10px; }
            .logos img { height: 28px; }
        }
    </style>
</head>

<body>
    <div class="header" role="banner" aria-label="Header kompetisi">
        <div class="logos" aria-hidden="false">
            <img src="{{ public_path('gambar/logo jaguar.png') }}" alt="logo jaguar">
            <img src="{{ public_path('gambar/logo.png') }}" alt="logo utama">
            <img src="{{ public_path('gambar/Logo Conie.png') }}" alt="logo conie">
        </div>
        <div class="title">{{ strtoupper($kompetisi->nama_kompetisi ?? '-') }}</div>
        <div class="subtitle">{{ $kompetisi->lokasi ?? '' }} &nbsp; | &nbsp; {{ $kompetisi->tgl_mulai ?? '' }} @if($kompetisi->tgl_selesai) - {{ $kompetisi->tgl_selesai }} @endif</div>
        <div class="small">Dicetak: {{ now()->format('d M Y H:i') }}</div>
    </div>

    @if (session('error'))
        <div class="error">
            <strong>Error!</strong> {{ session('error') }}
        </div>
    @endif

    @if (session('success'))
        <div class="success">
            <strong>Success!</strong> {{ session('success') }}
        </div>
    @endif

    @foreach ($lomba as $item)
        <div style="margin-bottom: 18px;">
            <h4 style="margin:6px 0 8px 0;">
                {{ $item->nomor_lomba }}. {{ $item->jarak }} M GAYA {{ strtoupper($item->jenis_gaya) }} KU
                {{ $item->tahun_lahir_minimal }} / {{ $item->tahun_lahir_maksimal }} {{ $item->jk }}
            </h4>
            @php
                $grouped = $item->detailLomba->groupBy('seri')->sortKeys();
            @endphp
            @forelse ($grouped as $seri => $kelompok)
                <div class="seri-title">Seri {{ $seri }}</div>
                <table>
                    <thead>
                        <tr>
                            <th class="col-lint">No Lint</th>
                            <th class="col-nama">Nama Peserta</th>
                            <th class="col-lahir">Lahir</th>
                            <th class="col-klub">Asal Klub</th>
                            <th class="col-limit">Limit Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kelompok->sortBy('no_lintasan') as $index => $detail)
                            <tr>
                                <td>{{ $detail->no_lintasan ?? '-' }}</td>
                                <td>{{ $detail->peserta->nama_peserta ?? '-' }}</td>
                                <td>{{ $detail->peserta->tgl_lahir ?? '-' }}</td>
                                <td>{{ $detail->peserta->asal_klub ?? '-' }}</td>
                                <td>{{ $detail->limit ?? $detail->limit_waktu ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="no-peserta">Tidak ada peserta untuk seri ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            @empty
                <p class="no-peserta">Tidak ada peserta untuk lomba ini.</p>
            @endforelse
        </div>
    @endforeach
</body>

</html>
