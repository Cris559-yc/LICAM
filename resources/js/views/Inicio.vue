<template>
    <!--
        Vista: Pagina de Inicio (Landing publica)
        Pagina de bienvenida del sistema LICAM

    -->
    <div class="min-h-screen bg-slate-950 text-slate-200">

        <!-- TOP BAR GUBERNAMENTAL -->
        <div class="bg-black/40 text-slate-400 text-xs py-2 border-b border-slate-800">
            <div class="max-w-7xl mx-auto px-6 flex justify-between items-center flex-wrap gap-2">
                <span>Gobierno Municipal de San Jorge · Republica de El Salvador</span>
                <div class="flex gap-4 flex-wrap">
                    <span>📞 (503) 2664-0000</span>
                    <span>✉ contacto@sanjorge.gob.sv</span>
                </div>
            </div>
        </div>

        <!-- NAVBAR -->
        <header class="bg-slate-900/80 backdrop-blur-md border-b border-slate-800 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-6 py-4 flex items-center">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-sky-500 to-cyan-400 rounded-xl flex items-center justify-center text-slate-900 font-extrabold text-lg shadow-lg shadow-sky-500/30">
                        SJ
                    </div>
                    <div>
                        <h1 class="font-bold text-white text-lg leading-tight">Alcaldia de San Jorge</h1>
                        <p class="text-xs text-slate-400">Sistema LICAM · Linea Ciudadana</p>
                    </div>
                </div>
            </div>
        </header>

        <!-- HERO PRINCIPAL -->
        <section class="relative py-24 sm:py-32 overflow-hidden">

            <!-- Efectos de luz de fondo -->
            <div class="absolute inset-0">
                <div class="absolute top-0 left-1/4 w-96 h-96 rounded-full bg-sky-600/20 blur-3xl"></div>
                <div class="absolute bottom-0 right-1/4 w-96 h-96 rounded-full bg-cyan-500/10 blur-3xl"></div>
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] rounded-full bg-blue-900/20 blur-3xl"></div>
            </div>

            <!-- Patron de puntos sutil -->
            <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle, #334155 1px, transparent 1px); background-size: 32px 32px;"></div>

            <div class="max-w-4xl mx-auto px-6 relative z-10 text-center">
                <span class="inline-block px-4 py-1.5 bg-sky-500/10 border border-sky-500/30 text-sky-400 rounded-full text-sm font-bold mb-6">
                    PLATAFORMA OFICIAL
                </span>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold mb-6 leading-tight text-white">
                    Construyamos juntos un
                    <span class="bg-gradient-to-r from-sky-400 to-cyan-300 bg-clip-text text-transparent">
                        San Jorge mejor
                    </span>
                </h1>
                <p class="text-lg sm:text-xl text-slate-400 mb-10 leading-relaxed max-w-2xl mx-auto">
                    Tu voz es importante. Reporta problemas en tu comunidad, da seguimiento
                    a su solucion y forma parte del cambio que necesita nuestro municipio.
                </p>

                <!-- botones de crear cuenta e iniciar sesion -->
                <div class="flex flex-wrap gap-4 justify-center">
                    <router-link
                        to="/registro"
                        class="px-8 py-4 bg-gradient-to-r from-sky-500 to-cyan-400 text-slate-900 font-bold rounded-xl hover:from-sky-400 hover:to-cyan-300 shadow-lg shadow-sky-500/30 transition flex items-center gap-2"
                    >
                        <span>📝</span> Crear mi cuenta
                    </router-link>
                    <router-link
                        to="/login"
                        class="px-8 py-4 bg-slate-800/80 border border-slate-700 text-white font-bold rounded-xl hover:bg-slate-700 transition flex items-center gap-2"
                    >
                        <span>🔐</span> Iniciar sesion
                    </router-link>
                </div>
            </div>
        </section>

        <!-- LEMA INSTITUCIONAL -->
        <section class="border-y border-slate-800 bg-slate-900/50 py-6">
            <div class="max-w-7xl mx-auto px-6 text-center">
                <p class="text-transparent bg-clip-text bg-gradient-to-r from-sky-400 to-cyan-300 font-bold text-lg sm:text-xl">
                    "Humildad, Transparencia y Honestidad"
                </p>
                <p class="text-slate-500 text-sm mt-1">Valores de nuestra Alcaldia Municipal</p>
            </div>
        </section>

        <!-- ESTADISTICA: TIPOS DE REPORTES -->
        <section class="py-14">
            <div class="max-w-sm mx-auto px-6">
                <div class="text-center bg-slate-900/50 border border-slate-800 rounded-2xl py-10 shadow-xl">
                    <div class="text-6xl font-extrabold bg-gradient-to-r from-sky-400 to-cyan-300 bg-clip-text text-transparent mb-2">
                        {{ categorias.length }}
                    </div>
                    <div class="text-slate-300 font-semibold text-lg">Tipos de reportes</div>
                    <div class="text-slate-500 text-sm mt-1">disponibles para la comunidad</div>
                </div>
            </div>
        </section>

        <!-- LLAMADO A LA ACCION COMUNITARIO -->
        <section class="py-16">
            <div class="max-w-4xl mx-auto px-6 text-center">
                <span class="inline-block px-4 py-1.5 bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 rounded-full text-sm font-bold mb-4">
                    UNIDOS POR SAN JORGE
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white mb-4">
                    Tu participacion hace la diferencia
                </h2>
                <p class="text-slate-400 text-lg leading-relaxed mb-6">
                    Cada reporte que envias ayuda a la Alcaldia a identificar y atender los problemas
                    que afectan a nuestra comunidad. Calles dañadas, alumbrado, basura, fugas de agua,
                    parques deteriorados... Todo lo que mejoremos juntos transforma nuestra ciudad.
                </p>
                <p class="text-white font-bold text-lg">
                    Si ves un problema, ¡reportalo! Tu comunidad cuenta contigo.
                </p>
            </div>
        </section>

        <!-- CATEGORIAS DE REPORTES -->
        <section class="py-16">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center mb-12">
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-white mb-3">
                        ¿Que problemas puedes reportar?
                    </h2>
                    <p class="text-slate-400 text-lg">
                        Estos son los tipos de problemas que la Alcaldia atiende
                    </p>
                </div>

                <!-- Estado de carga -->
                <div v-if="cargandoCategorias" class="text-center py-12">
                    <div class="text-5xl mb-3 animate-pulse">⏳</div>
                    <p class="text-slate-400">Cargando categorias...</p>
                </div>

                <!-- Lista de categorias -->
                <div v-else class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    <div
                        v-for="categoria in categorias"
                        :key="categoria.id"
                        class="bg-slate-900/50 rounded-2xl p-6 border border-slate-800 hover:border-sky-500/50 hover:bg-slate-900 hover:-translate-y-1 transition-all"
                    >
                        <div
                            class="w-16 h-16 rounded-2xl flex items-center justify-center text-3xl mb-4"
                            :style="{ backgroundColor: categoria.color + '25' }"
                        >
                            {{ categoria.icono }}
                        </div>
                        <h3 class="font-bold text-white text-lg mb-1">{{ categoria.nombre }}</h3>
                        <p class="text-sm text-slate-400">{{ categoria.descripcion }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- COMO FUNCIONA -->
        <section class="py-16 border-t border-slate-800">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center mb-12">
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-white mb-3">
                        ¿Como funciona?
                    </h2>
                    <p class="text-slate-400 text-lg">Tres pasos simples para mejorar tu comunidad</p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <!-- Paso 1 -->
                    <div class="bg-slate-900/50 rounded-2xl p-8 text-center border border-slate-800 hover:border-sky-500/50 transition">
                        <div class="w-16 h-16 bg-gradient-to-br from-sky-500 to-cyan-400 text-slate-900 rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4">
                            1
                        </div>
                        <div class="text-4xl mb-3">📝</div>
                        <h3 class="font-bold text-white text-xl mb-2">Reporta</h3>
                        <p class="text-slate-400">
                            Crea tu cuenta gratuita y reporta el problema con titulo, descripcion
                            y la ubicacion exacta.
                        </p>
                    </div>

                    <!-- Paso 2 -->
                    <div class="bg-slate-900/50 rounded-2xl p-8 text-center border border-slate-800 hover:border-amber-500/50 transition">
                        <div class="w-16 h-16 bg-gradient-to-br from-amber-400 to-orange-400 text-slate-900 rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4">
                            2
                        </div>
                        <div class="text-4xl mb-3">🔄</div>
                        <h3 class="font-bold text-white text-xl mb-2">Seguimiento</h3>
                        <p class="text-slate-400">
                            La Alcaldia clasifica tu reporte, lo prioriza y trabaja en su solucion.
                            Tu puedes ver el avance en todo momento.
                        </p>
                    </div>

                    <!-- Paso 3 -->
                    <div class="bg-slate-900/50 rounded-2xl p-8 text-center border border-slate-800 hover:border-emerald-500/50 transition">
                        <div class="w-16 h-16 bg-gradient-to-br from-emerald-400 to-green-400 text-slate-900 rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4">
                            3
                        </div>
                        <div class="text-4xl mb-3">✅</div>
                        <h3 class="font-bold text-white text-xl mb-2">Solucion</h3>
                        <p class="text-slate-400">
                            Recibe confirmacion cuando el problema sea atendido y resuelto.
                            ¡Tu comunidad agradece tu aporte!
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- FOOTER -->
        <footer class="bg-black/40 border-t border-slate-800 py-12">
            <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-3 gap-8">

                <!-- Columna 1: Identidad -->
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-sky-500 to-cyan-400 rounded-xl flex items-center justify-center text-slate-900 font-extrabold shadow-lg shadow-sky-500/30">
                            SJ
                        </div>
                        <div>
                            <span class="font-bold text-white block">Alcaldia de San Jorge</span>
                            <span class="text-xs text-slate-500">Fundado 10 de Julio 1920</span>
                        </div>
                    </div>
                    <p class="text-sm text-slate-400 leading-relaxed">
                        Alcaldia Municipal de San Jorge trabajando por una ciudad mejor con la
                        participacion activa de toda la comunidad.
                    </p>
                </div>

                <!-- Columna 2: Contacto -->
                <div>
                    <h4 class="font-bold text-white mb-3">Contacto</h4>
                    <ul class="space-y-2 text-sm text-slate-400">
                        <li>📍 San Jorge, San Miguel, El Salvador</li>
                        <li>📞 (503) 2664-0000</li>
                        <li>✉ contacto@sanjorge.gob.sv</li>
                    </ul>
                </div>

                <!-- Columna 3: Horarios -->
                <div>
                    <h4 class="font-bold text-white mb-3">Horarios de atencion</h4>
                    <p class="text-sm text-slate-400 leading-relaxed">
                        Lunes a Viernes: 8:00 - 16:00<br>
                        Sabado: 8:00 - 12:00<br>
                        Domingo: Cerrado
                    </p>
                </div>
            </div>

            <div class="max-w-7xl mx-auto px-6 mt-8 pt-6 border-t border-slate-800 text-center text-sm text-slate-500">
                © 2026 Alcaldia Municipal de San Jorge · Sistema LICAM · Todos los derechos reservados
            </div>
        </footer>
    </div>
</template>

<script setup>
/**
 * Vista de Inicio (Landing publica) del sistema LICAM.
 *
 * Pagina de bienvenida.
 * Presenta la identidad de la Alcaldia Municipal de San Jorge,
 * un llamado a la accion comunitario y muestra las categorias
 * de reportes disponibles.
 *
 * @project LICAM - Linea Ciudadana de Atencion Municipal
 */

import { ref, onMounted } from 'vue';
import axios from 'axios';

// Estado reactivo de la vista
const categorias = ref([]);
const cargandoCategorias = ref(false);

/**
 * Carga el listado publico de categorias desde la API.
 * Si la peticion falla, usa un listado predeterminado como respaldo.
 */
const cargarCategorias = async () => {
    cargandoCategorias.value = true;
    try {
        const response = await axios.get('/api/categorias');
        categorias.value = response.data.data || [];
    } catch (error) {
        // Respaldo: categorias predeterminadas si la API no responde
        categorias.value = obtenerCategoriasPorDefecto();
    } finally {
        cargandoCategorias.value = false;
    }
};

/**
 * Categorias predeterminadas que se muestran si la API no esta disponible.
 * Sirve como respaldo para que la pagina siempre se vea completa.
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
 * Hook de Vue: se ejecuta al montar el componente.
 * Carga las categorias disponibles.
 */
onMounted(() => {
    cargarCategorias();
});
</script>
