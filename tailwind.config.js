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
        },
    },
    plugins: [require('@tailwindcss/forms')],
};
