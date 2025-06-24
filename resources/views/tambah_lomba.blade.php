<!-- filepath: c:\laragon\www\Tugas_Akhir\resources\views\tambah_lomba.blade.php -->
@extends('layouts.app')

@section('title', 'Tambah Lomba')

@section('content')
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg">
                <div class="p-6 text-gray-800">
                    <h2 class="text-2xl font-bold mb-6 text-blue-600">Form Tambah Lomba</h2>
                    <form action="{{ route('simpan_lomba', $id) }}" method="POST">
                        @csrf
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="mb-4">
                            <label for="jarak" class="block text-sm font-medium text-gray-700">Jarak (meter)</label>
                            <select name="jarak" id="jarak"
                                class="mt-1 p-3 block w-full shadow-md sm:text-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300 rounded-lg">
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="200">200</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="jenis_gaya" class="block text-sm font-medium text-gray-700">Jenis Gaya</label>
                            <select name="jenis_gaya" id="jenis_gaya"
                                class="mt-1 p-3 block w-full shadow-md sm:text-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300 rounded-lg">
                                <option value="Bebas">Bebas</option>
                                <option value="Dada">Dada</option>
                                <option value="Kupu">Kupu</option>
                                <option value="Punggung">Punggung</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="jumlah_lintasan" class="block text-sm font-medium text-gray-700">Jumlah
                                Lintasan</label>
                            <input type="number" name="jumlah_lintasan" id="jumlah_lintasan"
                                placeholder="Masukkan jumlah lintasan"
                                class="mt-1 p-3 block w-full shadow-md sm:text-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300 rounded-lg">
                        </div>
                        <div class="mb-4">
                            <label for="nomor_lomba" class="block text-sm font-medium text-gray-700">Nomor Lomba</label>
                            <input type="number" name="nomor_lomba" id="nomor_lomba" placeholder="Masukkan nomor lomba"
                                class="mt-1 p-3 block w-full shadow-md sm:text-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300 rounded-lg">
                        </div>
                        <div class="mb-4">
                            <label for="tahun_lahir_minimal" class="block text-sm font-medium text-gray-700">Tahun Lahir
                                Minimal</label>
                            <input type="number" name="tahun_lahir_minimal" id="tahun_lahir_minimal"
                                placeholder="Masukkan tahun lahir minimal"
                                class="mt-1 p-3 block w-full shadow-md sm:text-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300 rounded-lg">
                        </div>
                        <div class="mb-4">
                            <label for="tahun_lahir_maksimal" class="block text-sm font-medium text-gray-700">Tahun Lahir
                                Maksimal</label>
                            <input type="number" name="tahun_lahir_maksimal" id="tahun_lahir_maksimal"
                                placeholder="Masukkan tahun lahir maksimal"
                                class="mt-1 p-3 block w-full shadow-md sm:text-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300 rounded-lg">
                        </div>
                        <div class="mb-4">
                            <label for="jk" class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                            <select name="jk" id="jk"
                                class="mt-1 p-3 block w-full shadow-md sm:text-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300 rounded-lg">
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="harga" class="block text-sm font-medium text-gray-700">Harga</label>
                            <input type="number" name="harga" id="harga" placeholder="Masukkan harga"
                                class="mt-1 p-3 block w-full shadow-md sm:text-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300 rounded-lg">
                        </div>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2">
                            Simpan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
