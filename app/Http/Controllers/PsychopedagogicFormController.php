<?php

namespace App\Http\Controllers;

use App\Models\PsychopedagogicForm;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PsychopedagogicFormController extends Controller
{
    public function index()
    {
        $forms = PsychopedagogicForm::with(['kardex.client.user', 'professional.user'])->get();
        return response()->json($forms);
    }

    public function show($id)
    {
        $form = PsychopedagogicForm::with(['kardex.client.user', 'professional.user'])->find($id);

        if (!$form) {
            return response()->json(['message' => 'Psychopedagogic form not found'], 404);
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

            // Secciones en formato JSON
            'patient_data' => 'nullable|array',
            'parent_data' => 'nullable|array',
            'development_data' => 'nullable|array',
            'language_data' => 'nullable|array',
            'social_data' => 'nullable|array',
            'cognitive_data' => 'nullable|array',
            'interaction_play_data' => 'nullable|array',
            'behavior_data' => 'nullable|array',
            'autonomy_data' => 'nullable|array',
            'atypical_behaviors' => 'nullable|array',
        ]);

        $form = PsychopedagogicForm::create([
            'id' => (string)Str::uuid(),
            'kardex_id' => $validated['kardex_id'],
            'professional_id' => $professional->id,
            'interview_date' => $validated['interview_date'],
            'interview_time' => $validated['interview_time'],
            'intervention_type' => $validated['intervention_type'] ?? null,

            'patient_data' => $validated['patient_data'] ?? [],
            'parent_data' => $validated['parent_data'] ?? [],
            'development_data' => $validated['development_data'] ?? [],
            'language_data' => $validated['language_data'] ?? [],
            'social_data' => $validated['social_data'] ?? [],
            'cognitive_data' => $validated['cognitive_data'] ?? [],
            'interaction_play_data' => $validated['interaction_play_data'] ?? [],
            'behavior_data' => $validated['behavior_data'] ?? [],
            'autonomy_data' => $validated['autonomy_data'] ?? [],
            'atypical_behaviors' => $validated['atypical_behaviors'] ?? [],
        ]);

        return response()->json($form, 201);
    }

    public function update(Request $request, $id)
    {
        $form = PsychopedagogicForm::find($id);

        if (!$form) {
            return response()->json(['message' => 'Psychopedagogic form not found'], 404);
        }

        $validated = $request->validate([
            'interview_date' => 'nullable|date',
            'interview_time' => 'nullable|date_format:H:i',
            'intervention_type' => 'nullable|string',

            'patient_data' => 'nullable|array',
            'parent_data' => 'nullable|array',
            'development_data' => 'nullable|array',
            'language_data' => 'nullable|array',
            'social_data' => 'nullable|array',
            'cognitive_data' => 'nullable|array',
            'interaction_play_data' => 'nullable|array',
            'behavior_data' => 'nullable|array',
            'autonomy_data' => 'nullable|array',
            'atypical_behaviors' => 'nullable|array',
        ]);

        $form->update($validated);

        return response()->json($form);
    }

    public function destroy($id)
    {
        $form = PsychopedagogicForm::find($id);

        if (!$form) {
            return response()->json(['message' => 'Psychopedagogic form not found'], 404);
        }

        $form->delete();
        return response()->json(['message' => 'Form deleted']);
    }
}
