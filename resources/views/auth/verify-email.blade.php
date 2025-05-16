<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification | Manajemen Renang</title>
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/js/all.min.js" crossorigin="anonymous"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-tr from-purple-500 via-indigo-500 to-blue-500 min-h-screen flex items-center justify-center font-[figtree]">

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 w-full max-w-md animate-fade-in-down">
        <div class="text-center mb-6">
            <i class="fas fa-envelope-open-text text-4xl text-indigo-500"></i>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white mt-2">Verifikasi Email</h2>
        </div>

        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
            Terima kasih telah mendaftar! Sebelum melanjutkan, silakan verifikasi email Anda melalui tautan yang kami kirim. Jika Anda belum menerima email tersebut, kami bisa mengirim ulang.
        </p>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 p-3 bg-green-100 dark:bg-green-700 text-green-800 dark:text-green-200 rounded-lg text-sm font-medium">
                Tautan verifikasi baru telah dikirim ke email yang Anda daftarkan.
            </div>
        @endif

        <div class="flex flex-col sm:flex-row justify-between gap-4 mt-6">
            <form method="POST" action="{{ route('verification.send') }}" class="w-full">
                @csrf
                <button type="submit"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-4 rounded-lg font-semibold shadow-md transition duration-300 ease-in-out transform hover:-translate-y-1">
                    <i class="fas fa-paper-plane mr-2"></i>Kirim Ulang Email Verifikasi
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit"
                    class="w-full text-sm text-gray-600 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 underline font-medium mt-2 sm:mt-0">
                    <i class="fas fa-sign-out-alt mr-1"></i>Keluar
                </button>
            </form>
        </div>
    </div>

    <style>
        @keyframes fade-in-down {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-fade-in-down {
            animation: fade-in-down 0.5s ease-out;
        }
    </style>
</body>
</html>
