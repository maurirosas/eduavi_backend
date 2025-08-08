<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Professional extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'specialty',
        'description',
        'experience',
        'location',
        'bio',
        'average_rating',
        'patient_count',
        'bank_account_number',
        'bank_name',
        'national_id',
        'profession',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFullNameAttribute()
    {
        return $this->user->name;
    }
}
