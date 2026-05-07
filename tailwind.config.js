module.exports = {
  darkMode: 'class',
  content: ['./resources/**/*.{blade.php,js}'],
  theme: {
    extend: {
      colors: {
        brand: {
          orange: '#E8710A',
          dark: '#111111',
          card: '#1a1a1a',
        },
      },
      maxWidth: { 'container': '1280px' },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
  ],
}
