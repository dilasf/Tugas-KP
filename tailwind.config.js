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
                '16px': '16px',
              },

            colors: {
                'custom-dark1': '#4a5568',
                'custom-dark': '#1B1E23',
                'side-dark' : '#515458',
                'hover-side' : '#448BE2',
                'light-blue' : '#448BE2',
                'light-yellow' : '#FFD000',
                'light-gray' : '#D9D9D9',
                'light-green' : "#0AA00A",
                'light-gray-input' : '#D9D9D9',
                'light-orange' : '#F39C12',
                'semi-dark-orange' : '#D78A0F',
                'aqua-blue' : '#00C0EF',
                'semi-dark-aqua' : '#03ABD4',
                'semi-green' : "#00BB65",
                'semi-dark-green' : "#00A75A",
                'light-puple' : '#605CA8',
                'semi-dark-purple' : '#504C8D',
                'semi-blue' :'#0087D9',
                'dark-blue' : '#0070B2',
              },
        },
    },

    plugins: [
        forms,
        require('preline/plugin'),
    ],
};
