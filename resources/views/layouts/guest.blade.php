<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gradient-to-tr from-indigo-500 via-purple-500 to-pink-500 text-gray-100 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md px-6 py-8 bg-white dark:bg-gray-800 rounded-2xl shadow-xl transition-all duration-300">
        <div class="text-center mb-6">
            <a href="/" class="inline-block">
                <x-application-logo class="w-16 h-16 fill-current text-indigo-600 dark:text-gray-100" />
            </a>
            <h1 class="mt-4 text-2xl font-semibold text-gray-800 dark:text-white">Selamat Datang 👋</h1>
            <p class="text-sm text-gray-500 dark:text-gray-300">Silakan login untuk melanjutkan</p>
        </div>

        <!-- Slot for login form -->
        <div>
            {{ $slot }}
        </div>

        <p class="mt-6 text-center text-sm text-gray-500 dark:text-gray-300">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-indigo-600 hover:text-indigo-400 dark:text-indigo-400 hover:underline">Daftar di sini</a>
        </p>
    </div>
</body>
</html>
