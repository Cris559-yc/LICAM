<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reporte;
use App\Models\Seguimiento;
use App\Models\Imagen;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

/**
 * Controlador: ReporteController
 *
 * Gestiona las operaciones CRUD para los reportes ciudadanos del sistema LICAM.
 * Es el controlador central del sistema y maneja toda la logica relacionada
 * con la creacion, consulta, clasificacion y resolucion de reportes.
 *
 * Caracteristicas principales:
 * - Los ciudadanos crean reportes (sin categoria, estado inicial: pendiente)
 * - Los administradores asignan categoria, cambian estado y prioridad
 * - Cada cambio de estado se registra automaticamente en la tabla seguimientos
 *
 * Endpoints disponibles:
 * - GET    /api/reportes              Listar reportes (con filtros opcionales)
 * - POST   /api/reportes              Crear un nuevo reporte (ciudadano)
 * - GET    /api/reportes/{id}         Ver un reporte especifico con relaciones
 * - PUT    /api/reportes/{id}         Actualizar reporte (administrador)
 * - DELETE /api/reportes/{id}         Eliminar un reporte
 *
 */
class ReporteController extends Controller
{
    /**
     * Listar reportes del sistema con filtros opcionales.
     *
     * GET /api/reportes
     *
     * Filtros disponibles via query parameters:
     * - ?estado=pendiente       Filtra por estado
     * - ?categoria_id=3         Filtra por categoria
     * - ?prioridad=alta         Filtra por prioridad
     * - ?user_id=5              Filtra por usuario (sus propios reportes)
     *
     * Los resultados incluyen las relaciones con usuario y categoria
     * para evitar consultas adicionales desde el frontend (eager loading).
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        // Iniciar la consulta cargando las relaciones necesarias
        $query = Reporte::with(['usuario:id,name,apellido,email', 'categoria', 'imagenes']);

        // Aplicar filtro por estado si se proporciona
        if ($request->has('estado')) {
            $query->where('estado', $request->estado);
        }

        // Aplicar filtro por categoria si se proporciona
        if ($request->has('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }

        // Aplicar filtro por prioridad si se proporciona
        if ($request->has('prioridad')) {
            $query->where('prioridad', $request->prioridad);
        }

        // Aplicar filtro por usuario (util para "mis reportes")
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Ordenar por fecha de creacion (mas recientes primero)
        $reportes = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'message' => 'Reportes obtenidos correctamente.',
            'total'   => $reportes->count(),
            'data'    => $reportes,
        ], 200);
    }

       /**
     * Crear un nuevo reporte ciudadano con imagenes opcionales.
     *
     * POST /api/reportes
     *
     * Reglas aplicadas:
     * - El user_id se obtiene del usuario autenticado (no se acepta del request)
     * - El estado inicial siempre es "pendiente"
     * - La categoria es opcional (el administrador la asignara despues)
     * - Se pueden adjuntar hasta 4 imagenes de evidencia
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        // Validar los datos enviados desde el frontend
        $validator = Validator::make($request->all(), [
            'titulo'       => 'required|string|max:150',
            'descripcion'  => 'required|string',
            'latitud'      => 'required|numeric|between:-90,90',
            'longitud'     => 'required|numeric|between:-180,180',
            'direccion'    => 'nullable|string|max:255',
            'categoria_id' => 'nullable|exists:categorias,id',
            'imagenes'     => 'nullable|array|max:4',
            'imagenes.*'   => 'image|mimes:jpeg,jpg,png,webp|max:5120', // 5MB por imagen
        ], [
            'titulo.required'      => 'El titulo del reporte es obligatorio.',
            'titulo.max'           => 'El titulo no puede exceder los 150 caracteres.',
            'descripcion.required' => 'La descripcion del problema es obligatoria.',
            'latitud.required'     => 'La latitud de la ubicacion es obligatoria.',
            'latitud.between'      => 'La latitud debe estar entre -90 y 90 grados.',
            'longitud.required'    => 'La longitud de la ubicacion es obligatoria.',
            'longitud.between'     => 'La longitud debe estar entre -180 y 180 grados.',
            'categoria_id.exists'  => 'La categoria seleccionada no existe.',
            'imagenes.max'         => 'Solo puedes adjuntar un maximo de 4 imagenes.',
            'imagenes.*.image'     => 'El archivo debe ser una imagen valida.',
            'imagenes.*.mimes'     => 'Las imagenes deben ser JPG, PNG o WEBP.',
            'imagenes.*.max'       => 'Cada imagen no puede superar los 5 MB.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validacion.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        // Usar transaccion para asegurar consistencia entre reporte e imagenes
        DB::beginTransaction();

        try {
            // Crear el reporte con los datos validados
            $reporte = Reporte::create([
                'user_id'       => $request->user()->id,
                'categoria_id'  => $request->categoria_id,
                'titulo'        => $request->titulo,
                'descripcion'   => $request->descripcion,
                'latitud'       => $request->latitud,
                'longitud'      => $request->longitud,
                'direccion'     => $request->direccion,
                'estado'        => Reporte::ESTADO_PENDIENTE,
                'prioridad'     => Reporte::PRIORIDAD_MEDIA,
                'fecha_reporte' => now(),
            ]);

            // Procesar las imagenes adjuntas si existen
            if ($request->hasFile('imagenes')) {
                foreach ($request->file('imagenes') as $archivo) {
                    // Guardar el archivo fisico en storage/app/public/reportes
                    $ruta = $archivo->store('reportes', 'public');

                    // Registrar la imagen en la base de datos
                    Imagen::create([
                        'reporte_id'     => $reporte->id,
                        'url'            => $ruta,
                        'nombre_archivo' => $archivo->getClientOriginalName(),
                        'tamano'         => $archivo->getSize(),
                        'tipo_mime'      => $archivo->getMimeType(),
                    ]);
                }
            }

            DB::commit();

            // Cargar las relaciones para incluirlas en la respuesta
            $reporte->load(['usuario:id,name,apellido,email', 'categoria', 'imagenes']);

            return response()->json([
                'success' => true,
                'message' => 'Reporte creado exitosamente. La alcaldia lo revisara pronto.',
                'data'    => $reporte,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error al crear el reporte.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * reporte especifico con todos sus detalles.
     *
     * GET /api/reportes/{id}
     *
     * Incluye todas las relaciones del reporte:
     * - Usuario que lo creo
     * - Categoria asignada
     * - Imagenes de evidencia
     * - Historial completo de seguimientos
     * - Comentarios asociados
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        // Buscar el reporte con todas sus relaciones
        $reporte = Reporte::with([
            'usuario:id,name,apellido,email,telefono',
            'categoria',
            'imagenes',
            'seguimientos.usuario:id,name,apellido',
            'comentarios.usuario:id,name,apellido',
        ])->find($id);

        if (!$reporte) {
            return response()->json([
                'success' => false,
                'message' => 'Reporte no encontrado.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Reporte obtenido correctamente.',
            'data'    => $reporte,
        ], 200);
    }

    /**
     * Actualizar un reporte existente.
     *
     * PUT /api/reportes/{id}
     *
     * Esta accion es realizada principalmente por administradores para:
     * - Asignar/cambiar la categoria del reporte
     * - Cambiar el estado (pendiente -> en_proceso -> resuelto)
     * - Modificar la prioridad
     *
     * IMPORTANTE: Si el estado cambia, se registra automaticamente
     * un nuevo seguimiento en la tabla 'seguimientos'.
     *
     * Si el estado nuevo es "resuelto", se establece la fecha_resolucion.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        // Buscar el reporte por ID
        $reporte = Reporte::find($id);

        if (!$reporte) {
            return response()->json([
                'success' => false,
                'message' => 'Reporte no encontrado.',
            ], 404);
        }

        // Validar los datos enviados
        $validator = Validator::make($request->all(), [
            'titulo'       => 'sometimes|string|max:150',
            'descripcion'  => 'sometimes|string',
            'categoria_id' => 'nullable|exists:categorias,id',
            'estado'       => 'sometimes|in:pendiente,en_proceso,resuelto,rechazado',
            'prioridad'    => 'sometimes|in:baja,media,alta,urgente',
            'direccion'    => 'nullable|string|max:255',
            'observacion'  => 'nullable|string', // Solo se usa al cambiar el estado
        ], [
            'estado.in'    => 'El estado debe ser: pendiente, en_proceso, resuelto o rechazado.',
            'prioridad.in' => 'La prioridad debe ser: baja, media, alta o urgente.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validacion.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        // Guardar el estado anterior para detectar cambios
        $estadoAnterior = $reporte->estado;
        $estadoNuevo = $request->input('estado', $estadoAnterior);

        // Usar transaccion para asegurar consistencia entre tablas.
        // Si algo falla, se revierten todos los cambios.
        DB::beginTransaction();

        try {
            // Preparar los datos para actualizar (excluir 'observacion')
            $datosActualizar = $request->only([
                'titulo',
                'descripcion',
                'categoria_id',
                'estado',
                'prioridad',
                'direccion',
            ]);

            // Si el nuevo estado es "resuelto", registrar la fecha de resolucion
            if ($estadoNuevo === Reporte::ESTADO_RESUELTO && $estadoAnterior !== Reporte::ESTADO_RESUELTO) {
                $datosActualizar['fecha_resolucion'] = now();
            }

            // Actualizar el reporte con los datos validados
            $reporte->update($datosActualizar);

            // Si hubo cambio de estado, registrarlo en el historial de seguimientos
            if ($estadoAnterior !== $estadoNuevo) {
                Seguimiento::create([
                    'reporte_id'      => $reporte->id,
                    'user_id'         => $request->user()->id,
                    'estado_anterior' => $estadoAnterior,
                    'estado_nuevo'    => $estadoNuevo,
                    'observacion'     => $request->input('observacion'),
                ]);
            }

            // Confirmar todos los cambios
            DB::commit();

            // Cargar las relaciones actualizadas
            $reporte->load(['usuario:id,name,apellido,email', 'categoria', 'seguimientos']);

            return response()->json([
                'success' => true,
                'message' => 'Reporte actualizado correctamente.',
                'data'    => $reporte,
            ], 200);

        } catch (\Exception $e) {
            // Si ocurre un error, revertir todos los cambios
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el reporte.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Eliminar un reporte del sistema.
     *
     * DELETE /api/reportes/{id}
     *
     * Al eliminar un reporte se eliminan en cascada:
     * - Sus imagenes asociadas
     * - Sus seguimientos
     * - Sus comentarios
     *
     * Esta accion es irreversible.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        // Buscar el reporte por ID
        $reporte = Reporte::find($id);

        if (!$reporte) {
            return response()->json([
                'success' => false,
                'message' => 'Reporte no encontrado.',
            ], 404);
        }

        // Eliminar el reporte (las tablas relacionadas se eliminan en cascada)
        $reporte->delete();

        return response()->json([
            'success' => true,
            'message' => 'Reporte eliminado correctamente.',
        ], 200);
    }
}
