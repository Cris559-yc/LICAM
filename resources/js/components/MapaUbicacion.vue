<template>
    <!--
        Componente: Mapa de Ubicacion
        Mapa interactivo basado en Leaflet + OpenStreetMap.
        Permite dos modos:
        - Modo seleccion: el usuario hace clic para marcar una ubicacion
        - Modo solo lectura: muestra una ubicacion fija sin permitir cambios
    -->
    <div>
        <div ref="contenedorMapa" class="w-full h-80 rounded-xl overflow-hidden border-2 border-slate-200 z-0"></div>
        <p v-if="seleccionable" class="text-xs text-slate-500 mt-2">
             Haz clic en el mapa para marcar la ubicacion exacta del problema.
        </p>
    </div>
</template>

<script setup>
/**
 * Componente: MapaUbicacion
 *
 * Encapsula un mapa interactivo de Leaflet para seleccionar o mostrar
 * una ubicacion geografica. Usa OpenStreetMap como proveedor de mapas.
 *
 * Props:
 * - latitud / longitud: coordenadas iniciales del marcador
 * - seleccionable: si es true, permite al usuario hacer clic para mover el marcador
 *
 * Emite:
 * - actualizar-ubicacion: cuando el usuario selecciona una nueva posicion
 *
 */

import { ref, onMounted, watch } from 'vue';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

/**
 * marcador en forma de circulo azul.
 *
 */
const iconoPersonalizado = L.divIcon({
    className: 'marcador-licam',
    html: `
        <div style="
            width: 26px;
            height: 26px;
            background: #0369a1;
            border: 4px solid white;
            border-radius: 50%;
            box-shadow: 0 2px 8px rgba(0,0,0,0.4);
        "></div>
    `,
    iconSize: [26, 26],
    iconAnchor: [13, 13], // El centro del circulo apunta a la coordenada
});

// Props que recibe el componente
const props = defineProps({
    latitud: {
        type: Number,
        default: 13.4775, // Coordenadas aproximadas de San Jorge
    },
    longitud: {
        type: Number,
        default: -88.4525,
    },
    seleccionable: {
        type: Boolean,
        default: false,
    },
});

// Eventos que emite el componente
const emit = defineEmits(['actualizar-ubicacion']);

// Referencias
const contenedorMapa = ref(null);
let mapa = null;
let marcador = null;

/**
 * Inicializa el mapa de Leaflet al montar el componente.
 */
const inicializarMapa = () => {
    // Crear el mapa centrado en las coordenadas iniciales
    mapa = L.map(contenedorMapa.value).setView([props.latitud, props.longitud], 15);

    // Agregar la capa de mapa de OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap',
        maxZoom: 19,
    }).addTo(mapa);

    // Agregar un marcador
    marcador = L.marker([props.latitud, props.longitud], { icon: iconoPersonalizado }).addTo(mapa);

    // Si el mapa es seleccionable, permitir mover el marcador al hacer clic
    if (props.seleccionable) {
        mapa.on('click', (e) => {
            const { lat, lng } = e.latlng;
            // Mover el marcador a la nueva posicion
            marcador.setLatLng([lat, lng]);
            // Emitir las nuevas coordenadas al componente padre
            emit('actualizar-ubicacion', {
                latitud: Number(lat.toFixed(8)),
                longitud: Number(lng.toFixed(8)),
            });
        });
    }
};

/**
 * Observa cambios en las coordenadas (por ejemplo, si el usuario usa
 * el boton "usar mi ubicacion actual") y actualiza el marcador.
 */
watch(() => [props.latitud, props.longitud], ([nuevaLat, nuevaLng]) => {
    if (mapa && marcador) {
        marcador.setLatLng([nuevaLat, nuevaLng]);
        mapa.setView([nuevaLat, nuevaLng], 15);
    }
});

/**
 * Hook de Vue: inicializa el mapa al montar el componente.
 */
onMounted(() => {
    inicializarMapa();
});
</script>
