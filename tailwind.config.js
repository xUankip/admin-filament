import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/wire-elements/modal/resources/views/*.blade.php',
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/kenepa/translation-manager/resources/**/*.blade.php',
        './vendor/masmerise/livewire-toaster/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './themes/green/resources/**/*.php',
        './themes/**/*.php',
        './resources/**/*.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },
    plugins: [],
};
