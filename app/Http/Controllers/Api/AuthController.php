<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Rol;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

/**
 * Controlador: AuthController
 *
 * Gestiona la autenticacion de usuarios en el sistema LICAM.
 * Maneja el registro de ciudadanos, inicio de sesion, cierre de sesion,
 * y obtencion de datos del usuario autenticado.
 *
 * Utiliza Laravel Sanctum para la generacion y gestion de tokens API.
 *
 * Endpoints disponibles:
 * - POST /api/registro   Registrar un nuevo ciudadano
 * - POST /api/login      Iniciar sesion
 * - POST /api/logout     Cerrar sesion (requiere autenticacion)
 * - GET  /api/usuario    Obtener datos del usuario autenticado
 *
 */
class AuthController extends Controller
{
    /**
     * Registrar un nuevo ciudadano en el sistema.
     *
     * POST /api/registro
     *
     * Por defecto, todos los registros desde esta ruta se crean con el rol "ciudadano".
     * Los administradores deben ser creados manualmente por otro administrador.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function registro(Request $request): JsonResponse
    {
        // Validar los datos del formulario de registro
        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|max:100',
            'apellido'  => 'required|string|max:100',
            'dui'       => 'nullable|string|max:10|unique:users,dui',
            'email'     => 'required|string|email|max:150|unique:users,email',
            'password'  => 'required|string|min:8|confirmed',
            'telefono'  => 'nullable|string|max:15',
            'direccion' => 'nullable|string|max:255',
        ], [
            // Mensajes de error personalizados en español
            'name.required'      => 'El nombre es obligatorio.',
            'apellido.required'  => 'El apellido es obligatorio.',
            'email.required'     => 'El correo electronico es obligatorio.',
            'email.email'        => 'El correo electronico no tiene un formato valido.',
            'email.unique'       => 'Ya existe un usuario registrado con ese correo.',
            'dui.unique'         => 'Ya existe un usuario registrado con ese DUI.',
            'password.required'  => 'La contraseña es obligatoria.',
            'password.min'       => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'La confirmacion de la contraseña no coincide.',
        ]);

        // Si la validacion falla, retornar errores
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validacion.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        // Obtener el rol "ciudadano" para asignarlo automaticamente
        $rolCiudadano = Rol::where('nombre', 'ciudadano')->first();

        // Verificar que el rol exista en el sistema
        if (!$rolCiudadano) {
            return response()->json([
                'success' => false,
                'message' => 'Error interno: el rol ciudadano no esta configurado en el sistema.',
            ], 500);
        }

        // Crear el nuevo usuario con la contraseña encriptada
        $user = User::create([
            'rol_id'    => $rolCiudadano->id,
            'name'      => $request->name,
            'apellido'  => $request->apellido,
            'dui'       => $request->dui,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'telefono'  => $request->telefono,
            'direccion' => $request->direccion,
            'activo'    => true,
        ]);

        // Generar un token de acceso para el usuario recien registrado
        $token = $user->createToken('auth_token')->plainTextToken;

        // Cargar la relacion con el rol para incluirla en la respuesta
        $user->load('rol');

        return response()->json([
            'success' => true,
            'message' => 'Usuario registrado exitosamente.',
            'data'    => [
                'user'  => $user,
                'token' => $token,
                'token_type' => 'Bearer',
            ],
        ], 201);
    }

    /**
     * Iniciar sesion en el sistema.
     *
     * POST /api/login
     *
     * Verifica las credenciales del usuario y retorna un token de acceso.
     * Tambien valida que el usuario este activo (no deshabilitado).
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        // Validar que se hayan enviado las credenciales
        $validator = Validator::make($request->all(), [
            'email'    => 'required|string|email',
            'password' => 'required|string',
        ], [
            'email.required'    => 'El correo electronico es obligatorio.',
            'email.email'       => 'El correo electronico no tiene un formato valido.',
            'password.required' => 'La contraseña es obligatoria.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validacion.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        // Buscar el usuario por su correo electronico
        $user = User::where('email', $request->email)->first();

        // Verificar que el usuario exista y que la contraseña sea correcta
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Las credenciales son incorrectas.',
            ], 401);
        }

        // Verificar que el usuario este activo
        if (!$user->activo) {
            return response()->json([
                'success' => false,
                'message' => 'Su cuenta ha sido deshabilitada. Contacte al administrador.',
            ], 403);
        }

        // Generar un token de acceso para el usuario
        $token = $user->createToken('auth_token')->plainTextToken;

        // Cargar la relacion con el rol para incluirla en la respuesta
        $user->load('rol');

        return response()->json([
            'success' => true,
            'message' => 'Inicio de sesion exitoso.',
            'data'    => [
                'user'       => $user,
                'token'      => $token,
                'token_type' => 'Bearer',
            ],
        ], 200);
    }

    /**
     * Cerrar sesion del usuario autenticado.
     *
     * POST /api/logout
     *
     * Elimina el token actual del usuario, invalidandolo.
     * Esto evita que el token se siga usando despues del logout.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        // Eliminar el token de acceso actual del usuario
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Sesion cerrada correctamente.',
        ], 200);
    }

    /**
     * Obtener los datos del usuario autenticado.
     *
     * GET /api/usuario
     *
     * Util para que el frontend muestre informacion del usuario logueado
     * (nombre, email, rol, etc.) sin tener que guardarlo localmente.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function usuario(Request $request): JsonResponse
    {
        // Cargar la relacion con el rol del usuario autenticado
        $user = $request->user()->load('rol');

        return response()->json([
            'success' => true,
            'message' => 'Datos del usuario obtenidos correctamente.',
            'data'    => $user,
        ], 200);
    }
}
