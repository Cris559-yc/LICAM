<template>
    <!--
        Vista: Crear Reporte (Ciudadano)
        Formulario para crear un nuevo reporte ciudadano.
        Version temporal con el Navbar. El formulario real se implementa despues.
    -->
    <div class="min-h-screen bg-slate-50">

        <!-- Barra de navegacion del ciudadano -->
        <NavbarCiudadano />

        <!-- Contenido principal -->
        <main class="max-w-7xl mx-auto px-6 py-8">
            <h2 class="text-3xl font-extrabold text-slate-800 mb-2">Nuevo Reporte Ciudadano</h2>
            <p class="text-slate-600 mb-8">Completa los siguientes campos para reportar el problema a la alcaldia</p>

            <div class="bg-white rounded-2xl p-8 shadow-sm text-center">
                <div class="text-6xl mb-4">📝</div>
                <h3 class="text-xl font-bold text-slate-800 mb-2">Formulario de reporte</h3>
                <p class="text-slate-500">El formulario se implementara en el siguiente paso.</p>
            </div>
        </main>
    </div>
</template>

<script setup>
/**
 * Vista del Ciudadano: Crear Reporte
 *
 * Formulario para que el ciudadano cree un nuevo reporte con titulo,
 * descripcion, ubicacion (coordenadas) e imagenes de evidencia.
 *
 * @project LICAM - Linea Ciudadana de Atencion Municipal
 */

import NavbarCiudadano from '@/components/NavbarCiudadano.vue';
</script>
