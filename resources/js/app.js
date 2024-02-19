import './bootstrap';
import { createApp } from 'vue';



import Alpine from 'alpinejs';



window.Alpine = Alpine;

Alpine.start();

const app = createApp({});

import Principal from './vuepublic/Principal.vue';
app.component('principal-vue', Principal);

import Votacion from './vuepublic/Votacion.vue';
app.component('votacion-vue', Votacion);

import Empleado from './vuepublic/Empleado.vue';
app.component('empleado-vue', Empleado);

import nominacion from './vuepublic/Nominacion.vue';
app.component('nominacion-vue', nominacion);

import ronda from './vuepublic/Ronda.vue';
app.component('ronda-vue', ronda);

import historico from './vuepublic/Historico.vue';
app.component('historico-vue', historico);


import Dashboard from './vueadmin/Dashboard.vue';
app.component('dashboard-vue', Dashboard);

import Datos from './vueadmin/Datos.vue';
app.component('datos-vue', Datos);

import Historial from './vueadmin/Historial.vue';
app.component('historial-vue', Historial);

app.mount('#app');
