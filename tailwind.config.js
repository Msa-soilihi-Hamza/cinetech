const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            screens: {
                'mobile': {'max': '949px'},
                'desktop': '950px',
            },
            animation: {
                'fade-in-up': 'fade-in-up 0.3s ease-out',
                'fade-in': 'fade-in 0.3s ease-out',
            },
            keyframes: {
                'fade-in-up': {
                    '0%': {
                        opacity: '0',
                        transform: 'translateY(20px)'
                    },
                    '100%': {
                        opacity: '1',
                        transform: 'translateY(0)'
                    },
                },
                'fade-in': {
                    '0%': {
                        opacity: '0'
                    },
                    '100%': {
                        opacity: '1'
                    },
                },
            },
        },
    },
    plugins: [require('@tailwindcss/forms')],
};
