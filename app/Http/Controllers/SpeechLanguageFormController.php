<?php

namespace App\Http\Controllers;

use App\Models\SpeechLanguageForm;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SpeechLanguageFormController extends Controller
{
    public function index()
    {
        $forms = SpeechLanguageForm::with(['kardex.client.user', 'professional.user'])->get();
        return response()->json($forms);
    }

    public function show($id)
    {
        $form = SpeechLanguageForm::with(['kardex.client.user', 'professional.user'])->find($id);

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
            'diagnosis_type' => 'nullable|string',

            'patient_data' => 'nullable|array',
            'parent_data' => 'nullable|array',
            'medical_background' => 'nullable|array',
            'language_development' => 'nullable|array',
            'comprehension' => 'nullable|array',
            'expression' => 'nullable|array',
            'interaction_play' => 'nullable|array',
            'behavior' => 'nullable|array',
            'unusual_behaviors' => 'nullable|array',
        ]);

        $form = SpeechLanguageForm::create([
            'id' => (string)Str::uuid(),
            'kardex_id' => $validated['kardex_id'],
            'professional_id' => $professional->id,
            'interview_date' => $validated['interview_date'],
            'interview_time' => $validated['interview_time'],
            'diagnosis_type' => $validated['diagnosis_type'] ?? null,
            'patient_data' => $validated['patient_data'] ?? [],
            'parent_data' => $validated['parent_data'] ?? [],
            'medical_background' => $validated['medical_background'] ?? [],
            'language_development' => $validated['language_development'] ?? [],
            'comprehension' => $validated['comprehension'] ?? [],
            'expression' => $validated['expression'] ?? [],
            'interaction_play' => $validated['interaction_play'] ?? [],
            'behavior' => $validated['behavior'] ?? [],
            'unusual_behaviors' => $validated['unusual_behaviors'] ?? [],
        ]);

        return response()->json($form, 201);
    }

    public function update(Request $request, $id)
    {
        $form = SpeechLanguageForm::find($id);

        if (!$form) {
            return response()->json(['message' => 'Form not found'], 404);
        }

        $validated = $request->validate([
            'interview_date' => 'nullable|date',
            'interview_time' => 'nullable|date_format:H:i',
            'diagnosis_type' => 'nullable|string',

            'patient_data' => 'nullable|array',
            'parent_data' => 'nullable|array',
            'medical_background' => 'nullable|array',
            'language_development' => 'nullable|array',
            'comprehension' => 'nullable|array',
            'expression' => 'nullable|array',
            'interaction_play' => 'nullable|array',
            'behavior' => 'nullable|array',
            'unusual_behaviors' => 'nullable|array',
        ]);

        $form->update($validated);

        return response()->json($form);
    }

    public function destroy($id)
    {
        $form = SpeechLanguageForm::find($id);

        if (!$form) {
            return response()->json(['message' => 'Form not found'], 404);
        }

        $form->delete();
        return response()->json(['message' => 'Form deleted']);
    }
}
