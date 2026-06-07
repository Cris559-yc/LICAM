<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoriaController;
use App\Http\Controllers\Api\ReporteController;
use App\Http\Controllers\Api\ComentarioController;
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
 */
Route::post('/registro', [AuthController::class, 'registro']);
Route::post('/login', [AuthController::class, 'login']);

/**
 * Endpoint publico de categorias (solo lectura).
 * Se expone para que la pagina de inicio pueda mostrarlas sin autenticacion.
 */
Route::get('/categorias', [CategoriaController::class, 'index']);

// ============================================================
// RUTAS PROTEGIDAS - REQUIEREN AUTENTICACION CON SANCTUM
// ============================================================

Route::middleware('auth:sanctum')->group(function () {

    // Rutas relacionadas a la sesion del usuario
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/usuario', [AuthController::class, 'usuario']);

    // CRUD de Categorias (crear, modificar, eliminar requieren autenticacion)
    Route::post('/categorias', [CategoriaController::class, 'store']);
    Route::get('/categorias/{id}', [CategoriaController::class, 'show']);
    Route::put('/categorias/{id}', [CategoriaController::class, 'update']);
    Route::delete('/categorias/{id}', [CategoriaController::class, 'destroy']);

    // CRUD de Reportes (corazon del sistema)
    Route::apiResource('reportes', ReporteController::class);

    // Comentarios de reporte (listar y crear)
    Route::get('reportes/{reporteId}/comentarios', [ComentarioController::class, 'index']);
    Route::post('reportes/{reporteId}/comentarios', [ComentarioController::class, 'store']);
});
