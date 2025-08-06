<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Hasil Kompetisi</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }

        th,
        td {
            /* border: 1px solid #222; */
            /* Hapus border pada td */
            padding: 6px 8px;
        }

        th {
            background: #f3f3f3;
            border: 1px solid #222;
            /* Tambahkan border hanya pada th jika ingin garis di header saja */
        }

        h2,
        h4 {
            margin: 8px 0;
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
            /* space for signature */
            font-weight: bold;
            text-decoration: underline;
        }

        .ttd .role {
            margin-top: 4px;
        }
    </style>
</head>

<body>
    <div style="position: relative; width:100%; min-height:70px; margin-bottom:8px;">
        <img src="{{ public_path('gambar/logo.png') }}" alt="GSC"
            style="height:70px; position:absolute; left:0; top:0;">
        <img src="{{ public_path('gambar/logo1.png') }}" alt="Akuatik Indonesia"
            style="height:70px; position:absolute; right:0; top:0;">
    </div>
    <div style="text-align:center; margin-bottom:16px;">
        <strong>BUKU HASIL<br>
            HAORNAS CUP SWIMMING 2025<br>
            SE PACITAN<br>
            PACITAN, 14 SEPTEMBER 2025</strong>
    </div>
    <h2>{{ $kompetisi->nama_kompetisi }}</h2>
    <p><strong>Tanggal Mulai:</strong> {{ $kompetisi->tgl_mulai }}</p>
    <p><strong>Tanggal Selesai:</strong> {{ $kompetisi->tgl_selesai }}</p>
    <p><strong>Lokasi:</strong> {{ $kompetisi->lokasi }}</p>

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
                        <td>{{ $peserta->peserta->limit ?? '-' }}</td>
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
