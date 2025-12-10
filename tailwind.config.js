import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'selector',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                display: ['Outfit', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    primary: '#0EA5E9',   // Sky Blue
                    secondary: '#F97316', // Orange
                    accent: '#FCD34D',    // Amber
                    dark: '#0F172A',      // Slate 900
                    light: '#F8FAFC',     // Slate 50
                }
            },
        },
    },
    plugins: [],
};
