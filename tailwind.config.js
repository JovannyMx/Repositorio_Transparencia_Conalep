import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                conalep: {
                    primary: '#00664f', // Verde principal
                    dark: '#004d3b',    // Verde oscuro para hover/bordes
                    light: '#f0fdf4',   // Fondo claro (green-50)
                }
            }
        },
    },

    plugins: [forms],
};