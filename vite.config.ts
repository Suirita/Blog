import vue from '@vitejs/plugin-vue';
import autoprefixer from 'autoprefixer';
import laravel from 'laravel-vite-plugin';
import path from 'path';
import tailwindcss from 'tailwindcss';
import { resolve } from 'node:path';
import { defineConfig } from 'vite';

export default defineConfig({
  plugins: [
    laravel({
      input: ['resources/js/app.ts'],
      ssr: 'resources/js/ssr.ts',
      // explicitly set these (defaults are the same, but it's clearer):
      publicDirectory: 'public',
      buildDirectory: 'build',
      refresh: true,
    }),
    vue({
      template: {
        transformAssetUrls: {
          base: null,
          includeAbsolute: false,
        },
      },
    }),
  ],

  resolve: {
    alias: {
      '@': path.resolve(__dirname, './resources/js'),
      'ziggy-js': resolve(__dirname, 'vendor/tightenco/ziggy'),
    },
  },

  css: {
    postcss: {
      plugins: [tailwindcss, autoprefixer],
    },
  },

  build: {
    // this must match Laravel’s expectation of public/build
    outDir: 'public/build',
    manifest: true,
    rollupOptions: {
      // ensure your entry matches exactly
      input: 'resources/js/app.ts',
    },
  },
});
