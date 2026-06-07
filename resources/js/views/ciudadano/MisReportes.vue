<template>
    <!--
        Vista: Mis Reportes (Ciudadano)
        Muestra el listado de reportes creados por el ciudadano autenticado.
        Incluye contadores por estado, filtros y opciones de navegacion.
    -->
    <div class="min-h-screen bg-slate-50">

        <!-- Barra de navegacion del ciudadano -->
        <NavbarCiudadano />

        <!-- Contenido principal -->
        <main class="max-w-7xl mx-auto px-6 py-8">

            <!-- ENCABEZADO DE LA PAGINA -->
            <div class="flex justify-between items-end mb-8 flex-wrap gap-4">
                <div>
                    <h2 class="text-3xl font-extrabold text-slate-800 mb-2">Mis Reportes</h2>
                    <p class="text-slate-600">Historial y seguimiento de tus reportes enviados</p>
                </div>
                <router-link
                    to="/ciudadano/crear-reporte"
                    class="px-6 py-3 bg-sky-700 text-white font-bold rounded-xl hover:bg-sky-800 shadow-lg shadow-sky-700/30 transition flex items-center gap-2"
                >
                    <span>+</span> Nuevo Reporte
                </router-link>
            </div>

            <!-- TARJETAS DE RESUMEN POR ESTADO -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white rounded-2xl p-5 border-l-4 border-slate-400 shadow-sm">
                    <p class="text-xs text-slate-500 font-semibold uppercase tracking-wide">Total</p>
                    <p class="text-3xl font-extrabold text-slate-800 mt-1">{{ contadores.total }}</p>
                </div>
                <div class="bg-white rounded-2xl p-5 border-l-4 border-amber-500 shadow-sm">
                    <p class="text-xs text-slate-500 font-semibold uppercase tracking-wide">Pendientes</p>
                    <p class="text-3xl font-extrabold text-amber-600 mt-1">{{ contadores.pendientes }}</p>
                </div>
                <div class="bg-white rounded-2xl p-5 border-l-4 border-blue-500 shadow-sm">
                    <p class="text-xs text-slate-500 font-semibold uppercase tracking-wide">En Proceso</p>
                    <p class="text-3xl font-extrabold text-blue-600 mt-1">{{ contadores.enProceso }}</p>
                </div>
                <div class="bg-white rounded-2xl p-5 border-l-4 border-emerald-500 shadow-sm">
                    <p class="text-xs text-slate-500 font-semibold uppercase tracking-wide">Resueltos</p>
                    <p class="text-3xl font-extrabold text-emerald-600 mt-1">{{ contadores.resueltos }}</p>
                </div>
            </div>

            <!-- BOTONES DE FILTRO -->
            <div class="bg-white rounded-2xl p-4 mb-6 shadow-sm flex flex-wrap gap-3 items-center">
                <span class="text-sm font-semibold text-slate-700">Filtrar:</span>
                <button
                    @click="aplicarFiltro('')"
                    :class="filtroActivo === ''
                        ? 'bg-sky-700 text-white'
                        : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
                    class="px-4 py-2 text-sm font-semibold rounded-lg transition"
                >
                    Todos ({{ contadores.total }})
                </button>
                <button
                    @click="aplicarFiltro('pendiente')"
                    :class="filtroActivo === 'pendiente'
                        ? 'bg-amber-600 text-white'
                        : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
                    class="px-4 py-2 text-sm font-semibold rounded-lg transition"
                >
                    Pendientes
                </button>
                <button
                    @click="aplicarFiltro('en_proceso')"
                    :class="filtroActivo === 'en_proceso'
                        ? 'bg-blue-600 text-white'
                        : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
                    class="px-4 py-2 text-sm font-semibold rounded-lg transition"
                >
                    En Proceso
                </button>
                <button
                    @click="aplicarFiltro('resuelto')"
                    :class="filtroActivo === 'resuelto'
                        ? 'bg-emerald-600 text-white'
                        : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
                    class="px-4 py-2 text-sm font-semibold rounded-lg transition"
                >
                    Resueltos
                </button>
            </div>

            <!-- ESTADO DE CARGA -->
            <div v-if="cargando" class="bg-white rounded-2xl p-12 shadow-sm text-center">
                <div class="text-5xl mb-3 animate-pulse">⏳</div>
                <p class="text-slate-600">Cargando tus reportes...</p>
            </div>

            <!-- MENSAJE DE ERROR -->
            <div v-else-if="error" class="bg-red-50 border-2 border-red-200 rounded-2xl p-6 text-red-700">
                <p class="font-semibold">Error al cargar los reportes:</p>
                <p class="text-sm mt-1">{{ error }}</p>
            </div>

            <!-- LISTA VACIA -->
            <div v-else-if="reportes.length === 0" class="bg-white rounded-2xl p-12 shadow-sm text-center">
                <div class="text-6xl mb-4">📋</div>
                <h3 class="text-xl font-bold text-slate-800 mb-2">No tienes reportes aun</h3>
                <p class="text-slate-500 mb-6">
                    {{ filtroActivo
                        ? 'No hay reportes con el filtro seleccionado.'
                        : 'Crea tu primer reporte para empezar a contribuir con tu comunidad.' }}
                </p>
                <router-link
                    to="/ciudadano/crear-reporte"
                    class="inline-block px-6 py-3 bg-sky-700 text-white font-bold rounded-xl hover:bg-sky-800"
                >
                    Crear mi primer reporte
                </router-link>
            </div>

            <!-- LISTA DE REPORTES -->
            <div v-else class="space-y-4">
                <div
                    v-for="reporte in reportes"
                    :key="reporte.id"
                    class="bg-white rounded-2xl p-6 shadow-sm hover:shadow-md transition-all"
                >
                    <div class="flex gap-5 flex-col sm:flex-row">

                        <!-- Icono visual del reporte segun categoria -->
                        <div
                            class="w-full sm:w-32 h-32 rounded-xl flex-shrink-0 flex items-center justify-center text-5xl"
                            :style="{ backgroundColor: reporte.categoria?.color || '#94A3B8' }"
                        >
                            {{ reporte.categoria?.icono || '📋' }}
                        </div>

                        <!-- Detalles del reporte -->
                        <div class="flex-1">
                            <!-- Etiquetas de categoria, estado y prioridad -->
                            <div class="flex items-center gap-2 mb-2 flex-wrap">
                                <span
                                    v-if="reporte.categoria"
                                    class="px-3 py-1 text-xs font-bold rounded-full uppercase"
                                    :style="{
                                        backgroundColor: reporte.categoria.color + '20',
                                        color: reporte.categoria.color
                                    }"
                                >
                                    {{ reporte.categoria.nombre }}
                                </span>
                                <span
                                    v-else
                                    class="px-3 py-1 bg-slate-100 text-slate-600 text-xs font-bold rounded-full"
                                >
                                    SIN CATEGORIZAR
                                </span>

                                <!-- Estado -->
                                <span
                                    :class="claseEstado(reporte.estado)"
                                    class="px-3 py-1 text-xs font-bold rounded-full uppercase"
                                >
                                    {{ etiquetaEstado(reporte.estado) }}
                                </span>

                                <!-- Prioridad -->
                                <span
                                    :class="clasePrioridad(reporte.prioridad)"
                                    class="px-3 py-1 text-xs font-bold rounded-full uppercase"
                                >
                                    PRIORIDAD {{ reporte.prioridad }}
                                </span>
                            </div>

                            <!-- Titulo y datos -->
                            <h3 class="font-bold text-slate-800 text-lg">{{ reporte.titulo }}</h3>
                            <p class="text-sm text-slate-500 mt-1">
                                📍 {{ reporte.direccion || 'Sin direccion especificada' }} · #REP-{{ reporte.id.toString().padStart(4, '0') }}
                            </p>

                            <!-- Descripcion (truncada) -->
                            <p class="text-slate-600 text-sm mt-3 line-clamp-2">
                                {{ reporte.descripcion }}
                            </p>

                            <!-- Miniaturas de imagenes -->
                            <div v-if="reporte.imagenes && reporte.imagenes.length > 0" class="flex gap-2 mt-3">
                                <a
                                    v-for="img in reporte.imagenes"
                                    :key="img.id"
                                    :href="img.url_completa"
                                    target="_blank"
                                    class="block w-16 h-16 rounded-lg overflow-hidden border border-slate-200 hover:opacity-90 transition"
                                >
                                    <img
                                        :src="img.url_completa"
                                        :alt="img.nombre_archivo"
                                        class="w-full h-full object-cover"
                                    >
                                </a>
                            </div>


                            <!-- Pie con fechas y boton de detalle -->
                            <div class="flex justify-between items-center pt-3 mt-3 border-t border-slate-100 flex-wrap gap-2">
                                <div class="flex gap-4 text-xs text-slate-500 flex-wrap">
                                    <span>📅 Reportado: {{ formatearFecha(reporte.fecha_reporte) }}</span>
                                    <span v-if="reporte.fecha_resolucion">
                                        ✅ Resuelto: {{ formatearFecha(reporte.fecha_resolucion) }}
                                    </span>
                                </div>
                                <router-link
                                    :to="`/ciudadano/reporte/${reporte.id}`"
                                    class="text-sky-700 text-sm font-bold hover:underline"
                                >
                                    Ver detalle →
                                </router-link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>
</template>

<script setup>
/**
 * Vista del Ciudadano: Mis Reportes
 *
 * Muestra el listado completo de reportes creados por el ciudadano autenticado.
 * Incluye contadores de resumen por estado, filtros interactivos y vista detallada
 * de cada reporte con su estado, categoria, prioridad y fechas.
 *
 * Se conecta con la API del backend para obtener los datos en tiempo real.
 *
 */

import { ref, computed, onMounted } from 'vue';
import { useAuthStore } from '@/stores/auth';
import api from '@/services/api';
import NavbarCiudadano from '@/components/NavbarCiudadano.vue';

// Store de autenticacion
const authStore = useAuthStore();

// Estado reactivo de la vista
const reportes = ref([]);
const cargando = ref(false);
const error = ref('');
const filtroActivo = ref('');

/**
 * Contadores de reportes por estado.
 * Se calculan automaticamente a partir del arreglo 'reportes'.
 */
const contadores = computed(() => {
    return {
        total: reportes.value.length,
        pendientes: reportes.value.filter(r => r.estado === 'pendiente').length,
        enProceso: reportes.value.filter(r => r.estado === 'en_proceso').length,
        resueltos: reportes.value.filter(r => r.estado === 'resuelto').length,
    };
});

/**
 * Carga los reportes del usuario autenticado desde la API.
 * Aplica el filtro de estado si esta activo.
 */
const cargarReportes = async () => {
    cargando.value = true;
    error.value = '';

    try {
        // Preparar parametros de filtro
        const params = {
            user_id: authStore.user.id, // Solo los reportes del usuario logueado
        };
        if (filtroActivo.value) {
            params.estado = filtroActivo.value;
        }

        // Llamar a la API
        const response = await api.get('/reportes', { params });
        reportes.value = response.data.data || [];

    } catch (err) {
        console.error('Error al cargar reportes:', err);
        error.value = err.response?.data?.message || 'No se pudieron cargar los reportes.';
    } finally {
        cargando.value = false;
    }
};

/**
 * Aplica un filtro de estado y recarga la lista de reportes.
 *
 * @param {string} estado - Estado a filtrar ('', 'pendiente', 'en_proceso', 'resuelto')
 */
const aplicarFiltro = (estado) => {
    filtroActivo.value = estado;
    cargarReportes();
};

/**
 * Retorna las clases CSS para mostrar el estado del reporte.
 *
 * @param {string} estado - Estado del reporte
 * @returns {string} Clases de Tailwind
 */
const claseEstado = (estado) => {
    const clases = {
        pendiente: 'bg-amber-100 text-amber-700',
        en_proceso: 'bg-blue-100 text-blue-700',
        resuelto: 'bg-emerald-100 text-emerald-700',
        rechazado: 'bg-red-100 text-red-700',
    };
    return clases[estado] || 'bg-slate-100 text-slate-700';
};

/**
 * Retorna la etiqueta legible del estado del reporte.
 *
 * @param {string} estado - Estado del reporte
 * @returns {string} Etiqueta visible
 */
const etiquetaEstado = (estado) => {
    const etiquetas = {
        pendiente: '⏳ Pendiente',
        en_proceso: '🔄 En Proceso',
        resuelto: '✓ Resuelto',
        rechazado: '✕ Rechazado',
    };
    return etiquetas[estado] || estado;
};

/**
 * Retorna las clases CSS para mostrar la prioridad del reporte.
 *
 * @param {string} prioridad - Prioridad del reporte
 * @returns {string} Clases de Tailwind
 */
const clasePrioridad = (prioridad) => {
    const clases = {
        baja: 'bg-slate-100 text-slate-700',
        media: 'bg-slate-100 text-slate-700',
        alta: 'bg-orange-100 text-orange-700',
        urgente: 'bg-red-100 text-red-700',
    };
    return clases[prioridad] || 'bg-slate-100 text-slate-700';
};

/**
 * Formatea una fecha ISO al formato local "DD MMM YYYY".
 *
 * @param {string} fechaISO - Fecha en formato ISO 8601
 * @returns {string} Fecha formateada
 */
const formatearFecha = (fechaISO) => {
    if (!fechaISO) return '';
    const fecha = new Date(fechaISO);
    return fecha.toLocaleDateString('es-SV', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    });
};

/**
 * Hook de Vue: se ejecuta al montar el componente.
 * Carga los reportes del usuario al entrar a la vista.
 */
onMounted(() => {
    cargarReportes();
});
</script>

<style scoped>
/* Trunca el texto a 2 lineas con "..." al final */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
