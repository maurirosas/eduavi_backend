<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    // Listar todos los clientes (solo admin)
    public function index()
    {
        $clients = Client::with('user')->get();
        return response()->json($clients);
    }

    // Ver un cliente específico
    public function show($id)
    {
        $client = Client::with('user')->find($id);
        if (!$client) {
            return response()->json(['message' => 'Client not found'], 404);
        }
        return response()->json($client);
    }

    // Actualizar datos del cliente logueado
    public function update(Request $request)
    {
        $user = auth('api')->user();

        if (!$user->isClient()) {
            return response()->json(['message' => 'Only clients can update their profile'], 403);
        }

        $client = $user->client;

        // Actualizar campos de cliente
        $client->update($request->only(['birth_date', 'gender', 'address']));

        // Actualizar datos de usuario si se envían
        $user->update($request->only(['name', 'email', 'phone_number']));

        return response()->json(['message' => 'Client profile updated successfully', 'client' => $client]);
    }

    // Eliminar cliente y usuario (solo admin o el mismo cliente)
    public function destroy($id)
    {
        $authUser = auth('api')->user();

        $client = Client::find($id);
        if (!$client) {
            return response()->json(['message' => 'Client not found'], 404);
        }

        if (!$authUser->isAdmin() && $authUser->id !== $client->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Eliminar el usuario asociado (cascade borra el cliente)
        User::where('id', $client->user_id)->delete();

        return response()->json(['message' => 'Client deleted successfully']);
    }
}
