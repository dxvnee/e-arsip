@extends('layouts.main')

@section('title', 'Login - Sistem E-Arsip Dinkes')

@push('styles')
@vite('resources/css/login.css')
@endpush

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="login-container w-full max-w-md">
            <!-- Logo & Title -->
            <div class="text-center mb-8">
                <!-- Logo Container -->
                <div class="inline-block relative mb-6">
                    <!-- Glow Effect Background -->
                    <div class="absolute inset-0 rounded-full opacity-20 blur-2xl" 
                         style="background: radial-gradient(circle, #efd856 0%, transparent 70%);"></div>
                    
                    <!-- Main Logo Circle -->
                    <div class="relative logo-pulse">
                        <div class="w-28 h-28 rounded-full flex items-center justify-center logo-glow shadow-2xl mx-auto"
                             style="background: linear-gradient(135deg, #efd856 0%, #f4e07d 50%, #efd856 100%);">
                            <!-- Inner Circle -->
                            <div class="w-24 h-24 rounded-full flex items-center justify-center"
                                 style="background: linear-gradient(135deg, rgba(255,255,255,0.3) 0%, rgba(255,255,255,0.1) 100%);">
                                <!-- Icon -->
                                <div class="relative">
                                    <i class="fas fa-archive text-5xl" style="color: #008e3c;"></i>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Security Badge -->
                        <div class="absolute -bottom-1 -right-1 w-11 h-11 rounded-full flex items-center justify-center shadow-xl"
                             style="background: linear-gradient(135deg, #008e3c 0%, #006b2e 100%);">
                            <i class="fas fa-shield-alt text-white text-lg"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Title -->
                <h1 class="text-3xl font-bold text-white mb-3">
                    Sistem E-Arsip
                </h1>
                
                <!-- Subtitle with Divider -->
                <div class="flex items-center justify-center space-x-3 mb-2">
                    <div class="w-12 h-0.5 rounded" style="background-color: #efd856;"></div>
                    <p class="text-white text-base font-semibold tracking-wide">
                        DINAS KESEHATAN
                    </p>
                    <div class="w-12 h-0.5 rounded" style="background-color: #efd856;"></div>
                </div>
                
                <!-- Description -->
                <p class="text-gray-200 text-sm">
                    Sistem Informasi Manajemen Arsip Digital
                </p>
            </div>
            
            <!-- Login Card -->
            <div class="bg-white rounded-2xl shadow-2xl p-8">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">
                        Selamat Datang! 👋
                    </h2>
                    <p class="text-gray-600 text-sm">
                        Silakan login untuk melanjutkan ke dashboard
                    </p>
                </div>
                
                <!-- Session Status -->
                @if (session('status'))
                    <div class="alert mb-4 p-4 rounded-lg bg-green-50 border border-green-200">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-600 mr-2"></i>
                            <span class="text-sm text-green-800">{{ session('status') }}</span>
                        </div>
                    </div>
                @endif
                
                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="alert mb-4 p-4 rounded-lg bg-red-50 border border-red-200">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-circle text-red-600 mr-2 mt-0.5"></i>
                            <div class="flex-1">
                                @foreach ($errors->all() as $error)
                                    <p class="text-sm text-red-800">{{ $error }}</p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
                
                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" id="loginForm">
                    @csrf
                    
                    <!-- Email -->
                    <div class="mb-5">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email Address
                        </label>
                        <div class="input-group">
                            <div class="input-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <input 
                                type="email" 
                                id="email" 
                                name="email"
                                value="{{ old('email') }}"
                                class="input-field w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition @error('email') border-red-500 @enderror"
                                placeholder="nama@email.com"
                                required 
                                autofocus
                                autocomplete="username">
                        </div>
                    </div>
                    
                    <!-- Password -->
                    <div class="mb-5">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Password
                        </label>
                        <div class="input-group">
                            <div class="input-icon">
                                <i class="fas fa-lock"></i>
                            </div>
                            <input 
                                type="password" 
                                id="password" 
                                name="password"
                                class="input-field w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition @error('password') border-red-500 @enderror"
                                placeholder="••••••••"
                                required
                                autocomplete="current-password">
                            <div class="absolute right-3 top-1/2 transform -translate-y-1/2 password-toggle" onclick="togglePassword()">
                                <i class="fas fa-eye text-gray-500" id="toggleIcon"></i>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between mb-6">
                        <label class="flex items-center cursor-pointer">
                            <input 
                                type="checkbox" 
                                id="remember_me" 
                                name="remember"
                                class="w-4 h-4 rounded border-gray-300 focus:ring-2 focus:ring-green-500"
                                style="color: #008e3c;">
                            <span class="ml-2 text-sm text-gray-600">Ingat Saya</span>
                        </label>
                        
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm font-medium hover:underline"
                               style="color: #008e3c;">
                                Lupa Password?
                            </a>
                        @endif
                    </div>
                    
                    <!-- Login Button -->
                    <button 
                        type="submit" 
                        class="btn-login w-full py-3 px-4 rounded-lg text-white font-semibold shadow-lg mb-4">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Login ke Dashboard
                    </button>
                    
                    <!-- Register Link -->
                    @if (Route::has('register'))
                        <div class="text-center">
                            <span class="text-sm text-gray-600">Belum punya akun? </span>
                            <a href="{{ route('register') }}" class="text-sm font-medium hover:underline"
                               style="color: #008e3c;">
                                Daftar Sekarang
                            </a>
                        </div>
                    @endif
                </form>
            </div>
            
            <!-- Footer -->
            <div class="text-center mt-6">
                <p class="text-sm text-gray-200">
                    &copy; {{ date('Y') }} Dinas Kesehatan. All rights reserved.
                </p>
                <p class="text-xs text-gray-300 mt-1">
                    Version 1.0.0
                </p>
            </div>
        </div>
    </div>
    
@endsection

@push('scripts')
@vite('resources/js/login.js')
@endpush