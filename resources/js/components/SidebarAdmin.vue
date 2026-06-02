<template>
    <!--
        Componente: Sidebar (barra lateral) del Administrador
        Se muestra a la izquierda en todas las vistas del administrador.
        Contiene el logo, menu de navegacion, datos del admin y boton de cerrar sesion.
    -->
    <aside class="w-64 bg-slate-900 text-white flex flex-col min-h-screen">

        <!-- LOGO E IDENTIDAD -->
        <div class="p-6 border-b border-slate-800">
            <router-link to="/admin/dashboard" class="flex items-center gap-3 hover:opacity-80 transition">
                <div class="w-11 h-11 bg-sky-600 rounded-xl flex items-center justify-center font-bold">
                    SJ
                </div>
                <div>
                    <h1 class="font-bold leading-tight">San Jorge</h1>
                    <p class="text-xs text-slate-400">Panel Administrativo</p>
                </div>
            </router-link>
        </div>

        <!-- MENU DE NAVEGACION -->
        <nav class="flex-1 p-4 space-y-1">

            <!-- Dashboard -->
            <router-link
                to="/admin/dashboard"
                class="flex items-center gap-3 px-4 py-3 rounded-xl transition"
                active-class="bg-sky-700 font-semibold"
                exact-active-class="bg-sky-700 font-semibold"
            >
                <span class="text-lg">📊</span>
                <span>Dashboard</span>
            </router-link>

            <!-- Reportes -->
            <router-link
                to="/admin/reportes"
                class="flex items-center gap-3 px-4 py-3 rounded-xl transition text-slate-300 hover:bg-slate-800"
                active-class="bg-sky-700 text-white font-semibold hover:bg-sky-700"
            >
                <span class="text-lg">📋</span>
                <span>Reportes</span>
                <span
                    v-if="contadorPendientes > 0"
                    class="ml-auto px-2 py-0.5 bg-amber-500 text-slate-900 text-xs font-bold rounded-full"
                >
                    {{ contadorPendientes }}
                </span>
            </router-link>

        </nav>

        <!-- DATOS DEL USUARIO Y BOTON DE LOGOUT -->
        <div class="p-4 border-t border-slate-800">
            <div class="flex items-center gap-3 p-3 bg-slate-800 rounded-xl">
                <div class="w-10 h-10 bg-amber-400 rounded-full flex items-center justify-center font-bold text-slate-900">
                    {{ iniciales }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-sm truncate">{{ nombreCompleto }}</p>
                    <p class="text-xs text-slate-400 truncate">Administrador</p>
                </div>
                <button
                    @click="cerrarSesion"
                    :disabled="cerrandoSesion"
                    title="Cerrar sesion"
                    class="text-slate-400 hover:text-red-400 disabled:opacity-50 transition"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H5a3 3 0 01-3-3V7a3 3 0 013-3h5a3 3 0 013 3v1" />
                    </svg>
                </button>
            </div>
        </div>
    </aside>
</template>

<script setup>
/**
 * Componente: SidebarAdmin
 *
 * Barra lateral oscura para las vistas del administrador.
 * Muestra el menu de navegacion, los datos del admin autenticado
 * y un boton para cerrar sesion. Incluye un contador dinamico
 * de reportes pendientes.
 *
 * @project LICAM - Linea Ciudadana de Atencion Municipal
 */

import { ref, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import api from '@/services/api';

// Hooks de Vue Router y Pinia
const router = useRouter();
const authStore = useAuthStore();

// Estado para el contador de pendientes
const contadorPendientes = ref(0);
const cerrandoSesion = ref(false);

/**
 * Nombre completo del usuario administrador.
 */
const nombreCompleto = computed(() => {
    if (!authStore.user) return 'Administrador';
    return `${authStore.user.name} ${authStore.user.apellido || ''}`;
});

/**
 * Iniciales del administrador para mostrar en el avatar.
 */
const iniciales = computed(() => {
    if (!authStore.user) return '?';
    const inicialNombre = authStore.user.name?.charAt(0) || '';
    const inicialApellido = authStore.user.apellido?.charAt(0) || '';
    return (inicialNombre + inicialApellido).toUpperCase();
});

/**
 * Carga el contador de reportes pendientes desde la API.
 * Se ejecuta al montar el componente para mostrar la cantidad en el menu.
 */
const cargarContadorPendientes = async () => {
    try {
        const response = await api.get('/reportes', {
            params: { estado: 'pendiente' }
        });
        contadorPendientes.value = response.data.total || 0;
    } catch (error) {
        console.error('Error al cargar contador de pendientes:', error);
    }
};

/**
 * Cierra la sesion del administrador y lo redirige al login.
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

/**
 * Hook de Vue: se ejecuta al montar el componente.
 * Carga el contador de pendientes para mostrarlo en el menu.
 */
onMounted(() => {
    cargarContadorPendientes();
});
</script>
