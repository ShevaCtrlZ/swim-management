<!-- filepath: c:\laragon\www\Tugas_Akhir\resources\views\index.blade.php -->
@extends('layouts.tamu')

@section('title', 'More')

@section('content')
<div class="relative">
    <!-- Gambar -->
    <img src="{{ asset('gambar/banner.webp') }}" class="w-full h-64 object-cover" alt="Banner">
    <!-- Overlay -->
    <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <h1 class="text-white text-4xl font-bold">More</h1>
    </div>
</div>
@endsection