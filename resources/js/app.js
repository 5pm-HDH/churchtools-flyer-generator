import { createApp } from 'vue';
import vuetifyPlugin from "@/vuetifyPlugin.js";
import StartPage from "@/Components/StartPage.vue";

const app = createApp({});
app.use(vuetifyPlugin);

app.component("start-page", StartPage);

app.mount('#app');

