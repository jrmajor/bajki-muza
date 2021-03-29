const mix = require('laravel-mix')

mix
  .js('resources/js/app.js', 'public/js')
  .postCss('resources/css/style.css', 'public/css', [
    require('@tailwindcss/jit'),
    require('autoprefixer'),
  ])
  .postCss('node_modules/croppr/src/css/croppr.css', 'public/css')
  .version()

if (!mix.inProduction()) {
  require('laravel-mix-bundle-analyzer')

  mix.bundleAnalyzer({
    analyzerMode: 'static',
    openAnalyzer: false,
  })
}
