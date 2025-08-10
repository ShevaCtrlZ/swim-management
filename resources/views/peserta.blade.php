@extends('layouts.app')

@section('title', 'Tambah Peserta')

@section('content')
<div class="max-w-lg mx-auto mt-10 bg-white p-8 rounded shadow">
    <h2 class="text-2xl font-bold mb-6">Tambah Peserta</h2>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            <ul class="list-disc ml-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('peserta.store') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label for="nama_peserta" class="block text-sm font-medium text-gray-700">Nama Peserta</label>
            <input type="text" name="nama_peserta" id="nama_peserta" value="{{ old('nama_peserta') }}"
                class="mt-1 p-2 w-full border rounded" required>
        </div>
        <div>
            <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
            <select name="jenis_kelamin" id="jenis_kelamin" class="mt-1 p-2 w-full border rounded" required>
                <option value="">Pilih Jenis Kelamin</option>
                <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
            </select>
        </div>
        <div>
            <label for="tgl_lahir" class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
            <input type="date" name="tgl_lahir" id="tgl_lahir" value="{{ old('tgl_lahir') }}"
                class="mt-1 p-2 w-full border rounded" required>
        </div>
        <div class="text-center">
            <button type="submit"
                class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 font-semibold">
                Tambah Peserta
            </button>
        </div>
    </form>
</div>
@endsection