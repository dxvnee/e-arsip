<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Sistem E-Arsip Dinkes</title>
    
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
        
        .login-container {
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
        
        .logo-shine {
            animation: shine 2s infinite;
        }
        
        @keyframes shine {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.8; }
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
        
        .btn-login {
            background: linear-gradient(135deg, #008e3c 0%, #006b2e 100%);
            transition: all 0.3s ease;
        }
        
        .btn-login:hover {
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
    </style>
</head>
<body class="antialiased">
    <div class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="login-container w-full max-w-md">
            <!-- Logo & Title -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full mb-4 logo-shine"
                     style="background-color: #efd856;">
                    <i class="fas fa-archive text-4xl" style="color: #008e3c;"></i>
                </div>
                <h1 class="text-3xl font-bold text-white mb-2">
                    Sistem E-Arsip
                </h1>
                <p class="text-gray-200 text-sm">
                    Dinas Kesehatan
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
            </div>
        </div>
    </div>
    
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
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
        
        // Form validation feedback
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
            submitBtn.disabled = true;
        });
    </script>
</body>
</html>