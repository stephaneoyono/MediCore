<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'patient_email',
        'doctor_id',
        'prescription_id',
        'title',
        'summary',
        'record_date',
    ];

    protected $casts = [
        'record_date' => 'date',
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function prescription()
    {
        return $this->belongsTo(Prescription::class);
    }
}
