import './bootstrap';
import { createApp } from 'vue';
import Alpine from 'alpinejs';
import 'bootstrap-icons/font/bootstrap-icons.css';


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

import ronda_dependencia from './vuepublic/RondaDependencia.vue';
app.component('ronda_dependencia-vue', ronda_dependencia);

import historico_dependencia from './vuepublic/HistoricoDependencia.vue';
app.component('historico_dependencia-vue', historico_dependencia);

import Dashboard from './vueadmin/Dashboard.vue';
app.component('dashboard-vue', Dashboard);

import Dependencias from './vueadmin/Dependencias.vue';
app.component('dependencias-vue', Dependencias);

import Respaldo from './vueadmin/Respaldo.vue';
app.component('respaldo-vue', Respaldo);

import Veda from './vueadmin/Veda.vue';
app.component('veda-vue', Veda);

import Datos from './vueadmin/Datos.vue';
app.component('datos-vue', Datos);

import Usuarios from './vueadmin/Usuarios.vue';
app.component('usuarios-vue', Usuarios);

import Roles from './vueadmin/Roles.vue';
app.component('roles-vue', Roles);

import Permisos from './vueadmin/Permisos.vue';
app.component('permisos-vue', Permisos);

import Bitacora from './vueadmin/Bitacora.vue';
app.component('bitacora-vue', Bitacora);

import Faq from './vueadmin/Faq.vue';
app.component('faq-vue', Faq);

app.mount('#app');
