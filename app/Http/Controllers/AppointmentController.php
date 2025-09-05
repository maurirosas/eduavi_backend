<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    public function index()
    {
        $user = auth('api')->user();

        if ($user->isClient()) {
            $appointments = Appointment::with(['service', 'professional.user'])
                ->where('client_id', $user->client->id)
                ->get();
        } elseif ($user->isProfessional()) {
            $appointments = Appointment::with(['service', 'client.user'])
                ->where('professional_id', $user->professional->id)
                ->get();
        } elseif ($user->isAdmin()) {
            $appointments = Appointment::with(['service', 'client.user', 'professional.user'])->get();
        } else {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($appointments);
    }

    public function store(Request $request)
    {
        $user = auth('api')->user();

        if (!$user->isClient()) {
            return response()->json(['message' => 'Only clients can create appointments'], 403);
        }

        $validated = $request->validate([
            'service_id' => 'required|uuid|exists:services,id',
            'date_time'  => 'required|date|after:now',
            'notes'      => 'nullable|string',
        ]);

        $service = Service::findOrFail($validated['service_id']);

        $start = Carbon::parse($validated['date_time']);
        $end   = $start->copy()->addMinutes($service->duration);

        // Validar conflictos para el profesional
        $profConflict = Appointment::where('professional_id', $service->professional_id)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('date_time', [$start, $end])
                    ->orWhereRaw('? BETWEEN date_time AND DATE_ADD(date_time, INTERVAL duration MINUTE)', [$start]);
            })->exists();

        if ($profConflict) {
            return response()->json(['message' => 'The professional already has a conflicting appointment.'], 409);
        }

        // Validar conflictos para el cliente
        $clientConflict = Appointment::where('client_id', $user->client->id)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('date_time', [$start, $end])
                    ->orWhereRaw('? BETWEEN date_time AND DATE_ADD(date_time, INTERVAL duration MINUTE)', [$start]);
            })->exists();

        if ($clientConflict) {
            return response()->json(['message' => 'You already have an appointment at this time.'], 409);
        }

        $appointment = Appointment::create([
            'id'              => (string) Str::uuid(),
            'client_id'       => $user->client->id,
            'professional_id' => $service->professional_id,
            'service_id'      => $service->id,
            'date_time'       => $validated['date_time'],
            'status'          => 'pending',
            'notes'           => $validated['notes'] ?? null
        ]);

        return response()->json($appointment, 201);
    }

    public function show($id)
    {
        $appointment = Appointment::with(['client.user', 'professional.user', 'service'])->find($id);

        if (!$appointment) {
            return response()->json(['message' => 'Appointment not found'], 404);
        }

        return response()->json($appointment);
    }

    public function update(Request $request, $id)
    {
        $user = auth('api')->user();
        $appointment = Appointment::find($id);

        if (!$appointment) {
            return response()->json(['message' => 'Appointment not found'], 404);
        }

        if ($user->isProfessional() && $user->professional->id !== $appointment->professional_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        if ($user->isClient() && $user->client->id !== $appointment->client_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'status' => 'nullable|in:pending,confirmed,completed,cancelled',
            'notes'  => 'nullable|string'
        ]);

        $appointment->update($validated);

        return response()->json($appointment);
    }

    public function destroy($id)
    {
        $user = auth('api')->user();
        $appointment = Appointment::find($id);

        if (!$appointment) {
            return response()->json(['message' => 'Appointment not found'], 404);
        }

        if (!$user->isAdmin() && (!$user->isClient() || $user->client->id !== $appointment->client_id)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $appointment->delete();
        return response()->json(['message' => 'Appointment deleted successfully']);
    }
}
