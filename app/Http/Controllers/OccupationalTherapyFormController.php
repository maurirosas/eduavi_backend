<?php

namespace App\Http\Controllers;

use App\Models\OccupationalTherapyForm;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OccupationalTherapyFormController extends Controller
{
    public function index()
    {
        $forms = OccupationalTherapyForm::with(['kardex.client.user', 'professional.user'])->get();
        return response()->json($forms);
    }

    public function show($id)
    {
        $form = OccupationalTherapyForm::with(['kardex.client.user', 'professional.user'])->find($id);

        if (!$form) {
            return response()->json(['message' => 'Form not found'], 404);
        }

        return response()->json($form);
    }

    public function store(Request $request)
    {
        $user = auth('api')->user();
        $professional = $user->professional;

        $validated = $request->validate([
            'kardex_id' => 'required|uuid|exists:kardexes,id',
            'interview_date' => 'required|date',
            'interview_time' => 'required|date_format:H:i',
            'intervention_type' => 'nullable|string',

            'patient_data' => 'nullable|array',
            'parent_data' => 'nullable|array',
            'medical_history' => 'nullable|array',
            'development_milestones' => 'nullable|array',
            'daily_living_activities' => 'nullable|array',
            'sensory_neuromotor_system' => 'nullable|array',
            'unusual_behaviors' => 'nullable|array',
        ]);

        $form = OccupationalTherapyForm::create([
            'id' => (string)Str::uuid(),
            'kardex_id' => $validated['kardex_id'],
            'professional_id' => $professional->id,
            'interview_date' => $validated['interview_date'],
            'interview_time' => $validated['interview_time'],
            'intervention_type' => $validated['intervention_type'] ?? null,
            'patient_data' => $validated['patient_data'] ?? [],
            'parent_data' => $validated['parent_data'] ?? [],
            'medical_history' => $validated['medical_history'] ?? [],
            'development_milestones' => $validated['development_milestones'] ?? [],
            'daily_living_activities' => $validated['daily_living_activities'] ?? [],
            'sensory_neuromotor_system' => $validated['sensory_neuromotor_system'] ?? [],
            'unusual_behaviors' => $validated['unusual_behaviors'] ?? [],
        ]);

        return response()->json($form, 201);
    }

    public function update(Request $request, $id)
    {
        $form = OccupationalTherapyForm::find($id);

        if (!$form) {
            return response()->json(['message' => 'Form not found'], 404);
        }

        $validated = $request->validate([
            'interview_date' => 'nullable|date',
            'interview_time' => 'nullable|date_format:H:i',
            'intervention_type' => 'nullable|string',

            'patient_data' => 'nullable|array',
            'parent_data' => 'nullable|array',
            'medical_history' => 'nullable|array',
            'development_milestones' => 'nullable|array',
            'daily_living_activities' => 'nullable|array',
            'sensory_neuromotor_system' => 'nullable|array',
            'unusual_behaviors' => 'nullable|array',
        ]);

        $form->update($validated);

        return response()->json($form);
    }

    public function destroy($id)
    {
        $form = OccupationalTherapyForm::find($id);

        if (!$form) {
            return response()->json(['message' => 'Form not found'], 404);
        }

        $form->delete();
        return response()->json(['message' => 'Form deleted']);
    }
}
