@extends('layouts.tamu')

@section('title', 'Beranda')

@section('content')
<div class="relative">
    <!-- Gambar -->
    <img src="{{ asset('gambar/banner.webp') }}" class="w-full h-64 object-cover" alt="Banner">
    <!-- Overlay -->
    <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <h1 class="text-white text-4xl font-bold">Hasil Kejuaraan</h1>
    </div>
</div>

<div class="bg-white p-4 md:p-6 rounded-lg shadow-md">
    <h2 class="text-xl md:text-2xl font-bold text-gray-800">{{ $kompetisi->nama_kompetisi }}</h2>
    <div class="mt-4 space-y-1 text-sm md:text-base">
        <p><strong>Tanggal Mulai:</strong> {{ $kompetisi->tgl_mulai }}</p>
        <p><strong>Tanggal Selesai:</strong> {{ $kompetisi->tgl_selesai }}</p>
        <p><strong>Lokasi:</strong> {{ $kompetisi->lokasi }}</p>
    </div>

    @foreach ($lomba as $item)
        <div class="mt-6">

            @foreach ($item->nomorLomba as $index => $kelompok)
            <h4 class="text-base md:text-lg font-bold text-gray-800">
                {{ $item->nomor_lomba }}. {{ $item->jarak }} M GAYA {{ strtoupper($item->jenis_gaya) }} KU
                {{ $item->tahun_lahir_minimal }} / {{ $item->tahun_lahir_maksimal }} {{ $item->jk }}
            </h4>
                            <div>
                                <!-- Scrollable Table Wrapper -->
                                <div class="overflow-x-auto rounded-md shadow-sm mt-2">
                                    <table class="min-w-full border-collapse border border-gray-300 text-sm">
                                        <thead class="bg-gray-100 text-left">
                                            <tr>
                                                <th class="border border-gray-300 px-4 py-2">Juara</th>
                                                <th class="border border-gray-300 px-4 py-2">Nama Peserta</th>
                                                <th class="border border-gray-300 px-4 py-2">Lahir</th>
                                                <th class="border border-gray-300 px-4 py-2">Asal Klub</th>
                                                <th class="border border-gray-300 px-4 py-2">Limit Waktu</th>
                                                <th class="border border-gray-300 px-4 py-2">Hasil</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($kelompok as $peserta)
                                                <tr class="hover:bg-gray-50">
                                                    <td class="border border-gray-300 px-4 py-2">{{ $loop->iteration }}
                                                    </td>
                                                    <td class="border border-gray-300 px-4 py-2">
                                                        {{ $peserta->nama_peserta }}</td>
                                                    <td class="border border-gray-300 px-4 py-2">{{ $peserta->tgl_lahir }}
                                                    </td>
                                                    <td class="border border-gray-300 px-4 py-2">{{ $peserta->asal_klub }}
                                                    </td>
                                                    <td class="border border-gray-300 px-4 py-2">{{ $peserta->limit ?? '-' }}</td>
                                                    <td class="border border-gray-300 px-4 py-2">{{ $peserta->catatan_waktu ?? '-' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach
                </div>
            @endforeach
</div>
@endsection