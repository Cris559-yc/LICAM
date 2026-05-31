/**
 * Configuracion del router de Vue para el sistema LICAM.
 *
 * Define todas las rutas del frontend y los guards de navegacion
 * que controlan el acceso segun el rol del usuario.
 *
 * @project LICAM - Linea Ciudadana de Atencion Municipal
 */

import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '@/stores/auth';

/**
 * Definicion de las rutas del sistema.
 * Cada ruta especifica el componente a renderizar y los meta datos
 * para control de acceso.
 */
const routes = [
    // ============================================================
    // RUTAS PUBLICAS
    // ============================================================
    {
        path: '/',
        name: 'inicio',
        component: () => import('@/views/Inicio.vue'),
    },
    {
        path: '/login',
        name: 'login',
        component: () => import('@/views/auth/Login.vue'),
        meta: { requiresGuest: true },
    },
    {
        path: '/registro',
        name: 'registro',
        component: () => import('@/views/auth/Registro.vue'),
        meta: { requiresGuest: true },
    },

    // ============================================================
    // RUTAS DEL CIUDADANO
    // ============================================================
    {
        path: '/ciudadano/reportes',
        name: 'ciudadano.reportes',
        component: () => import('@/views/ciudadano/MisReportes.vue'),
        meta: { requiresAuth: true, rol: 'ciudadano' },
    },
    {
        path: '/ciudadano/crear-reporte',
        name: 'ciudadano.crearReporte',
        component: () => import('@/views/ciudadano/CrearReporte.vue'),
        meta: { requiresAuth: true, rol: 'ciudadano' },
    },

    // ============================================================
    // RUTAS DEL ADMINISTRADOR
    // ============================================================
    {
        path: '/admin/dashboard',
        name: 'admin.dashboard',
        component: () => import('@/views/admin/Dashboard.vue'),
        meta: { requiresAuth: true, rol: 'administrador' },
    },
    {
        path: '/admin/reportes',
        name: 'admin.reportes',
        component: () => import('@/views/admin/ListaReportes.vue'),
        meta: { requiresAuth: true, rol: 'administrador' },
    },
];

/**
 * Crear instancia del router.
 */
const router = createRouter({
    history: createWebHistory(),
    routes,
});

/**
 * Guard global de navegacion.
 * Se ejecuta antes de cada cambio de ruta para verificar permisos.
 */
router.beforeEach((to, from, next) => {
    const authStore = useAuthStore();

    // Si la ruta requiere autenticacion y el usuario no esta logueado
    if (to.meta.requiresAuth && !authStore.isAuthenticated) {
        return next({ name: 'login' });
    }

    // Si la ruta es para invitados (login, registro) y el usuario ya esta logueado
    if (to.meta.requiresGuest && authStore.isAuthenticated) {
        // Redirigir segun el rol del usuario
        if (authStore.esAdministrador) {
            return next({ name: 'admin.dashboard' });
        }
        return next({ name: 'ciudadano.reportes' });
    }

    // Si la ruta requiere un rol especifico
    if (to.meta.rol && authStore.user?.rol?.nombre !== to.meta.rol) {
        return next({ name: 'inicio' });
    }

    next();
});

export default router;
