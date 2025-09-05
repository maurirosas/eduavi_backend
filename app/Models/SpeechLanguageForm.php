<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SpeechLanguageForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'kardex_id',
        'professional_id',
        'interview_date',
        'interview_time',
        'diagnosis_type',
        'patient_data',
        'parent_data',
        'medical_background',
        'language_development',
        'comprehension',
        'expression',
        'interaction_play',
        'behavior',
        'unusual_behaviors',
    ];

    protected $casts = [
        'patient_data' => 'array',
        'parent_data' => 'array',
        'medical_background' => 'array',
        'language_development' => 'array',
        'comprehension' => 'array',
        'expression' => 'array',
        'interaction_play' => 'array',
        'behavior' => 'array',
        'unusual_behaviors' => 'array',
    ];

    public $incrementing = false;
    protected $keyType = 'string';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->id) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    public function kardex()
    {
        return $this->belongsTo(Kardex::class);
    }

    public function professional()
    {
        return $this->belongsTo(Professional::class);
    }
}

