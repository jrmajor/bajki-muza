const mix = require('laravel-mix')
const tailwindcss = require('tailwindcss')
const path = require('path')

mix.js('resources/js/app.js', 'public/js')

mix.postCss('resources/css/style.css', 'public/css')
  .options({
    postCss: [tailwindcss('tailwind.config.js')]
  }).version()

mix.postCss('node_modules/croppr/src/css/croppr.css', 'public/css')

if (!mix.inProduction()) {
  require('laravel-mix-bundle-analyzer')

  mix.bundleAnalyzer({
    analyzerMode: 'static',
    openAnalyzer: false
  })
}
