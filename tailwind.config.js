import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],
    
    plugins: [forms, typography],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Work Sans', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'primary': '#6F22DA',
                'accent': '#22DABB',
                'background': '#0F172A',
                'surface': '#1E293B',
                'text-primary': '#F1F5F9',
                'text-secondary': '#94A3B8',
                'border': '#334155',
                'warning': '#FACC15',
                'error': '#F87171',
                'success': '#4ADE80',
            },
        },
    },

    plugins: [forms],
};