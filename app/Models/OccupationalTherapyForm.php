<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class OccupationalTherapyForm extends Model
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
        'medical_history',
        'development_milestones',
        'daily_living_activities',
        'sensory_neuromotor_system',
        'unusual_behaviors',
    ];

    protected $casts = [
        'interview_date'             => 'date',
        'interview_time'             => 'datetime:H:i',

        'patient_data'               => 'array',
        'parent_data'                => 'array',
        'medical_history'            => 'array',
        'development_milestones'    => 'array',
        'daily_living_activities'    => 'array',
        'sensory_neuromotor_system' => 'array',
        'unusual_behaviors'         => 'array',
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
