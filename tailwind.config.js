import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

const cleanGray = {
    50: '#f9f9f9',
    100: '#f2f2f2',
    200: '#dfdfdf',
    300: '#c4c4c4',
    400: '#9b9b9b',
    500: '#6f6f6f',
    600: '#4b4b4b',
    700: '#353535',
    800: '#1f1f1f',
    900: '#121212',
};

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['"Space Grotesk"', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                gray: cleanGray,
            },
        },
    },

    plugins: [forms],
};
