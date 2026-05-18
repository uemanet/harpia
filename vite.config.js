import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { viteStaticCopy } from 'vite-plugin-static-copy';
import { globSync } from 'glob';
import inject from '@rollup/plugin-inject';  // <-- troca aqui

const modulePages = globSync('modulos/*/Resources/js/pages/**/*.js');

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
                ...modulePages,
            ],
            refresh: true,
        }),
        viteStaticCopy({
            targets: [
                { src: 'node_modules/font-awesome/fonts/*', dest: '../fonts' }
            ]
        })
    ],
    build: {
        rollupOptions: {
            plugins: [
                inject({          // <-- vai aqui, dentro do rollupOptions
                    $: 'jquery',
                    jQuery: 'jquery',
                })
            ],
            output: {
                manualChunks(id) {
                    if (id.includes('node_modules')) {
                        return 'vendor';
                    }
                }
            }
        }
    }
});