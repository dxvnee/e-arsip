@extends('layouts.main')

@section('title', 'Register - Sistem E-Arsip Dinkes')

@push('styles') 
@vite('resources/css/register.css')
@endpush

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="register-container w-full max-w-2xl">
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
            
            <!-- Register Card -->
            <div class="bg-white rounded-2xl shadow-2xl p-8">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">
                        Buat Akun Baru 📝
                    </h2>
                    <p class="text-gray-600 text-sm">
                        Lengkapi form di bawah untuk mendaftar
                    </p>
                </div>
                
                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="alert mb-6 p-4 rounded-lg bg-red-50 border border-red-200">
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
                
                <!-- Register Form -->
                <form method="POST" action="{{ route('register') }}" id="registerForm">
                    @csrf
                    
                    <!-- Name -->
                    <div class="mb-5">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <div class="input-group">
                            <div class="input-icon">
                                <i class="fas fa-user"></i>
                            </div>
                            <input 
                                type="text" 
                                id="name" 
                                name="name"
                                value="{{ old('name') }}"
                                class="input-field w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition @error('name') border-red-500 @enderror"
                                placeholder="Masukkan nama lengkap"
                                required 
                                autofocus
                                autocomplete="name">
                        </div>
                    </div>
                    
                    <!-- Email -->
                    <div class="mb-5">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email Address <span class="text-red-500">*</span>
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
                                autocomplete="username">
                        </div>
                    </div>
                    
                    <!-- Role Selection -->
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Pilih Role/Jabatan <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="role-card p-4 bg-white border-2 rounded-lg" onclick="selectRole('admin')">
                                <input type="radio" name="role" value="admin" id="role_admin" class="hidden" {{ old('role') == 'admin' ? 'checked' : '' }}>
                                <div class="text-center">
                                    <i class="fas fa-user-shield text-3xl mb-2" style="color: #008e3c;"></i>
                                    <p class="font-medium text-gray-800">Administrator</p>
                                    <p class="text-xs text-gray-500 mt-1">Full akses sistem</p>
                                </div>
                            </div>
                            
                            <div class="role-card p-4 bg-white border-2 rounded-lg" onclick="selectRole('operator')">
                                <input type="radio" name="role" value="operator" id="role_operator" class="hidden" {{ old('role') == 'operator' ? 'checked' : '' }}>
                                <div class="text-center">
                                    <i class="fas fa-user-edit text-3xl mb-2" style="color: #008e3c;"></i>
                                    <p class="font-medium text-gray-800">Operator</p>
                                    <p class="text-xs text-gray-500 mt-1">Kelola arsip</p>
                                </div>
                            </div>
                            
                            <div class="role-card p-4 bg-white border-2 rounded-lg" onclick="selectRole('petugas')">
                                <input type="radio" name="role" value="petugas" id="role_petugas" class="hidden" {{ old('role') == 'petugas' ? 'checked' : '' }}>
                                <div class="text-center">
                                    <i class="fas fa-user-tie text-3xl mb-2" style="color: #008e3c;"></i>
                                    <p class="font-medium text-gray-800">Petugas</p>
                                    <p class="text-xs text-gray-500 mt-1">Input & disposisi</p>
                                </div>
                            </div>
                            
                            <div class="role-card p-4 bg-white border-2 rounded-lg" onclick="selectRole('viewer')">
                                <input type="radio" name="role" value="viewer" id="role_viewer" class="hidden" {{ old('role') == 'viewer' ? 'checked' : '' }}>
                                <div class="text-center">
                                    <i class="fas fa-eye text-3xl mb-2" style="color: #008e3c;"></i>
                                    <p class="font-medium text-gray-800">Viewer</p>
                                    <p class="text-xs text-gray-500 mt-1">Hanya lihat</p>
                                </div>
                            </div>
                        </div>
                        @error('role')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Password -->
                    <div class="mb-5">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Password <span class="text-red-500">*</span>
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
                                placeholder="Minimal 8 karakter"
                                required
                                autocomplete="new-password"
                                onkeyup="checkPasswordStrength()">
                            <div class="absolute right-3 top-1/2 transform -translate-y-1/2 password-toggle" onclick="togglePassword('password')">
                                <i class="fas fa-eye text-gray-500" id="toggleIconPassword"></i>
                            </div>
                        </div>
                        <div class="mt-2">
                            <div class="password-strength bg-gray-200" id="passwordStrength"></div>
                            <p class="text-xs text-gray-500 mt-1" id="strengthText">Kekuatan password: -</p>
                        </div>
                    </div>
                    
                    <!-- Confirm Password -->
                    <div class="mb-6">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            Konfirmasi Password <span class="text-red-500">*</span>
                        </label>
                        <div class="input-group">
                            <div class="input-icon">
                                <i class="fas fa-lock"></i>
                            </div>
                            <input 
                                type="password" 
                                id="password_confirmation" 
                                name="password_confirmation"
                                class="input-field w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition"
                                placeholder="Ulangi password"
                                required
                                autocomplete="new-password">
                            <div class="absolute right-3 top-1/2 transform -translate-y-1/2 password-toggle" onclick="togglePassword('password_confirmation')">
                                <i class="fas fa-eye text-gray-500" id="toggleIconConfirm"></i>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Terms -->
                    <div class="mb-6">
                        <label class="flex items-start cursor-pointer">
                            <input 
                                type="checkbox" 
                                id="terms" 
                                name="terms"
                                required
                                class="w-4 h-4 mt-1 rounded border-gray-300 focus:ring-2 focus:ring-green-500"
                                style="color: #008e3c;">
                            <span class="ml-2 text-sm text-gray-600">
                                Saya setuju dengan <a href="#" class="font-medium hover:underline" style="color: #008e3c;">syarat & ketentuan</a> yang berlaku
                            </span>
                        </label>
                    </div>
                    
                    <!-- Register Button -->
                    <button 
                        type="submit" 
                        class="btn-register w-full py-3 px-4 rounded-lg text-white font-semibold shadow-lg mb-4">
                        <i class="fas fa-user-plus mr-2"></i>
                        Daftar Sekarang
                    </button>
                    
                    <!-- Login Link -->
                    <div class="text-center">
                        <span class="text-sm text-gray-600">Sudah punya akun? </span>
                        <a href="{{ route('login') }}" class="text-sm font-medium hover:underline"
                           style="color: #008e3c;">
                            Login Disini
                        </a>
                    </div>
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
    @vite('resources/js/register.js')
    @endpush

