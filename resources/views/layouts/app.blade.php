<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'E-Arsip Dinkes') }} - @yield('title', 'Dashboard')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js -->
    <script src="//unpkg.com/alpinejs" defer></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @stack('styles')
</head>

<body class="font-sans antialiased bg-gray-50">


    <div x-data="{ sidebarOpen: false }" class="flex h-screen">
        <!-- Sidebar -->
        <div :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-30 w-64 transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0 shadow-xl"
            style="background-color: #008e3c;">

            <!-- Logo -->
            <div class="flex items-center justify-between h-20 px-4 shadow-lg" style="background-color: #006b2d;">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center"
                        style="background-color: #efd856;">
                        <i class="fas fa-archive text-2xl" style="color: #008e3c;"></i>
                    </div>
                    <div>
                        <div class="text-white text-lg font-bold">E-Arsip</div>
                        <div class="text-xs" style="color: #efd856;">Dinas Kesehatan</div>
                    </div>
                </div>
                <!-- Close Button (Mobile) -->
                <button @click="sidebarOpen = false" class="text-white focus:outline-none lg:hidden">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="mt-8 px-4 space-y-2">
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}"
                    class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200"
                    style="{{ request()->routeIs('dashboard') ? 'background-color: #006b2d; color: white; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);' : 'color: #e5e7eb;' }}"
                    onmouseover="if (!this.style.backgroundColor || this.style.backgroundColor === 'rgb(229, 231, 235)') { this.style.backgroundColor='rgba(255, 255, 255, 0.1)'; }"
                    onmouseout="if (this.style.backgroundColor === 'rgba(255, 255, 255, 0.1)') { this.style.backgroundColor=''; }">
                    <i class="fas fa-tachometer-alt mr-3 text-lg" style="color: #efd856;"></i>
                    <span>Dashboard</span>
                </a>

                <!-- Berkas Arsip -->
                <a href="{{ route('berkas-arsip.index') }}"
                    class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200"
                    style="{{ request()->routeIs('berkas-arsip.*') ? 'background-color: #006b2d; color: white; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);' : 'color: #e5e7eb;' }}"
                    onmouseover="if (!this.style.backgroundColor || this.style.backgroundColor === 'rgb(229, 231, 235)') { this.style.backgroundColor='rgba(255, 255, 255, 0.1)'; }"
                    onmouseout="if (this.style.backgroundColor === 'rgba(255, 255, 255, 0.1)') { this.style.backgroundColor=''; }">
                    <i class="fas fa-folder-open mr-3 text-lg" style="color: #efd856;"></i>
                    <span>Berkas Arsip</span>
                </a>

                @if(Route::has('arsip.index'))
                    @if(auth()->user()->role !== 'viewer')
                        <!-- Arsip -->
                        <div x-data="{ open: {{ request()->routeIs('arsip.*') ? 'true' : 'false' }} }">
                            <button @click="open = !open"
                                class="group w-full flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200"
                                style="color: #e5e7eb;" onmouseover="this.style.backgroundColor='rgba(255, 255, 255, 0.1)'"
                                onmouseout="this.style.backgroundColor=''">
                                <i class="fas fa-file-alt mr-3 text-lg" style="color: #efd856;"></i>
                                <span class="flex-1 text-left">Arsip</span>
                                <i class="fas fa-chevron-down transition-transform duration-200 text-xs"
                                    :class="open ? 'transform rotate-180' : ''"></i>
                            </button>
                            <div x-show="open" x-transition class="mt-2 ml-4 space-y-1">
                                <a href="{{ route('arsip.index') }}"
                                    class="flex items-center pl-8 pr-4 py-2.5 text-sm rounded-lg transition-all duration-200"
                                    style="{{ request()->routeIs('arsip.index') ? 'background-color: #006b2d; color: white;' : 'color: #d1d5db;' }}"
                                    onmouseover="if (!this.style.backgroundColor || this.style.backgroundColor === 'rgb(209, 213, 219)') { this.style.backgroundColor='rgba(255, 255, 255, 0.05)'; }"
                                    onmouseout="if (this.style.backgroundColor === 'rgba(255, 255, 255, 0.05)') { this.style.backgroundColor=''; }">
                                    <i class="fas fa-list mr-2 text-xs" style="color: #efd856;"></i>
                                    Daftar Arsip
                                </a>
                                <a href="{{ route('arsip.create') }}"
                                    class="flex items-center pl-8 pr-4 py-2.5 text-sm rounded-lg transition-all duration-200"
                                    style="{{ request()->routeIs('arsip.create') ? 'background-color: #007b2d; color: white;' : 'color: #d1d5db;' }}"
                                    onmouseover="if (!this.style.backgroundColor || this.style.backgroundColor === 'rgb(209, 213, 219)') { this.style.backgroundColor='rgba(255, 255, 255, 0.05)'; }"
                                    onmouseout="if (this.style.backgroundColor === 'rgba(255, 255, 255, 0.05)') { this.style.backgroundColor=''; }">
                                    <i class="fas fa-plus mr-2 text-xs" style="color: #efd856;"></i>
                                    Tambah Arsip
                                </a>
                            </div>
                        </div>
                    @else
                        <!-- View Only Arsip for Viewer -->
                        <a href="{{ route('arsip.index') }}"
                            class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200"
                            style="{{ request()->routeIs('arsip.*') ? 'background-color: #006b2d; color: white; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);' : 'color: #e5e7eb;' }}"
                            onmouseover="if (!this.style.backgroundColor || this.style.backgroundColor === 'rgb(229, 231, 235)') { this.style.backgroundColor='rgba(255, 255, 255, 0.1)'; }"
                            onmouseout="if (this.style.backgroundColor === 'rgba(255, 255, 255, 0.1)') { this.style.backgroundColor=''; }">
                            <i class="fas fa-file-alt mr-3 text-lg" style="color: #efd856;"></i>
                            <span>Daftar Arsip</span>
                        </a>
                    @endif
                @endif

                <!-- Master Data - Klasifikasi Arsip (Admin Only) -->
                @if(auth()->user()->role === 'admin')
                    <div
                        x-data="{ open: {{ request()->routeIs('klasifikasi-arsip.*') || request()->routeIs('lokasi-arsip.*') ? 'true' : 'false' }} }">
                        <button @click="open = !open"
                            class="group w-full flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200"
                            style="color: #e5e7eb;" onmouseover="this.style.backgroundColor='rgba(255, 255, 255, 0.1)'"
                            onmouseout="this.style.backgroundColor=''">
                            <i class="fas fa-database mr-3 text-lg" style="color: #efd856;"></i>
                            <span class="flex-1 text-left">Master Data</span>
                            <i class="fas fa-chevron-down transition-transform duration-200 text-xs"
                                :class="open ? 'transform rotate-180' : ''"></i>
                        </button>
                        <div x-show="open" x-transition class="mt-2 ml-4 space-y-1">
                            <a href="{{ route('klasifikasi-arsip.index') }}"
                                class="flex items-center pl-8 pr-4 py-2.5 text-sm rounded-lg transition-all duration-200"
                                style="{{ request()->routeIs('klasifikasi-arsip.*') ? 'background-color: #006b2d; color: white;' : 'color: #d1d5db;' }}"
                                onmouseover="if (!this.style.backgroundColor || this.style.backgroundColor === 'rgb(209, 213, 219)') { this.style.backgroundColor='rgba(255, 255, 255, 0.05)'; }"
                                onmouseout="if (this.style.backgroundColor === 'rgba(255, 255, 255, 0.05)') { this.style.backgroundColor=''; }">
                                <i class="fas fa-folder-tree mr-2 text-xs" style="color: #efd856;"></i>
                                Kode Klasifikasi
                            </a>
                            <a href="{{ route('lokasi-arsip.index') }}"
                                class="flex items-center pl-8 pr-4 py-2.5 text-sm rounded-lg transition-all duration-200"
                                style="{{ request()->routeIs('lokasi-arsip.*') ? 'background-color: #006b2d; color: white;' : 'color: #d1d5db;' }}"
                                onmouseover="if (!this.style.backgroundColor || this.style.backgroundColor === 'rgb(209, 213, 219)') { this.style.backgroundColor='rgba(255, 255, 255, 0.05)'; }"
                                onmouseout="if (this.style.backgroundColor === 'rgba(255, 255, 255, 0.05)') { this.style.backgroundColor=''; }">
                                <i class="fas fa-map-marker-alt mr-2 text-xs" style="color: #efd856;"></i>
                                Lokasi Arsip
                            </a>
                        </div>
                    </div>
                @endif

                <!-- Laporan -->
                @if(Route::has('laporan.index'))
                    <a href="{{ route('laporan.index') }}"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200"
                        style="{{ request()->routeIs('laporan.*') ? 'background-color: #006b2d; color: white; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);' : 'color: #e5e7eb;' }}"
                        onmouseover="if (!this.style.backgroundColor || this.style.backgroundColor === 'rgb(229, 231, 235)') { this.style.backgroundColor='rgba(255, 255, 255, 0.1)'; }"
                        onmouseout="if (this.style.backgroundColor === 'rgba(255, 255, 255, 0.1)') { this.style.backgroundColor=''; }">
                        <i class="fas fa-chart-bar mr-3 text-lg" style="color: #efd856;"></i>
                        <span>Laporan</span>
                    </a>
                @endif
            </nav>

            <!-- User Info -->
            <div class="absolute bottom-0 w-full px-4 py-4"
                style="background-color: #006b2d; border-top: 2px solid #efd856;">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <div class="w-11 h-11 rounded-full flex items-center justify-center shadow-lg"
                            style="background-color: #efd856;">
                            <span class="font-bold text-lg"
                                style="color: #008e3c;">{{ substr(auth()->user()->name, 0, 1) }}</span>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs capitalize truncate" style="color: #efd856;">{{ auth()->user()->role }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navigation -->
            <header class="bg-white shadow-sm">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center">
                        <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 focus:outline-none lg:hidden">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <h1 class="text-xl font-semibold text-gray-800 ml-4 lg:ml-0">@yield('title', 'Dashboard')</h1>
                    </div>

                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                                <i class="fas fa-bell text-xl"></i>
                                <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-400"></span>
                            </button>
                            <div x-show="open" @click.away="open = false" x-transition
                                class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg py-2 z-50">
                                <div class="px-4 py-2 border-b">
                                    <h3 class="text-sm font-semibold text-gray-800">Notifikasi</h3>
                                </div>
                                <div class="px-4 py-2">
                                    <p class="text-sm text-gray-600">Tidak ada notifikasi baru</p>
                                </div>
                            </div>
                        </div>

                        <!-- User Menu -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open"
                                class="flex items-center text-gray-500 hover:text-gray-700 focus:outline-none">
                                <span class="mr-2 text-sm font-medium">{{ auth()->user()->name }}</span>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>
                            <div x-show="open" @click.away="open = false" x-transition
                                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50">
                                <a href="{{ route('profile.edit') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user mr-2"></i> Profil
                                </a>
                                <form method="POST" action="{{ route('logout') }}" class="block">
                                    @csrf
                                    <button type="submit"
                                        class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50">
                <div class="container mx-auto px-6 py-8">
                    <!-- Flash Messages -->
                    @if(session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                            <p class="font-bold">Berhasil!</p>
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                            <p class="font-bold">Error!</p>
                            <p>{{ session('error') }}</p>
                        </div>
                    @endif

                    <div class="page-enter min-h-screen">
                        @yield('content')
                    </div>
                </div>
            </main>
        </div>
    </div>

    @stack('scripts')
</body>

</html>