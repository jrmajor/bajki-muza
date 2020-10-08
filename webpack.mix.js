const mix = require('laravel-mix')
const path = require('path')
const tailwindcss = require('tailwindcss')

mix.js('resources/js/app.js', 'public/js')

mix.postCss('resources/css/style.css', 'public/css')
  .options({
    postCss: [tailwindcss('tailwind.config.js')]
  }).version()

mix.webpackConfig({
  resolve: {
    alias: {
      ziggy: path.resolve('vendor/tightenco/ziggy/src/js/route.js')
    }
  }
})

if (!mix.inProduction()) {
  require('laravel-mix-bundle-analyzer')

  mix.bundleAnalyzer({
    analyzerMode: 'static',
    openAnalyzer: false
  })
}
