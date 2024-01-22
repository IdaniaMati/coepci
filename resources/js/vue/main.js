import { createApp } from "vue";
import App from "./App.vue";

import Oruga from '@oruga-ui/oruga-next';
createApp().use(Oruga);


const app =createApp(App)

app.mount('#app')