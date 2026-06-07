<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comentario;
use App\Models\Reporte;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Controlador: ComentarioController
 *
 * Gestiona los comentarios asociados a los reportes ciudadanos.
 * Permite la comunicacion entre el ciudadano que creo el reporte
 * y los administradores que lo atienden.
 *
 * Endpoints disponibles:
 * - GET  /api/reportes/{reporteId}/comentarios   Listar comentarios de un reporte
 * - POST /api/reportes/{reporteId}/comentarios   Crear un comentario en un reporte
 *
 */
class ComentarioController extends Controller
{
    /**
     * Listar todos los comentarios de un reporte especifico.
     *
     * GET /api/reportes/{reporteId}/comentarios
     *
     * @param int $reporteId
     * @return JsonResponse
     */
    public function index(int $reporteId): JsonResponse
    {
        // Verificar que el reporte exista
        $reporte = Reporte::find($reporteId);

        if (!$reporte) {
            return response()->json([
                'success' => false,
                'message' => 'Reporte no encontrado.',
            ], 404);
        }

        // Obtener los comentarios del reporte ordenados del mas antiguo al mas reciente,
        // incluyendo los datos del usuario que escribio cada comentario
        $comentarios = $reporte->comentarios()
            ->with('usuario:id,name,apellido,rol_id')
            ->with('usuario.rol:id,nombre')
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Comentarios obtenidos correctamente.',
            'data'    => $comentarios,
        ], 200);
    }

    /**
     * Crear un nuevo comentario en un reporte.
     *
     * POST /api/reportes/{reporteId}/comentarios
     *
     * El user_id se obtiene del usuario autenticado para garantizar
     * que el comentario quede registrado a nombre del usuario real.
     *
     * @param Request $request
     * @param int $reporteId
     * @return JsonResponse
     */
    public function store(Request $request, int $reporteId): JsonResponse
    {
        // Verificar que el reporte exista
        $reporte = Reporte::find($reporteId);

        if (!$reporte) {
            return response()->json([
                'success' => false,
                'message' => 'Reporte no encontrado.',
            ], 404);
        }

        // Validar el contenido del comentario
        $validator = Validator::make($request->all(), [
            'contenido' => 'required|string|max:1000',
        ], [
            'contenido.required' => 'El comentario no puede estar vacio.',
            'contenido.max'      => 'El comentario no puede exceder los 1000 caracteres.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validacion.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        // Crear el comentario asociado al reporte y al usuario autenticado
        $comentario = Comentario::create([
            'reporte_id' => $reporteId,
            'user_id'    => $request->user()->id,
            'contenido'  => $request->contenido,
        ]);

        // Cargar los datos del usuario para incluirlos en la respuesta
        $comentario->load('usuario:id,name,apellido,rol_id', 'usuario.rol:id,nombre');

        return response()->json([
            'success' => true,
            'message' => 'Comentario publicado correctamente.',
            'data'    => $comentario,
        ], 201);
    }
}
