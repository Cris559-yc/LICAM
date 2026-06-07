<template>
    <!--
        Vista: Registro de Ciudadanos
        Permite a los nuevos usuarios crear una cuenta en el sistema LICAM.
        Por defecto, todos los registros se crean con el rol "ciudadano".
    -->
    <div class="min-h-screen grid lg:grid-cols-2">

        <!-- LADO IZQUIERDO: Branding institucional -->
        <div class="hidden lg:flex bg-gradient-to-br from-sky-700 via-sky-800 to-sky-900 text-white p-12 flex-col justify-between relative overflow-hidden">

            <!-- Efectos decorativos de fondo -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-20 right-20 w-72 h-72 rounded-full bg-white blur-3xl"></div>
                <div class="absolute bottom-20 left-10 w-96 h-96 rounded-full bg-amber-400 blur-3xl"></div>
            </div>

            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-12">
                    <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center text-sky-700 font-bold text-2xl">
                        SJ
                    </div>
                    <div>
                        <h1 class="font-bold text-xl">Alcaldia de San Jorge</h1>
                        <p class="text-sky-200 text-sm">Portal de Reportes Ciudadanos</p>
                    </div>
                </div>

                <div class="mt-20">
                    <span class="inline-block px-4 py-1 bg-amber-400 text-slate-900 rounded-full text-xs font-bold mb-4">
                        REGISTRO CIUDADANO
                    </span>
                    <h2 class="text-4xl font-extrabold mb-4 leading-tight">
                        Unete al cambio en San Jorge
                    </h2>
                    <p class="text-sky-100 text-lg leading-relaxed">
                        Crea tu cuenta gratuita y comienza a reportar problemas en tu comunidad.
                        Tu participacion ayuda a mejorar la ciudad.
                    </p>
                </div>
            </div>

            <div class="relative z-10 text-sm text-sky-200">
                © 2026 Alcaldia Municipal de San Jorge
            </div>
        </div>

        <!-- LADO DERECHO: Formulario de registro -->
        <div class="flex items-center justify-center p-8 lg:p-16 bg-slate-50">
            <div class="w-full max-w-md py-8">

                <router-link
                    to="/"
                    class="inline-flex items-center gap-2 text-slate-500 hover:text-sky-700 font-semibold text-sm mb-6 transition"
                >
                    <span>←</span> Inicio
                </router-link>

                <!-- Logo movil -->
                <div class="lg:hidden flex items-center gap-3 mb-8">
                    <div class="w-12 h-12 bg-sky-700 rounded-full flex items-center justify-center text-white font-bold">
                        SJ
                    </div>
                    <span class="font-bold text-slate-800">Alcaldia de San Jorge</span>
                </div>

                <h2 class="text-3xl font-extrabold text-slate-800 mb-2">Crear Cuenta</h2>
                <p class="text-slate-500 mb-8">Completa el formulario para registrarte como ciudadano</p>

                <!-- Mensaje de error general -->
                <div v-if="errorGeneral" class="mb-4 p-4 bg-red-50 border-2 border-red-200 rounded-xl text-red-700 text-sm">
                    {{ errorGeneral }}
                </div>

                <!-- Mensaje de exito -->
                <div v-if="mensajeExito" class="mb-4 p-4 bg-emerald-50 border-2 border-emerald-200 rounded-xl text-emerald-700 text-sm">
                    {{ mensajeExito }}
                </div>

                <!-- Formulario de registro -->
                <form @submit.prevent="registrarUsuario" class="space-y-4">

                    <!-- Nombre y Apellido en la misma fila -->
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Nombre *
                            </label>
                            <input
                                v-model="formulario.name"
                                type="text"
                                placeholder="Carlos"
                                :disabled="cargando"
                                class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-sky-600 focus:outline-none disabled:bg-slate-100"
                            >
                            <p v-if="errores.name" class="text-red-600 text-xs mt-1">{{ errores.name[0] }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Apellido *
                            </label>
                            <input
                                v-model="formulario.apellido"
                                type="text"
                                placeholder="Mendoza"
                                :disabled="cargando"
                                class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-sky-600 focus:outline-none disabled:bg-slate-100"
                            >
                            <p v-if="errores.apellido" class="text-red-600 text-xs mt-1">{{ errores.apellido[0] }}</p>
                        </div>
                    </div>

                    <!-- DUI -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            DUI (opcional)
                        </label>
                        <input
                            v-model="formulario.dui"
                            type="text"
                            placeholder="00000000-0"
                            maxlength="10"
                            :disabled="cargando"
                            class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-sky-600 focus:outline-none disabled:bg-slate-100"
                        >
                        <p v-if="errores.dui" class="text-red-600 text-xs mt-1">{{ errores.dui[0] }}</p>
                    </div>

                    <!-- Correo electronico -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Correo electronico *
                        </label>
                        <input
                            v-model="formulario.email"
                            type="email"
                            placeholder="usuario@correo.com"
                            :disabled="cargando"
                            class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-sky-600 focus:outline-none disabled:bg-slate-100"
                        >
                        <p v-if="errores.email" class="text-red-600 text-xs mt-1">{{ errores.email[0] }}</p>
                    </div>

                    <!-- Telefono -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Telefono (opcional)
                        </label>
                        <input
                            v-model="formulario.telefono"
                            type="text"
                            placeholder="7777-8888"
                            maxlength="15"
                            :disabled="cargando"
                            class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-sky-600 focus:outline-none disabled:bg-slate-100"
                        >
                        <p v-if="errores.telefono" class="text-red-600 text-xs mt-1">{{ errores.telefono[0] }}</p>
                    </div>

                    <!-- Direccion -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Direccion (opcional)
                        </label>
                        <input
                            v-model="formulario.direccion"
                            type="text"
                            placeholder="Barrio El Centro, San Jorge"
                            :disabled="cargando"
                            class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-sky-600 focus:outline-none disabled:bg-slate-100"
                        >
                        <p v-if="errores.direccion" class="text-red-600 text-xs mt-1">{{ errores.direccion[0] }}</p>
                    </div>

                    <!-- Contraseña -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Contraseña *
                        </label>
                        <input
                            v-model="formulario.password"
                            type="password"
                            placeholder="Minimo 8 caracteres"
                            :disabled="cargando"
                            class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-sky-600 focus:outline-none disabled:bg-slate-100"
                        >
                        <p v-if="errores.password" class="text-red-600 text-xs mt-1">{{ errores.password[0] }}</p>
                    </div>

                    <!-- Confirmar contraseña -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Confirmar contraseña *
                        </label>
                        <input
                            v-model="formulario.password_confirmation"
                            type="password"
                            placeholder="Repite la contraseña"
                            :disabled="cargando"
                            class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-sky-600 focus:outline-none disabled:bg-slate-100"
                        >
                    </div>

                    <!-- Boton de registro -->
                    <button
                        type="submit"
                        :disabled="cargando"
                        class="w-full py-3.5 bg-sky-700 text-white font-bold rounded-xl hover:bg-sky-800 shadow-lg shadow-sky-700/30 disabled:opacity-50 disabled:cursor-not-allowed transition mt-6"
                    >
                        {{ cargando ? 'Registrando...' : 'Crear Cuenta' }}
                    </button>

                    <!-- Enlace al login -->
                    <p class="text-center text-slate-600 text-sm pt-4">
                        ¿Ya tienes cuenta?
                        <router-link to="/login" class="text-sky-700 font-bold hover:underline">
                            Iniciar sesion
                        </router-link>
                    </p>
                </form>

            </div>
        </div>
    </div>
</template>

<script setup>
/**
 * Vista de Registro de Ciudadanos del sistema LICAM.
 *
 * Permite a los nuevos usuarios crear una cuenta en el sistema.
 * Tras el registro exitoso, el usuario queda automaticamente logueado
 * y es redirigido a su panel de ciudadano.
 *
 * @project LICAM - Linea Ciudadana de Atencion Municipal
 */

import { ref, reactive } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';

const router = useRouter();
const authStore = useAuthStore();

// Estado reactivo del formulario
const formulario = reactive({
    name: '',
    apellido: '',
    dui: '',
    email: '',
    telefono: '',
    direccion: '',
    password: '',
    password_confirmation: '',
});

// Estados auxiliares
const cargando = ref(false);
const errorGeneral = ref('');
const mensajeExito = ref('');
const errores = ref({});

/**
 * Procesa el registro de un nuevo ciudadano.
 * Envia los datos a la API y, si es exitoso, redirige al panel del ciudadano.
 */
const registrarUsuario = async () => {
    // Resetear mensajes y errores
    errorGeneral.value = '';
    mensajeExito.value = '';
    errores.value = {};
    cargando.value = true;

    try {
        // Llamar al action del store que se conecta con la API
        await authStore.registro(formulario);

        // Mostrar mensaje de exito
        mensajeExito.value = 'Cuenta creada exitosamente. Redirigiendo...';

        // Redirigir al panel del ciudadano despues de un breve momento
        setTimeout(() => {
            router.push({ name: 'ciudadano.reportes' });
        }, 1500);

    } catch (error) {
        // Manejar errores de la API
        if (error.response) {
            if (error.response.status === 422) {
                errores.value = error.response.data.errors || {};
                errorGeneral.value = 'Por favor corrige los errores en el formulario.';
            } else {
                errorGeneral.value = error.response.data.message || 'Ocurrio un error inesperado.';
            }
        } else {
            errorGeneral.value = 'No se pudo conectar con el servidor.';
        }
    } finally {
        cargando.value = false;
    }
};
</script>
