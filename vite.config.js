import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

/**
 * Configuracion de Vite para el proyecto LICAM.
 *
 * Vite es el build tool que compila los archivos Vue, JS y CSS.
 * Configura la integracion con Laravel y habilita el soporte para Vue 3.
 *
 */
export default defineConfig({
    plugins: [
        // Plugin oficial de Laravel para integracion con Vite
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        // Plugin para soportar componentes Vue (.vue)
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
            // Alias '@' para acceder facilmente a la carpeta resources/js
            '@': '/resources/js',
        },
    },
});
