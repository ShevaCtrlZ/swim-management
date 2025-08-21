@extends('layouts.app')

@section('title', 'Daftar Klub')

@section('content')
    <div class="bg-white p-6 rounded-lg shadow-md">
        {{-- <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Daftar Klub</h2>
            <a href="{{ route('register_klub_form') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                <i class="fas fa-plus mr-2"></i> Tambah Klub
            </a>
        </div> --}}

        <!-- Tabel Daftar Klub -->
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="py-2 px-4 text-left text-sm font-medium text-gray-600">Nama Klub</th>
                        <th class="py-2 px-4 text-left text-sm font-medium text-gray-600">Alamat</th>
                        <th class="py-2 px-4 text-left text-sm font-medium text-grey-600">Kontak</th>
                        <th class="py-2 px-4 text-left text-sm font-medium text-gray-600">Jumlah Anggota</th>
                        <th class="py-2 px-4 text-left text-sm font-medium text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($klubs as $klub)
                        <tr class="border-b">
                            <td class="py-3 px-4 text-sm text-gray-800">{{ $klub->nama_klub }}</td>
                            <td class="py-3 px-4 text-sm text-gray-600">{{ $klub->alamat }}</td>
                            <td class="py-3 px-4 text-sm text-gray-600">{{ $klub->kontak }}</td>
                            <td class="py-3 px-4 text-sm text-gray-600">{{ $klub->peserta_count }}</td>
                            <td class="py-3 px-4 text-sm">
                                <!-- Tombol Edit dan Hapus -->
                                <a href="{{ route('edit_klub', $klub->id) }}" class="text-blue-600 hover:text-blue-800 mr-4">Edit</a>

                                <form action="{{ route('hapus_klub', $klub->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus klub ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
