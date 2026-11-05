<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HospitalNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'title',
        'content',
        'note_date',
    ];

    protected $casts = [
        'note_date' => 'date',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
