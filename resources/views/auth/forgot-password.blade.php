<x-guest-layout>
    <!-- Header Section -->
    <div class="mb-8 reveal-child stagger-1">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 bg-gray-900 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Lupa Password?</h2>
            </div>
        </div>
        <p class="text-sm text-gray-500 ml-[52px]">Tidak masalah. Masukkan email Anda dan kami akan mengirimkan link
            untuk mereset password.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4 reveal-child stagger-2" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div class="reveal-child stagger-2">
            <x-input-label for="email" :value="__('Email')" class="mb-2" />
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-gray-600 transition-colors" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                    </svg>
                </div>
                <x-text-input id="email" class="block w-full pl-10 input-playful" type="email" name="email"
                    :value="old('email')" required autofocus placeholder="nama@email.com" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Submit Button -->
        <div class="reveal-child stagger-3">
            <x-primary-button class="w-full justify-center py-3 btn-playful group">
                <svg class="w-5 h-5 mr-2 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <span class="text-white">Kirim Link Reset Password</span>
            </x-primary-button>
        </div>

        <!-- Divider -->
        <div class="relative reveal-child stagger-4">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-200"></div>
            </div>
            <div class="relative flex justify-center text-xs uppercase">
                <span class="bg-white px-2 text-gray-500 tracking-wider">atau</span>
            </div>
        </div>

        <!-- Back to Login Link -->
        <div class="text-center reveal-child stagger-4">
            <p class="text-sm text-gray-600">
                Ingat password Anda?
                <a href="{{ route('login') }}"
                    class="font-semibold text-gray-900 hover:underline focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 rounded-md transition-all inline-block hover:scale-105">
                    Kembali ke login
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>