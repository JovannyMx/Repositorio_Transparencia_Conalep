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
                sans: ['Garet', 'Figtree', ...defaultTheme.fontFamily.sans],
                garet: ['Garet', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                conalep: {
                    green: '#007D69',     // Verde institucional principal
                    dark: '#004D40',      // Verde oscuro
                    secondary: '#1F7E6D', // Verde secundario
                    mint: '#80C3AF',      // Verde claro / menta
                    orange: '#FD8204',    // Naranja acento
                    gold: '#B48E5C',      // Dorado / beige
                    magenta: '#E80A4D',   // Magenta institucional
                    wine: '#A12244',      // Vino / borgoña
                    light: '#F3F2F3',     // Gris claro / fondo
                    primary: '#00664f',   // Verde principal alternativo
                }
            }
        },
    },
    plugins: [forms],
};