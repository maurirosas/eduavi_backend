<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServicioController extends Controller
{
    public function addService(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:10|max:255',
            'description' => 'required|string|min:10|max:255',
            'price' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        Servicio::create([
            'name' => $request->get('name'),
            'description' => $request->get('description'),
            'price' => $request->get('price')
        ]);
        return response()->json(['message' => 'Servicio agregado'], 201);
    }

    public function getServices()
    {
        $services = Servicio::all();

        if ($services->isEmpty()) {
            return response()->json(['message' => 'No se encontraron servicios'], 404);
        }
        return response()->json($services, 200);
    }

    public function getServiceById($id)
    {
        $service = Servicio::find($id);

        if (!$service) {
            return response()->json(['message' => 'No se encontraron servicios'], 404);
        }

        return response()->json($service, 200);
    }

    public function updateServiceById(Request $request, $id)
    {
        $service = Servicio::find($id);

        if (!$service) {
            return response()->json(['message' => 'No se encontraron servicios'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|min:10|max:255',
            'description' => 'sometimes|string|min:10|max:255',
            'price' => 'sometimes|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if ($request->has('name')) {
            $service->name = $request->name;
        }

        if ($request->has('description')) {
            $service->description = $request->description;
        }

        if ($request->has('price')) {
            $service->price = $request->price;
        }

        $service->update();
        return response()->json(['message' => 'Servicio actualizado'], 200);
    }

    public function deleteServiceById($id)
    {
        $service = Servicio::find($id);
        if (!$service) {
            return response()->json(['message' => 'No se encontraron servicios'], 404);
        }

        $service->delete();
        return response()->json(['message' => 'Servicio eliminado'], 200);
    }
}
