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
             class="fixed inset-y-0 left-0 z-30 w-64 bg-primary transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0">
            
            <!-- Logo -->
            <div class="flex items-center justify-center h-16 bg-primary-dark">
                <div class="flex items-center">
                    <i class="fas fa-archive text-white text-2xl mr-2"></i>
                    <span class="text-white text-xl font-bold">E-Arsip Dinkes</span>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="mt-5 px-2">
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}" 
                   class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('dashboard') ? 'bg-primary-dark text-white' : 'text-gray-100 hover:bg-primary-dark hover:text-white' }}">
                    <i class="fas fa-tachometer-alt mr-3 text-gray-300 group-hover:text-white"></i>
                    Dashboard
                </a>

                @if(auth()->user()->role !== 'viewer')
                <!-- Arsip -->
                <div x-data="{ open: {{ request()->routeIs('arsip.*') ? 'true' : 'false' }} }" class="mt-1">
                    <button @click="open = !open" 
                            class="group w-full flex items-center px-2 py-2 text-sm font-medium rounded-md text-gray-100 hover:bg-primary-dark hover:text-white">
                        <i class="fas fa-file-alt mr-3 text-gray-300 group-hover:text-white"></i>
                        <span class="flex-1 text-left">Arsip</span>
                        <i class="fas fa-chevron-down transition-transform duration-200" :class="open ? 'transform rotate-180' : ''"></i>
                    </button>
                    <div x-show="open" x-transition class="mt-1 space-y-1">
                        <a href="{{ route('arsip.index') }}" 
                           class="group flex items-center pl-9 pr-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('arsip.index') ? 'bg-primary-dark text-white' : 'text-gray-200 hover:bg-primary-dark hover:text-white' }}">
                            Daftar Arsip
                        </a>
                        <a href="{{ route('arsip.create') }}" 
                           class="group flex items-center pl-9 pr-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('arsip.create') ? 'bg-primary-dark text-white' : 'text-gray-200 hover:bg-primary-dark hover:text-white' }}">
                            Tambah Arsip
                        </a>
                    </div>
                </div>
                @else
                <!-- View Only Arsip for Viewer -->
                <a href="{{ route('arsip.index') }}" 
                   class="mt-1 group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('arsip.*') ? 'bg-primary-dark text-white' : 'text-gray-100 hover:bg-primary-dark hover:text-white' }}">
                    <i class="fas fa-file-alt mr-3 text-gray-300 group-hover:text-white"></i>
                    Daftar Arsip
                </a>
                @endif

                @if(auth()->user()->role === 'admin')
                <!-- Master Data -->
                <div x-data="{ open: {{ request()->routeIs('kategori.*') || request()->routeIs('unit-kerja.*') ? 'true' : 'false' }} }" class="mt-1">
                    <button @click="open = !open" 
                            class="group w-full flex items-center px-2 py-2 text-sm font-medium rounded-md text-gray-100 hover:bg-primary-dark hover:text-white">
                        <i class="fas fa-database mr-3 text-gray-300 group-hover:text-white"></i>
                        <span class="flex-1 text-left">Master Data</span>
                        <i class="fas fa-chevron-down transition-transform duration-200" :class="open ? 'transform rotate-180' : ''"></i>
                    </button>
                    <div x-show="open" x-transition class="mt-1 space-y-1">
                        <a href="{{ route('kategori.index') }}" 
                           class="group flex items-center pl-9 pr-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('kategori.*') ? 'bg-primary-dark text-white' : 'text-gray-200 hover:bg-primary-dark hover:text-white' }}">
                            Kategori Arsip
                        </a>
                        <a href="{{ route('unit-kerja.index') }}" 
                           class="group flex items-center pl-9 pr-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('unit-kerja.*') ? 'bg-primary-dark text-white' : 'text-gray-200 hover:bg-primary-dark hover:text-white' }}">
                            Unit Kerja
                        </a>
                    </div>
                </div>

                <!-- User Management -->
                <a href="{{ route('users.index') }}" 
                   class="mt-1 group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('users.*') ? 'bg-primary-dark text-white' : 'text-gray-100 hover:bg-primary-dark hover:text-white' }}">
                    <i class="fas fa-users mr-3 text-gray-300 group-hover:text-white"></i>
                    Pengguna
                </a>
                @endif

                <!-- Laporan -->
                <a href="{{ route('laporan.index') }}" 
                   class="mt-1 group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('laporan.*') ? 'bg-primary-dark text-white' : 'text-gray-100 hover:bg-primary-dark hover:text-white' }}">
                    <i class="fas fa-chart-bar mr-3 text-gray-300 group-hover:text-white"></i>
                    Laporan
                </a>
            </nav>

            <!-- User Info -->
            <div class="absolute bottom-0 w-full px-4 py-4 bg-primary-dark">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-secondary rounded-full flex items-center justify-center">
                            <span class="text-primary font-bold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-white">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-300 capitalize">{{ auth()->user()->role }}</p>
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
                            <button @click="open = !open" class="flex items-center text-gray-500 hover:text-gray-700 focus:outline-none">
                                <span class="mr-2 text-sm font-medium">{{ auth()->user()->name }}</span>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>
                            <div x-show="open" @click.away="open = false" x-transition
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user mr-2"></i> Profil
                                </a>
                                <form method="POST" action="{{ route('logout') }}" class="block">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
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

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
