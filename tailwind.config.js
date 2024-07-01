/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './storage/framework/views/*.php',
    './resources/views/**/*.blade.php',
    './resources/js/**/*.vue',
    "./node_modules/flowbite/**/*.js"
  ],
  theme: {
    extend: {
      screens: {
        'xs': '320px',  // Custom breakpoint for 320px screens
      },
      gridTemplateColumns: {
        // Simple 16 row grid
        '16': 'repeat(16, minmax(0, 1fr))',
      },
    },
  },
  plugins: [
    require('flowbite/plugin')
  ],
}

