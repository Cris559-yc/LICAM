<?php

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
| Ejemplo: Route::get('/categorias') = http://licam.test/api/categorias
|
| @project LICAM - Linea Ciudadana de Atencion Municipal
*/

// ============================================================
// RUTAS PUBLICAS (no requieren autenticacion por ahora)
// ============================================================

/**
 * Rutas para la gestion de Categorias.
 *
 * Route::apiResource genera automaticamente las 5 rutas REST:
 * - GET    /categorias          -> index()
 * - POST   /categorias          -> store()
 * - GET    /categorias/{id}     -> show()
 * - PUT    /categorias/{id}     -> update()
 * - DELETE /categorias/{id}     -> destroy()
 */
Route::apiResource('categorias', CategoriaController::class);

// ============================================================
// RUTA DE PRUEBA: verificar que la API funciona
// ============================================================

/**
 * Ruta de prueba para confirmar que la API esta operativa.
 * Acceder a: http://licam.test/api/test
 */
Route::get('/test', function () {
    return response()->json([
        'success' => true,
        'message' => 'API de LICAM funcionando correctamente.',
        'fecha'   => now()->toDateTimeString(),
    ]);
});
