<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-6 py-2 bg-gray-900 border border-gray-900 rounded-full font-semibold text-xs text-white uppercase tracking-[0.4em] hover:bg-black focus:bg-black focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2 focus:ring-offset-gray-950 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
