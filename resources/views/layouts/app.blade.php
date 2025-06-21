<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans antialiased">
    <div class="flex h-screen overflow-hidden">

        <!-- Sidebar -->
        <aside id="sidebar" class="fixed z-40 inset-y-0 left-0 w-64 bg-white shadow-md transform -translate-x-full transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0">
            <div class="p-4 text-xl font-bold border-b">
                Kompetisi <span class="text-blue-600">Renang</span>
            </div>
            <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
                @if (auth()->check() && auth()->user()->role === 'klub')
                    <a href="{{ route('info.klub') }}" class="flex items-center p-2 text-gray-700 rounded hover:bg-gray-100">
                        <span class="material-icons mr-2">info</span> Info Klub
                    </a>
                    <a href="{{ route('add') }}" class="flex items-center p-2 text-gray-700 rounded hover:bg-gray-100">
                        <span class="material-icons mr-2">person_add</span> Tambah Peserta
                    </a>
                    <a href="{{ route('kompetisi.klub')}}" class="flex items-center p-2 text-gray-700 rounded hover:bg-gray-100">
                        <span class="material-icons mr-2">emoji_events</span> Info Kompetisi
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" class="flex items-center p-2 text-gray-700 rounded hover:bg-gray-100">
                        <span class="material-icons mr-2">dashboard</span> Dashboard
                    </a>
                    <a href="{{ route('list') }}" class="flex items-center p-2 text-gray-700 rounded hover:bg-gray-100">
                        <span class="material-icons mr-2">list</span> Daftar Peserta
                    </a>
                    <a href="{{ route('list_kompetisi') }}" class="flex items-center p-2 text-gray-700 rounded hover:bg-gray-100">
                        <span class="material-icons mr-2">emoji_events</span> Kompetisi
                    </a>
                    <a href="{{ route('klub') }}" class="flex items-center p-2 text-gray-700 rounded hover:bg-gray-100">
                        <span class="material-icons mr-2">group</span>Daftar Klub
                    </a>
                    <a href="{{ route('register_klub_form') }}" class="flex items-center p-2 text-gray-700 rounded hover:bg-gray-100">
                        <span class="material-icons mr-2">group_add</span> Registrasi Klub
                    </a>
                    <a href="{{ route('admin_hasil') }}" class="flex items-center p-2 text-gray-700 rounded hover:bg-gray-100">
                        <span class="material-icons mr-2">leaderboard</span> Hasil Kejuaraan
                    </a>
                @endif
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0">

            <!-- Topbar -->
            <header class="bg-white shadow flex items-center justify-between px-4 py-3 lg:px-6">
                <!-- Sidebar Toggle Button -->
                <button id="sidebarToggle" class="lg:hidden text-gray-700 hover:text-gray-900 focus:outline-none">
                    <span class="material-icons">menu</span>
                </button>
                <h1 class="text-lg md:text-xl font-bold text-gray-800 truncate">@yield('title')</h1>

                <!-- Profile Dropdown -->
                <div class="relative">
                    <button id="profileMenuButton" class="flex items-center space-x-2 text-gray-700 hover:text-gray-900 focus:outline-none">
                        <span class="hidden sm:inline">{{ auth()->user()->name }}</span>
                        <span class="material-icons">arrow_drop_down</span>
                    </button>
                    <div id="profileMenu" class="hidden absolute right-0 mt-2 w-48 bg-white shadow-lg rounded-md z-50">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Edit Profile</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-100">Logout</button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-4 md:p-6 overflow-auto">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        const profileMenuButton = document.getElementById('profileMenuButton');
        const profileMenu = document.getElementById('profileMenu');

        // Toggle Sidebar
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
        });

        // Toggle Profile Menu
        profileMenuButton.addEventListener('click', (e) => {
            e.stopPropagation();
            profileMenu.classList.toggle('hidden');
        });

        // Close dropdowns if clicked outside
        document.addEventListener('click', (event) => {
            if (!profileMenu.contains(event.target) && !profileMenuButton.contains(event.target)) {
                profileMenu.classList.add('hidden');
            }
        });

        // Auto-close sidebar when clicking outside on mobile
        document.addEventListener('click', function (event) {
            if (
                window.innerWidth < 1024 &&
                !sidebar.contains(event.target) &&
                !sidebarToggle.contains(event.target)
            ) {
                sidebar.classList.add('-translate-x-full');
            }
        });
    </script>
</body>
</html>
