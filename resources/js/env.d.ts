/// <reference types="vite/client" />

interface ImportMetaEnv {
    readonly VITE_HOST_NAME: string;
    // Другие переменные окружения...
}

interface ImportMeta {
    readonly env: ImportMetaEnv;
}
