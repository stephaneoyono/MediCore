<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Inertia\Inertia;

class PortalController extends Controller
{
    public function bookAppointment()
    {
        return Inertia::render('Patient/BookAppointment', [
            'appointments' => Appointment::where('email', auth()->user()->email)
                ->with(['doctor.user', 'prescriptions'])
                ->latest()
                ->get(),
        ]);
    }

    public function medicalRecords()
    {
        $appointments = Appointment::where('email', auth()->user()->email)
            ->with(['doctor.user', 'prescriptions', 'medicalRecords.prescription'])
            ->latest()
            ->get();

        return Inertia::render('Patient/MedicalRecords', [
            'appointments' => $appointments,
            'records' => $appointments->flatMap->medicalRecords->values(),
        ]);
    }

    public function profile()
    {
        return Inertia::render('Patient/Profile');
    }
}
