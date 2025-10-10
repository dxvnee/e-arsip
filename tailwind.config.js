import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    safelist: [
        'bg-primary',
        'bg-primary-dark',
        'bg-primary-light',
        'bg-secondary',
        'bg-secondary-dark',
        'bg-secondary-light',
        'text-primary',
        'text-primary-dark',
        'text-secondary',
        'text-secondary-dark',
        'border-primary',
        'border-secondary',
        'hover:bg-primary',
        'hover:bg-primary-dark',
        'hover:text-primary',
        'hover:text-primary-dark',
        'from-primary',
        'to-primary-dark',
        'bg-opacity-10',
        'bg-opacity-20',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    DEFAULT: '#008e3c',
                    dark: '#006b2d',
                    light: '#00a847',
                },
                secondary: {
                    DEFAULT: '#efd856',
                    dark: '#d4b93a',
                    light: '#f5e589',
                },
            },
        },
    },

    plugins: [forms],
};
