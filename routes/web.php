<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rutas Web del Sistema LICAM
|--------------------------------------------------------------------------
|
| Laravel solo necesita servir la vista principal de la aplicacion (SPA).
| Vue Router se encarga de manejar todas las rutas del lado del cliente.
|
| @project LICAM - Linea Ciudadana de Atencion Municipal
*/

/**
 * Ruta catch-all: cualquier URL que no sea /api/* devuelve la vista principal.
 * Esto permite que Vue Router maneje las rutas del frontend.
 */
Route::get('/{any?}', function () {
    return view('welcome');
})->where('any', '^(?!api).*$');
