/**
 * Store de autenticacion con Pinia.
 *
 * Maneja el estado global del usuario autenticado:
 * - Iniciar sesion
 * - Cerrar sesion
 * - Registrar nuevo ciudadano
 * - Obtener datos del usuario actual
 * - Verificar si el usuario es administrador o ciudadano
 *
 */

import { defineStore } from 'pinia';
import api from '@/services/api';

export const useAuthStore = defineStore('auth', {
    /**
     * Estado inicial del store.
     */
    state: () => ({
        user: JSON.parse(localStorage.getItem('user')) || null,
        token: localStorage.getItem('token') || null,
    }),

    /**
     * Getters: valores computados a partir del estado.
     */
    getters: {
        /**
         * Indica si el usuario esta autenticado.
         */
        isAuthenticated: (state) => !!state.token && !!state.user,

        /**
         * Indica si el usuario tiene rol de administrador.
         */
        esAdministrador: (state) => state.user?.rol?.nombre === 'administrador',

        /**
         * Indica si el usuario tiene rol de ciudadano.
         */
        esCiudadano: (state) => state.user?.rol?.nombre === 'ciudadano',
    },

    /**
     * Actions: funciones que modifican el estado.
     */
    actions: {
        /**
         * Iniciar sesion con email y contraseña.
         *
         * @param {Object} credenciales - { email, password }
         * @returns {Promise}
         */
        async login(credenciales) {
            const response = await api.post('/login', credenciales);
            this.guardarSesion(response.data.data);
            return response.data;
        },

        /**
         * Registrar un nuevo ciudadano.
         *
         * @param {Object} datos - Datos del nuevo usuario
         * @returns {Promise}
         */
        async registro(datos) {
            const response = await api.post('/registro', datos);
            this.guardarSesion(response.data.data);
            return response.data;
        },

        /**
         * Cerrar la sesion actual del usuario.
         */
        async logout() {
            try {
                await api.post('/logout');
            } catch (error) {
                console.error('Error al cerrar sesion:', error);
            } finally {
                this.limpiarSesion();
            }
        },

        /**
         * Guardar los datos de sesion en el estado y localStorage.
         *
         * @param {Object} data - { user, token }
         */
        guardarSesion(data) {
            this.user = data.user;
            this.token = data.token;
            localStorage.setItem('user', JSON.stringify(data.user));
            localStorage.setItem('token', data.token);
        },

        /**
         * Limpiar los datos de sesion del estado y localStorage.
         */
        limpiarSesion() {
            this.user = null;
            this.token = null;
            localStorage.removeItem('user');
            localStorage.removeItem('token');
        },
    },
});
