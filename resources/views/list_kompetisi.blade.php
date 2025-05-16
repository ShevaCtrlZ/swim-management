@extends('layouts.app')

@section('title', 'Daftar Kompetisi')

@section('content')
    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Daftar Kompetisi</h2>
            <!-- Tombol Plus -->
            <a href="{{ route('add_kompetisi') }}"
                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                <i class="fas fa-plus mr-2"></i> Tambah Kompetisi
            </a>
        </div>

        <!-- Card Kompetisi -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($data as $item)
                <!-- Card Kompetisi -->
                <div class="bg-white rounded-lg shadow-md p-4 relative hover:shadow-lg transition-shadow duration-200">
                    <!-- Titik Tiga -->
                    <div class="absolute top-2 right-2 z-20">
                        <button id="dropdownButton-{{ $item->id }}"
                            class="text-gray-500 hover:text-gray-700 focus:outline-none">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>

                        <!-- Dropdown Menu -->
                        <div id="dropdownMenu-{{ $item->id }}"
                            class="hidden absolute right-0 mt-2 w-40 bg-white rounded-lg shadow-lg z-30">
                            <!-- Edit -->
                            <a href="#" data-modal-target="modal-edit-{{ $item->id }}"
                                data-modal-toggle="modal-edit-{{ $item->id }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Edit</a>

                            <form action="{{ route('hapus_kompetisi', $item->id) }}" method="POST" class="block">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-100">Hapus</button>
                            </form>
                        </div>
                    </div>

                    <!-- Informasi Kompetisi -->
                    <a href="{{ route('lihat_kompetisi', $item->id) }}" class="block">
                        <h3 class="text-lg font-bold text-gray-800">{{ $item->nama_kompetisi }}</h3>
                        <p class="text-sm text-gray-600">Tanggal: {{ $item->tgl_mulai }} - {{ $item->tgl_selesai }}</p>
                        <p class="text-sm text-gray-600">Lokasi: {{ $item->lokasi }}</p>
                    </a>
                </div>
                <div id="modal-edit-{{ $item->id }}" tabindex="-1"
                    class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                    <div class="bg-white rounded-lg p-6 w-full max-w-md shadow-lg">
                        <h3 class="text-lg font-semibold mb-4">Edit Kompetisi</h3>
                        <form action="{{ route('update_kompetisi', $item->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <label class="block text-sm font-medium text-gray-700">Nama Kompetisi</label>
                            <input type="text" name="nama_kompetisi" value="{{ $item->nama_kompetisi }}"
                                class="mt-1 w-full border border-gray-300 rounded px-3 py-2 text-sm">

                            <label class="block mt-3 text-sm font-medium text-gray-700">Tanggal Mulai</label>
                            <input type="date" name="tgl_mulai" value="{{ $item->tgl_mulai }}"
                                class="mt-1 w-full border border-gray-300 rounded px-3 py-2 text-sm">

                            <label class="block mt-3 text-sm font-medium text-gray-700">Tanggal Selesai</label>
                            <input type="date" name="tgl_selesai" value="{{ $item->tgl_selesai }}"
                                class="mt-1 w-full border border-gray-300 rounded px-3 py-2 text-sm">

                            <label class="block mt-3 text-sm font-medium text-gray-700">Lokasi</label>
                            <input type="text" name="lokasi" value="{{ $item->lokasi }}"
                                class="mt-1 w-full border border-gray-300 rounded px-3 py-2 text-sm">

                            <div class="mt-4 flex justify-end space-x-2">
                                <button type="button" data-modal-hide="modal-edit-{{ $item->id }}"
                                    class="px-4 py-2 text-gray-700 border border-gray-300 rounded hover:bg-gray-100">Batal</button>
                                <button type="submit"
                                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <script>
        // JavaScript untuk toggle dropdown menu
        document.querySelectorAll('[id^="dropdownButton-"]').forEach(button => {
            button.addEventListener('click', function(event) {
                event.stopPropagation(); // Mencegah klik pada card
                const id = this.id.split('-')[1];
                const menu = document.getElementById(`dropdownMenu-${id}`);
                menu.classList.toggle('hidden');
            });
        });

        document.querySelectorAll('[data-modal-toggle]').forEach(button => {
            button.addEventListener('click', () => {
                const modalId = button.getAttribute('data-modal-target');
                document.getElementById(modalId).classList.remove('hidden');
            });
        });

        document.querySelectorAll('[data-modal-hide]').forEach(button => {
            button.addEventListener('click', () => {
                const modalId = button.getAttribute('data-modal-hide');
                document.getElementById(modalId).classList.add('hidden');
            });
        });
    </script>
@endsection
