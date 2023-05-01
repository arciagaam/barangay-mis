/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
  ],
  theme: {
    extend: {
      fontFamily: {
        inter: ['Inter Tight', 'sans-serif']
      },
      colors: {
        project: {
          black: "#0D0D0D",
          blue: "#003C71",
          yellow: "#FFCD00"
        },
        table: {
          even: '#E1E7ED',
          odd: '#FFFFFF'
        }
      }
    },
  },
  plugins: [],
}