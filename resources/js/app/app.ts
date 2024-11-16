import { createApp } from "vue";
import App from "./App.vue";
import { router, store } from "./providers";

const initializeApp = createApp(App).use(store).use(router).mount("#app");
export const app = initializeApp;
