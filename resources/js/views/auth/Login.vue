<template>
    <!--
        Vista: Login
        Pantalla de inicio de sesion del sistema LICAM.
        Diseño dividido: lado izquierdo con branding institucional,
        lado derecho con el formulario de autenticacion.
    -->
    <div class="min-h-screen grid lg:grid-cols-2">

        <!-- LADO IZQUIERDO: Branding institucional -->
        <div class="hidden lg:flex bg-gradient-to-br from-sky-700 via-sky-800 to-sky-900 text-white p-12 flex-col justify-between relative overflow-hidden">

            <!-- Efectos decorativos de fondo -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-20 right-20 w-72 h-72 rounded-full bg-white blur-3xl"></div>
                <div class="absolute bottom-20 left-10 w-96 h-96 rounded-full bg-amber-400 blur-3xl"></div>
            </div>

            <!-- Logo y nombre del sistema -->
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

                <!-- Mensaje de bienvenida -->
                <div class="mt-20">
                    <span class="inline-block px-4 py-1 bg-amber-400 text-slate-900 rounded-full text-xs font-bold mb-4">
                        BIENVENIDO
                    </span>
                    <h2 class="text-4xl font-extrabold mb-4 leading-tight">
                        Tu voz construye la ciudad
                    </h2>
                    <p class="text-sky-100 text-lg leading-relaxed">
                        Accede a tu cuenta para reportar problemas, dar seguimiento a tus solicitudes
                        y formar parte del cambio en San Jorge.
                    </p>
                </div>
            </div>

            <!-- Footer de copyright -->
            <div class="relative z-10 text-sm text-sky-200">
                © 2026 Alcaldia Municipal de San Jorge
            </div>
        </div>

        <!-- LADO DERECHO: Formulario de login -->
        <div class="flex items-center justify-center p-8 lg:p-16 bg-slate-50">
            <div class="w-full max-w-md">

                <router-link
                    to="/"
                    class="inline-flex items-center gap-2 text-slate-500 hover:text-sky-700 font-semibold text-sm mb-6 transition"
                >
                    <span>←</span> Inicio
                </router-link>

                <!-- Logo movil  -->
                <div class="lg:hidden flex items-center gap-3 mb-8">
                    <div class="w-12 h-12 bg-sky-700 rounded-full flex items-center justify-center text-white font-bold">
                        SJ
                    </div>
                    <span class="font-bold text-slate-800">Alcaldia de San Jorge</span>
                </div>

                <h2 class="text-3xl font-extrabold text-slate-800 mb-2">Iniciar Sesion</h2>
                <p class="text-slate-500 mb-8">Ingresa tus credenciales para acceder a tu cuenta</p>

                <!-- Mensaje de error general (si existe) -->
                <div v-if="errorGeneral" class="mb-4 p-4 bg-red-50 border-2 border-red-200 rounded-xl text-red-700 text-sm">
                    {{ errorGeneral }}
                </div>

                <!-- Formulario de inicio de sesion -->
                <form @submit.prevent="iniciarSesion" class="space-y-5">

                    <!-- Campo de correo electronico -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Correo electronico
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

                    <!-- Campo de contraseña -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Contraseña
                        </label>
                        <input
                            v-model="formulario.password"
                            type="password"
                            placeholder="••••••••"
                            :disabled="cargando"
                            class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-sky-600 focus:outline-none disabled:bg-slate-100"
                        >
                        <p v-if="errores.password" class="text-red-600 text-xs mt-1">{{ errores.password[0] }}</p>
                    </div>

                    <!-- Boton de envio -->
                    <button
                        type="submit"
                        :disabled="cargando"
                        class="w-full py-3.5 bg-sky-700 text-white font-bold rounded-xl hover:bg-sky-800 shadow-lg shadow-sky-700/30 disabled:opacity-50 disabled:cursor-not-allowed transition"
                    >
                        {{ cargando ? 'Ingresando...' : 'Ingresar' }}
                    </button>

                    <!-- Separador -->
                    <div class="relative my-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-slate-200"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-4 bg-slate-50 text-slate-500">o</span>
                        </div>
                    </div>

                    <!-- Enlace al registro -->
                    <p class="text-center text-slate-600 text-sm">
                        ¿Aun no tienes cuenta?
                        <router-link to="/registro" class="text-sky-700 font-bold hover:underline">
                            Registrarse
                        </router-link>
                    </p>
                </form>

            </div>
        </div>
    </div>
</template>

<script setup>
/**
 * Vista de Inicio de Sesion del sistema LICAM.
 *
 * Permite a los usuarios (ciudadanos y administradores) iniciar sesion
 * mediante correo electronico y contraseña. Tras la autenticacion exitosa,
 * redirige al usuario a su panel correspondiente segun su rol.
 *
 * @project LICAM - Linea Ciudadana de Atencion Municipal
 */

import { ref, reactive } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';

// Hooks de Vue Router y Pinia
const router = useRouter();
const authStore = useAuthStore();

// Estado reactivo del formulario
const formulario = reactive({
    email: '',
    password: '',
});

// Estados auxiliares
const cargando = ref(false);
const errorGeneral = ref('');
const errores = ref({});

/**
 * Procesa el inicio de sesion del usuario.
 * Valida las credenciales, guarda el token y redirige al panel correspondiente.
 */
const iniciarSesion = async () => {
    // Resetear errores antes de enviar
    errorGeneral.value = '';
    errores.value = {};
    cargando.value = true;

    try {
        // Llamar al action del store que se conecta con la API
        await authStore.login({
            email: formulario.email,
            password: formulario.password,
        });

        // Redirigir segun el rol del usuario autenticado
        if (authStore.esAdministrador) {
            router.push({ name: 'admin.dashboard' });
        } else {
            router.push({ name: 'ciudadano.reportes' });
        }

    } catch (error) {
        // Manejar errores de la API
        if (error.response) {
            if (error.response.status === 422) {
                // Errores de validacion
                errores.value = error.response.data.errors || {};
            } else if (error.response.status === 401) {
                // Credenciales incorrectas
                errorGeneral.value = error.response.data.message || 'Las credenciales son incorrectas.';
            } else if (error.response.status === 403) {
                // Cuenta deshabilitada
                errorGeneral.value = error.response.data.message;
            } else {
                errorGeneral.value = 'Ocurrio un error inesperado. Intenta de nuevo.';
            }
        } else {
            errorGeneral.value = 'No se pudo conectar con el servidor. Verifica tu conexion.';
        }
    } finally {
        cargando.value = false;
    }
};
</script>
