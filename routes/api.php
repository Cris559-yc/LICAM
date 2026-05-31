<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoriaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rutas API del Sistema LICAM
|--------------------------------------------------------------------------
|
| Aqui se registran todas las rutas API del sistema. Todas estas rutas
| se cargan con el prefijo "/api" automaticamente.
|
| @project LICAM - Linea Ciudadana de Atencion Municipal
*/

// ============================================================
// RUTA DE PRUEBA
// ============================================================

/**
 * Ruta para confirmar que la API esta operativa.
 */
Route::get('/test', function () {
    return response()->json([
        'success' => true,
        'message' => 'API de LICAM funcionando correctamente.',
        'fecha'   => now()->toDateTimeString(),
    ]);
});

// ============================================================
// RUTAS PUBLICAS - NO REQUIEREN AUTENTICACION
// ============================================================

/**
 * Rutas de autenticacion publicas.
 * Cualquier persona puede registrarse o iniciar sesion.
 */
Route::post('/registro', [AuthController::class, 'registro']);
Route::post('/login', [AuthController::class, 'login']);

// ============================================================
// RUTAS PROTEGIDAS - REQUIEREN AUTENTICACION CON SANCTUM
// ============================================================

/**
 * Grupo de rutas que requieren un token valido para acceder.
 * El middleware 'auth:sanctum' verifica el token en cada peticion.
 */
Route::middleware('auth:sanctum')->group(function () {

    // Rutas relacionadas a la sesion del usuario
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/usuario', [AuthController::class, 'usuario']);

    // CRUD de Categorias (solo usuarios autenticados pueden gestionarlas)
    Route::apiResource('categorias', CategoriaController::class);
});
