const laravel = require('laravel-vite-plugin');

module.exports = {
  plugins: [
    laravel({
      input: ['resources/css/app.css', 'resources/js/app.js'],
      refresh: true,
    }),
  ],
};
