@extends('layouts.app')

@section('title', 'Edit Klub')

@section('content')
    <div class="bg-white p-6 rounded-lg shadow-md max-w-xl mx-auto">
        <h2 class="text-xl font-bold mb-4">Edit Klub</h2>

        <form action="{{ route('update_klub', $klub->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-gray-700">Nama Klub</label>
                <input type="text" name="nama_klub" value="{{ $klub->nama_klub }}" class="w-full border px-3 py-2 rounded">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Alamat</label>
                <textarea name="alamat" class="w-full border px-3 py-2 rounded">{{ $klub->alamat }}</textarea>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Kontak</label>
                <input type="text" name="kontak" value="{{ $klub->kontak }}" class="w-full border px-3 py-2 rounded">
            </div>

            <div class="flex justify-end space-x-2">
                <a href="{{ route('daftar_klub') }}" class="px-4 py-2 bg-gray-300 rounded">Batal</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Simpan</button>
            </div>
        </form>
    </div>
@endsection
