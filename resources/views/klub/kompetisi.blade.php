@extends('layouts.app')

@section('title', 'Info Kompetisi Klub')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Daftar Kompetisi -->
            <div class="bg-white p-6 rounded-lg shadow-md mt-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Daftar Kompetisi</h2>
                </div>

                <!-- Card Kompetisi -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($kompetisiList as $kompetisi)
                        <div class="bg-white rounded-lg shadow-md p-6 mb-4 relative">
                            <h3 class="text-xl font-bold text-gray-800 mb-1">
                                {{ $kompetisi->nama_kompetisi ?? $kompetisi->lokasi }}</h3>
                            <p class="text-sm text-gray-600 mb-1">
                                <span class="font-semibold">Tanggal:</span>
                                {{ $kompetisi->tgl_mulai ?? '-' }}{{ $kompetisi->tgl_selesai ? ' - ' . $kompetisi->tgl_selesai : '' }}
                            </p>
                            <p class="text-sm text-gray-600 mb-2">
                                <span class="font-semibold">Lokasi:</span> {{ $kompetisi->lokasi }}
                            </p>
                            <p class="text-sm font-medium mb-1">
                                Total Harga Klub:
                                <span class="font-bold text-green-800">
                                    Rp{{ number_format($kompetisi->total_harga_klub, 0, ',', '.') }}
                                </span>
                            </p>

                            @if (!empty($kompetisi->deskripsi))
                                <p class="text-sm text-gray-500 mb-2">{{ $kompetisi->deskripsi }}</p>
                            @endif

                            <!-- Tombol untuk toggle daftar peserta -->
                            <button type="button"
                                class="mt-2 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 focus:outline-none"
                                onclick="togglePeserta('peserta-{{ $kompetisi->id }}')">
                                Lihat Peserta Klub
                            </button>

                            <!-- Daftar Peserta Klub (hidden by default) -->
                            <div id="peserta-{{ $kompetisi->id }}" class="hidden mt-4">
                                <h4 class="font-semibold mb-2">Peserta dari Klub Ini:</h4>
                                @php
                                    $pesertaKlub = collect();
                                    foreach ($kompetisi->lomba as $lomba) {
                                        $pesertaKlub = $pesertaKlub->merge($lomba->peserta);
                                    }
                                @endphp
                                @if ($pesertaKlub->count())
                                    <ul class="list-disc pl-5">
                                        @foreach ($pesertaKlub as $peserta)
                                            <li class="flex items-center justify-between">
                                                <div>
                                                    <span class="font-medium">{{ $peserta->nama_peserta }}</span>
                                                    <span class="text-xs text-gray-500">
                                                        ({{ $peserta->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }},
                                                        {{ $peserta->tgl_lahir }})
                                                    </span>
                                                </div>
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('peserta.edit', $peserta->id) }}"
                                                        class="text-blue-600 hover:underline text-xs">Edit</a>
                                                    <form action="{{ route('peserta.destroy', $peserta->id) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Yakin ingin menghapus peserta ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="text-red-600 hover:underline text-xs">Hapus</button>
                                                    </form>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-gray-500">Tidak ada peserta dari klub ini pada kompetisi ini.</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePeserta(id) {
            var el = document.getElementById(id);
            if (el.classList.contains('hidden')) {
                el.classList.remove('hidden');
            } else {
                el.classList.add('hidden');
            }
        }
    </script>
@endsection
