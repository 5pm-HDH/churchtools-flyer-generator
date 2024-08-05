import { createApp } from 'vue';
import vuetifyPlugin from "@/vuetifyPlugin.js";
import LandingPage from "@/Components/LandingPage.vue";

const app = createApp({});
app.use(vuetifyPlugin);

app.component("landing-page", LandingPage);

app.mount('#app');

