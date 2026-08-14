module.exports = {
  purge: {
    enabled: true,
    content: [
      './resources/views/admin/**/*.blade.php',
      './resources/views/components/admin/**/*.blade.php',
    ],
  },
  prefix: 'tw-',
  darkMode: false,
  theme: {
    extend: {
      colors: {
        brand: {
          DEFAULT: '#e8822a',
          dark: '#1c1e24',
          light: '#e8e2d9',
        },
      },
    },
  },
  corePlugins: {
    preflight: false,
  },
  variants: {
    extend: {},
  },
  plugins: [],
};
