<template>
    <!--
        Vista: Detalle de Reporte (Ciudadano)
        Muestra toda la informacion de un reporte propio: descripcion,
        ubicacion en mapa, imagenes, historial de seguimiento y comentarios.
        Permite al ciudadano comunicarse con la alcaldia mediante comentarios.
    -->
    <div class="min-h-screen bg-slate-50">

        <NavbarCiudadano />

        <main class="max-w-4xl mx-auto px-6 py-8">

            <!-- Boton volver -->
            <router-link
                to="/ciudadano/reportes"
                class="inline-flex items-center gap-2 text-slate-500 hover:text-sky-700 font-semibold text-sm mb-6 transition"
            >
                <span>←</span> Volver a mis reportes
            </router-link>

            <!-- ESTADO DE CARGA -->
            <div v-if="cargando" class="bg-white rounded-2xl p-12 shadow-sm text-center">
                <div class="text-5xl mb-3 animate-pulse">⏳</div>
                <p class="text-slate-600">Cargando reporte...</p>
            </div>

            <!-- REPORTE NO ENCONTRADO -->
            <div v-else-if="!reporte" class="bg-white rounded-2xl p-12 shadow-sm text-center">
                <div class="text-6xl mb-4">🔍</div>
                <h3 class="text-xl font-bold text-slate-800 mb-2">Reporte no encontrado</h3>
                <p class="text-slate-500">Este reporte no existe o no tienes acceso a el.</p>
            </div>

            <!-- CONTENIDO DEL REPORTE -->
            <div v-else class="space-y-6">

                <!-- Encabezado con estado -->
                <div class="bg-white rounded-2xl p-6 shadow-sm">
                    <div class="flex items-center gap-2 mb-3 flex-wrap">
                        <span
                            v-if="reporte.categoria"
                            class="px-3 py-1 text-xs font-bold rounded-full uppercase"
                            :style="{ backgroundColor: reporte.categoria.color + '20', color: reporte.categoria.color }"
                        >
                            {{ reporte.categoria.nombre }}
                        </span>
                        <span v-else class="px-3 py-1 bg-slate-100 text-slate-600 text-xs font-bold rounded-full">
                            SIN CATEGORIZAR
                        </span>
                        <span :class="claseEstado(reporte.estado)" class="px-3 py-1 text-xs font-bold rounded-full uppercase">
                            {{ etiquetaEstado(reporte.estado) }}
                        </span>
                        <span :class="clasePrioridad(reporte.prioridad)" class="px-3 py-1 text-xs font-bold rounded-full uppercase">
                            PRIORIDAD {{ reporte.prioridad }}
                        </span>
                    </div>
                    <h1 class="text-2xl font-extrabold text-slate-800 mb-1">{{ reporte.titulo }}</h1>
                    <p class="text-sm text-slate-500">
                        #REP-{{ reporte.id.toString().padStart(4, '0') }} · Reportado el {{ formatearFecha(reporte.fecha_reporte) }}
                    </p>
                </div>

                <!-- Descripcion -->
                <div class="bg-white rounded-2xl p-6 shadow-sm">
                    <h3 class="font-bold text-slate-800 mb-2">Descripcion</h3>
                    <p class="text-slate-700 leading-relaxed">{{ reporte.descripcion }}</p>
                </div>

                <!-- Imagenes -->
                <div v-if="reporte.imagenes && reporte.imagenes.length > 0" class="bg-white rounded-2xl p-6 shadow-sm">
                    <h3 class="font-bold text-slate-800 mb-3">Evidencia fotografica</h3>
                    <div class="grid grid-cols-3 gap-3">
                        <a
                            v-for="img in reporte.imagenes"
                            :key="img.id"
                            :href="img.url_completa"
                            target="_blank"
                            class="block aspect-square rounded-xl overflow-hidden border border-slate-200 hover:opacity-90 transition"
                        >
                            <img :src="img.url_completa" :alt="img.nombre_archivo" class="w-full h-full object-cover">
                        </a>
                    </div>
                </div>

                <!-- Ubicacion -->
                <div class="bg-white rounded-2xl p-6 shadow-sm">
                    <h3 class="font-bold text-slate-800 mb-3">Ubicacion</h3>
                    <p class="text-slate-600 text-sm mb-3">📍 {{ reporte.direccion || 'Sin direccion especificada' }}</p>
                    <MapaUbicacion
                        :latitud="Number(reporte.latitud)"
                        :longitud="Number(reporte.longitud)"
                        :seleccionable="false"
                    />
                </div>

                <!-- Historial de seguimiento -->
                <div v-if="reporte.seguimientos && reporte.seguimientos.length > 0" class="bg-white rounded-2xl p-6 shadow-sm">
                    <h3 class="font-bold text-slate-800 mb-4">Historial de seguimiento</h3>
                    <div class="space-y-3">
                        <div
                            v-for="seg in reporte.seguimientos"
                            :key="seg.id"
                            class="bg-slate-50 p-3 rounded-lg text-sm"
                        >
                            <div class="flex justify-between items-start">
                                <p class="font-semibold text-slate-700">
                                    {{ etiquetaEstado(seg.estado_anterior) }} → {{ etiquetaEstado(seg.estado_nuevo) }}
                                </p>
                                <span class="text-xs text-slate-500">{{ formatearFecha(seg.created_at) }}</span>
                            </div>
                            <p v-if="seg.observacion" class="text-xs text-slate-600 italic mt-1">"{{ seg.observacion }}"</p>
                        </div>
                    </div>
                </div>

                <!-- Comentarios -->
                <div class="bg-white rounded-2xl p-6 shadow-sm">
                    <h3 class="font-bold text-slate-800 mb-4">Comentarios ({{ comentarios.length }})</h3>

                    <!-- Lista de comentarios -->
                    <div v-if="comentarios.length > 0" class="space-y-3 mb-5">
                        <div
                            v-for="comentario in comentarios"
                            :key="comentario.id"
                            class="flex gap-3"
                        >
                            <div
                                class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-xs flex-shrink-0"
                                :class="esAdmin(comentario) ? 'bg-sky-600 text-white' : 'bg-amber-400 text-slate-900'"
                            >
                                {{ inicialesComentario(comentario.usuario) }}
                            </div>
                            <div
                                class="flex-1 rounded-xl p-3"
                                :class="esAdmin(comentario) ? 'bg-sky-50' : 'bg-slate-50'"
                            >
                                <div class="flex justify-between items-start mb-1">
                                    <p class="font-bold text-slate-800 text-sm">
                                        {{ comentario.usuario?.name }} {{ comentario.usuario?.apellido }}
                                        <span class="text-xs font-normal" :class="esAdmin(comentario) ? 'text-sky-700' : 'text-slate-500'">
                                            · {{ esAdmin(comentario) ? 'Alcaldia' : 'Tu' }}
                                        </span>
                                    </p>
                                    <span class="text-xs text-slate-400">{{ formatearFecha(comentario.created_at) }}</span>
                                </div>
                                <p class="text-slate-700 text-sm">{{ comentario.contenido }}</p>
                            </div>
                        </div>
                    </div>
                    <p v-else class="text-sm text-slate-400 mb-5">Aun no hay comentarios. Puedes escribir uno para comunicarte con la alcaldia.</p>

                    <!-- Formulario nuevo comentario -->
                    <div class="flex gap-2">
                        <textarea
                            v-model="nuevoComentario"
                            rows="2"
                            placeholder="Escribe un comentario para la alcaldia..."
                            class="flex-1 px-4 py-2 border-2 border-slate-200 rounded-lg focus:border-sky-600 focus:outline-none resize-none text-sm"
                        ></textarea>
                        <button
                            @click="enviarComentario"
                            :disabled="enviandoComentario || !nuevoComentario.trim()"
                            class="px-4 py-2 bg-sky-700 text-white font-semibold rounded-lg hover:bg-sky-800 disabled:opacity-50 disabled:cursor-not-allowed text-sm self-end"
                        >
                            {{ enviandoComentario ? '...' : 'Enviar' }}
                        </button>
                    </div>
                </div>

            </div>
        </main>
    </div>
</template>

<script setup>
/**
 * Vista del Ciudadano: Detalle de Reporte
 *
 * Muestra la informacion completa de un reporte propio e incluye
 * el historial de seguimiento y un hilo de comentarios para
 * comunicarse con la alcaldia.
 *
 * @project LICAM - Linea Ciudadana de Atencion Municipal
 */

import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import api from '@/services/api';
import NavbarCiudadano from '@/components/NavbarCiudadano.vue';
import MapaUbicacion from '@/components/MapaUbicacion.vue';

const route = useRoute();

// Estado reactivo
const reporte = ref(null);
const comentarios = ref([]);
const cargando = ref(false);
const nuevoComentario = ref('');
const enviandoComentario = ref(false);

/**
 * Carga los datos completos del reporte desde la API.
 */
const cargarReporte = async () => {
    cargando.value = true;
    try {
        const response = await api.get(`/reportes/${route.params.id}`);
        reporte.value = response.data.data;
        await cargarComentarios();
    } catch (error) {
        console.error('Error al cargar el reporte:', error);
        reporte.value = null;
    } finally {
        cargando.value = false;
    }
};

/**
 * Carga los comentarios del reporte.
 */
const cargarComentarios = async () => {
    try {
        const response = await api.get(`/reportes/${route.params.id}/comentarios`);
        comentarios.value = response.data.data || [];
    } catch (error) {
        console.error('Error al cargar comentarios:', error);
        comentarios.value = [];
    }
};

/**
 * Envia un nuevo comentario al reporte.
 */
const enviarComentario = async () => {
    if (!nuevoComentario.value.trim()) return;

    enviandoComentario.value = true;
    try {
        await api.post(`/reportes/${route.params.id}/comentarios`, {
            contenido: nuevoComentario.value,
        });
        nuevoComentario.value = '';
        await cargarComentarios();
    } catch (error) {
        console.error('Error al enviar comentario:', error);
    } finally {
        enviandoComentario.value = false;
    }
};

/**
 * Determina si un comentario fue escrito por un administrador.
 */
const esAdmin = (comentario) => {
    return comentario.usuario?.rol?.nombre === 'administrador';
};

/**
 * Calcula las iniciales del autor de un comentario.
 */
const inicialesComentario = (usuario) => {
    if (!usuario) return '?';
    const n = usuario.name?.charAt(0) || '';
    const a = usuario.apellido?.charAt(0) || '';
    return (n + a).toUpperCase();
};

/**
 * Clases CSS segun el estado del reporte.
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
 * Etiqueta legible del estado.
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
 * Clases CSS segun la prioridad.
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
 * Formatea una fecha ISO al formato local.
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
 * Hook de Vue: carga el reporte al montar el componente.
 */
onMounted(() => {
    cargarReporte();
});
</script>
