@extends('layouts.app')

@section('title', 'Tambahkan Peserta')

@section('content')
    <style>
        option[disabled] {
            text-decoration: line-through;
            color: red;
        }
    </style>
    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
            <strong>Terjadi kesalahan:</strong>
            <ul class="list-disc ml-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if (session('success'))
        <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif


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
                            <label for="kompetisi" class="block text-sm font-medium text-gray-700">Kompetisi</label>
                            <select id="kompetisi" name="kompetisi_id"
                                class="mt-1 p-3 block w-full shadow-md sm:text-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300 rounded-lg">
                                <option value="">Pilih Kompetisi</option>
                                @foreach ($kompetisi as $k)
                                    @php
                                        // Hari ini >= tgl_mulai → disable
                                        $isDisabled = \Carbon\Carbon::today()->greaterThanOrEqualTo(
                                            \Carbon\Carbon::parse($k->tgl_mulai),
                                        );
                                    @endphp
                                    <option value="{{ $k->id }}" {{ $isDisabled ? 'disabled' : '' }}>
                                        {{ $k->nama_kompetisi }} {{ $isDisabled ? '(Tidak bisa didaftar)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="nama_peserta" class="block text-sm font-medium text-gray-700">Nama</label>
                            <select name="peserta_id" id="peserta_id"
                                class="form-control mt-1 p-3 block w-full shadow-md sm:text-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300 rounded-lg"
                                required>
                                <option value="">-- Pilih Peserta --</option>
                                @foreach ($peserta as $p)
                                    <option value="{{ $p->id }}" data-tgl="{{ $p->tgl_lahir }}"
                                        data-jk="{{ $p->jenis_kelamin }}">
                                        {{ $p->nama_peserta }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Tanggal Lahir (read-only) --}}
                        <div class="mb-4">
                            <label for="tgl_lahir" class="form-label">Tanggal Lahir</label>
                            <input type="date" id="tgl_lahir"
                                class="form-control mt-1 p-3 block w-full shadow-md sm:text-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300 rounded-lg"
                                readonly>
                        </div>

                        <div class="mb-4">
                            <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                            <input type="text" id="jenis_kelamin"
                                class="form-control mt-1 p-3 block w-full shadow-md sm:text-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300 rounded-lg"
                                readonly>
                        </div>
                        <div class="mb-4">
                            <label for="metode" class="block text-sm font-medium text-gray-700">Metode Pendaftaran</label>
                            <select name="metode" id="metode"
                                class="mt-1 p-3 block w-full shadow-md sm:text-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300 rounded-lg">
                                <option value="normal">Reguler</option>
                                <option value="bundling">Bundling</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <div class="flex items-center place-content-between space-x-4 mb-2">
                                <label class="block text-sm font-medium text-gray-700">Lomba</label>
                                <label class="block text-sm font-medium text-gray-700">Limit</label>
                            </div>
                            <div id="listLomba" class="space-y-2 mt-2">
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
            const allLomba = @json($lomba);
            const hargaBundling = {{ $hargaBundling }};
            const syaratBundling = {{ $syaratBundling }};
            window.lombaSudahDipilih = @json($lombaSudahDipilih);

            // Fungsi untuk fetch lomba yang sudah dipilih peserta dan render ulang lomba
            function fetchLombaSudahDipilih(pesertaId, tgl, jk) {
                if (!pesertaId) {
                    window.lombaSudahDipilih = [];
                    document.getElementById('listLomba').innerHTML = '<em>Pilih peserta terlebih dahulu</em>';
                    return;
                }

                fetch(`/get-lomba-terpilih/${pesertaId}`)
                    .then(res => res.json())
                    .then(data => {
                        window.lombaSudahDipilih = data;
                        renderLomba(tgl, jk);
                    })
                    .catch(() => {
                        window.lombaSudahDipilih = [];
                        renderLomba(tgl, jk);
                    });
            }

            document.getElementById('peserta_id').addEventListener('change', function() {
                let pesertaId = this.value;
                let pesertaOption = this.options[this.selectedIndex];
                let tgl = pesertaOption.getAttribute('data-tgl');
                let jk = pesertaOption.getAttribute('data-jk');

                document.getElementById('tgl_lahir').value = tgl || '';
                document.getElementById('jenis_kelamin').value = jk || '';

                if (!pesertaId) {
                    document.getElementById('listLomba').innerHTML = '<em>Pilih peserta terlebih dahulu</em>';
                    return;
                }

                fetchLombaSudahDipilih(pesertaId, tgl, jk);
            });

            document.getElementById('kompetisi').addEventListener('change', function() {
                let pesertaSelect = document.getElementById('peserta_id');
                let pesertaId = pesertaSelect.value;
                let pesertaOption = pesertaSelect.options[pesertaSelect.selectedIndex];
                let tgl = pesertaOption ? pesertaOption.getAttribute('data-tgl') : '';
                let jk = pesertaOption ? pesertaOption.getAttribute('data-jk') : '';

                document.getElementById('tgl_lahir').value = tgl || '';
                document.getElementById('jenis_kelamin').value = jk || '';

                if (tgl && jk) {
                    // Fetch lombaSudahDipilih berdasarkan peserta saat ini, lalu render
                    fetchLombaSudahDipilih(pesertaId, tgl, jk);
                } else {
                    document.getElementById('listLomba').innerHTML = '<em>Pilih peserta terlebih dahulu</em>';
                }
            });

            function renderLomba(tgl, jk) {
                let tahunLahir = parseInt(tgl.split('-')[0]);
                let html = '';
                // Ambil kompetisi_id yang dipilih
                let kompetisiSelect = document.getElementById('kompetisi');
                let kompetisiId = kompetisiSelect ? kompetisiSelect.value : null;

                allLomba.forEach(l => {
                    // Filter kompetisi_id
                    if (kompetisiId && l.kompetisi_id != kompetisiId) return;
                    // skip jika lomba sudah dipilih peserta
                    if (window.lombaSudahDipilih.includes(l.id)) return;

                    let syaratJK = (l.jk === 'L' || l.jk === 'P') ? (l.jk === jk) : true;
                    let syaratUmur = tahunLahir >= l.min && tahunLahir <= l.max;


                    if (syaratJK && syaratUmur) {
                        html += `
            <div class="flex items-center place-content-between space-x-4 mb-2">
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="lomba_id[]" value="${l.id}" class="check-lomba" data-harga="${l.harga}">
                    <span>${l.jenis_gaya} ${l.jarak}m (Rp ${parseInt(l.harga).toLocaleString()})</span>
                </label>
                <input 
                    type="text" 
                    name="limit_per_lomba[${l.id}]" 
                    value="99:99:99" 
                    class="p-2 border rounded w-36"
                    title="Format MM:SS:CS (centiseconds), misal 01:30:50 untuk 1 menit 30,50 centis"
                />
            </div>`;
                    }
                });

                if (html === '') {
                    html = '<em>Tidak ada lomba yang sesuai atau sudah dipilih.</em>';
                }

                document.getElementById('listLomba').innerHTML = html;

                document.querySelectorAll('.check-lomba').forEach(cb => {
                    cb.addEventListener('change', hitungTotal);
                });

                hitungTotal();
            }

            function hitungTotal() {
                let checkboxes = document.querySelectorAll('.check-lomba:checked');
                let total = 0;

                checkboxes.forEach(cb => {
                    total += parseInt(cb.getAttribute('data-harga'));
                });

                if (document.getElementById('metode').value === 'bundling') {
                    if (checkboxes.length >= syaratBundling) {
                        // Harga bundling dulu
                        total = hargaBundling;

                        // Tambah harga untuk lomba lebih dari syarat bundling
                        if (checkboxes.length > syaratBundling) {
                            let hargaTambahan = 0;
                            checkboxes.forEach((cb, idx) => {
                                if (idx >= syaratBundling) {
                                    hargaTambahan += parseInt(cb.getAttribute('data-harga'));
                                }
                            });
                            total += hargaTambahan;
                        }
                    } else {
                        // kalau belum memenuhi syarat bundling, hitung normal
                        total = 0;
                        checkboxes.forEach(cb => {
                            total += parseInt(cb.getAttribute('data-harga'));
                        });
                    }
                } else {
                    // metode reguler → hitung normal
                    total = 0;
                    checkboxes.forEach(cb => {
                        total += parseInt(cb.getAttribute('data-harga'));
                    });
                }

                let totalHargaEl = document.getElementById('totalHarga');
                if (totalHargaEl) {
                    totalHargaEl.value = 'Rp ' + total.toLocaleString();
                }
            }
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
