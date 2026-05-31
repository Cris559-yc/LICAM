<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoriaController;
use App\Http\Controllers\Api\ReporteController;
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

Route::post('/registro', [AuthController::class, 'registro']);
Route::post('/login', [AuthController::class, 'login']);

// ============================================================
// RUTAS PROTEGIDAS - REQUIEREN AUTENTICACION CON SANCTUM
// ============================================================

Route::middleware('auth:sanctum')->group(function () {

    // Rutas relacionadas a la sesion del usuario
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/usuario', [AuthController::class, 'usuario']);

    // CRUD de Categorias
    Route::apiResource('categorias', CategoriaController::class);

    // CRUD de Reportes (corazon del sistema)
    Route::apiResource('reportes', ReporteController::class);
});
