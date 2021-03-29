const colors = require('tailwindcss/colors')
const defaultTheme = require('tailwindcss/defaultTheme')

module.exports = {
  theme: {
    colors: {
      transparent: 'transparent',
      current: 'currentColor',
      black: colors.black,
      white: colors.white,
      gray: {
        50: '#fafafa',
        100: '#f7f7f7',
        200: '#ebebeb',
        300: '#dbdbdb',
        400: '#b3b3b3',
        500: '#808080',
        600: '#636363',
        700: '#525252',
        800: '#404040',
        900: '#2e2e2e',
        950: '#121212',
      },
      red: colors.red,
      yellow: {
        kox: '#ffcc00',
        ciul: '#f6e05e',
        'kox-dark': '#896f09',
        'ciul-dark': '#847938',
        50: '#fdfdea',
        100: '#fdf6b2',
        200: '#fce96a',
        300: '#faca15',
        400: '#e3a008',
        500: '#c27803',
        600: '#9f580a',
        700: '#8e4b10',
        800: '#723b13',
        900: '#633112',
      },
      green: colors.green,
      filmpolski: {
        blue: '#566ea1',
        'blue-lighter': '#7393d9',
        gray: '#010101',
        'gray-inverted': '#eeeeee',
      },
    },
    extend: {
      fontFamily: {
        sans: ['Inter var', ...defaultTheme.fontFamily.sans],
      },
      fontSize: { '2xs': '0.7rem' },
      inset: { '-px': '-1px' },
      maxWidth: { '1/2': '50%' },
      spacing: {
        4.5: '1.125rem',
        13: '3.25rem',
        15: '3.75rem',
      },
      transitionProperty: {
        'colors-shadow': defaultTheme.transitionProperty.colors + ', ' + defaultTheme.transitionProperty.shadow,
      },
    },
  },
  darkMode: 'media',
  plugins: [
    require('@tailwindcss/forms'),
  ],
  purge: [
    './resources/views/**/*.blade.php',
    './app/View/Components/**/*.php',
  ],
}
