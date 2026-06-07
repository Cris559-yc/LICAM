<template>
    <!--
        Vista: Lista de Reportes (Administrador)
        Muestra todos los reportes del sistema con filtros y opciones
        para gestionarlos: clasificar, cambiar estado, asignar prioridad.
    -->
    <div class="flex min-h-screen bg-slate-100">

        <!-- Sidebar del administrador -->
        <SidebarAdmin />

        <!-- CONTENIDO PRINCIPAL -->
        <main class="flex-1 overflow-y-auto">

            <!-- TOPBAR -->
            <header class="bg-white border-b border-slate-200 px-8 py-4 sticky top-0 z-10">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-extrabold text-slate-800">Gestion de Reportes</h2>
                        <p class="text-sm text-slate-500">Administra los reportes ciudadanos del sistema</p>
                    </div>
                    <button
                        @click="cargarReportes"
                        :disabled="cargando"
                        class="px-4 py-2 bg-slate-100 text-slate-700 font-semibold rounded-xl hover:bg-slate-200 disabled:opacity-50 transition"
                    >
                        🔄 Refrescar
                    </button>
                </div>
            </header>

            <!-- CONTENIDO -->
            <div class="p-8">

                <!-- FILTROS -->
                <div class="bg-white rounded-2xl p-5 mb-6 shadow-sm">
                    <div class="flex flex-wrap gap-3 items-center">
                        <span class="text-sm font-semibold text-slate-700">Filtros:</span>

                        <!-- Filtro por estado -->
                        <select
                            v-model="filtros.estado"
                            @change="cargarReportes"
                            class="px-4 py-2 border-2 border-slate-200 rounded-lg text-sm focus:border-sky-600 focus:outline-none"
                        >
                            <option value="">Todos los estados</option>
                            <option value="pendiente">Pendientes</option>
                            <option value="en_proceso">En proceso</option>
                            <option value="resuelto">Resueltos</option>
                            <option value="rechazado">Rechazados</option>
                        </select>

                        <!-- Filtro por categoria -->
                        <select
                            v-model="filtros.categoria_id"
                            @change="cargarReportes"
                            class="px-4 py-2 border-2 border-slate-200 rounded-lg text-sm focus:border-sky-600 focus:outline-none"
                        >
                            <option value="">Todas las categorias</option>
                            <option
                                v-for="cat in categorias"
                                :key="cat.id"
                                :value="cat.id"
                            >
                                {{ cat.icono }} {{ cat.nombre }}
                            </option>
                        </select>

                        <!-- Filtro por prioridad -->
                        <select
                            v-model="filtros.prioridad"
                            @change="cargarReportes"
                            class="px-4 py-2 border-2 border-slate-200 rounded-lg text-sm focus:border-sky-600 focus:outline-none"
                        >
                            <option value="">Todas las prioridades</option>
                            <option value="baja">Baja</option>
                            <option value="media">Media</option>
                            <option value="alta">Alta</option>
                            <option value="urgente">Urgente</option>
                        </select>

                        <!-- Boton limpiar filtros -->
                        <button
                            v-if="hayFiltrosActivos"
                            @click="limpiarFiltros"
                            class="px-4 py-2 bg-red-100 text-red-700 text-sm font-semibold rounded-lg hover:bg-red-200 transition"
                        >
                            ✕ Limpiar filtros
                        </button>

                        <!-- Contador de resultados -->
                        <span class="ml-auto text-sm text-slate-500">
                            <b class="text-slate-800">{{ reportes.length }}</b> reportes encontrados
                        </span>
                    </div>
                </div>

                <!-- ESTADO DE CARGA -->
                <div v-if="cargando" class="bg-white rounded-2xl p-12 shadow-sm text-center">
                    <div class="text-5xl mb-3 animate-pulse">⏳</div>
                    <p class="text-slate-600">Cargando reportes...</p>
                </div>

                <!-- LISTA VACIA -->
                <div v-else-if="reportes.length === 0" class="bg-white rounded-2xl p-12 shadow-sm text-center">
                    <div class="text-6xl mb-4">📭</div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">No hay reportes</h3>
                    <p class="text-slate-500">
                        {{ hayFiltrosActivos
                            ? 'No hay reportes que coincidan con los filtros aplicados.'
                            : 'Aun no se han recibido reportes ciudadanos.' }}
                    </p>
                </div>

                <!-- TABLA DE REPORTES -->
                <div v-else class="bg-white rounded-2xl shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-slate-50">
                                <tr class="text-left text-xs font-bold text-slate-500 uppercase border-b border-slate-200">
                                    <th class="py-3 px-4">ID</th>
                                    <th class="py-3 px-4">Reporte</th>
                                    <th class="py-3 px-4">Categoria</th>
                                    <th class="py-3 px-4">Ciudadano</th>
                                    <th class="py-3 px-4">Prioridad</th>
                                    <th class="py-3 px-4">Estado</th>
                                    <th class="py-3 px-4">Fecha</th>
                                    <th class="py-3 px-4 text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm">
                                <tr
                                    v-for="reporte in reportes"
                                    :key="reporte.id"
                                    class="border-b border-slate-100 hover:bg-slate-50 transition"
                                >
                                    <td class="py-4 px-4 font-mono text-slate-600">
                                        #{{ reporte.id.toString().padStart(4, '0') }}
                                    </td>
                                    <td class="py-4 px-4 font-semibold text-slate-800 max-w-xs">
                                        <div class="truncate">{{ reporte.titulo }}</div>
                                        <div class="text-xs text-slate-500 font-normal truncate">
                                            📍 {{ reporte.direccion || 'Sin direccion' }}
                                        </div>
                                    </td>
                                    <td class="py-4 px-4">
                                        <span
                                            v-if="reporte.categoria"
                                            class="px-2 py-1 text-xs font-bold rounded whitespace-nowrap"
                                            :style="{
                                                backgroundColor: reporte.categoria.color + '20',
                                                color: reporte.categoria.color
                                            }"
                                        >
                                            {{ reporte.categoria.nombre }}
                                        </span>
                                        <span v-else class="text-xs text-amber-700 font-semibold">
                                            ⚠ Sin clasificar
                                        </span>
                                    </td>
                                    <td class="py-4 px-4 text-slate-600">
                                        {{ reporte.usuario?.name }} {{ reporte.usuario?.apellido }}
                                    </td>
                                    <td class="py-4 px-4">
                                        <span
                                            :class="clasePrioridad(reporte.prioridad)"
                                            class="px-2 py-1 text-xs font-bold rounded uppercase"
                                        >
                                            {{ reporte.prioridad }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-4">
                                        <span
                                            :class="claseEstado(reporte.estado)"
                                            class="px-2 py-1 text-xs font-bold rounded whitespace-nowrap"
                                        >
                                            {{ etiquetaEstado(reporte.estado) }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-4 text-slate-500 whitespace-nowrap">
                                        {{ formatearFecha(reporte.created_at) }}
                                    </td>
                                    <td class="py-4 px-4 text-center">
                                        <button
                                            @click="abrirModalGestion(reporte)"
                                            class="px-3 py-1.5 bg-sky-700 text-white text-xs font-bold rounded hover:bg-sky-800 transition"
                                        >
                                            Gestionar
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </main>

        <!-- MODAL DE GESTION DE REPORTE -->
        <div
            v-if="reporteSeleccionado"
            class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4 overflow-y-auto"
            @click.self="cerrarModal"
        >
            <div class="bg-white rounded-2xl max-w-3xl w-full max-h-[90vh] overflow-y-auto my-8">

                <!-- Encabezado del modal -->
                <div class="sticky top-0 bg-white border-b border-slate-200 px-6 py-4 flex justify-between items-center z-10">
                    <div>
                        <p class="text-xs text-slate-500 font-mono">
                            #REP-{{ reporteSeleccionado.id.toString().padStart(4, '0') }}
                        </p>
                        <h3 class="text-xl font-bold text-slate-800">{{ reporteSeleccionado.titulo }}</h3>
                    </div>
                    <button
                        @click="cerrarModal"
                        class="text-slate-400 hover:text-slate-700 text-2xl leading-none"
                    >
                        ✕
                    </button>
                </div>

                <!-- Contenido del modal -->
                <div class="p-6 space-y-6">

                    <!-- Mensaje de exito o error -->
                    <div v-if="mensajeExito" class="p-4 bg-emerald-50 border-2 border-emerald-200 rounded-xl text-emerald-700">
                        ✅ {{ mensajeExito }}
                    </div>
                    <div v-if="errorGuardar" class="p-4 bg-red-50 border-2 border-red-200 rounded-xl text-red-700">
                        ⚠️ {{ errorGuardar }}
                    </div>

                    <!-- Info del ciudadano -->
                    <div class="bg-slate-50 rounded-xl p-4 flex items-center gap-4">
                        <div class="w-14 h-14 bg-amber-400 rounded-full flex items-center justify-center font-bold text-slate-900 text-lg">
                            {{ iniciales(reporteSeleccionado.usuario) }}
                        </div>
                        <div class="flex-1">
                            <p class="font-bold text-slate-800">
                                {{ reporteSeleccionado.usuario?.name }} {{ reporteSeleccionado.usuario?.apellido }}
                            </p>
                            <p class="text-xs text-slate-500">{{ reporteSeleccionado.usuario?.email }}</p>
                        </div>
                    </div>

                    <!-- Descripcion del problema -->
                    <div>
                        <p class="text-xs font-bold text-slate-500 uppercase mb-2">Descripcion del problema</p>
                        <p class="text-slate-700 bg-slate-50 p-4 rounded-xl leading-relaxed">
                            {{ reporteSeleccionado.descripcion }}
                        </p>
                    </div>

                    <!-- Ubicacion -->
                    <div>
                        <p class="text-xs font-bold text-slate-500 uppercase mb-2">Ubicacion</p>
                        <div class="bg-slate-50 p-4 rounded-xl space-y-1">
                            <p class="text-slate-700">📍 {{ reporteSeleccionado.direccion || 'Sin direccion especificada' }}</p>
                            <p class="text-xs text-slate-500 font-mono">
                                Lat: {{ reporteSeleccionado.latitud }}, Lng: {{ reporteSeleccionado.longitud }}
                            </p>
                        </div>
                    </div>

                   <!-- Evidencia fotografica -->
                    <div v-if="reporteSeleccionado.imagenes && reporteSeleccionado.imagenes.length > 0">
                        <p class="text-xs font-bold text-slate-500 uppercase mb-2">Evidencia fotografica</p>
                        <div class="grid grid-cols-3 gap-3">
                            <a
                                v-for="img in reporteSeleccionado.imagenes"
                                :key="img.id"
                                :href="img.url_completa"
                                target="_blank"
                                class="block aspect-square rounded-xl overflow-hidden border border-slate-200 hover:opacity-90 transition"
                            >
                                <img
                                    :src="img.url_completa"
                                    :alt="img.nombre_archivo"
                                    class="w-full h-full object-cover"
                                >
                            </a>
                        </div>
                    </div>

                    <!-- FORMULARIO DE GESTION -->
                    <div class="bg-sky-50 border-2 border-sky-200 rounded-xl p-5 space-y-4">
                        <h4 class="font-bold text-slate-800">⚙️ Gestion del reporte</h4>

                        <!-- Categoria -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Categoria
                            </label>
                            <select
                                v-model="gestion.categoria_id"
                                class="w-full px-4 py-2 border-2 border-slate-200 rounded-lg focus:border-sky-600 focus:outline-none bg-white"
                            >
                                <option :value="null">-- Sin categoria --</option>
                                <option
                                    v-for="cat in categorias"
                                    :key="cat.id"
                                    :value="cat.id"
                                >
                                    {{ cat.icono }} {{ cat.nombre }}
                                </option>
                            </select>
                        </div>

                        <!-- Estado y prioridad en la misma fila -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">
                                    Estado
                                </label>
                                <select
                                    v-model="gestion.estado"
                                    class="w-full px-4 py-2 border-2 border-slate-200 rounded-lg focus:border-sky-600 focus:outline-none bg-white"
                                >
                                    <option value="pendiente">⏳ Pendiente</option>
                                    <option value="en_proceso">🔄 En proceso</option>
                                    <option value="resuelto">✓ Resuelto</option>
                                    <option value="rechazado">✕ Rechazado</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">
                                    Prioridad
                                </label>
                                <select
                                    v-model="gestion.prioridad"
                                    class="w-full px-4 py-2 border-2 border-slate-200 rounded-lg focus:border-sky-600 focus:outline-none bg-white"
                                >
                                    <option value="baja">Baja</option>
                                    <option value="media">Media</option>
                                    <option value="alta">Alta</option>
                                    <option value="urgente">Urgente</option>
                                </select>
                            </div>
                        </div>

                        <!-- Observacion -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Observacion (opcional)
                            </label>
                            <textarea
                                v-model="gestion.observacion"
                                rows="3"
                                placeholder="Escribe una observacion sobre el cambio realizado..."
                                class="w-full px-4 py-2 border-2 border-slate-200 rounded-lg focus:border-sky-600 focus:outline-none resize-none bg-white"
                            ></textarea>
                            <p class="text-xs text-slate-500 mt-1">
                                Se registrara en el historial si cambias el estado.
                            </p>
                        </div>
                    </div>

                    <!-- HISTORIAL DE SEGUIMIENTOS -->
                    <div v-if="reporteSeleccionado.seguimientos && reporteSeleccionado.seguimientos.length > 0">
                        <p class="text-xs font-bold text-slate-500 uppercase mb-2">Historial de cambios</p>
                        <div class="space-y-2">
                            <div
                                v-for="seg in reporteSeleccionado.seguimientos"
                                :key="seg.id"
                                class="bg-slate-50 p-3 rounded-lg text-sm"
                            >
                                <div class="flex justify-between items-start">
                                    <p class="font-semibold text-slate-700">
                                        {{ etiquetaEstado(seg.estado_anterior) }} → {{ etiquetaEstado(seg.estado_nuevo) }}
                                    </p>
                                    <span class="text-xs text-slate-500">{{ formatearFecha(seg.created_at) }}</span>
                                </div>
                                <p v-if="seg.observacion" class="text-xs text-slate-600 italic mt-1">
                                    "{{ seg.observacion }}"
                                </p>
                                <p class="text-xs text-slate-500 mt-1">
                                    Por: {{ seg.usuario?.name }} {{ seg.usuario?.apellido }}
                                </p>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- BOTONES DEL MODAL -->
                <div class="sticky bottom-0 bg-slate-50 border-t border-slate-200 px-6 py-4 flex justify-end gap-3">
                    <button
                        @click="cerrarModal"
                        :disabled="guardando"
                        class="px-5 py-2.5 bg-white border-2 border-slate-200 text-slate-700 font-semibold rounded-xl hover:bg-slate-100 disabled:opacity-50"
                    >
                        Cancelar
                    </button>
                    <button
                        @click="guardarCambios"
                        :disabled="guardando"
                        class="px-6 py-2.5 bg-sky-700 text-white font-bold rounded-xl hover:bg-sky-800 disabled:opacity-50 shadow-lg shadow-sky-700/30 transition"
                    >
                        {{ guardando ? 'Guardando...' : '💾 Guardar cambios' }}
                    </button>
                </div>

            </div>
        </div>
    </div>
</template>

<script setup>
/**
 * Vista del Administrador: Lista de Reportes
 *
 * Muestra todos los reportes del sistema en formato tabla con filtros
 * dinamicos por estado, categoria y prioridad. Permite abrir un modal
 * de gestion para cada reporte donde el administrador puede:
 * - Asignar o cambiar la categoria
 * - Modificar el estado (pendiente, en proceso, resuelto, rechazado)
 * - Cambiar la prioridad (baja, media, alta, urgente)
 * - Agregar una observacion sobre el cambio
 *
 * Los cambios de estado se registran automaticamente en el historial
 * de seguimientos por el backend.
 *
 * @project LICAM - Linea Ciudadana de Atencion Municipal
 */

import { ref, reactive, computed, onMounted } from 'vue';
import api from '@/services/api';
import SidebarAdmin from '@/components/SidebarAdmin.vue';

// Estado reactivo principal
const reportes = ref([]);
const categorias = ref([]);
const cargando = ref(false);

// Filtros activos
const filtros = reactive({
    estado: '',
    categoria_id: '',
    prioridad: '',
});

// Estado del modal de gestion
const reporteSeleccionado = ref(null);
const gestion = reactive({
    categoria_id: null,
    estado: '',
    prioridad: '',
    observacion: '',
});
const guardando = ref(false);
const mensajeExito = ref('');
const errorGuardar = ref('');

/**
 * Indica si hay algun filtro activo.
 */
const hayFiltrosActivos = computed(() => {
    return filtros.estado !== '' || filtros.categoria_id !== '' || filtros.prioridad !== '';
});

/**
 * Carga el listado de reportes desde la API aplicando los filtros activos.
 */
const cargarReportes = async () => {
    cargando.value = true;
    try {
        // Construir parametros de la peticion solo con filtros activos
        const params = {};
        if (filtros.estado) params.estado = filtros.estado;
        if (filtros.categoria_id) params.categoria_id = filtros.categoria_id;
        if (filtros.prioridad) params.prioridad = filtros.prioridad;

        const response = await api.get('/reportes', { params });
        reportes.value = response.data.data || [];
    } catch (error) {
        console.error('Error al cargar reportes:', error);
    } finally {
        cargando.value = false;
    }
};

/**
 * Carga el listado de categorias disponibles.
 */
const cargarCategorias = async () => {
    try {
        const response = await api.get('/categorias');
        categorias.value = response.data.data || [];
    } catch (error) {
        console.error('Error al cargar categorias:', error);
    }
};

/**
 * Restablece todos los filtros y recarga los reportes.
 */
const limpiarFiltros = () => {
    filtros.estado = '';
    filtros.categoria_id = '';
    filtros.prioridad = '';
    cargarReportes();
};

/**
 * Abre el modal de gestion para un reporte especifico.
 * Carga los datos detallados del reporte incluyendo seguimientos.
 *
 * @param {Object} reporte - Reporte de la tabla
 */
const abrirModalGestion = async (reporte) => {
    // Resetear mensajes
    mensajeExito.value = '';
    errorGuardar.value = '';

    try {
        // Obtener los datos completos del reporte (con seguimientos)
        const response = await api.get(`/reportes/${reporte.id}`);
        reporteSeleccionado.value = response.data.data;

        // Inicializar los campos del formulario de gestion
        gestion.categoria_id = reporteSeleccionado.value.categoria_id;
        gestion.estado = reporteSeleccionado.value.estado;
        gestion.prioridad = reporteSeleccionado.value.prioridad;
        gestion.observacion = '';
    } catch (error) {
        console.error('Error al cargar detalles del reporte:', error);
    }
};

/**
 * Cierra el modal de gestion sin guardar cambios.
 */
const cerrarModal = () => {
    reporteSeleccionado.value = null;
    mensajeExito.value = '';
    errorGuardar.value = '';
};

/**
 * Guarda los cambios del modal en la API.
 * Si el cambio incluye una modificacion de estado, el backend
 * registra automaticamente un seguimiento en el historial.
 */
const guardarCambios = async () => {
    guardando.value = true;
    errorGuardar.value = '';
    mensajeExito.value = '';

    try {
        // Enviar peticion PUT a la API
        await api.put(`/reportes/${reporteSeleccionado.value.id}`, gestion);

        mensajeExito.value = 'Reporte actualizado exitosamente.';

        // Recargar la lista de reportes para reflejar los cambios
        await cargarReportes();

        // Cerrar el modal despues de 1.5 segundos
        setTimeout(() => {
            cerrarModal();
        }, 1500);

    } catch (error) {
        if (error.response) {
            errorGuardar.value = error.response.data.message || 'Error al guardar los cambios.';
        } else {
            errorGuardar.value = 'No se pudo conectar con el servidor.';
        }
    } finally {
        guardando.value = false;
    }
};

/**
 * Calcula las iniciales del usuario para mostrar en el avatar.
 */
const iniciales = (usuario) => {
    if (!usuario) return '?';
    const n = usuario.name?.charAt(0) || '';
    const a = usuario.apellido?.charAt(0) || '';
    return (n + a).toUpperCase();
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
 * Retorna las clases CSS para mostrar la prioridad del reporte.
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
 * Hook de Vue: se ejecuta al montar el componente.
 * Carga las categorias y los reportes iniciales.
 */
onMounted(() => {
    cargarCategorias();
    cargarReportes();
});
</script>
