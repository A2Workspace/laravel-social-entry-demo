const mix = require("laravel-mix");

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
**/

mix.js("resources/js/app.js", "public/assets").vue({ version: 2 });

mix.options({
  hmrOptions: {
    host: (process.argv.includes('--host'))
      ? process.argv[process.argv.indexOf('--host') + 1]
      : 'localhost',
    port: 8080,
  },
})
