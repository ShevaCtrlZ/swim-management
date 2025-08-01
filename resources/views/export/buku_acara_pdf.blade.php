{{-- filepath: /c:/laragon/www/Tugas_Akhir/resources/views/export/buku_acara_pdf.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Buku Acara Kompetisi</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        th, td { border: 1px solid #222; padding: 6px 8px; }
        th { background: #f3f3f3; }
        h2, h4, h5 { margin: 8px 0; }
        .seri-title { margin-top: 10px; margin-bottom: 4px; font-weight: bold; }
        .no-peserta { color: #888; font-style: italic; }
        .success { background: #d4edda; color: #155724; padding: 8px; border-radius: 4px; margin-bottom: 8px; }
        .error { background: #f8d7da; color: #721c24; padding: 8px; border-radius: 4px; margin-bottom: 8px; }

        .ttd-section {
            margin-top: 60px;
            text-align: right;
        }

        .ttd {
            display: inline-block;
            text-align: center;
            margin-right: 60px;
        }

        .ttd .name {
            margin-top: 80px; /* untuk ruang tanda tangan */
            font-weight: bold;
            text-decoration: underline;
        }

        .ttd .role {
            margin-top: 4px;
        }
    </style>
</head>
<body>
    <h2>{{ $kompetisi->nama_kompetisi }}</h2>
    <p><strong>Tanggal Mulai:</strong> {{ $kompetisi->tgl_mulai }}</p>
    <p><strong>Tanggal Selesai:</strong> {{ $kompetisi->tgl_selesai }}</p>
    <p><strong>Lokasi:</strong> {{ $kompetisi->lokasi }}</p>

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
                    Nomor Lomba {{ $item->nomor_lomba }} - Seri {{ $seri }}
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
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kelompok as $detail)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $detail->peserta->nama_peserta ?? '-' }}</td>
                                <td>{{ $detail->peserta->tgl_lahir ?? '-' }}</td>
                                <td>{{ $detail->peserta->asal_klub ?? '-' }}</td>
                                <td>{{ $detail->peserta->limit ?? '-' }}</td>
                                <td>{{ $detail->catatan_waktu ?? '-' }}</td>
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
