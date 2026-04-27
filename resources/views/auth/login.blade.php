@extends('layouts.app')
@section('content')

<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-custom-50 to-slate-100 px-4 py-12 relative overflow-hidden">
    <!-- Background decoration -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-0 right-0 w-96 h-96 bg-custom-100 rounded-full opacity-10 blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-custom-100 rounded-full opacity-10 blur-3xl"></div>
    </div>

    <!-- Login Card -->
    <div class="relative z-10 w-full max-w-md">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xl p-8">
            <!-- Logo -->
            <div class="text-center mb-8">
                <img src="{{ asset('assets/images/logo-sinergi.png') }}" alt="Sinergi" class="h-16 mx-auto">
            </div>

            <!-- Heading -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-slate-900 mb-2">Welcome Back!</h1>
                <p class="text-slate-600 text-sm">Sign in to HRIS Sinergi Hotel & Villa</p>
            </div>

            <!-- Form -->
            <form action="{{ route('login') }}" method="POST" class="space-y-8">
                @csrf

                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-xs uppercase font-bold tracking-wider text-slate-700 mb-3">
                        Username / Email
                    </label>
                    <input 
                        type="text" 
                        id="email"
                        name="email" 
                        placeholder="Masukan Emailmu" 
                        required 
                        value="{{ old('email') }}"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-lg text-slate-900 placeholder-slate-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-custom-500 focus:border-transparent transition-all duration-200"
                    >
                </div>

                <!-- Password Input -->
                <div>
                    <label for="password" class="block text-xs uppercase font-bold tracking-wider text-slate-700 mb-3">
                        Password
                    </label>
                    <input 
                        type="password" 
                        id="password"
                        name="password" 
                        placeholder="••••••••" 
                        required
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-lg text-slate-900 placeholder-slate-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-custom-500 focus:border-transparent transition-all duration-200 mb-3"
                    >
                </div>

                <!-- Remember & Forgot -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input 
                            type="checkbox" 
                            name="remember"
                            class="w-4 h-4 rounded border-slate-300 text-custom-500 focus:ring-custom-500 cursor-pointer"
                        >
                        <span class="text-sm text-slate-600">Remember me</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit"
                    class="w-full py-3 bg-custom-500 hover:bg-custom-600 text-white font-bold uppercase tracking-wider rounded-lg transition-all duration-200 shadow-md hover:shadow-lg active:scale-95"
                >
                    Sign In
                </button>

                <!-- Divider -->
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-slate-300"></div>
                    </div>
                </div>


                <!-- Sign Up Link -->
                <div class="text-center pt-4 border-t border-slate-200">
                    <p class="text-sm text-slate-600">
                        Tidak memiliki akun?
                        <a href="{{ route('register') }}" class="font-bold text-custom-500 hover:text-custom-600 transition-colors">
                            Sign Up
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
