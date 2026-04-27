@extends('layouts.app')
@section('content')

<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-custom-50 to-slate-100 px-4 py-12 relative overflow-hidden">
    <!-- Background decoration -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-0 right-0 w-96 h-96 bg-custom-100 rounded-full opacity-10 blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-custom-100 rounded-full opacity-10 blur-3xl"></div>
    </div>

    <!-- Register Card -->
    <div class="relative z-10 w-full max-w-md">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xl p-8">
            <!-- Logo -->
            <div class="text-center mb-8">
                <img src="{{ asset('assets/images/logo-sinergi.png') }}" alt="Sinergi" class="h-12 mx-auto">
            </div>

            <!-- Heading -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-slate-900 mb-2">Buat Akun</h1>
                <p class="text-slate-600 text-sm">Daftar untuk mulai menggunakan sistem</p>
            </div>

            <!-- Form -->
            <form action="{{ route('register') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Name Input -->
                <div>
                    <label for="name" class="block text-xs uppercase font-bold tracking-wider text-slate-700 mb-2">
                        Nama Lengkap
                    </label>
                    <input 
                        type="text" 
                        id="name"
                        name="name" 
                        placeholder="Masukan nama lengkap Anda" 
                        required
                        value="{{ old('name') }}"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-lg text-slate-900 placeholder-slate-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-custom-500 focus:border-transparent transition-all duration-200"
                    >
                    @error('name')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-xs uppercase font-bold tracking-wider text-slate-700 mb-2">
                        Email
                    </label>
                    <input 
                        type="email" 
                        id="email"
                        name="email" 
                        placeholder="Masukan email Anda" 
                        required
                        value="{{ old('email') }}"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-lg text-slate-900 placeholder-slate-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-custom-500 focus:border-transparent transition-all duration-200"
                    >
                    @error('email')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Input -->
                <div>
                    <label for="password" class="block text-xs uppercase font-bold tracking-wider text-slate-700 mb-2">
                        Password
                    </label>
                    <input 
                        type="password" 
                        id="password"
                        name="password" 
                        placeholder="Masukan password (min. 8 karakter)" 
                        required
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-lg text-slate-900 placeholder-slate-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-custom-500 focus:border-transparent transition-all duration-200"
                    >
                    @error('password')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Confirmation Input -->
                <div>
                    <label for="password_confirmation" class="block text-xs uppercase font-bold tracking-wider text-slate-700 mb-2">
                        Konfirmasi Password
                    </label>
                    <input 
                        type="password" 
                        id="password_confirmation"
                        name="password_confirmation" 
                        placeholder="Ulangi password Anda" 
                        required
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-lg text-slate-900 placeholder-slate-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-custom-500 focus:border-transparent transition-all duration-200"
                    >
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit"
                    class="w-full py-3 mt-6 bg-custom-500 hover:bg-custom-600 text-white font-bold uppercase tracking-wider rounded-lg transition-all duration-200 shadow-md hover:shadow-lg active:scale-95"
                >
                    Sign Up
                </button>

                <!-- Login Link -->
                <div class="text-center pt-4 border-t border-slate-200">
                    <p class="text-sm text-slate-600">
                        Telah memiliki akun?
                        <a href="{{ route('login') }}" class="font-bold text-custom-500 hover:text-custom-600 transition-colors">
                            Login
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();
</script>
@endsection
