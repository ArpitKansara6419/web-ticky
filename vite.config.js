import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { Modal } from 'flowbite';
import { resolve, join, relative } from "path";
import { readdirSync, statSync } from "fs";

const jsFiles = getJsFiles(resolve(__dirname, "resources/js"));

export default defineConfig({
    //  server: {
    //     host: '192.168.1.12',
    //     port: 8000,
    //     hmr: {
    //         host: '192.168.1.12',
    //     },
    // },
    safelist: [
        'sm:ml-16',
        'sm:ml-64'
    ],
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',

                ...jsFiles,
            ],
            refresh: true,
        }),
    ],
});

function getJsFiles(dir) {
    let jsFiles = [];
    const entries = readdirSync(dir);

    for (const entry of entries) {
        const fullPath = join(dir, entry);
        if (statSync(fullPath).isDirectory()) {
            jsFiles = jsFiles.concat(getJsFiles(fullPath));
        } else if (fullPath.endsWith(".js")) {
            jsFiles.push(fullPath.replace(__dirname, "").replace(/\\/g, "/"));
        }
    }

    return jsFiles;
}