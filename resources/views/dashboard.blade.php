@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Selamat Datang di Dashboard!</h1>

        <!-- Grid Card Statistik -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Card Jumlah Peserta -->
            <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                <a href="{{ route('list') }}">
                <div class="flex items-center space-x-4">
                    <div class="p-3 bg-blue-100 rounded-full">
                        <i class="fas fa-users text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-700">Jumlah Peserta</h2>
                        <p class="text-2xl font-bold text-gray-900">{{ $jumlahPeserta }}</p>
                    </div>
                </div>
                </a>
            </div>

            <!-- Card Jumlah Kompetisi -->
            <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                <a href="{{ route('list_kompetisi') }}">
                <div class="flex items-center space-x-4">
                    <div class="p-3 bg-green-100 rounded-full">
                        <i class="fas fa-trophy text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-700">Jumlah Kompetisi</h2>
                        <p class="text-2xl font-bold text-gray-900">{{ $jumlahKompetisi }}</p>
                    </div>
                </div>
                </a>
            </div>

            <!-- Card Jumlah Klub -->
            <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                <a href="{{ route('klub') }}">
                <div class="flex items-center space-x-4">
                    <div class="p-3 bg-purple-100 rounded-full">
                        <i class="fas fa-building text-purple-600 text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-700">Jumlah Klub</h2>
                        <p class="text-2xl font-bold text-gray-900">{{ $jumlahKlub }}</p>
                    </div>
                </div>
                </a>
            </div>
        </div>
    </div>
@endsection
