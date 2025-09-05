<?php

namespace App\Http\Controllers;

use App\Models\Kardex;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KardexController extends Controller
{
    public function index()
    {
        $kardexes = Kardex::with('client.user')->get();
        return response()->json($kardexes);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|uuid|exists:clients,id',
            'general_summary' => 'nullable|string',
        ]);

        $kardex = Kardex::create([
            'id' => (string)Str::uuid(),
            'client_id' => $validated['client_id'],
            'general_summary' => $validated['general_summary'],
            'last_updated_at' => now(),
        ]);

        return response()->json($kardex, 201);
    }

    public function show($id)
    {
        $kardex = Kardex::with('client.user')->find($id);

        if (!$kardex) {
            return response()->json(['message' => 'Kardex not found'], 404);
        }

        return response()->json($kardex);
    }

    public function update(Request $request, $id)
    {
        $kardex = Kardex::find($id);

        if (!$kardex) {
            return response()->json(['message' => 'Kardex not found'], 404);
        }

        $validated = $request->validate([
            'general_summary' => 'nullable|string',
        ]);

        $kardex->update([
            'general_summary' => $validated['general_summary'],
            'last_updated_at' => now(),
        ]);

        return response()->json($kardex);
    }

    public function destroy($id)
    {
        $kardex = Kardex::find($id);

        if (!$kardex) {
            return response()->json(['message' => 'Kardex not found'], 404);
        }

        $kardex->delete();
        return response()->json(['message' => 'Kardex deleted']);
    }
}
