import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
        './node_modules/flowbite/**/*.js',
        './layouts/**/*.html',
        './content/**/*.md',
        './content/**/*.html',
        './src/**/*.js',
    ],

    safelist: [
        'w-64',
        'w-1/2',
        'rounded-l-lg',
        'rounded-r-lg',
        'bg-gray-200',
        'grid-cols-4',
        'grid-cols-7',
        'h-6',
        'leading-6',
        'h-9',
        'leading-9',
        'shadow-lg'
    ],

    // Choose either 'class' or 'media', not both
    darkMode: 'class', // or 'media'

    theme: {
        extend: {
            fontFamily: {
                // sans: ['Inter', ...defaultTheme.fontFamily.sans],
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors:{
                primary: '#02307b'
            },
            backgroundColor: {
                'primary-light': 'rgb(32 100 135 / 0.5)', // 50% opacity
                'primary-dark': 'rgb(32 100 135 / 0.9)', // 90% opacity,
                'primary-light-one' : '#02307b'
            },
        },
    },

    plugins: [
        forms,
        require('flowbite/plugin')({
            datatables: true,
            charts: true,
        }),
        require('flowbite-typography'),
    ],
};




