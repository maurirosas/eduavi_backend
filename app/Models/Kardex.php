<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Kardex extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'client_id',
        'general_summary',
        'last_updated_at',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->id) {
                $model->id = (string)Str::uuid();
            }
        });
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function occupationalTherapyForms()
    {
        return $this->hasMany(OccupationalTherapyForm::class);
    }

    public function speechLanguageForms()
    {
        return $this->hasMany(SpeechLanguageForm::class);
    }

    public function psychopedagogicForms()
    {
        return $this->hasMany(PsychopedagogicForm::class);
    }
}
