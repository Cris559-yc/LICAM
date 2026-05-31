/**
 * Servicio de Axios para comunicacion con la API del backend.
 *
 * Configura una instancia de Axios con la URL base del backend
 * y un interceptor que automaticamente agrega el token de autenticacion
 * a todas las peticiones que lo requieran.
 *
 */

import axios from 'axios';

// Crear instancia de Axios con configuracion base
const api = axios.create({
    baseURL: '/api',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    },
});

/**
 * Interceptor de peticiones.
 * Antes de enviar cada peticion, agrega el token de autenticacion si existe.
 */
api.interceptors.request.use(
    (config) => {
        const token = localStorage.getItem('token');
        if (token) {
            config.headers.Authorization = `Bearer ${token}`;
        }
        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

/**
 * Interceptor de respuestas.
 * Maneja errores comunes globalmente (token expirado, no autenticado, etc.).
 */
api.interceptors.response.use(
    (response) => response,
    (error) => {
        // Si el token es invalido o expiro, cerrar sesion automaticamente
        if (error.response && error.response.status === 401) {
            localStorage.removeItem('token');
            localStorage.removeItem('user');
            // Redirigir al login (lo haremos cuando configuremos el router)
            if (window.location.pathname !== '/login') {
                window.location.href = '/login';
            }
        }
        return Promise.reject(error);
    }
);

export default api;
