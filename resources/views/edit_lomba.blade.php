@extends('layouts.app')

@section('title', 'Edit Nomor Lomba')

@section('content')
<div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow-md mt-6">
    <h2 class="text-xl font-bold text-gray-800 mb-4">Edit Nomor Lomba</h2>

    <form action="{{ route('update_lomba', $lomba->id) }}" method="POST">
        @csrf
        @method('PUT')
        @if (session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Berhasil!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        <div class="mb-4">
            <label for="nomor_lomba" class="block font-semibold text-sm text-gray-700 mb-1">Nomor Lomba</label>
            <input type="text" name="nomor_lomba" id="nomor_lomba"
                class="w-full border-gray-300 rounded-md shadow-sm" value="{{ old('nomor_lomba', $lomba->nomor_lomba) }}" required>
        </div>

        <div class="mb-4">
            <label for="jarak" class="block font-semibold text-sm text-gray-700 mb-1">Jarak (Meter)</label>
            <input type="number" name="jarak" id="jarak"
                class="w-full border-gray-300 rounded-md shadow-sm" value="{{ old('jarak', $lomba->jarak) }}" required>
        </div>

        <div class="mb-4">
            <label for="jenis_gaya" class="block font-semibold text-sm text-gray-700 mb-1">Jenis Gaya</label>
            <select name="jenis_gaya" id="jenis_gaya" class="w-full border-gray-300 rounded-md shadow-sm">
                <option value="bebas" {{ $lomba->jenis_gaya == 'bebas' ? 'selected' : '' }}>Gaya Bebas</option>
                <option value="dada" {{ $lomba->jenis_gaya == 'dada' ? 'selected' : '' }}>Gaya Dada</option>
                <option value="punggung" {{ $lomba->jenis_gaya == 'punggung' ? 'selected' : '' }}>Gaya Punggung</option>
                <option value="kupu" {{ $lomba->jenis_gaya == 'kupu' ? 'selected' : '' }}>Gaya Kupu-Kupu</option>
            </select>
        </div>

        <div class="mb-4">
            <label for="tahun_lahir_minimal" class="block font-semibold text-sm text-gray-700 mb-1">Tahun Lahir Minimal</label>
            <input type="number" name="tahun_lahir_minimal" id="tahun_lahir_minimal"
                class="w-full border-gray-300 rounded-md shadow-sm" value="{{ old('tahun_lahir_minimal', $lomba->tahun_lahir_minimal) }}" required>
        </div>

        <div class="mb-4">
            <label for="tahun_lahir_maksimal" class="block font-semibold text-sm text-gray-700 mb-1">Tahun Lahir Maksimal</label>
            <input type="number" name="tahun_lahir_maksimal" id="tahun_lahir_maksimal"
                class="w-full border-gray-300 rounded-md shadow-sm" value="{{ old('tahun_lahir_maksimal', $lomba->tahun_lahir_maksimal) }}" required>
        </div>

        <div class="mb-6">
            <label for="jk" class="block font-semibold text-sm text-gray-700 mb-1">Jenis Kelamin</label>
            <select name="jk" id="jk" class="w-full border-gray-300 rounded-md shadow-sm">
                <option value="L" {{ $lomba->jk == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                <option value="P" {{ $lomba->jk == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
            </select>
        </div>

        <div class="flex justify-between">
            <a href="{{ url()->previous() }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400">Kembali</a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
