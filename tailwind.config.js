import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            colors: {
                medical: {
                    primary: '#0F766E',
                    primaryHover: '#115E59',
                    secondary: '#14B8A6',
                    background: '#F8FAFC',
                    surface: '#FFFFFF',
                    success: '#16A34A',
                    danger: '#DC2626',
                    warning: '#F59E0B',
                    text: '#0F172A',
                    muted: '#475569',
                    border: '#E2E8F0',
                },
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
