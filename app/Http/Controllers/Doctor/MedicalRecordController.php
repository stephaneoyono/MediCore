<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class MedicalRecordController extends Controller
{
    public function index()
    {
        $doctor = Auth::user()->doctor;

        if (!$doctor) {
            abort(403, 'Accès non autorisé');
        }

        $records = MedicalRecord::where('doctor_id', $doctor->id)
            ->with(['appointment.prescriptions', 'appointment.doctor.user', 'prescription'])
            ->latest('record_date')
            ->latest()
            ->get();

        $appointments = Appointment::where('doctor_id', $doctor->id)
            ->with(['prescriptions', 'medicalRecords'])
            ->latest('preferred_date')
            ->get();

        return Inertia::render('Doctor/MedicalRecords', [
            'records' => $records,
            'appointments' => $appointments,
        ]);
    }

    public function store(Request $request, Appointment $appointment)
    {
        $doctor = Auth::user()->doctor;

        if (!$doctor || $appointment->doctor_id !== $doctor->id) {
            abort(403, 'Accès non autorisé');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'summary' => 'nullable|string',
            'record_date' => 'required|date',
            'prescription_id' => 'nullable|exists:prescriptions,id',
        ]);

        if (!empty($validated['prescription_id'])) {
            $belongsToAppointment = $appointment->prescriptions()
                ->whereKey($validated['prescription_id'])
                ->exists();

            if (!$belongsToAppointment) {
                abort(422, 'Ordonnance invalide pour ce rendez-vous.');
            }
        }

        $record = MedicalRecord::create([
            'appointment_id' => $appointment->id,
            'patient_email' => $appointment->email,
            'doctor_id' => $doctor->id,
            'prescription_id' => $validated['prescription_id'] ?? null,
            'title' => $validated['title'],
            'summary' => $validated['summary'] ?? null,
            'record_date' => $validated['record_date'],
        ]);

        return response()->json([
            'message' => 'Dossier médical enregistré avec succès.',
            'record' => $record->load(['appointment.prescriptions', 'prescription']),
        ], 201);
    }
}
