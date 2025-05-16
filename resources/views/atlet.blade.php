<!-- filepath: c:\laragon\www\Tugas_Akhir\resources\views\index.blade.php -->
@extends('layouts.tamu')

@section('title', 'Atlet')

@section('content')
<div class="relative">
    <!-- Gambar -->
    <img src="{{ asset('gambar/banner.webp') }}" class="w-full h-64 object-cover" alt="Banner">
    <!-- Overlay -->
    <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <h1 class="text-white text-4xl font-bold">Atlet</h1>
    </div>
</div>

<div class="p-6 overflow-x-auto">
    <div class="mb-4">
        <input type="text" id="searchInput" placeholder="Cari berdasarkan nama..." 
            class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
    </div>
    <table class="w-full min-w-full divide-y divide-blue-200 dark:divide-blue-700">
        <thead class="bg-blue-500 dark:bg-blue-700">
            <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                    Nama
                    <button id="sortAsc" class="ml-2 text-white">▲</button>
                    <button id="sortDesc" class="text-white">▼</button>
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                    Jenis Kelamin
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                    Tanggal Lahir
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                    Asal Klub
                </th>  
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                    Limit
                </th>
            </tr>
        </thead>
        <tbody id="tableBody" class="bg-white divide-y divide-blue-200 dark:bg-white-800 dark:divide-blue-700">
            @if ($data->isEmpty())
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                        Tidak ada data atlet.
                    </td>
                </tr>
            @endif
            @foreach ($data as $item)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-black-900 dark:text-black-200">
                        {{ $item->nama_peserta }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-black-900 dark:text-black-200">
                        {{ $item->jenis_kelamin }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-black-900 dark:text-black-200">
                        {{ $item->tgl_lahir }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-black-900 dark:text-black-200">
                        {{ $item->asal_klub }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-black-900 dark:text-black-200">
                        {{ $item->limit }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<script>
    // JavaScript untuk fitur pencarian
    document.getElementById('searchInput').addEventListener('keyup', function () {
        const searchValue = this.value.toLowerCase();
        const tableRows = document.querySelectorAll('#tableBody tr');

        tableRows.forEach(row => {
            const nameCell = row.querySelector('td:first-child');
            const nameText = nameCell.textContent.toLowerCase();

            if (nameText.includes(searchValue)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // JavaScript untuk fitur sorting
    document.getElementById('sortAsc').addEventListener('click', function () {
        sortTable(true);
    });

    document.getElementById('sortDesc').addEventListener('click', function () {
        sortTable(false);
    });

    function sortTable(ascending) {
        const tableBody = document.getElementById('tableBody');
        const rows = Array.from(tableBody.querySelectorAll('tr'));

        rows.sort((a, b) => {
            const nameA = a.querySelector('td:first-child').textContent.toLowerCase();
            const nameB = b.querySelector('td:first-child').textContent.toLowerCase();

            if (ascending) {
                return nameA.localeCompare(nameB);
            } else {
                return nameB.localeCompare(nameA);
            }
        });

        rows.forEach(row => tableBody.appendChild(row));
    }
</script>
@endsection