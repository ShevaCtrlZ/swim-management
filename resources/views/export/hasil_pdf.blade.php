<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Hasil Kompetisi</title>
    <style>
        /* Umum */
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #111;
            margin: 0;
            padding: 8px;
        }

        /* Header logo dan judul */
        .header-logos {
            text-align: center;
            margin-bottom: 6px;
            page-break-inside: avoid;
        }

        .header-logos img {
            height: 36px;
            /* kecilkan logo */
            width: auto;
            margin: 0 8px;
            object-fit: contain;
            vertical-align: middle;
            display: inline-block;
        }

        .title {
            font-weight: 700;
            font-size: 12px;
            text-align: center;
            margin: 2px 0 4px 0;
        }

        .subtitle,
        .small {
            text-align: center;
            font-size: 9px;
            color: #444;
            margin: 0;
        }

        /* Tabel compact dan rapi */
        .result-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin: 8px 0 14px 0;
            page-break-inside: avoid;
            word-wrap: break-word;
        }

        .result-table thead th {
            background: #f5f5f5;
            color: #111;
            font-weight: 700;
            font-size: 9px;
            padding: 4px 6px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .result-table tbody td {
            padding: 3px 6px;
            font-size: 9px;
            border-bottom: 1px solid #eee;
            vertical-align: middle;
            text-align: left;
        }

        /* Lebar kolom (sesuaikan) */
        .col-juara {
            width: 6%;
        }

        .col-nama {
            width: 36%;
        }

        .col-lahir {
            width: 12%;
        }

        .col-klub {
            width: 18%;
        }

        .col-limit {
            width: 10%;
        }

        .col-hasil {
            width: 12%;
        }

        .col-ket {
            width: 6%;
        }

        /* Alignment spesifik */
        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        /* Mencegah baris terpotong */
        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }

        /* Ukuran lebih rapat untuk mobile/print */
        @media print {
            body {
                padding: 4px;
                font-size: 9.5px;
            }

            .header-logos img {
                height: 32px;
                margin: 0 6px;
            }

            .result-table thead th,
            .result-table tbody td {
                padding: 3px 5px;
                font-size: 9px;
            }
        }

        /* Ensure table headers (including series row) repeat and avoid orphaned series title */
        .result-table {
            page-break-inside: auto;
        }

        .result-table thead {
            display: table-header-group;
        }

        /* series-title row inside thead so it stays with table and repeats on new page */
        .result-table thead tr.series-title th {
            background: transparent;
            font-weight: 700;
            text-align: left;
            padding: 6px 6px;
            border: none;
        }

        /* avoid splitting individual rows across pages */
        .result-table tbody tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }

        /* Printing / page-break rules to keep header with first rows */
        .result-table {
            page-break-inside: auto;
        }

        .result-table thead {
            display: table-header-group;
        }

        .result-table tfoot {
            display: table-footer-group;
        }

        /* ensure the series-title header row won't be orphaned or split */
        .result-table thead tr.series-title {
            page-break-inside: avoid;
            page-break-after: avoid;
        }

        /* body rows should not be split across pages */
        .result-table tbody {
            display: table-row-group;
        }

        .result-table tbody tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }

        /* special: keep first body row together with header when possible */
        tr.keep-with-header {
            page-break-inside: avoid;
            page-break-after: avoid;
        }

        /* tighten spacing a bit more for compactness */
        .result-table thead th {
            padding: 3px 6px;
        }

        .result-table tbody td {
            padding: 2px 6px;
        }

        /* keep an entire lomba (judul + semua seri/table) together when possible:
           if the remaining space is insufficient, the whole .lomba-block will start on the next page */
        .lomba-block {
            /* legacy + modern fallbacks */
            page-break-inside: avoid;       /* dompdf / many engines */
            -webkit-column-break-inside: avoid;
            -moz-page-break-inside: avoid;
            break-inside: avoid;            /* modern */
            display: block;
            margin-bottom: 10px;
        }

        /* ensure table header repeats on new page and rows are not orphaned */
        .result-table {
            page-break-inside: auto;
        }

        .result-table thead {
            display: table-header-group;
        }

        .result-table tfoot {
            display: table-footer-group;
        }

        .result-table tbody tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }

        tr.keep-with-header {
            page-break-inside: avoid;
            page-break-after: avoid;
        }
    </style>
</head>

<body>
    <div class="header-logos" role="banner" aria-label="Header kompetisi">
        <img src="{{ public_path('gambar/logo jaguar.png') }}" alt="logo jaguar">
        <img src="{{ public_path('gambar/logo.png') }}" alt="logo utama">
        <img src="{{ public_path('gambar/Logo Conie.png') }}" alt="logo conie">
    </div>

    <div class="title">{{ strtoupper($kompetisi->nama_kompetisi ?? '-') }}</div>
    <div class="subtitle">{{ $kompetisi->lokasi ?? '' }} &nbsp; | &nbsp;
        {{ $kompetisi->tgl_mulai ?? '' }} @if($kompetisi->tgl_selesai) - {{ $kompetisi->tgl_selesai }} @endif
    </div>
    <div class="small">Dicetak: {{ now()->format('d M Y H:i') }}</div>

    @foreach ($lomba as $item)
        <div class="lomba-block" role="group" aria-label="Lomba {{ $item->nomor_lomba }}">
            <h4 style="margin:10px 0 6px 0; font-size:11px; text-align:left;">
                {{ $item->nomor_lomba }}. {{ $item->jarak }} M GAYA {{ strtoupper($item->jenis_gaya ?? '') }}
                {{ $item->tahun_lahir_minimal ?? '-' }} / {{ $item->tahun_lahir_maksimal ?? '-' }}
                {{ $item->jk ?? '' }}
            </h4>

            @php
                $grouped = ($item->detailLomba ?? collect())->groupBy('seri')->sortKeys();
            @endphp

            @forelse ($grouped as $seri => $kelompok)
                {{-- put series title inside table thead so it won't be orphaned at page bottom --}}
                @php
                    $rows = $kelompok->sortBy(function($d){
                        return $d->catatan_waktu ?? '99:99:99';
                    })->values();
                @endphp

                <table class="result-table" role="table" aria-label="Hasil seri {{ $seri }}">
                    <thead>
                        <tr class="series-title">
                            <th colspan="7">Seri {{ $seri }}</th>
                        </tr>
                        <tr>
                            <th class="col-juara">No</th>
                            <th class="col-nama">Nama Peserta</th>
                            <th class="col-lahir">Lahir</th>
                            <th class="col-klub">Asal Klub</th>
                            <th class="col-limit center">Limit</th>
                            <th class="col-hasil center">Hasil</th>
                            <th class="col-ket center">Ket</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rows as $index => $detail)
                            <tr class="{{ $index === 0 ? 'keep-with-header' : '' }}">
                                <td class="center">{{ $index + 1 }}</td>
                                <td>{{ $detail->peserta->nama_peserta ?? '-' }}</td>
                                <td class="center">{{ $detail->peserta->tgl_lahir ?? '-' }}</td>
                                <td>{{ $detail->peserta->asal_klub ?? '-' }}</td>
                                <td class="center">{{ $detail->limit ?? $detail->limit_waktu ?? '-' }}</td>
                                <td class="center">{{ $detail->catatan_waktu ?? '-' }}</td>
                                <td class="center">{{ $detail->keterangan ?? '' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="center" style="padding:6px 0;">Tidak ada peserta untuk seri ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            @empty
                <p style="font-size:9px; color:#666; margin:4px 0;">Tidak ada peserta untuk lomba ini.</p>
            @endforelse
        </div> {{-- .lomba-block --}}
     @endforeach
</body>

</html>
