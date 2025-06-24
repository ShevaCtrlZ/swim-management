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
                            <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                            <select name="jenis_kelamin" id="jenis_kelamin"
                                class="mt-1 p-3 block w-full shadow-md sm:text-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300 rounded-lg">
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="limit" class="block text-sm font-medium text-gray-700">Limit</label>
                            <input type="text" name="limit" id="limit" placeholder="Masukkan limit peserta"
                                class="mt-1 p-3 block w-full shadow-md sm:text-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300 rounded-lg">
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Lomba</label>
                            <div id="lomba-container" name="lomba_id[]" class="space-y-2 mt-2">
                                <p class="text-gray-500 text-sm italic">Silakan pilih kompetisi, jenis kelamin, dan tanggal
                                    lahir terlebih dahulu.</p>
                            </div>
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

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const kompetisiSelect = document.getElementById('kompetisi');
                const jenisKelaminSelect = document.getElementById('jenis_kelamin');
                const tglLahirInput = document.getElementById('tgl_lahir');
                const lombaContainer = document.getElementById('lomba-container');

                const allLombaData = @json($allLombaData);

                function formatRupiah(angka) {
                    return 'Rp' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                }

                function renderLombaCheckbox() {
                    const kompetisiId = kompetisiSelect.value;
                    const jenisKelamin = jenisKelaminSelect.value;
                    const tglLahirValue = tglLahirInput.value;

                    lombaContainer.innerHTML = '';

                    if (!kompetisiId || !jenisKelamin || !tglLahirValue) {
                        lombaContainer.innerHTML = `
                    <p class="text-gray-500 text-sm italic">
                        Silakan pilih kompetisi, jenis kelamin, dan tanggal lahir terlebih dahulu.
                    </p>`;
                        return;
                    }

                    const tahunLahir = new Date(tglLahirValue).getFullYear();

                    const filtered = allLombaData.filter(l => {
                        const cocokJK = (l.jk === jenisKelamin);
                        return l.kompetisi_id == kompetisiId &&
                            tahunLahir >= l.min &&
                            tahunLahir <= l.max &&
                            cocokJK;
                    });

                    if (filtered.length === 0) {
                        lombaContainer.innerHTML = `
                    <p class="text-red-500 text-sm italic">Tidak ada lomba yang cocok.</p>`;
                        return;
                    }

                    // Tambahkan checkbox per lomba
                    filtered.forEach(l => {
                        const wrapper = document.createElement('div');
                        wrapper.classList.add('flex', 'items-center', 'gap-2');

                        const checkbox = document.createElement('input');
                        checkbox.type = 'checkbox';
                        checkbox.name = 'lomba_id[]';
                        checkbox.value = l.id;
                        checkbox.id = `lomba_${l.id}`;
                        checkbox.dataset.harga = l.harga;
                        checkbox.classList.add('checkbox-lomba');

                        const label = document.createElement('label');
                        label.setAttribute('for', `lomba_${l.id}`);
                        label.classList.add('text-sm', 'text-gray-700');
                        label.innerText =
                            `${l.jenis_gaya} - ${l.jarak}m (${l.min}/${l.max}) - ${l.jk} - ${formatRupiah(l.harga)}`;

                        wrapper.appendChild(checkbox);
                        wrapper.appendChild(label);
                        lombaContainer.appendChild(wrapper);
                    });

                    // Elemen total harga
                    const totalElement = document.createElement('div');
                    totalElement.classList.add('text-right', 'mt-4', 'text-sm', 'text-gray-700', 'font-semibold');
                    totalElement.id = 'totalHargaContainer';
                    totalElement.innerText = `Total Harga: Rp0`;
                    lombaContainer.appendChild(totalElement);

                    // Event listener untuk semua checkbox
                    document.querySelectorAll('.checkbox-lomba').forEach(cb => {
                        cb.addEventListener('change', updateTotalHarga);
                    });

                    updateTotalHarga(); // panggil awal
                }

                function updateTotalHarga() {
                    const checkboxes = document.querySelectorAll('.checkbox-lomba:checked');
                    let total = 0;
                    checkboxes.forEach(cb => {
                        total += parseInt(cb.dataset.harga || 0);
                    });
                    const totalHargaElement = document.getElementById('totalHargaContainer');
                    if (totalHargaElement) {
                        totalHargaElement.innerText = `Total Harga: ${formatRupiah(total)}`;
                    }
                }

                // Event
                kompetisiSelect.addEventListener('change', renderLombaCheckbox);
                jenisKelaminSelect.addEventListener('change', renderLombaCheckbox);
                tglLahirInput.addEventListener('change', renderLombaCheckbox);
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
