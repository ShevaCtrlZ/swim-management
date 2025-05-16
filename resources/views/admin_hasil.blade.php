@extends('layouts.app')

@section('title', 'Hasil Kompetisi')

@section('content')
    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Hasil Kompetisi</h2>
        </div>

        <!-- Card Kompetisi -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($data as $item)
                <!-- Card Kompetisi -->
                <div class="bg-white rounded-lg shadow-md p-4 relative hover:shadow-lg transition-shadow duration-200">
                    <!-- Titik Tiga -->
                    <div class="absolute top-2 right-2 z-20">
                        <button id="dropdownButton-{{ $item->id }}" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                
                        <!-- Dropdown Menu -->
                        <div id="dropdownMenu-{{ $item->id }}" class="hidden absolute right-0 mt-2 w-40 bg-white rounded-lg shadow-lg z-30">
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Edit</a>
                            <form action="#" method="POST" class="block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-100">Hapus</button>
                            </form>
                        </div>
                    </div>

                    <!-- Informasi Kompetisi -->
                    <a href="{{ route('hasil_juara', $item->id) }}" class="block">
                        <h3 class="text-lg font-bold text-gray-800">{{ $item->nama_kompetisi }}</h3>
                        <p class="text-sm text-gray-600">Tanggal: {{ $item->tgl_mulai }} - {{ $item->tgl_selesai }}</p>
                        <p class="text-sm text-gray-600">Lokasi: {{ $item->lokasi }}</p>
                    </a>
                </div>
            @endforeach
        </div>
    </div>

    <script>
        // JavaScript untuk toggle dropdown menu
        document.querySelectorAll('[id^="dropdownButton-"]').forEach(button => {
            button.addEventListener('click', function (event) {
                event.stopPropagation(); // Mencegah klik pada card
                const id = this.id.split('-')[1];
                const menu = document.getElementById(`dropdownMenu-${id}`);
                menu.classList.toggle('hidden');
            });
        });
    </script>
@endsection
