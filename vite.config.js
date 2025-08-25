import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import legacy from '@vitejs/plugin-legacy';

export default defineConfig({
  base: './',
  plugins: [
    laravel({
      input: ['resources/js/app.js'],
      refresh: true,
    }),
    legacy({
      targets: ['defaults', 'not IE 11'],
    }),
  ],
});
