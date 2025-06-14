/** @type {import('tailwindcss').Config} */
module.exports = {
  presets: [
    require('tailwindcss-preset-email'),
  ],
  content: [
    './components/**/*.html',
    './emails/**/*.html',
    './layouts/**/*.html',
    './components/**/*.blade.php',
    './emails/**/*.blade.php',
    './layouts/**/*.blade.php',
  ],
}
