<!-- filepath: c:\laragon\www\Tugas_Akhir\resources\views\index.blade.php -->
@extends('layouts.tamu')

@section('title', 'Jadwal')

@section('content')
<div class="relative">
    <!-- Gambar -->
    <img src="{{ asset('gambar/banner.webp') }}" class="w-full h-64 object-cover" alt="Banner">
    <!-- Overlay -->
    <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <h1 class="text-white text-4xl font-bold">Jadwal</h1>
    </div>
</div>

<div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($data as $item)
            <!-- Card Kompetisi -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-md p-4 relative hover:shadow-lg transition-shadow duration-200">
                
                <a href="{{ route('lihat_hasil', $item->id) }}" class="block">
                    <h3 class="text-lg font-bold text-gray-800">{{ $item->nama_kompetisi }}</h3>
                    <p class="text-sm text-gray-600">Tanggal: {{ $item->tgl_mulai }} - {{ $item->tgl_selesai }}</p>
                    <p class="text-sm text-gray-600">Lokasi: {{ $item->lokasi }}</p>
                </a>
            </div>
        @endforeach
    </div>
</div>
@endsection