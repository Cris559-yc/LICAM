<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Archivo de configuracion principal de la aplicacion Laravel.
 *
 * Define el comportamiento de:
 * - Enrutamiento (rutas web y API)
 * - Middleware global y de grupos
 * - Manejo de excepciones (respuestas personalizadas para errores)
 *
 * @project LICAM - Linea Ciudadana de Atencion Municipal
 */
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Configuracion de middleware personalizada (vacia por ahora)
    })
    ->withExceptions(function (Exceptions $exceptions) {

        /**
         * Manejo de excepciones de autenticacion para rutas API.
         * Cuando un usuario intenta acceder a una ruta protegida sin autenticarse
         * o con un token invalido, se retorna 401 Unauthorized en formato JSON.
         */
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No autenticado. Debe iniciar sesion o proporcionar un token valido.',
                ], 401);
            }
        });

        /**
         * Manejo de errores 404 para rutas API.
         * Cuando se accede a una ruta API que no existe.
         */
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Recurso no encontrado.',
                ], 404);
            }
        });

        /**
         * Manejo de errores de validacion para rutas API.
         * Cuando los datos enviados no cumplen las reglas de validacion.
         */
        $exceptions->render(function (ValidationException $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validacion.',
                    'errors'  => $e->errors(),
                ], 422);
            }
        });

    })->create();
