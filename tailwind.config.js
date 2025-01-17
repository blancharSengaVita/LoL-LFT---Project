import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import colors from 'tailwindcss/colors.js';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './vendor/masmerise/livewire-toaster/resources/views/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                'gray-900': '#F6F0E5',
                'gray-800': '#F0E6D2',
                'gray-700': '#F0E6D2',
                'gray-600': '#C8AA6E',
                'gray-500': '#BCC2BE',
                'gray-400': '#BCC2BE',
                'gray-300' : '#52696A',
                'gray-200': '#52696A',
                'gray-100': '#52696A',
                'gray-50': '#183B44',
                'indigo-500': '#F0E6D2',
                'indigo-600': '#C8AA6E',
                'indigo-700': '#C8AA6E',
                'black': '#F0E6D2',
                'white': '#0A323C',
                'red-600' : '#f87171',
                'red-500' : '#dc2626'
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [
        forms,
        require('@tailwindcss/forms'),
    ],
};
