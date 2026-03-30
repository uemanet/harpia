import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { viteStaticCopy } from 'vite-plugin-static-copy';
import { globSync } from 'glob';

// Busca todos os JS dentro da pasta 'pages' de todos os módulos
const modulePages = globSync('modulos/*/Resources/js/pages/**/*.js');

// TODO: Remover - apenas para debug
console.log('--- ARQUIVOS ENCONTRADOS PELO GLOB ---');
console.log(modulePages);
console.log('--------------------------------------');

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',

                // Espalha todos os arquivos encontrados automaticamente aqui
                ...modulePages,
            ],
            // Ativa o Hot Module Replacement (atualiza a página sozinho ao salvar no PHP/JS/CSS)
            refresh: true,
        }),
        // Replica o comportamento do mix.copy para mover as fontes do font-awesome para a pasta public
        viteStaticCopy({
            targets: [
                {
                    src: 'node_modules/font-awesome/fonts/*',
                    dest: '../fonts'
                }
            ]
        })
    ]
});