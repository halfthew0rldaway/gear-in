<x-guest-layout>
    <div class="mb-6 reveal-child stagger-1">
        <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Buat Akun</h2>
        <p class="text-sm text-gray-500 mt-1">Bergabung dengan gear-in sekarang.</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="reveal-child stagger-2">
            <x-input-label for="name" :value="__('Nama')" />
            <x-text-input id="name" class="block mt-1 w-full input-playful" type="text" name="name" :value="old('name')"
                required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4 reveal-child stagger-3">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full input-playful" type="email" name="email"
                :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4 reveal-child stagger-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full input-playful" type="password" name="password" required
                autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4 reveal-child stagger-5">
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full input-playful" type="password"
                name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="mt-6 reveal-child stagger-5">
            <x-primary-button class="w-full justify-center py-3 btn-playful">
                Buat Akun
            </x-primary-button>
        </div>

        <div class="mt-6 text-center text-sm text-gray-600 reveal-child stagger-5">
            Sudah punya akun?
            <a href="{{ route('login') }}"
                class="font-medium text-gray-900 hover:underline focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 rounded-md transition-all hover:text-black inline-block hover:scale-105">
                Masuk
            </a>
        </div>
    </form>
</x-guest-layout>