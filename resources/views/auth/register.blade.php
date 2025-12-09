<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - Sistem E-Arsip Dinkes</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            background: linear-gradient(135deg, #008e3c 0%, #006b2e 50%, #004d21 100%);
            min-height: 100vh;
            font-family: 'Figtree', sans-serif;
        }
        
        .register-container {
            animation: fadeIn 0.6s ease-in;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .logo-pulse {
            animation: pulse 2s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }
        
        .logo-glow {
            box-shadow: 0 0 30px rgba(239, 216, 86, 0.4), 0 0 60px rgba(239, 216, 86, 0.2);
        }
        
        .input-group {
            position: relative;
        }
        
        .input-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
            pointer-events: none;
        }
        
        .input-field {
            padding-left: 40px;
        }
        
        .btn-register {
            background: linear-gradient(135deg, #008e3c 0%, #006b2e 100%);
            transition: all 0.3s ease;
        }
        
        .btn-register:hover {
            background: linear-gradient(135deg, #006b2e 0%, #004d21 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 142, 60, 0.3);
        }
        
        .alert {
            animation: slideDown 0.3s ease-out;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .password-toggle {
            cursor: pointer;
            transition: color 0.2s;
        }
        
        .password-toggle:hover {
            color: #008e3c;
        }
        
        .role-card {
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        
        .role-card:hover {
            border-color: #008e3c;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 142, 60, 0.2);
        }
        
        .role-card.selected {
            border-color: #008e3c;
            background-color: #f0fdf4;
        }
        
        .password-strength {
            height: 4px;
            border-radius: 2px;
            transition: all 0.3s;
        }
    </style>
</head>
<body class="antialiased">
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
    
    <script>
        function togglePassword(fieldId) {
            const passwordInput = document.getElementById(fieldId);
            const toggleIcon = fieldId === 'password' ? document.getElementById('toggleIconPassword') : document.getElementById('toggleIconConfirm');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
        
        function selectRole(role) {
            // Remove selected class from all cards
            document.querySelectorAll('.role-card').forEach(card => {
                card.classList.remove('selected');
            });
            
            // Add selected class to clicked card
            event.currentTarget.classList.add('selected');
            
            // Check the radio button
            document.getElementById('role_' + role).checked = true;
        }
        
        function checkPasswordStrength() {
            const password = document.getElementById('password').value;
            const strengthBar = document.getElementById('passwordStrength');
            const strengthText = document.getElementById('strengthText');
            
            let strength = 0;
            if (password.length >= 8) strength++;
            if (password.match(/[a-z]+/)) strength++;
            if (password.match(/[A-Z]+/)) strength++;
            if (password.match(/[0-9]+/)) strength++;
            if (password.match(/[$@#&!]+/)) strength++;
            
            switch(strength) {
                case 0:
                case 1:
                    strengthBar.style.width = '20%';
                    strengthBar.style.backgroundColor = '#ef4444';
                    strengthText.textContent = 'Kekuatan password: Sangat Lemah';
                    strengthText.style.color = '#ef4444';
                    break;
                case 2:
                    strengthBar.style.width = '40%';
                    strengthBar.style.backgroundColor = '#f97316';
                    strengthText.textContent = 'Kekuatan password: Lemah';
                    strengthText.style.color = '#f97316';
                    break;
                case 3:
                    strengthBar.style.width = '60%';
                    strengthBar.style.backgroundColor = '#eab308';
                    strengthText.textContent = 'Kekuatan password: Cukup';
                    strengthText.style.color = '#eab308';
                    break;
                case 4:
                    strengthBar.style.width = '80%';
                    strengthBar.style.backgroundColor = '#84cc16';
                    strengthText.textContent = 'Kekuatan password: Kuat';
                    strengthText.style.color = '#84cc16';
                    break;
                case 5:
                    strengthBar.style.width = '100%';
                    strengthBar.style.backgroundColor = '#22c55e';
                    strengthText.textContent = 'Kekuatan password: Sangat Kuat';
                    strengthText.style.color = '#22c55e';
                    break;
            }
        }
        
        // Set default role if exists
        document.addEventListener('DOMContentLoaded', function() {
            const selectedRole = document.querySelector('input[name="role"]:checked');
            if (selectedRole) {
                selectedRole.parentElement.classList.add('selected');
            }
        });
        
        // Form validation feedback
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
            submitBtn.disabled = true;
        });
    </script>
</body>
</html>