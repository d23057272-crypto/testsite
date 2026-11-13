// tailwind.config.js
module.exports = {
  darkMode: 'class', // Keep your existing dark mode configuration
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
  ],
  theme: {
    extend: {
      colors: {
        dark: {
          900: '#111827',
          800: '#1f2937',
          700: '#374151',
        },
      },
    },
  },
  plugins: [],
};
