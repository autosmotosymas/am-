/** @type {import('tailwindcss').Config} */
module.exports = {
  darkMode: 'class',
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
  ],
  theme: {
    extend: {
      colors: {
        brand: {
          orange:  '#E8710A',
          orange2: '#F08030',
          dark:    '#111111',
          card:    '#1a1a1a',
          border:  '#2e2e2e',
          muted:   '#888888',
        },
      },
      maxWidth: {
        container: '1280px',
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
  ],
}
