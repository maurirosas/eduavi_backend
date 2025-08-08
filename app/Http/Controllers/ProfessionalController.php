<?php

namespace App\Http\Controllers;

use App\Models\Professional;
use App\Models\User;
use Illuminate\Http\Request;

class ProfessionalController extends Controller
{
    // Listar todos los profesionales (solo admin)
    public function index()
    {
        $professionals = Professional::with('user')->get();
        return response()->json($professionals);
    }

    // Ver un profesional específico
    public function show($id)
    {
        $professional = Professional::with('user')->find($id);
        if (!$professional) {
            return response()->json(['message' => 'Professional not found'], 404);
        }
        return response()->json($professional);
    }

    // Actualizar datos del profesional logueado
    public function update(Request $request)
    {
        $user = auth('api')->user();

        if (!$user->isProfessional()) {
            return response()->json(['message' => 'Only professionals can update their profile'], 403);
        }

        $professional = $user->professional;

        // Actualizar campos de profesional
        $professional->update($request->only([
            'specialty',
            'description',
            'experience',
            'location',
            'bio',
            'status'
        ]));

        // Actualizar datos de usuario si se envían
        $user->update($request->only(['name', 'email', 'phone_number']));

        return response()->json(['message' => 'Professional profile updated successfully', 'professional' => $professional]);
    }

    // Eliminar profesional y usuario (solo admin o el mismo profesional)
    public function destroy($id)
    {
        $authUser = auth('api')->user();

        $professional = Professional::find($id);
        if (!$professional) {
            return response()->json(['message' => 'Professional not found'], 404);
        }

        if (!$authUser->isAdmin() && $authUser->id !== $professional->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Eliminar el usuario asociado (cascade borra el profesional)
        User::where('id', $professional->user_id)->delete();

        return response()->json(['message' => 'Professional deleted successfully']);
    }
}
