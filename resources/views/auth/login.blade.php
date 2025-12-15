<x-guest-layout>
    <div class="mb-6 reveal-child stagger-1">
        <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Selamat Datang Kembali</h2>
        <p class="text-sm text-gray-500 mt-1">Silakan masuk untuk melanjutkan.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4 reveal-child stagger-2" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" id="loginForm">
        @csrf

        <!-- Email Address -->
        <div class="reveal-child stagger-2">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full input-playful" type="email" name="email"
                :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4 reveal-child stagger-3">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full input-playful" type="password" name="password" required
                autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between mt-4 reveal-child stagger-4">
            <label for="remember_me" class="inline-flex items-center group cursor-pointer">
                <input id="remember_me" type="checkbox"
                    class="rounded border-gray-300 text-gray-900 shadow-sm focus:ring-gray-900 group-hover:scale-110 transition-transform duration-200"
                    name="remember">
                <span class="ms-2 text-sm text-gray-600 group-hover:text-gray-900 transition-colors">Ingat saya</span>
            </label>

            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 transition-all hover:scale-105 inline-block"
                    href="{{ route('password.request') }}">
                    Lupa password?
                </a>
            @endif
        </div>

        <div class="mt-6 reveal-child stagger-5">
            <x-primary-button class="w-full justify-center py-3 btn-playful" id="loginButton">
                Masuk
            </x-primary-button>
        </div>

        <div class="mt-6 text-center text-sm text-gray-600 reveal-child stagger-5">
            Belum punya akun?
            <a href="{{ route('register') }}"
                class="font-medium text-gray-900 hover:underline focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 rounded-md transition-all hover:text-black inline-block hover:scale-105">
                Daftar sekarang
            </a>
        </div>
    </form>

    <script>
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