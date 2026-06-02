<template>
    <!--
        Vista: Pagina de Inicio (Landing publica)
        Pagina de bienvenida del sistema LICAM con:
        - Escudo y datos de la Alcaldia de San Jorge
        - Llamado a la accion para el ciudadano
        - Botones de Iniciar Sesion y Registrarse
        - Listado de categorias de reportes
        - Estadisticas del sistema
        - Pasos explicativos del proceso
    -->
    <div class="min-h-screen bg-slate-50">

        <!-- TOP BAR GUBERNAMENTAL -->
        <div class="bg-slate-900 text-white text-xs py-2">
            <div class="max-w-7xl mx-auto px-6 flex justify-between items-center flex-wrap gap-2">
                <span>Gobierno Municipal de San Jorge · Republica de El Salvador</span>
                <div class="flex gap-4 flex-wrap">
                    <span>📞 (503) 2664-0000</span>
                    <span>✉ contacto@sanjorge.gob.sv</span>
                </div>
            </div>
        </div>

        <!-- NAVBAR -->
        <header class="bg-white shadow-sm sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">

                <!-- Logo y nombre -->
                <div class="flex items-center gap-3">
                    <img
                        src="c:\laragon\www\LICAM\public\img\escudo-san-jorge.jpeg"

                        alt="Escudo Alcaldia de San Jorge"
                        class="w-14 h-14 object-contain"
                    >
                    <div>
                        <h1 class="font-bold text-slate-800 text-lg leading-tight">Alcaldia de San Jorge</h1>
                        <p class="text-xs text-slate-500">Sistema LICAM · Linea Ciudadana</p>
                    </div>
                </div>

                <!-- Botones de acceso -->
                <div class="flex gap-3">
                    <router-link
                        to="/login"
                        class="px-4 sm:px-5 py-2 text-sky-700 font-semibold hover:bg-sky-50 rounded-lg transition"
                    >
                        Iniciar Sesion
                    </router-link>
                    <router-link
                        to="/registro"
                        class="px-4 sm:px-5 py-2 bg-sky-700 text-white font-semibold rounded-lg hover:bg-sky-800 transition shadow-md"
                    >
                        Registrarse
                    </router-link>
                </div>
            </div>
        </header>

        <!-- HERO PRINCIPAL CON ESCUDO -->
        <section class="bg-gradient-to-br from-sky-700 via-sky-800 to-sky-900 text-white py-16 sm:py-20 relative overflow-hidden">

            <!-- Efectos decorativos -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-10 right-10 w-72 h-72 rounded-full bg-white blur-3xl"></div>
                <div class="absolute bottom-10 left-10 w-96 h-96 rounded-full bg-amber-400 blur-3xl"></div>
            </div>

            <div class="max-w-7xl mx-auto px-6 relative z-10">
                <div class="grid lg:grid-cols-2 gap-12 items-center">

                    <!-- Texto del hero -->
                    <div class="text-center lg:text-left order-2 lg:order-1">
                        <span class="inline-block px-4 py-1 bg-amber-400 text-slate-900 rounded-full text-sm font-bold mb-6">
                            PLATAFORMA OFICIAL
                        </span>
                        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold mb-6 leading-tight">
                            Construyamos juntos un San Jorge mejor
                        </h1>
                        <p class="text-lg sm:text-xl text-sky-100 mb-8 leading-relaxed">
                            Tu voz es importante. Reporta problemas en tu comunidad, da seguimiento
                            a su solucion y forma parte del cambio que necesita nuestro municipio.
                        </p>

                        <!-- Botones principales de accion -->
                        <div class="flex flex-wrap gap-4 justify-center lg:justify-start">
                            <router-link
                                to="/registro"
                                class="px-8 py-4 bg-amber-400 text-slate-900 font-bold rounded-xl hover:bg-amber-300 shadow-lg transition flex items-center gap-2"
                            >
                                <span>📝</span> Crear mi cuenta
                            </router-link>
                            <router-link
                                to="/login"
                                class="px-8 py-4 bg-white/10 backdrop-blur-sm border-2 border-white/30 text-white font-bold rounded-xl hover:bg-white/20 transition flex items-center gap-2"
                            >
                                <span>🔐</span> Iniciar sesion
                            </router-link>
                        </div>
                    </div>

                    <!-- Escudo de la alcaldia -->
                    <div class="flex justify-center order-1 lg:order-2">
                        <div class="relative">
                            <!-- Aro decorativo detras del escudo -->
                            <div class="absolute inset-0 bg-white/10 rounded-full blur-2xl"></div>
                            <img
                                src="/img/escudo-san-jorge.png"
                                alt="Escudo Alcaldia Municipal de San Jorge"
                                class="relative w-72 sm:w-80 lg:w-96 h-auto drop-shadow-2xl"
                            >
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- LEMA INSTITUCIONAL -->
        <section class="bg-amber-400 py-6">
            <div class="max-w-7xl mx-auto px-6 text-center">
                <p class="text-slate-900 font-bold text-lg sm:text-xl">
                    "Humildad, Transparencia y Honestidad"
                </p>
                <p class="text-slate-700 text-sm mt-1">Valores de nuestra Alcaldia Municipal</p>
            </div>
        </section>

        <!-- ESTADISTICAS DEL SISTEMA -->
        <section class="bg-white py-12 border-b border-slate-200">
            <div class="max-w-7xl mx-auto px-6 grid grid-cols-2 md:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="text-4xl font-extrabold text-sky-700 mb-1">{{ estadisticas.total }}</div>
                    <div class="text-sm text-slate-600 font-medium">Reportes recibidos</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-extrabold text-emerald-600 mb-1">{{ estadisticas.resueltos }}</div>
                    <div class="text-sm text-slate-600 font-medium">Problemas resueltos</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-extrabold text-amber-600 mb-1">{{ estadisticas.enProceso }}</div>
                    <div class="text-sm text-slate-600 font-medium">En proceso</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-extrabold text-slate-700 mb-1">{{ categorias.length }}</div>
                    <div class="text-sm text-slate-600 font-medium">Tipos de reportes</div>
                </div>
            </div>
        </section>

        <!-- LLAMADO A LA ACCION COMUNITARIO -->
        <section class="bg-gradient-to-r from-slate-100 to-sky-50 py-16">
            <div class="max-w-4xl mx-auto px-6 text-center">
                <span class="inline-block px-4 py-1 bg-sky-700 text-white rounded-full text-sm font-bold mb-4">
                    🤝 UNIDOS POR SAN JORGE
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-800 mb-4">
                    Tu participacion hace la diferencia
                </h2>
                <p class="text-slate-600 text-lg leading-relaxed mb-6">
                    Cada reporte que envias ayuda a la Alcaldia a identificar y atender los problemas
                    que afectan a nuestra comunidad. Calles dañadas, alumbrado, basura, fugas de agua,
                    parques deteriorados... Todo lo que mejoremos juntos transforma nuestra ciudad.
                </p>
                <p class="text-slate-700 font-bold text-lg mb-8">
                    Si ves un problema, ¡reportalo! Tu comunidad cuenta contigo. 💪
                </p>
                <router-link
                    to="/registro"
                    class="inline-block px-8 py-4 bg-sky-700 text-white font-bold rounded-xl hover:bg-sky-800 shadow-lg shadow-sky-700/30 transition"
                >
                    Unirme ahora →
                </router-link>
            </div>
        </section>

        <!-- CATEGORIAS DE REPORTES -->
        <section class="py-16">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center mb-12">
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-800 mb-3">
                        ¿Que problemas puedes reportar?
                    </h2>
                    <p class="text-slate-600 text-lg">
                        Estos son los tipos de problemas que la Alcaldia atiende
                    </p>
                </div>

                <!-- Lista de categorias -->
                <div v-if="cargandoCategorias" class="text-center py-12">
                    <div class="text-5xl mb-3 animate-pulse">⏳</div>
                    <p class="text-slate-600">Cargando categorias...</p>
                </div>

                <div v-else class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    <div
                        v-for="categoria in categorias"
                        :key="categoria.id"
                        class="bg-white rounded-2xl p-6 border border-slate-200 hover:shadow-xl hover:-translate-y-1 transition-all cursor-pointer"
                    >
                        <div
                            class="w-16 h-16 rounded-2xl flex items-center justify-center text-3xl mb-4"
                            :style="{ backgroundColor: categoria.color + '20' }"
                        >
                            {{ categoria.icono }}
                        </div>
                        <h3 class="font-bold text-slate-800 text-lg mb-1">{{ categoria.nombre }}</h3>
                        <p class="text-sm text-slate-500">{{ categoria.descripcion }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- COMO FUNCIONA -->
        <section class="bg-slate-100 py-16">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center mb-12">
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-800 mb-3">
                        ¿Como funciona?
                    </h2>
                    <p class="text-slate-600 text-lg">Tres pasos simples para mejorar tu comunidad</p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <!-- Paso 1 -->
                    <div class="bg-white rounded-2xl p-8 text-center shadow-sm hover:shadow-md transition">
                        <div class="w-16 h-16 bg-sky-700 text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4">
                            1
                        </div>
                        <div class="text-4xl mb-3">📝</div>
                        <h3 class="font-bold text-slate-800 text-xl mb-2">Reporta</h3>
                        <p class="text-slate-600">
                            Crea tu cuenta gratuita y reporta el problema con titulo, descripcion
                            y la ubicacion exacta.
                        </p>
                    </div>

                    <!-- Paso 2 -->
                    <div class="bg-white rounded-2xl p-8 text-center shadow-sm hover:shadow-md transition">
                        <div class="w-16 h-16 bg-amber-500 text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4">
                            2
                        </div>
                        <div class="text-4xl mb-3">🔄</div>
                        <h3 class="font-bold text-slate-800 text-xl mb-2">Seguimiento</h3>
                        <p class="text-slate-600">
                            La Alcaldia clasifica tu reporte, lo prioriza y trabaja en su solucion.
                            Tu puedes ver el avance en todo momento.
                        </p>
                    </div>

                    <!-- Paso 3 -->
                    <div class="bg-white rounded-2xl p-8 text-center shadow-sm hover:shadow-md transition">
                        <div class="w-16 h-16 bg-emerald-600 text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4">
                            3
                        </div>
                        <div class="text-4xl mb-3">✅</div>
                        <h3 class="font-bold text-slate-800 text-xl mb-2">Solucion</h3>
                        <p class="text-slate-600">
                            Recibe confirmacion cuando el problema sea atendido y resuelto.
                            ¡Tu comunidad agradece tu aporte!
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA FINAL -->
        <section class="bg-gradient-to-br from-sky-700 to-sky-900 text-white py-16">
            <div class="max-w-4xl mx-auto px-6 text-center">
                <h2 class="text-3xl sm:text-4xl font-extrabold mb-4">
                    ¿Listo para sumarte al cambio?
                </h2>
                <p class="text-sky-100 text-lg mb-8">
                    Es gratis, rapido y tu participacion realmente importa.
                </p>
                <div class="flex flex-wrap gap-4 justify-center">
                    <router-link
                        to="/registro"
                        class="px-8 py-4 bg-amber-400 text-slate-900 font-bold rounded-xl hover:bg-amber-300 shadow-lg transition"
                    >
                        Crear cuenta gratuita
                    </router-link>
                    <router-link
                        to="/login"
                        class="px-8 py-4 bg-white/10 backdrop-blur-sm border-2 border-white/30 text-white font-bold rounded-xl hover:bg-white/20 transition"
                    >
                        Ya tengo cuenta
                    </router-link>
                </div>
            </div>
        </section>

        <!-- FOOTER -->
        <footer class="bg-slate-900 text-slate-300 py-12">
            <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-3 gap-8">

                <!-- Columna 1: Identidad -->
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <img
                            src="/img/escudo-san-jorge.png"
                            alt="Escudo"
                            class="w-12 h-12 object-contain"
                        >
                        <div>
                            <span class="font-bold text-white block">Alcaldia de San Jorge</span>
                            <span class="text-xs text-slate-400">Fundado 10 de Julio 1920</span>
                        </div>
                    </div>
                    <p class="text-sm leading-relaxed">
                        Alcaldia Municipal de San Jorge trabajando por una ciudad mejor con la
                        participacion activa de toda la comunidad.
                    </p>
                </div>

                <!-- Columna 2: Contacto -->
                <div>
                    <h4 class="font-bold text-white mb-3">Contacto</h4>
                    <ul class="space-y-2 text-sm">
                        <li>📍 Centro de San Jorge, El Salvador</li>
                        <li>📞 (503) 2664-0000</li>
                        <li>✉ contacto@sanjorge.gob.sv</li>
                    </ul>
                </div>

                <!-- Columna 3: Horarios -->
                <div>
                    <h4 class="font-bold text-white mb-3">Horarios de atencion</h4>
                    <p class="text-sm leading-relaxed">
                        Lunes a Viernes: 8:00 - 16:00<br>
                        Sabado: 8:00 - 12:00<br>
                        Domingo: Cerrado
                    </p>
                </div>
            </div>

            <div class="max-w-7xl mx-auto px-6 mt-8 pt-6 border-t border-slate-800 text-center text-sm">
                © 2026 Alcaldia Municipal de San Jorge · Sistema LICAM · Todos los derechos reservados
            </div>
        </footer>
    </div>
</template>

<script setup>
/**
 * Vista de Inicio (Landing publica) del sistema LICAM.
 *
 * Pagina de bienvenida visible para visitantes no autenticados.
 * Presenta la identidad de la Alcaldia Municipal de San Jorge,
 * un llamado a la accion para la comunidad, y muestra las
 * categorias de reportes disponibles asi como estadisticas
 * generales del sistema.
 *
 * @project LICAM - Linea Ciudadana de Atencion Municipal
 */

import { ref, computed, onMounted } from 'vue';
import axios from 'axios';

// Estado reactivo de la vista
const categorias = ref([]);
const reportes = ref([]);
const cargandoCategorias = ref(false);

/**
 * Estadisticas calculadas a partir de los reportes obtenidos de la API.
 * Se muestran como motivacion publica en la landing.
 */
const estadisticas = computed(() => {
    return {
        total: reportes.value.length,
        resueltos: reportes.value.filter(r => r.estado === 'resuelto').length,
        enProceso: reportes.value.filter(r => r.estado === 'en_proceso').length,
    };
});

/**
 * Carga el listado publico de categorias.
 * Se usa axios directo (sin token) porque esta vista es publica.
 */
const cargarCategorias = async () => {
    cargandoCategorias.value = true;
    try {
        // Hacemos peticion publica intentando con el endpoint
        // Si requiere autenticacion, simplemente no mostraremos las categorias
        const response = await axios.get('/api/categorias');
        categorias.value = response.data.data || [];
    } catch (error) {
        // Si falla por autenticacion, mostramos categorias predeterminadas
        categorias.value = obtenerCategoriasPorDefecto();
    } finally {
        cargandoCategorias.value = false;
    }
};

/**
 * Categorias predeterminadas que se muestran si no se puede consultar la API.
 * Sirve como fallback para que la pagina siempre se vea completa.
 */
const obtenerCategoriasPorDefecto = () => {
    return [
        { id: 1, nombre: 'Calles y Baches', descripcion: 'Pavimento dañado, hoyos.', icono: '🚧', color: '#EF4444' },
        { id: 2, nombre: 'Alumbrado Publico', descripcion: 'Postes caidos, luminarias.', icono: '💡', color: '#F59E0B' },
        { id: 3, nombre: 'Basura y Limpieza', descripcion: 'Desechos, vertederos.', icono: '🗑️', color: '#10B981' },
        { id: 4, nombre: 'Agua y Drenaje', descripcion: 'Fugas, tuberias rotas.', icono: '💧', color: '#3B82F6' },
        { id: 5, nombre: 'Parques y Areas Verdes', descripcion: 'Espacios deteriorados.', icono: '🌳', color: '#8B5CF6' },
        { id: 6, nombre: 'Señalizacion Vial', descripcion: 'Semaforos, señales.', icono: '🚦', color: '#EC4899' },
        { id: 7, nombre: 'Obras Publicas', descripcion: 'Infraestructura municipal.', icono: '🏚️', color: '#06B6D4' },
        { id: 8, nombre: 'Otros', descripcion: 'Otras incidencias urbanas.', icono: '📋', color: '#64748B' },
    ];
};

/**
 * Intenta cargar las estadisticas de reportes.
 * Si falla, las estadisticas quedan en cero.
 */
const cargarEstadisticas = async () => {
    try {
        const response = await axios.get('/api/reportes');
        reportes.value = response.data.data || [];
    } catch (error) {
        // Si falla, dejamos las estadisticas en cero
        reportes.value = [];
    }
};

/**
 * Hook de Vue: se ejecuta al montar el componente.
 * Carga las categorias y estadisticas iniciales.
 */
onMounted(() => {
    cargarCategorias();
    cargarEstadisticas();
});
</script>
