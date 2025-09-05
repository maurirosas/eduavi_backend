<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PsychopedagogicForm extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'kardex_id',
        'professional_id',
        'interview_date',
        'interview_time',
        'intervention_type',
        'patient_data',
        'parent_data',
        'development_data',
        'language_data',
        'social_data',
        'cognitive_data',
        'interaction_play_data',
        'behavior_data',
        'autonomy_data',
        'atypical_behaviors',
    ];

    protected $casts = [
        'patient_data'           => 'array',
        'parent_data'            => 'array',
        'development_data'       => 'array',
        'language_data'          => 'array',
        'social_data'            => 'array',
        'cognitive_data'         => 'array',
        'interaction_play_data'  => 'array',
        'behavior_data'          => 'array',
        'autonomy_data'          => 'array',
        'atypical_behaviors'     => 'array',
        'interview_date'         => 'date',
        'interview_time'         => 'datetime:H:i',
    ];

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
