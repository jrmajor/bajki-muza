const mix = require('laravel-mix');
const tailwindcss = require('tailwindcss')

mix.js('resources/js/app.js', 'public/js')
  .options({
    terser: { extractComments: false }
  })

mix.postCss('resources/sass/app.css', 'public/css')
  .options({
    // processCssUrls: false,
    postCss: [ tailwindcss('tailwind.config.js') ],
  }).version()
