<template>
    <!--
        Componente: Barra de navegacion del Ciudadano
        Se muestra en la parte superior de todas las vistas del ciudadano.
        Contiene el logo, menu de navegacion, datos del usuario y boton de cerrar sesion.
    -->
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">

            <!-- LOGO E IDENTIDAD -->
            <router-link to="/ciudadano/reportes" class="flex items-center gap-3 hover:opacity-80 transition">
                <div class="w-12 h-12 bg-sky-700 rounded-full flex items-center justify-center text-white font-bold text-lg">
                    SJ
                </div>
                <div class="hidden sm:block">
                    <h1 class="font-bold text-slate-800 leading-tight">Alcaldia de San Jorge</h1>
                    <p class="text-xs text-slate-500">Portal Ciudadano</p>
                </div>
            </router-link>

            <!-- MENU DE NAVEGACION (escritorio) -->
            <nav class="hidden md:flex items-center gap-8">
                <router-link
                    to="/ciudadano/reportes"
                    class="text-slate-700 hover:text-sky-700 font-medium transition"
                    active-class="text-sky-700 border-b-2 border-sky-700 pb-1 font-bold"
                >
                    Mis Reportes
                </router-link>
                <router-link
                    to="/ciudadano/crear-reporte"
                    class="text-slate-700 hover:text-sky-700 font-medium transition"
                    active-class="text-sky-700 border-b-2 border-sky-700 pb-1 font-bold"
                >
                    Crear Reporte
                </router-link>
            </nav>

            <!-- DATOS DEL USUARIO Y BOTON DE LOGOUT -->
            <div class="flex items-center gap-3">
                <span class="hidden sm:block text-sm text-slate-600">
                    Hola, <b>{{ nombreUsuario }}</b>
                </span>

                <!-- Avatar con iniciales del usuario -->
                <div class="w-10 h-10 bg-amber-400 rounded-full flex items-center justify-center font-bold text-slate-800">
                    {{ iniciales }}
                </div>

                <!-- Boton de cerrar sesion -->
                <button
                    @click="cerrarSesion"
                    :disabled="cerrandoSesion"
                    title="Cerrar sesion"
                    class="ml-2 p-2 text-slate-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition disabled:opacity-50"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H5a3 3 0 01-3-3V7a3 3 0 013-3h5a3 3 0 013 3v1" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- MENU MOVIL (visible solo en pantallas pequeñas) -->
        <div class="md:hidden border-t border-slate-200 px-6 py-3 flex gap-6 bg-slate-50">
            <router-link
                to="/ciudadano/reportes"
                class="text-slate-700 text-sm font-medium"
                active-class="text-sky-700 font-bold"
            >
                Mis Reportes
            </router-link>
            <router-link
                to="/ciudadano/crear-reporte"
                class="text-slate-700 text-sm font-medium"
                active-class="text-sky-700 font-bold"
            >
                Crear Reporte
            </router-link>
        </div>
    </header>
</template>

<script setup>
/**
 * Componente: NavbarCiudadano
 *
 * Barra de navegacion superior para las vistas del ciudadano.
 * Muestra el nombre del usuario autenticado, su avatar con iniciales,
 * los enlaces de navegacion y un boton para cerrar sesion.
 *
 * @project LICAM - Linea Ciudadana de Atencion Municipal
 */

import { ref, computed } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';

// Hooks de Vue Router y Pinia
const router = useRouter();
const authStore = useAuthStore();

// Estado para controlar el boton de logout
const cerrandoSesion = ref(false);

/**
 * Nombre completo del usuario autenticado.
 * Si por alguna razon no hay usuario, retorna "Usuario".
 */
const nombreUsuario = computed(() => {
    if (!authStore.user) return 'Usuario';
    return `${authStore.user.name} ${authStore.user.apellido?.charAt(0) || ''}.`;
});

/**
 * Iniciales del usuario para mostrar en el avatar.
 * Toma la primera letra del nombre y la primera del apellido.
 */
const iniciales = computed(() => {
    if (!authStore.user) return '?';
    const inicialNombre = authStore.user.name?.charAt(0) || '';
    const inicialApellido = authStore.user.apellido?.charAt(0) || '';
    return (inicialNombre + inicialApellido).toUpperCase();
});

/**
 * Cierra la sesion del usuario y lo redirige a la pagina de login.
 */
const cerrarSesion = async () => {
    cerrandoSesion.value = true;
    try {
        await authStore.logout();
        router.push({ name: 'login' });
    } catch (error) {
        console.error('Error al cerrar sesion:', error);
    } finally {
        cerrandoSesion.value = false;
    }
};
</script>
