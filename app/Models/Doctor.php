<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Schedule;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use App\Models\HospitalNote;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'designation',
        'speciality',
        'phone',
        'about'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }

    public function hospitalNotes()
    {
        return $this->hasMany(HospitalNote::class);
    }
}
