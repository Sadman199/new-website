module.exports = {
  purge: {
    enabled: true,
    content: [
      './resources/**/*.blade.php',
      './resources/**/*.js',
      './app/**/*.php',
    ],
  },
  darkMode: false,
  theme: {
    extend: {
      colors: {
        'bc-primary': '#007AAD',
        'bc-dark': '#0C1D32',
        'bc-ice': '#D9E2E9',
        'bc-white': '#FFFBFC',
      },
    },
  },
  corePlugins: {
    container: false,
  },
  variants: {
    extend: {},
  },
  plugins: [],
};
