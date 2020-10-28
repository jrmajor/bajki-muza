module.exports = {
  theme: {
    colors: {
      transparent: 'transparent',
      white: '#ffffff',
      black: '#000000',
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
        950: '#121212'
      },
      'cool-gray': {
        50: '#fbfdfe',
        100: '#f1f5f9',
        200: '#e2e8f0',
        300: '#cfd8e3',
        400: '#97a6ba',
        500: '#64748b',
        600: '#475569',
        700: '#364152',
        800: '#27303f',
        900: '#1a202e'
      },
      red: {
        50: '#fdf2f2',
        100: '#fde8e8',
        200: '#fbd5d5',
        300: '#f8b4b4',
        400: '#f98080',
        500: '#f05252',
        600: '#e02424',
        700: '#c81e1e',
        800: '#9b1c1c',
        900: '#771d1d'
      },
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
        900: '#633112'
      },
      green: {
        50: '#f3faf7',
        100: '#def7ec',
        200: '#bcf0da',
        300: '#84e1bc',
        400: '#31c48d',
        500: '#0e9f6e',
        600: '#057a55',
        700: '#046c4e',
        800: '#03543f',
        900: '#014737'
      },
      blue: {
        50: '#ebf5ff',
        100: '#e1effe',
        200: '#c3ddfd',
        300: '#a4cafe',
        400: '#76a9fa',
        500: '#3f83f8',
        600: '#1c64f2',
        700: '#1a56db',
        800: '#1e429f',
        900: '#233876'
      },
      filmpolski: {
        blue: '#566ea1',
        'blue-lighter': '#7393d9',
        gray: '#010101',
        'gray-inverted': '#eeeeee'
      }
    },
    extend: {
      fontSize: { '2xs': '0.7rem' },
      spacing: { 4.5: '1.125rem' },
      inset: { '-px': '-1px' },
      maxWidth: { '1/2': '50%' },
    }
  },
  dark: 'media',
  variants: {
    backgroundColor: ({ after }) => after(['dark']),
    borderColor: ({ after }) => after(['dark']),
    opacity: ({ after }) => after(['group-hover']),
    textColor: ({ after }) => after(['dark'])
  },
  plugins: [
    require('@tailwindcss/ui')
  ],
  purge: ['./resources/views/**/*.blade.php'],
  experimental: {
    additionalBreakpoint: true,
    applyComplexClasses: true,
    darkModeVariant: true,
    extendedFontSizeScale: true,
    extendedSpacingScale: true,
    uniformColorPalette: false
  },
  future: {
    defaultLineHeights: true,
    purgeLayersByDefault: true,
    removeDeprecatedGapUtilities: true,
    standardFontWeights: true
  }
}
