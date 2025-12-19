<x-guest-layout>
    <!-- Header Section -->
    <div class="mb-8 reveal-child stagger-1">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 bg-gray-900 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Selamat Datang Kembali</h2>
            </div>
        </div>
        <p class="text-sm text-gray-500 ml-[52px]">Masuk ke akun gear-in Anda untuk melanjutkan.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4 reveal-child stagger-2" :status="session('status')" />

    <!-- General Errors (for failed login) -->
    @if ($errors->any() && !$errors->has('email') && !$errors->has('password'))
        <div class="mb-4 bg-red-50 border border-red-200 rounded-lg p-4 reveal-child stagger-2">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                        clip-rule="evenodd" />
                </svg>
                <div class="flex-1">
                    <h3 class="text-sm font-semibold text-red-800 mb-1">Login Gagal</h3>
                    <ul class="text-sm text-red-700 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" id="loginForm" class="space-y-5">
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
                    :value="old('email')" required autofocus autocomplete="username" placeholder="nama@email.com" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="reveal-child stagger-3">
            <x-input-label for="password" :value="__('Password')" class="mb-2" />
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-gray-600 transition-colors" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <x-text-input id="password" class="block w-full pl-10 pr-10 input-playful" type="password"
                    name="password" required autocomplete="current-password" placeholder="••••••••" />
                <button type="button" onclick="togglePassword('password')"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                    <svg id="password-eye-open" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg id="password-eye-closed" class="h-5 w-5 hidden" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between reveal-child stagger-4">
            <label for="remember_me" class="inline-flex items-center group cursor-pointer">
                <input id="remember_me" type="checkbox"
                    class="rounded border-gray-300 text-gray-900 shadow-sm focus:ring-gray-900 group-hover:scale-110 transition-transform duration-200"
                    name="remember">
                <span class="ml-2 text-sm text-gray-600 group-hover:text-gray-600 transition-colors">Ingat saya</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-gray-600 hover:text-gray-900 font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 transition-all hover:scale-105 inline-block"
                    href="{{ route('password.request') }}">
                    Lupa password?
                </a>
            @endif
        </div>

        <!-- Submit Button -->
        <div class="reveal-child stagger-5">
            <x-primary-button class="w-full justify-center py-3 btn-playful group" id="loginButton">
                <span class="text-white">Masuk</span>
                <svg class="w-5 h-5 ml-2 text-white group-hover:translate-x-1 transition-transform" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </x-primary-button>
        </div>

        <!-- Divider -->
        <div class="relative reveal-child stagger-5">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-200"></div>
            </div>
            <div class="relative flex justify-center text-xs uppercase">
                <span class="bg-white px-2 text-gray-500 tracking-wider">atau</span>
            </div>
        </div>

        <!-- Register Link -->
        <div class="text-center reveal-child stagger-5">
            <p class="text-sm text-gray-600">
                Belum punya akun?
                <a href="{{ route('register') }}"
                    class="font-semibold text-gray-900 hover:underline focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 rounded-md transition-all inline-block hover:scale-105">
                    Daftar sekarang
                </a>
            </p>
        </div>
    </form>

    <script>
        // Password visibility toggle
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const eyeOpen = document.getElementById(inputId + '-eye-open');
            const eyeClosed = document.getElementById(inputId + '-eye-closed');

            if (input.type === 'password') {
                input.type = 'text';
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            } else {
                input.type = 'password';
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            }
        }

        // Fix untuk "Page Expired" - Refresh halaman jika form terlalu lama dibuka
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('loginForm');
            const formLoadTime = Date.now();
            const MAX_FORM_AGE = 10 * 60 * 1000; // 10 menit

            // Cek apakah form sudah terlalu lama dibuka
            form.addEventListener('submit', function (e) {
                const formAge = Date.now() - formLoadTime;

                if (formAge > MAX_FORM_AGE) {
                    // Jika form lebih dari 10 menit, refresh halaman untuk mendapatkan token baru
                    e.preventDefault();
                    window.customConfirm('Form sudah terlalu lama dibuka. Halaman akan di-refresh untuk memperbarui token keamanan.', 'Perbarui Token').then(confirmed => {
                        if (confirmed) {
                            window.location.reload();
                        }
                    });
                }
            });

            // Refresh token secara berkala (setiap 5 menit)
            setInterval(function () {
                const formAge = Date.now() - formLoadTime;
                if (formAge > 5 * 60 * 1000) { // Jika lebih dari 5 menit
                    // Refresh halaman untuk mendapatkan token baru
                    window.location.reload();
                }
            }, 5 * 60 * 1000);
        });
    </script>
</x-guest-layout>