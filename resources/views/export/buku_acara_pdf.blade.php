{{-- filepath: /c:/laragon/www/Tugas_Akhir/resources/views/export/buku_acara_pdf.blade.php --}}
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Buku Acara Kompetisi</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        /* Logo center + ukuran aman agar tidak terpotong */
        .header {
            text-align: center;              /* pastikan seluruh header ter-center */
            margin-top: 8px;
            margin-bottom: 8px;
            page-break-inside: avoid;
        }

        .logos {
            display: inline-block;           /* inline-block agar berada tepat di tengah */
            text-align: center;
            margin-bottom: 6px;
        }

        .logos img {
            height: 44px;                    /* kecilkan sesuai kebutuhan */
            width: auto;
            margin: 0 10px;                  /* jarak antar logo */
            object-fit: contain;
            vertical-align: middle;
        }

        .title,
        .subtitle,
        .small {
            text-align: center;
        }

        /* tetap jaga tabel dan teks tetap rapi */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }
        th, td { padding:6px 8px; font-size:11px; text-align: left; }
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
        <div style="margin-bottom: 24px;">
            <h4>
                {{ $item->nomor_lomba }}. {{ $item->jarak }} M GAYA {{ strtoupper($item->jenis_gaya) }} KU
                {{ $item->tahun_lahir_minimal }} / {{ $item->tahun_lahir_maksimal }} {{ $item->jk }}
            </h4>
            @php
                $grouped = $item->detailLomba->groupBy('seri')->sortKeys();
            @endphp
            @forelse ($grouped as $seri => $kelompok)
                <div class="seri-title">
                    Seri {{ $seri }}
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>No Lint</th>
                            <th>Nama Peserta</th>
                            <th>Lahir</th>
                            <th>Asal Klub</th>
                            <th>Limit Waktu</th>
                            <th>Hasil</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kelompok->sortBy('no_lintasan') as $detail)
                            <tr>
                                <td>{{ $detail->no_lintasan ?? $loop->iteration }}</td>
                                <td>{{ $detail->peserta->nama_peserta ?? '-' }}</td>
                                <td>{{ $detail->peserta->tgl_lahir ?? '-' }}</td>
                                <td>{{ $detail->peserta->asal_klub ?? '-' }}</td>
                                <td>{{ $detail->limit ?? '-' }}</td>
                                <td>{{ $detail->catatan_waktu ?? ' ' }}</td>
                                <td>
                                    {{ $detail->keterangan ?? ' ' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="no-peserta">Tidak ada peserta untuk seri ini.</td>
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
