@extends('layouts.app')

@section('title', 'Tambah Kompetisi')

@section('content')
    <div class="bg-white p-6 rounded-lg shadow-md">
        <!-- Header -->
        <div class="p-6 bg-gradient-to-r from-blue-500 to-indigo-600 text-white text-center rounded-t-lg">
            <h3 class="text-2xl font-bold">Tambah Kompetisi</h3>
            <p class="text-sm mt-1">Isi detail kompetisi</p>
        </div>

        <!-- Form -->
        <form action="{{ route('store_kompetisi') }}" method="POST" class="p-6 space-y-6" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label for="nama_kompetisi" class="block text-sm font-medium text-gray-700">
                    Nama Kompetisi
                </label>
                <input type="text" name="nama_kompetisi" id="nama_kompetisi" placeholder="Masukkan nama kompetisi"
                    class="mt-1 p-3 block w-full shadow-sm sm:text-sm focus:ring-blue-500 focus:border-blue-500 border border-gray-300 rounded-lg"
                    required>
            </div>

            <div class="mb-4">
                <label for="tgl_mulai" class="block text-sm font-medium text-gray-700">
                    Tanggal Mulai Kompetisi
                </label>
                <input type="date" name="tgl_mulai" id="tgl_mulai"
                    class="mt-1 p-3 block w-full shadow-sm sm:text-sm focus:ring-blue-500 focus:border-blue-500 border border-gray-300 rounded-lg"
                    required>
            </div>

            <div class="mb-4">
                <label for="tgl_selesai" class="block text-sm font-medium text-gray-700">
                    Tanggal Selesai Kompetisi
                </label>
                <input type="date" name="tgl_selesai" id="tgl_selesai"
                    class="mt-1 p-3 block w-full shadow-sm sm:text-sm focus:ring-blue-500 focus:border-blue-500 border border-gray-300 rounded-lg"
                    required>
            </div>

            <div class="mb-4">
                <label for="lokasi" class="block text-sm font-medium text-gray-700">
                    Lokasi Kompetisi
                </label>
                <input type="text" name="lokasi" id="lokasi" placeholder="Masukkan lokasi kompetisi"
                    class="mt-1 p-3 block w-full shadow-sm sm:text-sm focus:ring-blue-500 focus:border-blue-500 border border-gray-300 rounded-lg"
                    required>
            </div>

            <div class="text-center">
                <button type="submit"
                    class="w-full sm:w-auto px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Simpan
                </button>
            </div>
        </form>
    </div>
@endsection
