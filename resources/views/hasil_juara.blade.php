@extends('layouts.app')

@section('title', 'Hasil Kompetisi')
@section('header')
    
@section('content')
<div class="bg-gradient-to-r from-blue-500 to-teal-600 text-white p-6 rounded-lg shadow-md">
    <h1 class="text-2xl font-bold">Hasil Kompetisi</h1>
    <p class="mt-2">Berikut adalah hasil dari kompetisi yang telah dilaksanakan.</p>
</div>
    <div class="bg-white p-4 md:p-6 rounded-lg shadow-md">
        <h2 class="text-xl md:text-2xl font-bold text-gray-800">{{ $kompetisi->nama_kompetisi }}</h2>
        <div class="mt-4 space-y-1 text-sm md:text-base">
            <p><strong>Tanggal Mulai:</strong> {{ $kompetisi->tgl_mulai }}</p>
            <p><strong>Tanggal Selesai:</strong> {{ $kompetisi->tgl_selesai }}</p>
            <p><strong>Lokasi:</strong> {{ $kompetisi->lokasi }}</p>
        </div>
        <a href="{{ route('export.hasil_pdf', $kompetisi->id) }}" target="_blank"
            class="inline-block px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
             Export PDF
         </a>

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mt-4" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mt-4" role="alert">
                <strong class="font-bold">Success!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

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
                                                    <td class="border border-gray-300 px-4 py-2">{{ $peserta->limit }}</td>
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
    </div>
</div>
@endsection
