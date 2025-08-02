<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:10|max:255',
            'role' => 'required|string|in:admin,user',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        User::create([
            'name' => $request->get('name'),
            'role' => $request->get('role'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password')),
        ]);

        return response()->json(['message' => 'User succesfully registered'], 201);
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

            return response()->json(['token' => $token], 200);

        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token', 'message' => $e->getMessage()], 500);
        }
    }

    public function getUser()
    {
        $user = Auth::user();
        return response()->json($user, 200);
    }

    public function logout(Request $request)
    {
        auth('api')->logout();
        return response()->json(['message' => 'Successfully logged out.'], 200);
    }
}
