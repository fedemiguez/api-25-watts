<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    /**
     * Registro de Usuarios
     */
    public function signUp(Request $request)
    {
        /**
        * @OA\Post(
        *     path="/api/auth/signup",
        *     summary="Crear usuarios",
        *     tags={"Registro"},
        *     operationId="registro",
        *     @OA\Response(
        *         response=200,
        *         description="Crear usuario.",
        *         @OA\JsonContent()
        *     ),
        *     @OA\Response(
        *         response="default",
        *         description="Ha ocurrido un error.",
        *         @OA\JsonContent()
        *     ),
        *   @OA\RequestBody(
        *       required=true,
        *       description="Campos para registro de usuario",
        *       @OA\JsonContent(
        *           required={"email","password", "name"},
        *           @OA\Property(property="email", type="string", format="email", example="juanperez1@mail.com"),
        *           @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
        *           @OA\Property(property="name", type="string", example="Juan Perez"),
        *       ),
        *   )
        *)
        */
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        return response()->json([
            'message' => 'Usuario creado con exito!'
        ], 201);
    }

    /**
    * Login de Usuarios
    */
    public function login(Request $request)
    {
        /**
        * @OA\Post(
        *     path="/api/auth/login",
        *     summary="Login usuarios",
        *     tags={"Login"},
        *     operationId="login",
        *     @OA\Response(
        *         response=200,
        *         description="Login usuario.",
        *         @OA\JsonContent()
        *     ),
        *     @OA\Response(
        *         response="default",
        *         description="Ha ocurrido un error.",
        *         @OA\JsonContent()
        *     ),
        *   @OA\RequestBody(
        *       required=true,
        *       description="Campos para login de usuario",
        *       @OA\JsonContent(
        *           required={"email","password", "remember_me"},
        *           @OA\Property(property="email", type="string", format="email", example="juanperez1@mail.com"),
        *           @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
        *           @OA\Property(property="remember_me", type="boolean", example=true),
        *       ),
        *   )
        *)
        */
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);

        $credentials = request(['email', 'password']);

        if (!Auth::attempt($credentials))
            return response()->json([
                'message' => 'No Autorizado'
            ], 401);

        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');

        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();

        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse($token->expires_at)->toDateTimeString()
        ]);
    }

    /**
     * Cierre de sesión (anular el token)
     */
    public function logout(Request $request)
    {
        /**
        * @OA\Get(
        *     path="/api/auth/logout",
        *     summary="Logout de usuarios",
        *     tags={"LogOut"},
        *     operationId="LogOut",
        *     security={ {"passport": {}} },
        *     @OA\Response(
        *         response=200,
        *         description="Logout usuario.",
        *         @OA\JsonContent()
        *     ),
        *     @OA\Response(
        *         response="default",
        *         description="Ha ocurrido un error.",
        *         @OA\JsonContent()
        *     ),
        *)
        */
        $request->user()->token()->revoke();

        return response()->json([
            'message' => 'Deslogueado Correctamente'
        ]);
    }

    /**
     * Obtener el objeto User como json
     */
    public function user(Request $request)
    {
        /**
        * @OA\Get(
        *     path="/api/auth/user",
        *     summary="Obtener mi perfil de usuario",
        *     security={ {"passport": {}} },
        *     tags={"Perfil"},
        *     operationId="Perfil",
        *     @OA\Response(
        *         response=200,
        *         description="Perfil usuario.",
        *         @OA\JsonContent()
        *     ),
        *     @OA\Response(
        *         response="default",
        *         description="Ha ocurrido un error.",
        *         @OA\JsonContent()
        *     ),
        *)
        */
        if($request->user()){
            return response()->json($request->user());
        }else{
            return response()->json([
                'message' => 'No Autorizado'
            ], 401);
        }
    }
}
