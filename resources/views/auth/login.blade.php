<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" id="loginForm">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3" id="loginButton">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>

    <script>
        // Fix untuk "Page Expired" - Refresh halaman jika form terlalu lama dibuka
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('loginForm');
            const formLoadTime = Date.now();
            const MAX_FORM_AGE = 10 * 60 * 1000; // 10 menit

            // Cek apakah form sudah terlalu lama dibuka
            form.addEventListener('submit', function(e) {
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
            setInterval(function() {
                const formAge = Date.now() - formLoadTime;
                if (formAge > 5 * 60 * 1000) { // Jika lebih dari 5 menit
                    // Refresh halaman untuk mendapatkan token baru
                    window.location.reload();
                }
            }, 5 * 60 * 1000);
        });
    </script>
</x-guest-layout>
