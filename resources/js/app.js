/**
 * Punto de entrada principal de la aplicacion Vue.js para LICAM.
 *
 * Inicializa Vue 3 con sus plugins:
 * - Vue Router para manejo de rutas
 * - Pinia para gestion de estado global
 *
 * @project LICAM - Linea Ciudadana de Atencion Municipal
 */

import './bootstrap';
import '../css/app.css';

import { createApp } from 'vue';
import { createPinia } from 'pinia';
import App from './App.vue';
import router from './router';

// Crear la instancia de Vue
const app = createApp(App);

// Registrar plugins
app.use(createPinia());
app.use(router);

// Montar la aplicacion en el div con id="app"
app.mount('#app');
