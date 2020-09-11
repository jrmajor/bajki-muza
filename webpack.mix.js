const mix = require('laravel-mix');
const tailwindcss = require('tailwindcss')

mix.js('resources/js/app.js', 'public/js')
  .options({
    terser: { extractComments: false }
  })

mix.postCss('resources/css/app.css', 'public/css')
  .options({
    postCss: [ tailwindcss('tailwind.config.js') ],
  }).version()
