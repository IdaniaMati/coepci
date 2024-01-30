import './bootstrap';
import { createApp } from 'vue';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

const app = createApp({});

import Formulario from './vuepublic/Votacion.vue';
app.component('formulario-vue', Formulario);

import Dashboard from './vueadmin/Dashboard.vue';
app.component('dashboard-vue', Dashboard);

import Datos from './vueadmin/Datos.vue';
app.component('datos-vue', Datos);

import Historial from './vueadmin/Historial.vue';
app.component('historial-vue', Historial);

app.mount('#app');
