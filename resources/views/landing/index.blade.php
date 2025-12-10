<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS System - Kelola Toko Lebih Mudah</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-white">
    <!-- Header -->
    <header class="border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 md:px-8 py-6 flex items-center justify-between">
            <div class="text-3xl font-bold text-blue-600">LOGO</div>
            <nav class="hidden md:flex items-center gap-8">
                <a href="#" class="text-gray-900 font-medium hover:text-blue-600 transition">Home</a>
                <a href="#" class="text-gray-600 hover:text-gray-900 transition">Layanan</a>
                <a href="#" class="text-gray-600 hover:text-gray-900 transition">Fitur</a>
            </nav>
            <div class="flex items-center gap-3">
                @if (Route::has('login'))
                    @auth
                        @php
                            $tenant = auth()->user()->outlets()->first();
                        @endphp

                        <a href="{{ route('filament.admin.pages.dashboard', ['tenant' => $tenant?->id]) }}"
                            class="px-6 py-2 border border-blue-600 text-blue-600 rounded-md hover:bg-gray-50 transition">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="px-6 py-2 border border-blue-600 text-blue-600 rounded-md hover:bg-gray-50 transition">
                            Login
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                                class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                                Sign up
                            </a>
                        @endif
                    @endauth
                    </nav>
                @endif
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="max-w-7xl mx-auto px-4 md:px-8 py-16 md:py-24">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div class="space-y-6">
                <h1 class="text-4xl md:text-5xl font-bold leading-tight">
                    Kelola Toko Lebih <span class="text-blue-600">Mudah</span>, Kasir Lebih <span
                        class="text-blue-600">Cepat</span>
                </h1>
                <p class="text-lg text-gray-600 leading-relaxed">
                    Atur stok, catat penjualan, dan pantau omzet setiap hari. Semua dalam satu aplikasi POS yang praktis
                    untuk toko dan cafe.
                </p>
                <button class="px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition font-medium">
                    Coba Sekarang Gratis
                </button>
            </div>
            <div class="relative">
                <div class="rounded-2xl overflow-hidden shadow-2xl">
                    <img src="{{ asset('assets/img/hero.jpg') }}" alt="POS System in action"
                        class="w-full h-auto object-cover">
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="bg-slate-50 py-16 md:py-24">
        <div class="max-w-7xl mx-auto px-4 md:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="flex flex-col items-center text-center gap-3 p-6 bg-white rounded-xl shadow-sm">
                    <div class="bg-blue-600 text-white p-3 rounded-xl flex items-center justify-center">
                        <img src="{{ asset('assets/icon/calculator.svg') }}" alt="Calc Icon" class="w-12 h-12">
                    </div>
                    <h2 class="text-base font-semibold text-gray-900">Kasir Cepat dan Mudah</h2>
                </div>
                <div class="flex flex-col items-center text-center gap-3 p-6 bg-white rounded-xl shadow-sm">
                    <div class="bg-blue-600 text-white p-3 rounded-xl flex items-center justify-center">
                        <img src="{{ asset('assets/icon/rectangle-stack.svg') }}" alt="Calc Icon" class="w-12 h-12">
                    </div>
                    <h2 class="text-base font-semibold text-gray-900">Atur Stok</h2>
                </div>
                <div class="flex flex-col items-center text-center gap-3 p-6 bg-white rounded-xl shadow-sm">
                    <div class="bg-blue-600 text-white p-3 rounded-xl flex items-center justify-center">
                        <img src="{{ asset('assets/icon/newspaper.svg') }}" alt="Calc Icon" class="w-12 h-12">
                    </div>
                    <h2 class="text-base font-semibold text-gray-900">Laporan Jelas</h2>
                </div>
                <div class="flex flex-col items-center text-center gap-3 p-6 bg-white rounded-xl shadow-sm">
                    <div class="bg-blue-600 text-white p-3 rounded-xl flex items-center justify-center">
                        <img src="{{ asset('assets/icon/device.svg') }}" alt="Calc Icon" class="w-12 h-12">
                    </div>
                    <h2 class="text-base font-semibold text-gray-900">Support Banyak Device (HP/Tablet/PC)</h2>
                </div>
            </div>
        </div>
    </section>

    <!-- Feature Preview Section -->
    <section class="max-w-7xl mx-auto px-4 md:px-8 py-16 md:py-24">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">Pratinjau Fitur</h2>
            <p class="text-gray-600 text-lg">Lihat bagaimana fitur-fitur kami bekerja</p>
        </div>

        <!-- Carousel container with relative positioning for controls -->
        <div class="relative">
            <div class="card overflow-hidden shadow-xl bg-gray-100 relative h-96">
                <!-- Carousel slides -->
                <div
                    class="carousel-slide active absolute w-full h-full transition-opacity duration-500 ease-in-out opacity-100">
                    <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=1200&h=400&fit=crop"
                        alt="Laporan Penjualan Dashboard" class="w-full h-full object-cover">
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent p-6">
                        <h3 class="text-white text-xl font-bold">Laporan Penjualan</h3>
                        <p class="text-white/80 text-sm">Analitik lengkap dan real-time untuk setiap transaksi</p>
                    </div>
                </div>

                <div
                    class="carousel-slide absolute w-full h-full transition-opacity duration-500 ease-in-out opacity-0">
                    <img src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=1200&h=400&fit=crop"
                        alt="Manajemen Stok" class="w-full h-full object-cover">
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent p-6">
                        <h3 class="text-white text-xl font-bold">Manajemen Stok</h3>
                        <p class="text-white/80 text-sm">Kelola inventori dengan mudah dan pantau stok secara real-time
                        </p>
                    </div>
                </div>

                <div
                    class="carousel-slide absolute w-full h-full transition-opacity duration-500 ease-in-out opacity-0">
                    <img src="https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=1200&h=400&fit=crop"
                        alt="Sistem Kasir" class="w-full h-full object-cover">
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent p-6">
                        <h3 class="text-white text-xl font-bold">Sistem Kasir Cepat</h3>
                        <p class="text-white/80 text-sm">Interface kasir intuitif untuk transaksi yang lebih cepat dan
                            akurat</p>
                    </div>
                </div>

                <div
                    class="carousel-slide absolute w-full h-full transition-opacity duration-500 ease-in-out opacity-0">
                    <img src="https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=1200&h=400&fit=crop"
                        alt="Multi Device Support" class="w-full h-full object-cover">
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent p-6">
                        <h3 class="text-white text-xl font-bold">Multi Device Support</h3>
                        <p class="text-white/80 text-sm">Akses dari mobile, tablet, atau desktop dengan sinkronisasi
                            otomatis</p>
                    </div>
                </div>

                <!-- Left Arrow Button -->
                <button onclick="changeSlide(-1)"
                    class="absolute left-4 top-1/2 -translate-y-1/2 z-10 bg-white/80 hover:bg-white text-blue-600 rounded-full p-3 transition shadow-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                <!-- Right Arrow Button -->
                <button onclick="changeSlide(1)"
                    class="absolute right-4 top-1/2 -translate-y-1/2 z-10 bg-white/80 hover:bg-white text-blue-600 rounded-full p-3 transition shadow-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>

            <!-- Carousel Indicators -->
            <div class="flex justify-center gap-2 mt-6">
                <button onclick="goToSlide(0)"
                    class="carousel-dot active w-3 h-3 rounded-full bg-blue-600 transition-all cursor-pointer"></button>
                <button onclick="goToSlide(1)"
                    class="carousel-dot w-3 h-3 rounded-full bg-gray-300 transition-all cursor-pointer hover:bg-gray-400"></button>
                <button onclick="goToSlide(2)"
                    class="carousel-dot w-3 h-3 rounded-full bg-gray-300 transition-all cursor-pointer hover:bg-gray-400"></button>
                <button onclick="goToSlide(3)"
                    class="carousel-dot w-3 h-3 rounded-full bg-gray-300 transition-all cursor-pointer hover:bg-gray-400"></button>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section class="bg-slate-50 py-16 md:py-24">
        <div class="max-w-7xl mx-auto px-4 md:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold mb-4">Layanan Kami</h2>
                <p class="text-gray-600 text-lg">Pilih paket yang sesuai kebutuhan bisnis Anda</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">

                <!-- Basic Plan -->
                <div
                    class="p-8 bg-white rounded-2xl shadow-md border flex flex-col justify-between hover:shadow-xl transition">
                    <div>
                        <h3 class="text-xl font-bold">Basic Plan</h3>
                        <p class="text-sm text-gray-600 mt-1 mb-6">Untuk toko kecil yang baru mulai</p>

                        <div class="mb-8">
                            <span class="text-4xl font-bold text-blue-600">Rp.79.000</span>
                            <span class="text-gray-600">/ Bulan</span>
                        </div>

                        <ul class="space-y-3 text-sm text-gray-700">
                            <li class="flex gap-2"><span class="text-blue-600 font-bold">✓</span>1 Outlet</li>
                            <li class="flex gap-2"><span class="text-blue-600 font-bold">✓</span>Maks 2 Perangkat</li>
                            <li class="flex gap-2"><span class="text-blue-600 font-bold">✓</span>Modul Kasir Lengkap
                            </li>
                            <li class="flex gap-2"><span class="text-blue-600 font-bold">✓</span>Manajemen Stok &
                                Harga
                            </li>
                            <li class="flex gap-2"><span class="text-blue-600 font-bold">✓</span>Laporan Harian</li>
                            <li class="flex gap-2"><span class="text-blue-600 font-bold">✓</span>Support Print Struk
                            </li>
                        </ul>
                    </div>

                    <button
                        class="mt-10 w-full py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition font-medium">
                        Mulai dengan Basic Plan
                    </button>
                </div>

                <!-- Advance Plan -->
                <div
                    class="p-8 bg-white rounded-2xl shadow-lg border-2 border-blue-600 flex flex-col justify-between scale-[1.02]">
                    <div>
                        <span
                            class="bg-blue-600 text-white text-xs font-bold px-3 py-1 rounded-full mb-4 inline-block">
                            RECOMMENDED
                        </span>
                        <h3 class="text-xl font-bold">Advance Plan</h3>
                        <p class="text-sm text-gray-600 mt-1 mb-6">Untuk toko dengan banyak transaksi</p>

                        <div class="mb-8">
                            <span class="text-4xl font-bold text-blue-600">Rp.204.000</span>
                            <span class="text-gray-600">/ Bulan</span>
                        </div>

                        <ul class="space-y-3 text-sm text-gray-700">
                            <li class="flex gap-2"><span class="text-blue-600 font-bold">✓</span>5 Outlet</li>
                            <li class="flex gap-2"><span class="text-blue-600 font-bold">✓</span>Maks 15 Perangkat
                            </li>
                            <li class="flex gap-2"><span class="text-blue-600 font-bold">✓</span>Modul Kasir Lengkap
                            </li>
                            <li class="flex gap-2"><span class="text-blue-600 font-bold">✓</span>Manajemen Stok &
                                Harga
                            </li>
                            <li class="flex gap-2"><span class="text-blue-600 font-bold">✓</span>Laporan Harian</li>
                            <li class="flex gap-2"><span class="text-blue-600 font-bold">✓</span>Support Print Struk &
                                Barcode</li>
                        </ul>
                    </div>

                    <button
                        class="mt-10 w-full py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition font-medium">
                        Mulai dengan Advance Plan
                    </button>
                </div>

                <!-- Pro Plan -->
                <div
                    class="p-8 bg-white rounded-2xl shadow-md border flex flex-col justify-between hover:shadow-xl transition">
                    <div>
                        <h3 class="text-xl font-bold">Pro Plan</h3>
                        <p class="text-sm text-gray-600 mt-1 mb-6">Untuk enterprise dan multi-outlet</p>

                        <div class="mb-8">
                            <span class="text-4xl font-bold text-blue-600">Rp.899.000</span>
                            <span class="text-gray-600">/ Bulan</span>
                        </div>

                        <ul class="space-y-3 text-sm text-gray-700">
                            <li class="flex gap-2"><span class="text-blue-600 font-bold">✓</span>Unlimited Outlet</li>
                            <li class="flex gap-2"><span class="text-blue-600 font-bold">✓</span>Unlimited Perangkat
                            </li>
                            <li class="flex gap-2"><span class="text-blue-600 font-bold">✓</span>Modul Kasir Lengkap
                            </li>
                            <li class="flex gap-2"><span class="text-blue-600 font-bold">✓</span>Multi-user (Kasir &
                                Admin)</li>
                            <li class="flex gap-2"><span class="text-blue-600 font-bold">✓</span>Laporan Profit &
                                Analisa Produk</li>
                            <li class="flex gap-2"><span class="text-blue-600 font-bold">✓</span>Support Print Struk &
                                Barcode</li>
                        </ul>
                    </div>

                    <button
                        class="mt-10 w-full py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition font-medium">
                        Mulai dengan Pro Plan
                    </button>
                </div>

            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-blue-600 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 md:px-8">
            <div class="grid md:grid-cols-4 gap-8 mb-12">
                <div>
                    <div class="text-3xl font-bold mb-2">LOGO</div>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Tautan Cepat</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:opacity-80 transition">Home</a></li>
                        <li><a href="#" class="hover:opacity-80 transition">Layanan Kami</a></li>
                        <li><a href="#" class="hover:opacity-80 transition">Pratinjau Fitur</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Hubungi Kami</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="mailto:support@test.com" class="hover:opacity-80 transition">support@test.com</a>
                        </li>
                        <li><a href="tel:+1234567890" class="hover:opacity-80 transition">+123.XXXX.XXXX</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Sosial Media</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:opacity-80 transition">Instagram</a></li>
                        <li><a href="#" class="hover:opacity-80 transition">Facebook</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-white/20 pt-8">
                <p class="text-sm text-center opacity-80">© 2025 Your Company. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>

</html>
