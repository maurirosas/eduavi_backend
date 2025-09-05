<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::with('professional.user')->get();
        return response()->json($services);
    }

    public function store(Request $request)
    {
        $user = auth('api')->user();

        if (!$user->isProfessional()) {
            return response()->json(['message' => 'Only professionals can create services'], 403);
        }

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration'    => 'required|integer|min:1',
            'price'       => 'required|numeric|min:0',
        ]);

        $service = Service::create(array_merge($validated, [
            'id' => (string) Str::uuid(),
            'professional_id' => $user->professional->id,
        ]));

        return response()->json($service, 201);
    }

    public function show($id)
    {
        $service = Service::with('professional.user')->find($id);
        if (!$service) {
            return response()->json(['message' => 'Service not found'], 404);
        }
        return response()->json($service);
    }

    public function update(Request $request, $id)
    {
        $user = auth('api')->user();
        $service = Service::find($id);

        if (!$service) {
            return response()->json(['message' => 'Service not found'], 404);
        }

        if (!$user->isAdmin() && $user->professional->id !== $service->professional_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'name'        => 'string|max:255',
            'description' => 'nullable|string',
            'duration'    => 'integer|min:1',
            'price'       => 'numeric|min:0',
        ]);

        $service->update($validated);
        return response()->json($service);
    }

    public function destroy($id)
    {
        $user = auth('api')->user();
        $service = Service::find($id);

        if (!$service) {
            return response()->json(['message' => 'Service not found'], 404);
        }

        if (!$user->isAdmin() && $user->professional->id !== $service->professional_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $service->delete();
        return response()->json(['message' => 'Service deleted successfully']);
    }
}
