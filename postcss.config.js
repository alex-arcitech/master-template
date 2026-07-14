module.exports = {
  plugins: [
    require('postcss-import-ext-glob'),
    require('postcss-import'),
    require('@tailwindcss/nesting'), // <-- add this line
    require('tailwindcss'),
    require('autoprefixer'),
  ],
}