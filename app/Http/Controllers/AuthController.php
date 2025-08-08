<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Professional;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3|max:255',
            'role' => 'required|string|in:admin,professional,client',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Crear usuario
        $user = User::create([
            'id' => (string)Str::uuid(),
            'name' => $request->get('name'),
            'role' => $request->get('role'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
            'phone_number' => $request->get('phone_number'),
            'auth_provider' => $request->get('auth_provider'),
            'provider_id' => $request->get('provider_id'),
        ]);

        // Crear registro adicional según rol
        if ($user->isClient()) {
            Client::create([
                'id' => (string)Str::uuid(),
                'user_id' => $user->id,
                'birth_date' => $request->get('birth_date'),
                'gender' => $request->get('gender'),
                'address' => $request->get('address'),
            ]);
        } elseif ($user->isProfessional()) {
            Professional::create([
                'id' => (string)Str::uuid(),
                'user_id' => $user->id,
                'specialty' => $request->get('specialty'),
                'description' => $request->get('description'),
                'experience' => $request->get('experience'),
                'location' => $request->get('location'),
                'bio' => $request->get('bio'),
                'status' => 'inactive', // Por defecto
            ]);
        }

        return response()->json(['message' => 'User successfully registered'], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $credentials = $request->only('email', 'password');

        try {
            if (!$token = auth('api')->attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }

            return response()->json([
                'token' => $token,
                'user' => auth('api')->user()
            ], 200);

        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token', 'message' => $e->getMessage()], 500);
        }
    }

    public function getUser()
    {
        $user = Auth::user()->load(['client', 'professional']);
        return response()->json($user, 200);
    }

    public function logout()
    {
        auth('api')->logout();
        return response()->json(['message' => 'Successfully logged out.'], 200);
    }
}
