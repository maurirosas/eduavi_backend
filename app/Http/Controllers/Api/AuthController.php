<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use App\Services\JWTService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    private string $jwtSecret;

    public function __construct()
    {
        $this->jwtSecret = env('JWT_SECRET', 'secret');
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $usuario = Usuario::where('email', $request->email)->first();

        if (!$usuario || !Hash::check($request->password, $usuario->password)) {
            throw ValidationException::withMessages([
                'email' => ['Credenciales incorrectas.'],
            ]);
        }

        $payload = [
            'sub' => $usuario->id_usuario,
            'email' => $usuario->email,
            'iat' => time(),
            'exp' => time() + 60 * 60 * 24,
        ];

        $token = JWTService::encode($payload, $this->jwtSecret);

        return response()->json([
            'token' => $token,
            'usuario' => $usuario,
        ]);
    }

    public function googleLogin(Request $request)
    {
        $request->validate([
            'id_token' => 'required',
        ]);

        $googleResponse = @file_get_contents('https://oauth2.googleapis.com/tokeninfo?id_token=' . $request->id_token);
        if (!$googleResponse) {
            return response()->json(['message' => 'Token de Google inválido'], 401);
        }

        $data = json_decode($googleResponse, true);
        if (!isset($data['sub']) || !isset($data['email'])) {
            return response()->json(['message' => 'Token de Google inválido'], 401);
        }

        $usuario = Usuario::where('proveedor_autenticacion', 'google')
            ->where('auth_id', $data['sub'])
            ->first();

        if (!$usuario) {
            $usuario = Usuario::create([
                'nombre' => $data['name'] ?? $data['email'],
                'email' => $data['email'],
                'password' => Hash::make(str()->random(16)),
                'tipo' => 'cliente',
                'proveedor_autenticacion' => 'google',
                'auth_id' => $data['sub'],
            ]);
        }

        $payload = [
            'sub' => $usuario->id_usuario,
            'email' => $usuario->email,
            'iat' => time(),
            'exp' => time() + 60 * 60 * 24,
        ];

        $token = JWTService::encode($payload, $this->jwtSecret);

        return response()->json([
            'token' => $token,
            'usuario' => $usuario,
        ]);
    }

    public function logout(Request $request)
    {
        // JWT is stateless, so just acknowledge the request
        return response()->json(['message' => 'Sesión cerrada correctamente']);
    }

    public function me(Request $request)
    {
        $user = $this->userFromRequest($request);
        if (!$user) {
            return response()->json(['message' => 'Token inválido'], 401);
        }
        return $user;
    }

    private function userFromRequest(Request $request): ?Usuario
    {
        $authHeader = $request->header('Authorization');
        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return null;
        }
        $token = substr($authHeader, 7);
        $payload = JWTService::decode($token, $this->jwtSecret);
        if (!$payload || !isset($payload['sub'])) {
            return null;
        }
        return Usuario::find($payload['sub']);
    }
}
