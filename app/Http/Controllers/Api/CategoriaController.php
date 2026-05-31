<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Controlador: CategoriaController
 *
 * Gestiona las operaciones CRUD para las categorias de reportes ciudadanos.
 * Expone endpoints API REST consumidos por el frontend Vue.js.
 *
 * Endpoints disponibles:
 * - GET    /api/categorias       Listar todas las categorias
 * - POST   /api/categorias       Crear una nueva categoria
 * - GET    /api/categorias/{id}  Obtener una categoria especifica
 * - PUT    /api/categorias/{id}  Actualizar una categoria existente
 * - DELETE /api/categorias/{id}  Eliminar una categoria
 *
 */
class CategoriaController extends Controller
{
    /**
     * Listar todas las categorias del sistema.
     *
     * GET /api/categorias
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        // Obtener todas las categorias ordenadas alfabeticamente
        $categorias = Categoria::orderBy('nombre', 'asc')->get();

        return response()->json([
            'success' => true,
            'message' => 'Categorias obtenidas correctamente.',
            'data' => $categorias,
        ], 200);
    }

    /**
     * Crear una nueva categoria.
     *
     * POST /api/categorias
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        // Validar los datos enviados desde el frontend
        $validator = Validator::make($request->all(), [
            'nombre'      => 'required|string|max:80|unique:categorias,nombre',
            'descripcion' => 'nullable|string|max:255',
            'icono'       => 'nullable|string|max:50',
            'color'       => 'nullable|string|max:7',
            'activo'      => 'nullable|boolean',
        ], [
            // Mensajes personalizados en español
            'nombre.required' => 'El nombre de la categoria es obligatorio.',
            'nombre.unique'   => 'Ya existe una categoria con ese nombre.',
            'nombre.max'      => 'El nombre no puede tener mas de 80 caracteres.',
        ]);

        // Si la validacion falla, retornar errores
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validacion.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        // Crear la nueva categoria con los datos validados
        $categoria = Categoria::create($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Categoria creada exitosamente.',
            'data'    => $categoria,
        ], 201);
    }

    /**
     * Obtener una categoria especifica por su ID.
     *
     * GET /api/categorias/{id}
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        // Buscar la categoria por ID
        $categoria = Categoria::find($id);

        // Si no existe, retornar error 404
        if (!$categoria) {
            return response()->json([
                'success' => false,
                'message' => 'Categoria no encontrada.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Categoria obtenida correctamente.',
            'data'    => $categoria,
        ], 200);
    }

    /**
     * Actualizar una categoria existente.
     *
     * PUT /api/categorias/{id}
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        // Buscar la categoria por ID
        $categoria = Categoria::find($id);

        if (!$categoria) {
            return response()->json([
                'success' => false,
                'message' => 'Categoria no encontrada.',
            ], 404);
        }

        // Validar los datos enviados desde el frontend.
        // 'unique' ignora el ID actual para permitir mantener el mismo nombre.
        $validator = Validator::make($request->all(), [
            'nombre'      => 'sometimes|required|string|max:80|unique:categorias,nombre,' . $id,
            'descripcion' => 'nullable|string|max:255',
            'icono'       => 'nullable|string|max:50',
            'color'       => 'nullable|string|max:7',
            'activo'      => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validacion.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        // Actualizar la categoria con los datos validados
        $categoria->update($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Categoria actualizada correctamente.',
            'data'    => $categoria->fresh(),
        ], 200);
    }

    /**
     * Eliminar una categoria del sistema.
     *
     * DELETE /api/categorias/{id}
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        // Buscar la categoria por ID
        $categoria = Categoria::find($id);

        if (!$categoria) {
            return response()->json([
                'success' => false,
                'message' => 'Categoria no encontrada.',
            ], 404);
        }

        // Verificar si la categoria tiene reportes asociados antes de eliminar
        if ($categoria->reportes()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar la categoria porque tiene reportes asociados. '
                          . 'Considere desactivarla en su lugar.',
            ], 409);
        }

        // Eliminar la categoria
        $categoria->delete();

        return response()->json([
            'success' => true,
            'message' => 'Categoria eliminada correctamente.',
        ], 200);
    }
}
