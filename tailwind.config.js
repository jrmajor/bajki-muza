import defaultTheme from 'tailwindcss/defaultTheme';
import colors from 'tailwindcss/colors';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './app/View/Components/**/*.php',
    './resources/views/**/*.blade.php',
  ],
  theme: {
    colors: {
      inherit: colors.inherit,
      transparent: colors.transparent,
      current: colors.current,
      black: colors.black,
      white: colors.white,
      brand: {
        primary: '#ffcc00',
        lighter: '#f6e05e',
        'primary-dark': '#896f09',
        'lighter-dark': '#847938',
      },
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
  plugins: [forms],
}
