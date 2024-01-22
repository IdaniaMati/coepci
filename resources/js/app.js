import './bootstrap';
import { createApp } from 'vue';

const app = createApp({})

import Votacion from './vuepublic/votacion.vue';
app.component('votacion-vue', Votacion)



app.mount('#app')