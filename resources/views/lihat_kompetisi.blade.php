@extends('layouts.app')

@section('title', 'Det')
@section('content')
    <div class="bg-white p-4 md:p-6 rounded-lg shadow-md">
        <h2 class="text-xl md:text-2xl font-bold text-gray-800">{{ $kompetisi->nama_kompetisi }}</h2>
        <div class="mt-4 space-y-1 text-sm md:text-base">
            <p><strong>Tanggal Mulai:</strong> {{ $kompetisi->tgl_mulai }}</p>
            <p><strong>Tanggal Selesai:</strong> {{ $kompetisi->tgl_selesai }}</p>
            <p><strong>Lokasi:</strong> {{ $kompetisi->lokasi }}</p>
        </div>

        <a href="{{ route('export.buku_acara_pdf', $kompetisi->id) }}" target="_blank"
           class="inline-block px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 mb-4">
            <i class="fas fa-file-pdf mr-2"></i> Export PDF
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

        <div class="mt-6 flex flex-col md:flex-row justify-end space-y-2 md:space-y-0 md:space-x-4">
            <a href="{{ route('tambah_lomba', $kompetisi->id) }}"
                class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-md">
                <i class="fas fa-plus mr-2"></i> Tambah Lomba
            </a>
            <form action="{{ route('sort_all_peserta', $kompetisi->id) }}" method="POST">
                @csrf
                <button type="submit"
                    class="inline-flex items-center justify-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg shadow-md">
                    <i class="fas fa-sort-amount-up-alt mr-2"></i> Urutkan Peserta Berdasarkan Limit Waktu
                </button>
            </form>
        </div>

        <!-- Tabel Hasil Lomba -->
        <div class="mt-6 space-y-6">
            @foreach ($lomba as $item)
                <div class="space-y-4">
                    <h4 class="text-base md:text-lg font-bold text-gray-800">
                        {{ $item->nomor_lomba }}. {{ $item->jarak }} M GAYA {{ strtoupper($item->jenis_gaya) }} KU
                        {{ $item->tahun_lahir_minimal }} / {{ $item->tahun_lahir_maksimal }} {{ $item->jk }}
                    </h4>
                    <div class="flex items-center space-x-2 mt-1">
                        <a href="{{ route('edit_lomba', $item->id) }}"
                            class="inline-flex items-center px-3 py-1 text-xs font-medium text-white bg-yellow-500 rounded hover:bg-yellow-600">
                            <i class="fas fa-edit mr-1"></i>Edit
                        </a>

                        <form action="{{ route('hapus_lomba', $item->id) }}" method="POST"
                            onsubmit="return confirm('Yakin ingin menghapus lomba ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="inline-flex items-center px-3 py-1 text-xs font-medium text-white bg-red-600 rounded hover:bg-red-700">
                                <i class="fas fa-trash-alt mr-1"></i>Hapus
                            </button>
                        </form>

                    </div>

                    @if ($item->nomorLomba->isNotEmpty())
                        @foreach ($item->nomorLomba as $seri => $kelompok)
                            <div>
                                <h5 class="text-sm md:text-md font-semibold text-gray-700 mt-2">
                                    Nomor Lomba {{ $item->nomor_lomba }} - Seri {{ $seri }}
                                </h5>
                                <!-- Tombol untuk menaruh peserta limit tertinggi di tengah -->
                                @if (!empty($seri))
                                    <form
                                        action="{{ route('center_max_limit_peserta', ['lomba_id' => $item->id, 'seri' => $seri]) }}"
                                        method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="inline-flex items-center px-3 py-1 text-xs font-medium text-white bg-pink-600 rounded hover:bg-pink-700">
                                            <i class="fas fa-arrows-alt-h mr-1"></i> Limit Tertinggi ke Tengah
                                        </button>
                                    </form>
                                @else
                                    <p class="text-red-500 text-xs">Seri belum diatur, tidak bisa urutkan ke tengah.</p>
                                @endif

                                <!-- Scrollable Table Wrapper -->
                                <div class="overflow-x-auto rounded-md shadow-sm mt-2">
                                    <table class="min-w-full border-collapse border border-gray-300 text-sm">
                                        <thead class="bg-gray-100 text-left">
                                            <tr>
                                                <th class="border border-gray-300 px-4 py-2">No Lint</th>
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
                                                    <td class="border border-gray-300 px-4 py-2">
                                                        <form action="{{ route('update_hasil', $peserta->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            <input type="text" name="hasil"
                                                                value="{{ $peserta->catatan_waktu ?? '' }}"
                                                                placeholder="00:00:00"
                                                                class="border border-gray-300 rounded px-2 py-1 text-sm">
                                                            <button type="submit"
                                                                class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs ml-2">
                                                                Simpan
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-gray-600 text-sm italic">Tidak ada peserta untuk lomba ini.</p>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
@endsection
