<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Hasil Kompetisi</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            text-align: center;
            /* center semua teks secara default */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }

        th,
        td {
            /* Hapus border pada td */
            padding: 6px 8px;
            text-align: center;
            /* teks di tabel di-tengah */
        }

        th {
            background: #f3f3f3;
            border: 1px solid #222;
        }

        h2,
        h4 {
            margin: 8px 0;
            text-align: center;
            /* pastikan heading di tengah */
        }

        .ttd-section {
            margin-top: 40px;
            text-align: right;
        }

        .ttd {
            display: inline-block;
            text-align: center;
            margin-right: 60px;
        }

        .ttd .name {
            margin-top: 80px;
            font-weight: bold;
            text-decoration: underline;
        }

        .ttd .role {
            margin-top: 4px;
        }

        /* centered logos using flex (prevent overlap) */
        .header-logos {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
            page-break-inside: avoid;
        }

        .header-logos img {
            max-height: 60px;
            /* batasi tinggi agar tidak terpotong */
            max-width: 30%;
            /* batasi lebar agar berjajar rapi */
            width: auto;
            height: auto;
            object-fit: contain;
            display: inline-block;
        }
    </style>
</head>

<body>
    <div class="header-logos">
        <img src="{{ public_path('gambar/logo jaguar.png') }}" alt="logo jaguar">
        <img src="{{ public_path('gambar/logo.png') }}" alt="logo utama">
        <img src="{{ public_path('gambar/Logo Conie.png') }}" alt="logo conie">
    </div>

    <div class="title">{{ strtoupper($kompetisi->nama_kompetisi ?? '-') }}</div>
    <div class="subtitle">{{ $kompetisi->lokasi ?? '' }} &nbsp; | &nbsp; {{ $kompetisi->tgl_mulai ?? '' }} @if($kompetisi->tgl_selesai) - {{ $kompetisi->tgl_selesai }} @endif</div>
    <div class="small">Dicetak: {{ now()->format('d M Y H:i') }}</div>

    @foreach ($lomba as $item)
        <h4>
            {{ $item->jarak }} M GAYA {{ strtoupper($item->jenis_gaya) }} KU
            {{ $item->tahun_lahir_minimal }} / {{ $item->tahun_lahir_maksimal }} {{ $item->jk }}
        </h4>
        <table>
            <thead>
                <tr>
                    <th>Juara</th>
                    <th>Nama Peserta</th>
                    <th>Lahir</th>
                    <th>Asal Klub</th>
                    <th>Limit Waktu</th>
                    <th>Hasil</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($item->detailLomba->sortBy(function ($d) {
        return $d->catatan_waktu ?? '99:99:99';
    }) as $peserta)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $peserta->peserta->nama_peserta ?? '-' }}</td>
                        <td>{{ $peserta->peserta->tgl_lahir ?? '-' }}</td>
                        <td>{{ $peserta->peserta->asal_klub ?? '-' }}</td>
                        <td>{{ $peserta->limit ?? '-' }}</td>
                        <td>
                            {{ $peserta->catatan_waktu ?? '-' }}
                        </td>
                        <td>
                            {{ $peserta->keterangan ?? '-' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach
</body>

</html>
