@extends('layouts.app')

@section('title', 'Tambahkan Peserta')

@section('content')
    @if (auth()->check() && auth()->user()->role === 'klub')
        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                    <!-- Header -->
                    <div class="p-6 bg-gradient-to-r from-blue-500 to-indigo-600 text-white text-center rounded-t-lg">
                        <h3 class="text-2xl font-bold">{{ __('Tambah Peserta') }}</h3>
                        <p class="text-sm mt-1">Isi data peserta dengan lengkap</p>
                    </div>

                    <!-- Form -->
                    <form action="/store-data" method="post" class="p-6 space-y-6">
                        @csrf
                        <div class="mb-4">
                            <label for="nama_peserta" class="block text-sm font-medium text-gray-700">Nama</label>
                            <input type="text" name="nama_peserta" id="nama_peserta" placeholder="Masukkan nama peserta"
                                class="mt-1 p-3 block w-full shadow-md sm:text-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300 rounded-lg">
                        </div>
                        <div class="mb-4">
                            <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                            <select name="jenis_kelamin" id="jenis_kelamin"
                                class="mt-1 p-3 block w-full shadow-md sm:text-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300 rounded-lg">
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="kompetisi" class="block text-sm font-medium text-gray-700">Kompetisi</label>
                            <select id="kompetisi"
                                class="mt-1 p-3 block w-full shadow-md sm:text-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300 rounded-lg">
                                <option value="">Pilih Kompetisi</option>
                                @foreach ($kompetisi as $k)
                                    <option value="{{ $k->id }}">{{ $k->nama_kompetisi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="tgl_lahir" class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                            <input type="date" name="tgl_lahir" id="tgl_lahir"
                                class="mt-1 p-3 block w-full shadow-md sm:text-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300 rounded-lg">
                        </div>
                        <div class="mb-4">
                            <label for="limit" class="block text-sm font-medium text-gray-700">Limit</label>
                            <input type="text" name="limit" id="limit" placeholder="Masukkan limit peserta"
                                class="mt-1 p-3 block w-full shadow-md sm:text-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300 rounded-lg">
                        </div>
                        <div class="mb-4">
                            <label for="lomba_id" class="block text-sm font-medium text-gray-700">Lomba</label>
                            <select name="lomba_id" id="lomba_id"
                                class="mt-1 p-3 block w-full shadow-md sm:text-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300 rounded-lg">
                                <option value="">Pilih Lomba</option>
                                @foreach ($kompetisi as $k)
                                    @foreach ($k->lomba as $l)
                                        <option value="{{ $l->id }}" data-kompetisi="{{ $k->id }}"
                                            data-min="{{ $l->tahun_lahir_minimal }}"
                                            data-max="{{ $l->tahun_lahir_maksimal }}" data-jk="{{ $l->jk }}">
                                            {{ $l->jenis_gaya }} - {{ $l->jarak }}m
                                            ({{ $l->tahun_lahir_minimal }}/{{ $l->tahun_lahir_maksimal }})
                                            -
                                            {{ $l->jk }}
                                        </option>
                                    @endforeach
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            const kompetisiSelect = document.getElementById('kompetisi');
                                            const lombaSelect = document.getElementById('lomba_id');
                                            const tglLahirInput = document.getElementById('tgl_lahir');
                                            const jenisKelaminSelect = document.getElementById('jenis_kelamin');

                                            const allLombaOptions = Array.from(lombaSelect.querySelectorAll('option[data-kompetisi]'));

                                            function filterLomba() {
                                                const selectedKompetisi = kompetisiSelect.value;
                                                const jenisKelamin = jenisKelaminSelect.value;
                                                const tglLahir = new Date(tglLahirInput.value);

                                                if (!selectedKompetisi || !jenisKelamin || !tglLahirInput.value) {
                                                    lombaSelect.innerHTML = '<option value="">Pilih Lomba</option>';
                                                    return;
                                                }

                                                const tahunLahir = tglLahir.getFullYear();

                                                lombaSelect.innerHTML = '<option value="">Pilih Lomba</option>';

                                                const filtered = allLombaOptions.filter(opt => {
                                                    const kompetisiId = opt.getAttribute('data-kompetisi');
                                                    const min = parseInt(opt.getAttribute('data-min'));
                                                    const max = parseInt(opt.getAttribute('data-max'));
                                                    const jk = opt.getAttribute('data-jk');

                                                    const cocokJK = (jk === 'All') || (jk === jenisKelamin);

                                                    return kompetisiId === selectedKompetisi &&
                                                        tahunLahir >= min && tahunLahir <= max &&
                                                        cocokJK;
                                                });

                                                filtered.forEach(opt => lombaSelect.appendChild(opt.cloneNode(true)));
                                            }

                                            kompetisiSelect.addEventListener('change', filterLomba);
                                            tglLahirInput.addEventListener('change', filterLomba);
                                            jenisKelaminSelect.addEventListener('change', filterLomba);
                                        });
                                    </script>
                                @endforeach
                            </select>
                        </div>
                        <div class="text-center">
                            <button type="submit"
                                class="w-full sm:w-auto px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                Tambahkan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Script untuk Filter Lomba -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const kompetisiSelect = document.getElementById('kompetisi');
                const lombaSelect = document.getElementById('lomba_id');

                // Ambil semua opsi lomba yang punya atribut data-kompetisi
                const allLombaOptions = Array.from(lombaSelect.querySelectorAll('option[data-kompetisi]'));

                kompetisiSelect.addEventListener('change', function() {
                    const selectedKompetisi = this.value;

                    // Kosongkan dropdown lomba
                    lombaSelect.innerHTML = '<option value="">Pilih Lomba</option>';

                    // Filter dan tambahkan opsi yang sesuai kompetisi yang dipilih
                    const filteredOptions = allLombaOptions.filter(opt =>
                        opt.getAttribute('data-kompetisi') === selectedKompetisi
                    );

                    filteredOptions.forEach(opt => {
                        lombaSelect.appendChild(opt.cloneNode(true));
                    });
                });
            });
        </script>
    @else
        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="p-6 text-center">
                        <h3 class="text-xl font-bold text-red-600">{{ __('Anda tidak memiliki akses ke halaman ini.') }}
                        </h3>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
