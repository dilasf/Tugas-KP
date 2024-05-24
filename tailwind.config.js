import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        'node_modules/preline/dist/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            fontSize: {
                '10px': '10px',
                '12px': '12px',
                '14px': '14px',
                // Tambahkan ukuran teks lainnya jika diperlukan
              },

            colors: {
                'custom-dark': '#1B1E23',
                'side-dark' : '#515458',
                'hover-side' : '#448BE2',
                'light-blue' : '#448BE2',
                'light-yellow' : '#FFD000',
                'light-gray' : '#D9D9D9',
                'light-green' : "#0AA00A",
                'light-gray-input' : '#D9D9D9',
                'dark-blue' : '#00509D',
              },
        },
    },

    plugins: [
        forms,
        require('preline/plugin'),
    ],
};
