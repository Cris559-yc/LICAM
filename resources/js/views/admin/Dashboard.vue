<template>
    <!--
        Vista: Dashboard del Administrador
        Panel principal con estadisticas generales del sistema,
        graficas por categoria y listado de reportes recientes.
    -->
    <div class="flex min-h-screen bg-slate-100">

        <!-- Sidebar del administrador -->
        <SidebarAdmin />

        <!-- CONTENIDO PRINCIPAL -->
        <main class="flex-1 overflow-y-auto">

            <!-- TOPBAR -->
            <header class="bg-white border-b border-slate-200 px-8 py-4 flex justify-between items-center sticky top-0 z-10">
                <div>
                    <h2 class="text-2xl font-extrabold text-slate-800">Dashboard</h2>
                    <p class="text-sm text-slate-500">Resumen general del sistema · {{ fechaHoy }}</p>
                </div>
                <router-link
                    to="/admin/reportes"
                    class="px-5 py-2.5 bg-sky-700 text-white font-semibold rounded-xl hover:bg-sky-800 transition"
                >
                    Ver todos los reportes
                </router-link>
            </header>

            <!-- CONTENIDO -->
            <div class="p-8">

                <!-- ESTADO DE CARGA -->
                <div v-if="cargando" class="bg-white rounded-2xl p-12 shadow-sm text-center">
                    <div class="text-5xl mb-3 animate-pulse">⏳</div>
                    <p class="text-slate-600">Cargando datos del sistema...</p>
                </div>

                <!-- CONTENIDO REAL -->
                <div v-else>

                    <!-- TARJETAS DE KPIS -->
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-8">

                        <!-- Total -->
                        <div class="bg-white rounded-2xl p-6 shadow-sm">
                            <div class="flex justify-between items-start mb-3">
                                <div class="w-12 h-12 bg-sky-100 text-sky-700 rounded-xl flex items-center justify-center text-2xl">
                                    📋
                                </div>
                            </div>
                            <p class="text-3xl font-extrabold text-slate-800">{{ kpis.total }}</p>
                            <p class="text-sm text-slate-500 font-medium">Total de reportes</p>
                        </div>

                        <!-- Pendientes -->
                        <div class="bg-white rounded-2xl p-6 shadow-sm">
                            <div class="flex justify-between items-start mb-3">
                                <div class="w-12 h-12 bg-amber-100 text-amber-700 rounded-xl flex items-center justify-center text-2xl">
                                    ⏳
                                </div>
                            </div>
                            <p class="text-3xl font-extrabold text-slate-800">{{ kpis.pendientes }}</p>
                            <p class="text-sm text-slate-500 font-medium">Pendientes</p>
                        </div>

                        <!-- En proceso -->
                        <div class="bg-white rounded-2xl p-6 shadow-sm">
                            <div class="flex justify-between items-start mb-3">
                                <div class="w-12 h-12 bg-blue-100 text-blue-700 rounded-xl flex items-center justify-center text-2xl">
                                    🔄
                                </div>
                            </div>
                            <p class="text-3xl font-extrabold text-slate-800">{{ kpis.enProceso }}</p>
                            <p class="text-sm text-slate-500 font-medium">En proceso</p>
                        </div>

                        <!-- Resueltos -->
                        <div class="bg-white rounded-2xl p-6 shadow-sm">
                            <div class="flex justify-between items-start mb-3">
                                <div class="w-12 h-12 bg-emerald-100 text-emerald-700 rounded-xl flex items-center justify-center text-2xl">
                                    ✓
                                </div>
                            </div>
                            <p class="text-3xl font-extrabold text-slate-800">{{ kpis.resueltos }}</p>
                            <p class="text-sm text-slate-500 font-medium">Resueltos</p>
                        </div>

                    </div>

                    <!-- GRAFICA DE BARRAS POR CATEGORIA Y TARJETA DE EFICIENCIA -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-8">

                        <!-- Grafica de distribucion por categoria (2/3) -->
                        <div class="lg:col-span-2 bg-white rounded-2xl p-6 shadow-sm">
                            <div class="mb-6">
                                <h3 class="font-bold text-slate-800 text-lg">Reportes por categoria</h3>
                                <p class="text-sm text-slate-500">Distribucion del total de reportes</p>
                            </div>

                            <div v-if="distribucionCategorias.length === 0" class="text-center py-8 text-slate-500">
                                Aun no hay datos suficientes para mostrar.
                            </div>

                            <div v-else class="space-y-4">
                                <div
                                    v-for="cat in distribucionCategorias"
                                    :key="cat.id"
                                >
                                    <div class="flex justify-between text-sm mb-1">
                                        <span class="font-semibold text-slate-700">
                                            {{ cat.icono }} {{ cat.nombre }}
                                        </span>
                                        <span class="font-bold text-slate-800">
                                            {{ cat.total }} ({{ cat.porcentaje }}%)
                                        </span>
                                    </div>
                                    <div class="h-3 bg-slate-100 rounded-full overflow-hidden">
                                        <div
                                            class="h-full rounded-full transition-all duration-500"
                                            :style="{
                                                width: cat.porcentaje + '%',
                                                backgroundColor: cat.color
                                            }"
                                        ></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tarjeta de eficiencia (1/3) -->
                        <div class="bg-gradient-to-br from-sky-700 to-sky-900 text-white rounded-2xl p-6 shadow-sm">
                            <h3 class="font-bold text-lg mb-1">Eficiencia operativa</h3>
                            <p class="text-sm text-sky-200 mb-6">Resolucion de reportes</p>

                            <div class="text-center my-8">
                                <p class="text-6xl font-extrabold mb-1">{{ kpis.tasaResolucion }}%</p>
                                <p class="text-sky-200 font-semibold">tasa de resolucion</p>
                            </div>

                            <div class="space-y-3 pt-4 border-t border-sky-600">
                                <div class="flex justify-between text-sm">
                                    <span class="text-sky-200">Total atendidos</span>
                                    <span class="font-bold">{{ kpis.resueltos }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-sky-200">En proceso</span>
                                    <span class="font-bold">{{ kpis.enProceso }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-sky-200">Por atender</span>
                                    <span class="font-bold text-amber-300">{{ kpis.pendientes }}</span>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- REPORTES RECIENTES -->
                    <div class="bg-white rounded-2xl p-6 shadow-sm">
                        <div class="flex justify-between items-center mb-5 flex-wrap gap-2">
                            <div>
                                <h3 class="font-bold text-slate-800 text-lg">Reportes recientes</h3>
                                <p class="text-sm text-slate-500">Los ultimos reportes recibidos</p>
                            </div>
                            <router-link
                                to="/admin/reportes"
                                class="text-sky-700 font-bold text-sm hover:underline"
                            >
                                Ver todos →
                            </router-link>
                        </div>

                        <div v-if="reportesRecientes.length === 0" class="text-center py-12 text-slate-500">
                            <div class="text-5xl mb-3">📭</div>
                            <p>No hay reportes registrados aun.</p>
                        </div>

                        <div v-else class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="text-left text-xs font-bold text-slate-500 uppercase border-b border-slate-200">
                                        <th class="py-3 px-2">ID</th>
                                        <th class="py-3 px-2">Reporte</th>
                                        <th class="py-3 px-2">Categoria</th>
                                        <th class="py-3 px-2">Ciudadano</th>
                                        <th class="py-3 px-2">Fecha</th>
                                        <th class="py-3 px-2">Estado</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm">
                                    <tr
                                        v-for="reporte in reportesRecientes"
                                        :key="reporte.id"
                                        class="border-b border-slate-100 hover:bg-slate-50 transition"
                                    >
                                        <td class="py-4 px-2 font-mono text-slate-600">
                                            #{{ reporte.id.toString().padStart(4, '0') }}
                                        </td>
                                        <td class="py-4 px-2 font-semibold text-slate-800 max-w-xs truncate">
                                            {{ reporte.titulo }}
                                        </td>
                                        <td class="py-4 px-2">
                                            <span
                                                v-if="reporte.categoria"
                                                class="px-2 py-1 text-xs font-bold rounded"
                                                :style="{
                                                    backgroundColor: reporte.categoria.color + '20',
                                                    color: reporte.categoria.color
                                                }"
                                            >
                                                {{ reporte.categoria.nombre }}
                                            </span>
                                            <span v-else class="text-xs text-slate-400">Sin asignar</span>
                                        </td>
                                        <td class="py-4 px-2 text-slate-600">
                                            {{ reporte.usuario?.name }} {{ reporte.usuario?.apellido?.charAt(0) }}.
                                        </td>
                                        <td class="py-4 px-2 text-slate-500 whitespace-nowrap">
                                            {{ formatearFecha(reporte.created_at) }}
                                        </td>
                                        <td class="py-4 px-2">
                                            <span
                                                :class="claseEstado(reporte.estado)"
                                                class="px-2 py-1 text-xs font-bold rounded"
                                            >
                                                {{ etiquetaEstado(reporte.estado) }}
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </main>
    </div>
</template>

<script setup>
/**
 * Vista del Administrador: Dashboard principal
 *
 * Panel de control con estadisticas generales del sistema LICAM:
 * - KPIs principales (total, pendientes, en proceso, resueltos)
 * - Distribucion de reportes por categoria
 * - Tasa de resolucion (eficiencia operativa)
 * - Tabla con los reportes mas recientes
 *
 * Todos los datos se calculan dinamicamente a partir de los reportes
 * obtenidos de la API del backend.
 *
 * @project LICAM - Linea Ciudadana de Atencion Municipal
 */

import { ref, computed, onMounted } from 'vue';
import api from '@/services/api';
import SidebarAdmin from '@/components/SidebarAdmin.vue';

// Estado reactivo
const reportes = ref([]);
const cargando = ref(false);

/**
 * Fecha actual formateada en español.
 */
const fechaHoy = computed(() => {
    return new Date().toLocaleDateString('es-SV', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
    });
});

/**
 * KPIs (indicadores) calculados a partir de los reportes.
 */
const kpis = computed(() => {
    const total = reportes.value.length;
    const pendientes = reportes.value.filter(r => r.estado === 'pendiente').length;
    const enProceso = reportes.value.filter(r => r.estado === 'en_proceso').length;
    const resueltos = reportes.value.filter(r => r.estado === 'resuelto').length;

    // Tasa de resolucion: porcentaje de reportes resueltos sobre el total
    const tasaResolucion = total > 0 ? Math.round((resueltos / total) * 100) : 0;

    return { total, pendientes, enProceso, resueltos, tasaResolucion };
});

/**
 * Distribucion de reportes por categoria con su porcentaje.
 * Solo incluye categorias que tengan al menos un reporte.
 */
const distribucionCategorias = computed(() => {
    const conteo = {};

    // Contar reportes por categoria
    reportes.value.forEach(reporte => {
        if (reporte.categoria) {
            const id = reporte.categoria.id;
            if (!conteo[id]) {
                conteo[id] = {
                    id: id,
                    nombre: reporte.categoria.nombre,
                    icono: reporte.categoria.icono,
                    color: reporte.categoria.color,
                    total: 0,
                };
            }
            conteo[id].total++;
        }
    });

    // Convertir a arreglo y calcular porcentajes
    const total = reportes.value.length;
    return Object.values(conteo)
        .map(cat => ({
            ...cat,
            porcentaje: total > 0 ? Math.round((cat.total / total) * 100) : 0,
        }))
        .sort((a, b) => b.total - a.total); // Ordenar de mayor a menor
});

/**
 * Los 5 reportes mas recientes para mostrar en la tabla.
 */
const reportesRecientes = computed(() => {
    return reportes.value.slice(0, 5);
});

/**
 * Carga todos los reportes del sistema desde la API.
 */
const cargarReportes = async () => {
    cargando.value = true;
    try {
        const response = await api.get('/reportes');
        reportes.value = response.data.data || [];
    } catch (error) {
        console.error('Error al cargar reportes:', error);
    } finally {
        cargando.value = false;
    }
};

/**
 * Retorna las clases CSS para mostrar el estado del reporte.
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
 * Retorna la etiqueta legible del estado.
 */
const etiquetaEstado = (estado) => {
    const etiquetas = {
        pendiente: 'Pendiente',
        en_proceso: 'En proceso',
        resuelto: 'Resuelto',
        rechazado: 'Rechazado',
    };
    return etiquetas[estado] || estado;
};

/**
 * Formatea una fecha ISO al formato local "DD MMM".
 */
const formatearFecha = (fechaISO) => {
    if (!fechaISO) return '';
    const fecha = new Date(fechaISO);
    return fecha.toLocaleDateString('es-SV', {
        day: '2-digit',
        month: 'short',
    });
};

/**
 * Hook de Vue: se ejecuta al montar el componente.
 */
onMounted(() => {
    cargarReportes();
});
</script>
