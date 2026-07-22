/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './*.php',
        './**/*.php',
        './scripts/**/*.js',
    ],
    safelist: [
        'sub-menu',
        'menu-theme',
        'menu-item-has-children',
        'fluentform',
        "ff_columns_total_2",
        "ff_submit_btn_wrapper",
        'wp-block-heading',
        'wp-block-paragraph'
    ],
    theme: {
        extend: {
            colors: {
                theme: "#264653",
                themedark: "#0D181C",
                themelight: "#F1F6F9",
                accent: "#F0544F",
                accent2: "#429EA6",
                grey: "#D4D4D4",
            },
            fontFamily: {
                heading: ["Libre Baskerville", "serif"],
                body: ["Lexend", "sans-serif"]
            },
        },
    },
    plugins: [],
    darkMode: 'class',
}

