<!-- filepath: c:\laragon\www\Tugas_Akhir\resources\views\index.blade.php -->
@extends('layouts.tamu')

@section('title', 'Beranda')

@section('content')
<div class="relative">
    <!-- Gambar -->
    <img src="{{ asset('gambar/banner.webp') }}" class="w-full h-64 object-cover" alt="Banner">
    <!-- Overlay -->
    <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <h1 class="text-white text-4xl font-bold">Beranda</h1>
    </div>
</div>

<!-- Section: Tentang Berenang -->
<div class="py-12 bg-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Tentang Berenang</h2>
        <p class="text-gray-600 leading-relaxed">
            Berenang adalah salah satu olahraga yang menyehatkan tubuh dan menyegarkan pikiran. Selain itu, berenang juga merupakan salah satu cabang olahraga yang dipertandingkan dalam berbagai kompetisi, mulai dari tingkat lokal hingga internasional. Dengan berbagai gaya seperti gaya bebas, gaya dada, gaya punggung, dan gaya kupu-kupu, berenang menjadi olahraga yang menarik untuk diikuti dan ditonton.
        </p>
    </div>
</div>

<!-- Section: Kompetisi yang Sedang Berlangsung -->
<div class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Kompetisi yang Sedang Berlangsung</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($kompetisi as $k)
                <div class="bg-white border border-gray-200 rounded-lg shadow-md overflow-hidden">
                    <div class="p-4">
                        <h3 class="text-lg font-bold text-gray-800">{{ $k->nama_kompetisi }}</h3>
                        <p class="text-sm text-gray-600 mt-2">Tanggal: {{ $k->tgl_mulai }} - {{ $k->tgl_selesai }}</p>
                        <p class="text-sm text-gray-600">Lokasi: {{ $k->lokasi }}</p>
                        <a href="{{ route('user_kompetisi', $k->id) }}" class="mt-4 inline-block px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg shadow-md hover:bg-blue-700">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Section: Carousel -->
<div class="py-12 bg-gray-100">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-4 text-center">Galeri</h2>
        <div id="carouselExample" class="relative" data-te-carousel-init data-te-carousel-slide>
            <!-- Carousel Wrapper -->
            <div class="relative w-full overflow-hidden rounded-lg shadow-md">
                <!-- Slides -->
                <div class="block duration-700 ease-in-out" data-te-carousel-item>
                    <img src="{{ asset('gambar/slide1.jpg') }}" class="block w-full h-48 object-cover" alt="Slide 1">
                </div>
                <div class="hidden duration-700 ease-in-out" data-te-carousel-item>
                    <img src="{{ asset('gambar/slide2.jpg') }}" class="block w-full h-48 object-cover" alt="Slide 2">
                </div>
                <div class="hidden duration-700 ease-in-out" data-te-carousel-item>
                    <img src="{{ asset('gambar/slide3.webp') }}" class="block w-full h-48 object-cover" alt="Slide 3">
                </div>
            </div>
            <!-- Controls -->
            <button class="absolute top-0 bottom-0 left-0 z-30 flex items-center justify-center px-4 cursor-pointer group focus:outline-none" data-te-target="#carouselExample" data-te-slide="prev">
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-black/30 group-hover:bg-black/50 group-focus:ring-2 group-focus:ring-white group-focus:outline-none">
                    <svg aria-hidden="true" class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15 19l-7-7 7-7"></path>
                    </svg>
                </span>
            </button>
            <button class="absolute top-0 bottom-0 right-0 z-30 flex items-center justify-center px-4 cursor-pointer group focus:outline-none" data-te-target="#carouselExample" data-te-slide="next">
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-black/30 group-hover:bg-black/50 group-focus:ring-2 group-focus:ring-white group-focus:outline-none">
                    <svg aria-hidden="true" class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 5l7 7-7 7"></path>
                    </svg>
                </span>
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const slides = document.querySelectorAll('[data-te-carousel-item]');
        let currentIndex = 0;

        function showSlide(index) {
            slides.forEach((slide, i) => {
                slide.classList.toggle('block', i === index);
                slide.classList.toggle('hidden', i !== index);
            });
        }

        // Show the first slide initially
        showSlide(currentIndex);

        // Add event listeners for controls
        document.querySelector('[data-te-slide="prev"]').addEventListener('click', function () {
            currentIndex = (currentIndex - 1 + slides.length) % slides.length;
            showSlide(currentIndex);
        });

        document.querySelector('[data-te-slide="next"]').addEventListener('click', function () {
            currentIndex = (currentIndex + 1) % slides.length;
            showSlide(currentIndex);
        });
    });
</script>
@endsection