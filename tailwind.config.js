module.exports = {
  purge: [
    './resources/views/**/*.blade.php',
    './resources/js/**/*.vue',
  ],
  theme: {
    extend: {
      spacing: {
        4.5: '1.125rem',
      },
    },
  },
  variants: {},
  plugins: [
    require('@tailwindcss/ui'),
  ],
  experimental: {
    applyComplexClasses: true,
    defaultLineHeights: true,
    extendedFontSizeScale: true,
    extendedSpacingScale: true,
    // uniformColorPalette: true,
  },
  future: {
    removeDeprecatedGapUtilities: true,
  },
}
