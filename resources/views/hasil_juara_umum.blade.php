{{-- filepath: c:\laragon\www\Tugas_Akhir\resources\views\hasil_juara_umum.blade.php --}}
@extends('layouts.app')

@section('title', 'Juara Umum - ' . $kompetisi->nama_kompetisi)
@section('content')
    <div class="bg-white p-4 md:p-6 rounded-lg shadow-md">
        <h2 class="text-xl md:text-2xl font-bold text-gray-800">Juara Umum {{ $kompetisi->nama_kompetisi }}</h2>
        <div class="mt-4 space-y-1 text-sm md:text-base">
            <p><strong>Tanggal Mulai:</strong> {{ $kompetisi->tgl_mulai }}</p>
            <p><strong>Tanggal Selesai:</strong> {{ $kompetisi->tgl_selesai }}</p>
            <p><strong>Lokasi:</strong> {{ $kompetisi->lokasi }}</p>
        </div>

        @foreach ($rekap as $tahun => $pesertas)
            <h3 class="mt-6 text-lg font-bold text-blue-700">Kategori Tahun Lahir: {{ $tahun }}</h3>
            <table class="min-w-full border-collapse border border-gray-300 text-sm mt-2 mb-4">
                <thead class="bg-gray-100 text-left">
                    <tr>
                        <th class="border border-gray-300 px-4 py-2">Nama Peserta</th>
                        <th class="border border-gray-300 px-4 py-2">Asal Klub</th>
                        <th class="border border-gray-300 px-4 py-2">Juara 1</th>
                        <th class="border border-gray-300 px-4 py-2">Juara 2</th>
                        <th class="border border-gray-300 px-4 py-2">Juara 3</th>
                        <th class="border border-gray-300 px-4 py-2">Total Medali</th>
                        <th class="border border-gray-300 px-4 py-2">Total Lomba Diikuti</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pesertas as $peserta)
                        <tr>
                            <td class="border border-gray-300 px-4 py-2">{{ $peserta['nama'] }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $peserta['asal_klub'] }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $peserta['total_juara1'] }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $peserta['total_juara2'] }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $peserta['total_juara3'] }}</td>
                            <td class="border border-gray-300 px-4 py-2">
                                {{ $peserta['total_juara1'] + $peserta['total_juara2'] + $peserta['total_juara3'] }}
                            </td>
                            <td class="border border-gray-300 px-4 py-2">
                                {{ $peserta['total_lomba'] }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endforeach
    </div>
@endsection
