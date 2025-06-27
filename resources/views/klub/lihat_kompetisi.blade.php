@extends('layouts.app')

@section('title', 'Daftar Peserta Klub')

@section('content')
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-4">Peserta Klub pada Kompetisi: {{ $kompetisi->nama_kompetisi ?? '-' }}</h2>

        {{-- Daftar Nama Lomba yang Diikuti --}}
        @php
            $lombaDiikuti = collect($lomba)->filter(function ($l) {
                return isset($l->peserta) && count($l->peserta) > 0;
            });
        @endphp
        @if ($lombaDiikuti->count())
            <div class="mb-4">
                <span class="font-semibold">Lomba yang diikuti klub Anda:</span>
                <ul class="list-disc list-inside text-blue-700">
                    @foreach ($lombaDiikuti as $l)
                        <li>{{ $l->nama_lomba ?? '-' }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (isset($lomba) && count($lomba))
            @foreach ($lomba as $l)
                <div class="mb-6">
                    <h4 class="text-base md:text-lg font-bold text-gray-800">
                        {{ $l->nomor_lomba }}. {{ $l->jarak }} M GAYA {{ strtoupper($l->jenis_gaya) }} KU
                        {{ $l->tahun_lahir_minimal }} / {{ $l->tahun_lahir_maksimal }} {{ $l->jk }}
                    </h4>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200 mb-2">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 border">No</th>
                                    <th class="px-4 py-2 border">Nama Peserta</th>
                                    <th class="px-4 py-2 border">Tanggal Lahir</th>
                                    <th class="px-4 py-2 border">Jenis Kelamin</th>
                                    <th class="px-4 py-2 border">Edit</th>
                                    <th class="px-4 py-2 border">Hapus</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 1; @endphp
                                @foreach ($l->peserta ?? [] as $peserta)
                                    <tr>
                                        <td class="px-4 py-2 border">{{ $no++ }}</td>
                                        <td class="px-4 py-2 border">{{ $peserta->nama_peserta ?? '-' }}</td>
                                        <td class="px-4 py-2 border">{{ $peserta->tgl_lahir ?? '-' }}</td>
                                        <td class="px-4 py-2 border">{{ $peserta->jenis_kelamin ?? '-' }}</td>
                                        <td class="px-4 py-2 border">
                                            <a href="{{ route('peserta.edit', $peserta->id) }}"
                                                class="text-blue-600 hover:underline">Edit</a>
                                        </td>
                                        <td class="px-4 py-2 border">
                                            <form action="{{ route('peserta.destroy', $peserta->id) }}" method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus peserta ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                @if (empty($l->peserta) || count($l->peserta) == 0)
                                    <tr>
                                        <td colspan="8" class="px-4 py-2 border text-center text-gray-500">Tidak ada
                                            peserta dari klub Anda pada lomba ini.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        @else
            <div class="text-gray-500">Tidak ada lomba pada kompetisi ini.</div>
        @endif
    </div>
@endsection
