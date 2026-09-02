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

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                serif: ['"Playfair Display"', ...defaultTheme.fontFamily.serif],
            },
            colors: {
                institutional: {
                    navy: '#0A1128',
                    ivory: '#F9F8F6',
                    'warm-gray': '#EAE8E3',
                    'warm-gray-dark': '#8A8680',
                    gold: '#C5A880',
                    'gold-light': '#D6BFA0',
                },
                museum: {
                    black: '#0a0a0a',
                    dark: '#1c1c1c',
                    gray: '#737373',
                    light: '#f5f5f4',
                    paper: '#FDFBF7', 
                },
                // Sapientia theme palette
                sapientia: {
                    primary: '#448AFF',
                    secondary: '#82B1FF',
                    light: '#E3F2FD',
                    // Was #1565C0, a saturated Material blue. The site ran two
                    // competing dark palettes — that bright blue on 19 surfaces
                    // and 46 text usages, against the muted jp navy everywhere
                    // else — so the cabinet section and footer read as belonging
                    // to a different design. Aligned to the jp navy: one dark
                    // colour, changed at the token so no template needed editing.
                    deep: '#1B3A5C',
                    cream: '#F8FBFF',
                    mist: '#EEF5FF',
                },
                // Japanese wave-inspired palette
                jp: {
                    indigo: '#1B3A5C',
                    'indigo-deep': '#0F2744',
                    'indigo-light': '#2A5580',
                    cream: '#FBF9F4',
                    'cream-warm': '#F5F0E8',
                    wave: '#1E3F64',
                    'wave-light': '#2D5A8A',
                    gold: '#C5A47E',
                    'gold-light': '#D4BC9A',
                    ink: '#1A1A2E',
                    mist: '#E8E4DD',
                    lotus: '#C4A882',
                },
            },
            boxShadow: {
                'art': '0 4px 30px rgba(0, 0, 0, 0.03)',
                'elegant': '0 20px 40px -10px rgba(10, 17, 40, 0.08)',
                'wave': '0 8px 32px rgba(27, 58, 92, 0.12)',
                'wave-lg': '0 20px 50px rgba(27, 58, 92, 0.15)',
            },
            spacing: {
                '18': '4.5rem',
                '22': '5.5rem',
                '30': '7.5rem',
                'section': '12rem',
            },
            letterSpacing: {
                'widest': '.25em',
            },
            transitionTimingFunction: {
                'cinematic': 'cubic-bezier(0.19, 1, 0.22, 1)',
            },
            transitionDuration: {
                '800': '800ms',
                '1000': '1000ms',
                '1200': '1200ms',
            },
        },
    },

    plugins: [forms, typography],
};
