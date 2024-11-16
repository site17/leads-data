import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";
import path from "path"; // Импортируйте модуль path

export default defineConfig({
    plugins: [laravel(["resources/js/app/app.ts"]), vue()],
    resolve: {
        alias: {
            "@": path.resolve(__dirname, "resources/js"), // Настройка алиаса '@' на 'resources/js'
        },
    },
    server: {
        port: 3001, // Убедитесь, что порт 3000 свободен
        host: "localhost",
    },
});
