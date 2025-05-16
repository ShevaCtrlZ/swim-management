@extends('layouts.tamu')

@section('title', 'Detail')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white border border-gray-200 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h3 class="text-2xl font-bold text-gray-800">{{ $kompetisi->nama_kompetisi }}</h3>
                <p class="mt-4 text-gray-600"><strong>Tanggal Mulai:</strong> {{ $kompetisi->tgl_mulai }}</p>
                <p class="text-gray-600"><strong>Tanggal Selesai:</strong> {{ $kompetisi->tgl_selesai }}</p>
                <p class="text-gray-600"><strong>Lokasi:</strong> {{ $kompetisi->lokasi }}</p>
            </div>
        </div>

        <!-- Tabel Hasil Lomba -->
        <div class="mt-6">
            @foreach ($lomba as $item)
                <div class="mb-6">
                    <h4 class="text-lg font-bold text-gray-800">
                        {{ $item->nomor_lomba }}. {{ $item->jarak }} M {{ strtoupper($item->jenis_gaya) }}
                    </h4>

                    @if ($item->nomorLomba->isNotEmpty())
                        @foreach ($item->nomorLomba as $index => $kelompok)
                            <h5 class="mt-4 text-md font-semibold text-gray-700">
                                Nomor Lomba {{ $item->nomor_lomba }} - Seri {{ $index + 1 }}
                            </h5>
                            <table class="w-full border-collapse border border-gray-300 mt-2">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="border border-gray-300 px-4 py-2 text-left text-sm font-medium text-gray-800">No Lint</th>
                                        <th class="border border-gray-300 px-4 py-2 text-left text-sm font-medium text-gray-800">Nama Peserta</th>
                                        <th class="border border-gray-300 px-4 py-2 text-left text-sm font-medium text-gray-800">Lahir</th>
                                        <th class="border border-gray-300 px-4 py-2 text-left text-sm font-medium text-gray-800">Asal Klub</th>
                                        <th class="border border-gray-300 px-4 py-2 text-left text-sm font-medium text-gray-800">Limit Waktu</th>
                                        <th class="border border-gray-300 px-4 py-2 text-left text-sm font-medium text-gray-800">Hasil</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white">
                                    @foreach ($kelompok as $key => $peserta)
                                        <tr class="hover:bg-gray-50">
                                            <!-- No Lintasan dimulai dari 1 lagi untuk setiap seri -->
                                            <td class="border border-gray-300 px-4 py-2 text-sm text-gray-800">{{ $loop->iteration }}</td>
                                            <td class="border border-gray-300 px-4 py-2 text-sm text-gray-800">{{ $peserta->nama_peserta }}</td>
                                            <td class="border border-gray-300 px-4 py-2 text-sm text-gray-800">{{ $peserta->tgl_lahir }}</td>
                                            <td class="border border-gray-300 px-4 py-2 text-sm text-gray-800">{{ $peserta->asal_klub }}</td>
                                            <td class="border border-gray-300 px-4 py-2 text-sm text-gray-800">{{ $peserta->limit }}</td>
                                            <td class="border border-gray-300 px-4 py-2 text-sm text-gray-800">{{ $peserta->catatan_waktu ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endforeach
                    @else
                        <p class="text-gray-600 mt-4">Tidak ada peserta untuk lomba ini.</p>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection