/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
      './views/**/*.latte',
      './assets/**/*.js',
      './assets/**/*.ts',
  ],
  theme: {
    extend: {
      colors: {
          'app-dark': '#151431',
          'message-info': '#4444f1',
          'message-success': '#68b468',
          'message-notice': '#808080',
          'message-warning': '#de9c39',
          'message-error': '#b23d3d'
      }
    },
  },
  plugins: [],
}

