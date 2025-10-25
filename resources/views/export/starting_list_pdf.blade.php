<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Daftar Peserta - {{ $kompetisi->nama_kompetisi ?? '' }}</title>
    <style>
        @page { margin:30px 20px; }
        body { font-family: DejaVu Sans, sans-serif; font-size:10.5px; color:#111; }
        .header { text-align:center; margin-bottom:6px; }
        .logos img { height:50px; margin:0 6px; vertical-align:middle; }
        .title { font-weight:700; font-size:13px; margin-top:6px; }
        .subtitle { font-size:10px; margin-top:2px; color:#333; }
        .meta { text-align:center; font-size:10px; margin:6px 0 10px; }
        table { width:100%; border-collapse:collapse; page-break-inside:avoid; }
        thead th { background:#efefef; border:1px solid #222; padding:6px 8px; font-size:9.5px; vertical-align:middle; }
        tbody td { border:1px solid #222; padding:5px 8px; font-size:9.5px; vertical-align:middle; }
        .small { font-size:9px; color:#555; }
        tr { page-break-inside:avoid; page-break-after:auto; }
        .right { text-align:right; }
        .center { text-align:center; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logos">
            <img src="{{ public_path('gambar/logo jaguar.png') }}" alt="logo1">
            <img src="{{ public_path('gambar/logo.png') }}" alt="logo2">
            <img src="{{ public_path('gambar/Logo Conie.png') }}" alt="logo3">
        </div>

        <div class="title">{{ strtoupper($kompetisi->nama_kompetisi ?? '-') }}</div>
        <div class="subtitle">{{ $kompetisi->lokasi ?? '' }} &nbsp; | &nbsp; {{ $kompetisi->tgl_mulai ?? '' }} @if($kompetisi->tgl_selesai) - {{ $kompetisi->tgl_selesai }} @endif</div>
        <div class="small">Dicetak: {{ now()->format('d M Y H:i') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:4%;">No.</th>
                <th style="width:22%;">Nama Atlet</th>
                <th style="width:8%;">Lahir</th>
                <th style="width:18%;">Nomor / Lomba</th>
                <th style="width:12%;">Tahun Lahir (Min / Maks)</th>
                <th style="width:12%;">Nama Tim</th>
                <th style="width:8%;">Limit</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pesertas as $i => $p)
                <tr>
                    <td class="center">{{ $i + 1 }}</td>
                    <td>{{ $p->nama_peserta ?? '-' }}</td>
                    <td class="center">{{ $p->tgl_lahir ?? '-'}}</td>

                    {{-- ubah tampilan Nomor / Lomba menjadi: "100 M GAYA PUNGGUNG PUTRA" --}}
                    <td>
                        {{ $p->jarak ?? '-' }} M
                        @if(!empty($p->jenis_gaya))
                            GAYA {{ strtoupper($p->jenis_gaya) }}
                        @endif
                        @php
                            $jk = $p->jenis_kelamin ?? $p->jk ?? null;
                            if($jk) {
                                $jkUp = strtoupper(trim($jk));
                                if(in_array($jkUp, ['L','LAKI','LAKI-LAKI','LAKI LAKI'])) $jkLabel = 'PUTRA';
                                elseif(in_array($jkUp, ['P','PEREMPUAN'])) $jkLabel = 'PUTRI';
                                else $jkLabel = $jkUp;
                            } else {
                                $jkLabel = null;
                            }
                        @endphp
                        @if(!empty($jkLabel)) {{ $jkLabel }} @endif
                    </td>

                    <td class="center">
                        {{ $p->tahun_lahir_minimal ?? $p->tahun_min ?? '-' }}
                        /
                        {{ $p->tahun_lahir_maksimal ?? $p->tahun_max ?? '-' }}
                    </td>

                    <td class="center">{{ $p->asal_klub ?? '-' }}</td>
                    <td class="center">{{ $p->limit_waktu ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="center">Tidak ada peserta</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>