import { fileURLToPath, URL } from 'node:url'

import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import vueDevTools from 'vite-plugin-vue-devtools'

// https://vite.dev/config/

export default defineConfig(({ command, mode }) => {
  // Check if running in development serve command
  if (command === 'serve') {
    return {
      // Development-specific configuration
      plugins: [vue()],
      resolve: {
        alias: {
          '@': fileURLToPath(new URL('./src', import.meta.url))
        },
      },
    };
  } else {
    // command === 'build'
    return {
      // Production-specific configuration
      plugins: [vue()],
      resolve: {
        alias: {
          '@': fileURLToPath(new URL('./src', import.meta.url))
        },
      },
      // Specify base path if deploying to a nested directory, e.g., GitHub Pages
      base: '/mosaic/'
    };
  }
});


// export default defineConfig({
//   base: '/mosaic/'
//   plugins: [
//     vue()
//   ],
//   resolve: {
//     alias: {
//       '@': fileURLToPath(new URL('./src', import.meta.url))
//     },
//   },
// })
