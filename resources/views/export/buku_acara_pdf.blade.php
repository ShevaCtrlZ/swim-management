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
    </style>
</head>
<body>
    <h2>{{ $kompetisi->nama_kompetisi }}</h2>
    <p><strong>Tanggal Mulai:</strong> {{ $kompetisi->tgl_mulai }}</p>
    <p><strong>Tanggal Selesai:</strong> {{ $kompetisi->tgl_selesai }}</p>
    <p><strong>Lokasi:</strong> {{ $kompetisi->lokasi }}</p>

    @foreach ($lomba as $item)
        <div style="margin-bottom: 24px;">
            <h4>
                {{ $item->nomor_lomba }}. {{ $item->jarak }} M GAYA {{ strtoupper($item->jenis_gaya) }} KU
                {{ $item->tahun_lahir_minimal }} / {{ $item->tahun_lahir_maksimal }} {{ $item->jk }}
            </h4>
            @if ($item->nomorLomba && $item->nomorLomba->isNotEmpty())
                @foreach ($item->nomorLomba as $seri => $kelompok)
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
                            @if(is_iterable($kelompok) && count($kelompok) > 0)
                                @foreach ($kelompok as $peserta)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $peserta->nama_peserta }}</td>
                                        <td>{{ $peserta->tgl_lahir }}</td>
                                        <td>{{ $peserta->asal_klub }}</td>
                                        <td>{{ $peserta->limit }}</td>
                                        <td>{{ $peserta->catatan_waktu ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" class="no-peserta">Tidak ada peserta untuk seri ini.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                @endforeach
            @else
                <p class="no-peserta">Tidak ada peserta untuk lomba ini.</p>
            @endif
        </div>
    @endforeach
</body>
</html>