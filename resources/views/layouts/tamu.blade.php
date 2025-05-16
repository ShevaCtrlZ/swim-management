<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Manajemen Renang')</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 text-gray-800">
    <!-- Navbar -->
    <nav class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white shadow-md">
        <div class="container mx-auto px-4 flex justify-between items-center h-16">
            <!-- Logo -->
            <a class="flex items-center text-white text-xl font-bold" href="#">
                <i class="fa-solid fa-person-swimming mr-2"></i> Renang
            </a>

            <!-- Hamburger -->
            <button class="lg:hidden text-white focus:outline-none" id="hamburgerButton">
                <i class="fas fa-bars text-xl"></i>
            </button>

            <!-- Menu (desktop) -->
            <div class="hidden lg:flex space-x-6" id="navbarNav">
                <a class="text-white hover:text-gray-300" href="{{ route('index') }}">Beranda</a>
                <a class="text-white hover:text-gray-300" href="{{ route('kompetisi') }}">Kompetisi</a>
                <a class="text-white hover:text-gray-300" href="{{ route('hasil') }}">Hasil Kejuaraan</a>
                <a class="text-white hover:text-gray-300" href="{{ route('atlet') }}">Atlet</a> 
            </div>

            <!-- User Dropdown -->
            <div class="relative z-50">
                <button class="text-white hover:text-gray-300 focus:outline-none" id="userDropdown">
                    <i class="fas fa-user text-xl"></i>
                </button>
                <ul class="hidden absolute right-0 mt-2 w-48 bg-white text-gray-800 rounded-lg shadow-lg" id="userDropdownMenu">
                    <li>
                        <a class="block px-4 py-2 hover:bg-gray-100" href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt mr-2"></i> Login
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Menu Mobile -->
        <div class="hidden lg:hidden transition-all duration-300 ease-in-out" id="mobileMenu">
            <ul class="space-y-2 p-4 bg-blue-600">
                <li><a class="block text-white hover:text-gray-300" href="{{ route('index') }}">Beranda</a></li>
                <li><a class="block text-white hover:text-gray-300" href="{{ route('kompetisi') }}">Kompetisi</a></li>
                <li><a class="block text-white hover:text-gray-300" href="{{ route('hasil') }}">Jadwal</a></li>
                <li><a class="block text-white hover:text-gray-300" href="{{ route('atlet') }}">Atlet</a></li>
                <li><a class="block text-white hover:text-gray-300" href="{{ route('more') }}">More</a></li>
            </ul>
        </div>
    </nav>

    <!-- Content -->
    @yield('content')

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-lg font-bold mb-4">Tentang Kami</h3>
                    <p class="text-sm text-gray-400">Manajemen Renang adalah platform untuk mengelola kompetisi renang...</p>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-4">Ikuti Kami</h3>
                    <ul class="flex space-x-4">
                        <li><a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-facebook-f"></i></a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-twitter"></i></a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-instagram"></i></a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-youtube"></i></a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-4">Kontak</h3>
                    <ul class="text-sm text-gray-400">
                        <li>Email: info@manajemenrenang.com</li>
                        <li>Telepon: +62 812-3456-7890</li>
                        <li>Alamat: Jl. Renang No. 123, Jakarta</li>
                    </ul>
                </div>
            </div>
            <div class="mt-8 text-center text-sm text-gray-400">&copy; 2025 Manajemen Renang. Semua Hak Dilindungi.</div>
        </div>
    </footer>

    <!-- Script -->
    <script>
        document.getElementById('hamburgerButton').addEventListener('click', () => {
            document.getElementById('mobileMenu').classList.toggle('hidden');
        });

        document.getElementById('userDropdown').addEventListener('click', () => {
            document.getElementById('userDropdownMenu').classList.toggle('hidden');
        });

        // Optional: Tutup dropdown saat klik di luar
        document.addEventListener('click', function (event) {
            const dropdown = document.getElementById('userDropdownMenu');
            const button = document.getElementById('userDropdown');
            if (!button.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        });
    </script>
</body>

</html>
