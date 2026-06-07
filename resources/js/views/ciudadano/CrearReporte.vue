<template>
    <!--
        Vista: Crear Reporte (Ciudadano)
        Formulario para crear un nuevo reporte ciudadano.
        Permite ingresar titulo, descripcion, categoria sugerida y ubicacion.
    -->
    <div class="min-h-screen bg-slate-50">

        <!-- Barra de navegacion del ciudadano -->
        <NavbarCiudadano />

        <!-- Contenido principal -->
        <main class="max-w-7xl mx-auto px-6 py-8">

            <!-- ENCABEZADO DE LA PAGINA -->
            <div class="mb-8">
                <h2 class="text-3xl font-extrabold text-slate-800 mb-2">Nuevo Reporte Ciudadano</h2>
                <p class="text-slate-600">Completa los siguientes campos para reportar el problema a la alcaldia</p>
            </div>

            <!-- MENSAJE DE EXITO -->
            <div v-if="mensajeExito" class="mb-6 p-4 bg-emerald-50 border-2 border-emerald-200 rounded-xl text-emerald-700 flex items-center gap-3">
                <span class="text-2xl">✅</span>
                <div>
                    <p class="font-bold">{{ mensajeExito }}</p>
                    <p class="text-sm">Seras redirigido a tus reportes en un momento...</p>
                </div>
            </div>

            <!-- MENSAJE DE ERROR GENERAL -->
            <div v-if="errorGeneral" class="mb-6 p-4 bg-red-50 border-2 border-red-200 rounded-xl text-red-700 flex items-center gap-3">
                <span class="text-2xl">⚠️</span>
                <div>
                    <p class="font-bold">{{ errorGeneral }}</p>
                </div>
            </div>

            <!-- FORMULARIO PRINCIPAL -->
            <form @submit.prevent="enviarReporte" class="grid lg:grid-cols-3 gap-8">

                <!-- COLUMNA IZQUIERDA: Datos del reporte (2/3 del ancho) -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- TARJETA: Informacion del problema -->
                    <div class="bg-white rounded-2xl p-8 shadow-sm">
                        <h3 class="font-bold text-slate-800 text-xl mb-6 flex items-center gap-2">
                            <span class="w-8 h-8 bg-sky-100 text-sky-700 rounded-lg flex items-center justify-center">📝</span>
                            Informacion del problema
                        </h3>

                        <div class="space-y-5">

                            <!-- Titulo del reporte -->
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">
                                    Titulo del reporte *
                                </label>
                                <input
                                    v-model="formulario.titulo"
                                    type="text"
                                    placeholder="Ej: Bache profundo frente a la escuela"
                                    maxlength="150"
                                    :disabled="cargando"
                                    class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-sky-600 focus:outline-none disabled:bg-slate-100"
                                >
                                <div class="flex justify-between mt-1">
                                    <p v-if="errores.titulo" class="text-red-600 text-xs">{{ errores.titulo[0] }}</p>
                                    <p class="text-xs text-slate-400 ml-auto">{{ formulario.titulo.length }}/150</p>
                                </div>
                            </div>

                            <!-- Descripcion detallada -->
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">
                                    Descripcion detallada *
                                </label>
                                <textarea
                                    v-model="formulario.descripcion"
                                    rows="5"
                                    placeholder="Describe el problema con el mayor detalle posible..."
                                    :disabled="cargando"
                                    class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-sky-600 focus:outline-none resize-none disabled:bg-slate-100"
                                ></textarea>
                                <p v-if="errores.descripcion" class="text-red-600 text-xs mt-1">{{ errores.descripcion[0] }}</p>
                            </div>

                            <!-- Categoria sugerida -->
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">
                                    Categoria sugerida (opcional)
                                </label>
                                <select
                                    v-model="formulario.categoria_id"
                                    :disabled="cargando || cargandoCategorias"
                                    class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-sky-600 focus:outline-none disabled:bg-slate-100"
                                >
                                    <option :value="null">-- Selecciona una categoria --</option>
                                    <option
                                        v-for="categoria in categorias"
                                        :key="categoria.id"
                                        :value="categoria.id"
                                    >
                                        {{ categoria.icono }} {{ categoria.nombre }}
                                    </option>
                                </select>
                                <p class="text-xs text-slate-500 mt-1">
                                    El administrador confirmara la categoria definitiva.
                                </p>
                                <p v-if="errores.categoria_id" class="text-red-600 text-xs mt-1">{{ errores.categoria_id[0] }}</p>
                            </div>

                        </div>
                    </div>

                    <!-- TARJETA: Ubicacion del problema -->
                    <div class="bg-white rounded-2xl p-8 shadow-sm">
                        <h3 class="font-bold text-slate-800 text-xl mb-6 flex items-center gap-2">
                            <span class="w-8 h-8 bg-emerald-100 text-emerald-700 rounded-lg flex items-center justify-center">📍</span>
                            Ubicacion del problema
                        </h3>

                        <!-- TARJETA: Evidencia fotografica -->
                    <div class="bg-white rounded-2xl p-8 shadow-sm">
                        <h3 class="font-bold text-slate-800 text-xl mb-6 flex items-center gap-2">
                            <span class="w-8 h-8 bg-amber-100 text-amber-700 rounded-lg flex items-center justify-center">📷</span>
                            Evidencia fotografica (opcional)
                        </h3>

                        <!-- Zona de carga -->
                        <div class="border-2 border-dashed border-slate-300 rounded-xl p-8 text-center hover:border-sky-500 hover:bg-sky-50/50 transition">
                            <div class="text-5xl mb-3">📸</div>
                            <p class="font-semibold text-slate-700 mb-1">Selecciona hasta 4 fotos del problema</p>
                            <p class="text-sm text-slate-500 mb-4">JPG, PNG o WEBP · Maximo 5 MB por imagen</p>
                            <label class="inline-block px-6 py-2 bg-sky-700 text-white font-semibold rounded-lg hover:bg-sky-800 cursor-pointer transition">
                                Seleccionar fotos
                                <input
                                    type="file"
                                    accept="image/jpeg,image/jpg,image/png,image/webp"
                                    multiple
                                    @change="seleccionarImagenes"
                                    :disabled="cargando"
                                    class="hidden"
                                >
                            </label>
                        </div>

                        <!-- Vista previa de las imagenes seleccionadas -->
                        <div v-if="imagenesPreview.length > 0" class="grid grid-cols-4 gap-3 mt-4">
                            <div
                                v-for="(img, index) in imagenesPreview"
                                :key="index"
                                class="relative aspect-square rounded-xl overflow-hidden border border-slate-200"
                            >
                                <img :src="img.preview" alt="Vista previa" class="w-full h-full object-cover">
                                <button
                                    type="button"
                                    @click="quitarImagen(index)"
                                    class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full text-xs font-bold hover:bg-red-600"
                                >
                                    ×
                                </button>
                            </div>
                        </div>

                        <!-- Error de imagenes -->
                        <p v-if="errores.imagenes" class="text-red-600 text-xs mt-2">{{ errores.imagenes[0] }}</p>
                    </div>

                        <div class="space-y-5">

                            <!-- Direccion textual -->
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">
                                    Direccion (opcional)
                                </label>
                                <input
                                    v-model="formulario.direccion"
                                    type="text"
                                    placeholder="Ej: Calle Principal, Barrio El Centro, San Jorge"
                                    maxlength="255"
                                    :disabled="cargando"
                                    class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-sky-600 focus:outline-none disabled:bg-slate-100"
                                >
                                <p v-if="errores.direccion" class="text-red-600 text-xs mt-1">{{ errores.direccion[0] }}</p>
                            </div>

                            <!-- Coordenadas: Latitud y Longitud -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                                        Latitud *
                                    </label>
                                    <input
                                        v-model.number="formulario.latitud"
                                        type="number"
                                        step="any"
                                        placeholder="13.47750000"
                                        :disabled="cargando"
                                        class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-sky-600 focus:outline-none disabled:bg-slate-100"
                                    >
                                    <p v-if="errores.latitud" class="text-red-600 text-xs mt-1">{{ errores.latitud[0] }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                                        Longitud *
                                    </label>
                                    <input
                                        v-model.number="formulario.longitud"
                                        type="number"
                                        step="any"
                                        placeholder="-88.45250000"
                                        :disabled="cargando"
                                        class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-sky-600 focus:outline-none disabled:bg-slate-100"
                                    >
                                    <p v-if="errores.longitud" class="text-red-600 text-xs mt-1">{{ errores.longitud[0] }}</p>
                                </div>
                            </div>

                            <!-- Boton para obtener ubicacion actual -->
                            <button
                                type="button"
                                @click="obtenerUbicacionActual"
                                :disabled="cargando || obteniendoUbicacion"
                                class="w-full py-3 bg-emerald-600 text-white font-semibold rounded-xl hover:bg-emerald-700 disabled:opacity-50 disabled:cursor-not-allowed transition flex items-center justify-center gap-2"
                            >
                                <span>🎯</span>
                                {{ obteniendoUbicacion ? 'Obteniendo ubicacion...' : 'Usar mi ubicacion actual' }}
                            </button>

                            <!-- Mensaje informativo sobre coordenadas -->
                            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-sm text-blue-700">
                                <p class="font-semibold mb-1">💡 Ayuda con las coordenadas:</p>
                                <p>San Jorge esta aproximadamente en latitud 13.4775 y longitud -88.4525. Puedes usar el boton de arriba para obtener tu ubicacion automaticamente.</p>
                            </div>

                        </div>
                    </div>

                </div>

                <!-- COLUMNA DERECHA: Tips y boton de envio (1/3 del ancho) -->
                <div class="space-y-6">

                    <!-- TARJETA: Consejos -->
                    <div class="bg-gradient-to-br from-sky-700 to-sky-900 text-white rounded-2xl p-6">
                        <h3 class="font-bold text-lg mb-4 flex items-center gap-2">
                            💡 Consejos utiles
                        </h3>
                        <ul class="space-y-3 text-sm text-sky-100">
                            <li class="flex gap-2"><span class="text-amber-400">✓</span> Se especifico en la descripcion</li>
                            <li class="flex gap-2"><span class="text-amber-400">✓</span> Indica la ubicacion exacta</li>
                            <li class="flex gap-2"><span class="text-amber-400">✓</span> Reporta solo un problema a la vez</li>
                            <li class="flex gap-2"><span class="text-amber-400">✓</span> La alcaldia revisara tu reporte pronto</li>
                        </ul>
                    </div>

                    <!-- TARJETA: Datos del reportante -->
                    <div class="bg-white rounded-2xl p-6 shadow-sm">
                        <h3 class="font-bold text-slate-800 mb-4">Datos del reportante</h3>
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-12 h-12 bg-amber-400 rounded-full flex items-center justify-center font-bold text-slate-800">
                                {{ iniciales }}
                            </div>
                            <div>
                                <p class="font-bold text-slate-800">
                                    {{ authStore.user?.name }} {{ authStore.user?.apellido }}
                                </p>
                                <p class="text-slate-500 text-xs">{{ authStore.user?.email }}</p>
                            </div>
                        </div>
                        <div class="text-xs text-slate-500 pt-3 border-t border-slate-100 space-y-1">
                            <p v-if="authStore.user?.telefono">📞 {{ authStore.user.telefono }}</p>
                            <p v-if="authStore.user?.direccion">📍 {{ authStore.user.direccion }}</p>
                        </div>
                    </div>

                    <!-- BOTONES DE ACCION -->
                    <div class="space-y-3">
                        <button
                            type="submit"
                            :disabled="cargando"
                            class="w-full py-3.5 bg-sky-700 text-white font-bold rounded-xl hover:bg-sky-800 shadow-lg shadow-sky-700/30 disabled:opacity-50 disabled:cursor-not-allowed transition"
                        >
                            {{ cargando ? 'Enviando reporte...' : 'Enviar Reporte' }}
                        </button>
                        <router-link
                            to="/ciudadano/reportes"
                            class="block w-full py-3 bg-white border-2 border-slate-200 text-slate-700 font-semibold rounded-xl hover:bg-slate-50 text-center transition"
                        >
                            Cancelar
                        </router-link>
                    </div>

                </div>

            </form>
        </main>
    </div>
</template>

<script setup>
/**
 * Vista del Ciudadano: Crear Reporte
 *
 * Formulario completo para que el ciudadano cree un nuevo reporte.
 * Permite ingresar titulo, descripcion, seleccionar categoria sugerida,
 * indicar la ubicacion (direccion y coordenadas geograficas) y enviar
 * el reporte a la API del backend.
 *
 * Incluye funcionalidad de geolocalizacion del navegador para obtener
 * automaticamente las coordenadas del usuario.
 *
 */

import { ref, reactive, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import api from '@/services/api';
import NavbarCiudadano from '@/components/NavbarCiudadano.vue';

// Hooks de Vue Router y Pinia
const router = useRouter();
const authStore = useAuthStore();

// Estado reactivo del formulario
const formulario = reactive({
    titulo: '',
    descripcion: '',
    categoria_id: null,
    direccion: '',
    latitud: null,
    longitud: null,
});

// Imagenes seleccionadas (archivos) y sus vistas previas
const imagenesArchivos = ref([]);
const imagenesPreview = ref([]);

// Listado de categorias disponibles
const categorias = ref([]);

// Estados auxiliares
const cargando = ref(false);
const cargandoCategorias = ref(false);
const obteniendoUbicacion = ref(false);
const mensajeExito = ref('');
const errorGeneral = ref('');
const errores = ref({});

/**
 * Iniciales del usuario autenticado para mostrar en el avatar.
 */
const iniciales = computed(() => {
    if (!authStore.user) return '?';
    const inicialNombre = authStore.user.name?.charAt(0) || '';
    const inicialApellido = authStore.user.apellido?.charAt(0) || '';
    return (inicialNombre + inicialApellido).toUpperCase();
});

/**
 * Carga el listado de categorias disponibles desde la API.
 * Se ejecuta al montar el componente.
 */
const cargarCategorias = async () => {
    cargandoCategorias.value = true;
    try {
        const response = await api.get('/categorias');
        categorias.value = response.data.data || [];
    } catch (error) {
        console.error('Error al cargar categorias:', error);
    } finally {
        cargandoCategorias.value = false;
    }
};

/**
 * Obtiene la ubicacion actual del usuario usando la API de geolocalizacion del navegador.
 * Solicita permiso al usuario y rellena automaticamente los campos de latitud y longitud.
 */
const obtenerUbicacionActual = () => {
    // Verificar si el navegador soporta geolocalizacion
    if (!navigator.geolocation) {
        errorGeneral.value = 'Tu navegador no soporta la geolocalizacion.';
        return;
    }

    obteniendoUbicacion.value = true;
    errorGeneral.value = '';

    navigator.geolocation.getCurrentPosition(
        // Exito: rellenar las coordenadas
        (posicion) => {
            formulario.latitud = Number(posicion.coords.latitude.toFixed(8));
            formulario.longitud = Number(posicion.coords.longitude.toFixed(8));
            obteniendoUbicacion.value = false;
        },
        // Error: mostrar mensaje
        (error) => {
            obteniendoUbicacion.value = false;
            const mensajes = {
                1: 'Permiso denegado. Por favor habilita la ubicacion en tu navegador.',
                2: 'No se pudo determinar tu ubicacion.',
                3: 'Tiempo de espera agotado. Intenta de nuevo.',
            };
            errorGeneral.value = mensajes[error.code] || 'Error al obtener la ubicacion.';
        },
        // Opciones de la peticion
        {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 0,
        }
    );
};

/**
 * Maneja la seleccion de imagenes desde el input de archivos.
 * Genera una vista previa de cada imagen y las almacena para el envio.
 *
 * @param {Event} evento - Evento change del input file
 */
const seleccionarImagenes = (evento) => {
    const archivos = Array.from(evento.target.files);

    // Validar que no se excedan 4 imagenes en total
    if (imagenesArchivos.value.length + archivos.length > 4) {
        errorGeneral.value = 'Solo puedes adjuntar un maximo de 4 imagenes.';
        return;
    }

    archivos.forEach((archivo) => {
        // Guardar el archivo para el envio
        imagenesArchivos.value.push(archivo);

        // Generar vista previa con FileReader
        const lector = new FileReader();
        lector.onload = (e) => {
            imagenesPreview.value.push({ preview: e.target.result });
        };
        lector.readAsDataURL(archivo);
    });

    // Limpiar el input para permitir volver a seleccionar los mismos archivos
    evento.target.value = '';
};

/**
 * Quita una imagen de la lista de seleccionadas.
 *
 * @param {number} index - Indice de la imagen a quitar
 */
const quitarImagen = (index) => {
    imagenesArchivos.value.splice(index, 1);
    imagenesPreview.value.splice(index, 1);
};

/**
 * Envia el formulario de reporte a la API del backend.
 * Usa FormData para poder incluir los archivos de imagen.
 * Si es exitoso, muestra un mensaje y redirige a "Mis Reportes".
 */
const enviarReporte = async () => {
    // Resetear mensajes
    errorGeneral.value = '';
    mensajeExito.value = '';
    errores.value = {};
    cargando.value = true;

    try {
        // Construir FormData para enviar datos + archivos
        const datos = new FormData();
        datos.append('titulo', formulario.titulo);
        datos.append('descripcion', formulario.descripcion);
        datos.append('latitud', formulario.latitud);
        datos.append('longitud', formulario.longitud);
        if (formulario.direccion) {
            datos.append('direccion', formulario.direccion);
        }
        if (formulario.categoria_id) {
            datos.append('categoria_id', formulario.categoria_id);
        }
        // Adjuntar cada imagen seleccionada
        imagenesArchivos.value.forEach((archivo) => {
            datos.append('imagenes[]', archivo);
        });

        // Enviar peticion POST con cabecera multipart
        const response = await api.post('/reportes', datos, {
            headers: { 'Content-Type': 'multipart/form-data' },
        });

        mensajeExito.value = response.data.message || 'Reporte creado exitosamente.';

        // Esperar 2 segundos y redirigir a Mis Reportes
        setTimeout(() => {
            router.push({ name: 'ciudadano.reportes' });
        }, 2000);

    } catch (error) {
        if (error.response) {
            if (error.response.status === 422) {
                errores.value = error.response.data.errors || {};
                errorGeneral.value = 'Por favor corrige los errores en el formulario.';
            } else {
                errorGeneral.value = error.response.data.message || 'Ocurrio un error al crear el reporte.';
            }
        } else {
            errorGeneral.value = 'No se pudo conectar con el servidor. Verifica tu conexion.';
        }
    } finally {
        cargando.value = false;
    }
};

/**
 * Hook de Vue: se ejecuta al montar el componente.
 * Carga el listado de categorias disponibles.
 */
onMounted(() => {
    cargarCategorias();
});
</script>
