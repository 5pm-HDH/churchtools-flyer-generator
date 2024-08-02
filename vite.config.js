import { defineConfig } from 'vite';
import vue from "@vitejs/plugin-vue";
import vuetify, {transformAssetUrls} from "vite-plugin-vuetify";
import { fileURLToPath, URL } from "node:url";

export default defineConfig({
    plugins: [
        vue({
            template: {
                compilerOptions: {
                    compatConfig: {
                        MODE: 2,
                    },
                },
                transformAssetUrls,
            },
        }),
        vuetify({ autoImport: true }),
    ],
    define: {
        "process.env": {},
    },
    globals: true,
    root: "resources/js",
    resolve: {
        alias: {
            vue: "vue/dist/vue.esm-bundler.js",
            "@": fileURLToPath(new URL("resources/js", import.meta.url)),
        },
        extensions: [".js", ".vue", ".ts"],
    },
    build: {
        outDir: "../../public/js",
        assetsDir: "",
        lib: {
            entry: "app.js",
            formats: ["es"],
            fileName: "app",
            name: "app",
        },
        rollupOptions: {
            external: ["vue"],
            output: {
                globals: {
                    vue: "Vue",
                },
            },
        },
        minify: false,
    },
    server: {
        port: 3000,
    },
    test: {
        globals: true,
        environment: "jsdom",
        resolve: {
            alias: {
                "@": fileURLToPath(new URL("resources/js", import.meta.url)),
            },
        },
        server: {
            deps: {
                inline: ["vuetify"],
            },
        },
    },
});

