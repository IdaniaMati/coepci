import './bootstrap';
import { createApp } from 'vue';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

const app = createApp({});

import Votacion from './vuepublic/Votacion.vue';
app.component('votacion-vue', Votacion);

import Dashboard from './vueadmin/Dashboard.vue';
app.component('dashboard-vue', Dashboard);

import Datos from './vueadmin/Datos.vue';
app.component('datos-vue', Datos);

import Historial from './vueadmin/Historial.vue';
app.component('historial-vue', Historial);

import Empleado from './vueadmin/Empleado.vue';
app.component('empleado-vue', Empleado);

app.mount('#app');
