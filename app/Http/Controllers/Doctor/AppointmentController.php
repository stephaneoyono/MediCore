<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use App\Models\Prescription;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\PrescriptionMail;
use Barryvdh\DomPDF\Facade\Pdf;

class AppointmentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $doctor = $user->doctor; // Assuming user has doctor relationship

        if (!$doctor) {
            abort(403, 'Accès non autorisé');
        }

        $appointments = Appointment::where('doctor_id', $doctor->id)
            ->with('doctor.user')
            ->latest()
            ->get();

        return Inertia::render('Doctor/Appointments/Index', [
            'appointments' => $appointments
        ]);
    }

    public function show(Appointment $appointment)
    {
        $user = Auth::user();
        $doctor = $user->doctor;

        if (!$doctor || $appointment->doctor_id !== $doctor->id) {
            abort(403, 'Accès non autorisé');
        }

        return Inertia::render('Doctor/Appointments/Show', [
            'appointment' => $appointment->load('doctor.user', 'prescriptions')
        ]);
    }

    public function update(Request $request, Appointment $appointment)
    {
        $user = Auth::user();
        $doctor = $user->doctor;

        if (!$doctor || $appointment->doctor_id !== $doctor->id) {
            abort(403, 'Accès non autorisé');
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled',
        ]);

        $appointment->update($validated);

        return response()->json([
            'message' => 'Rendez-vous modifié avec succès !',
            'appointment' => $appointment
        ]);
    }

    public function storePrescription(Request $request, Appointment $appointment)
    {
        $user = Auth::user();
        $doctor = $user->doctor;

        if (!$doctor || $appointment->doctor_id !== $doctor->id) {
            abort(403, 'Accès non autorisé');
        }

        $validated = $request->validate([
            'prescription_text' => 'required|string',
        ]);

        [$prescription, $medicalRecord] = DB::transaction(function () use ($appointment, $doctor, $validated) {
            $prescription = Prescription::create([
                'appointment_id' => $appointment->id,
                'prescription_text' => $validated['prescription_text'],
            ]);

            $medicalRecord = MedicalRecord::create([
                'appointment_id' => $appointment->id,
                'patient_email' => $appointment->email,
                'doctor_id' => $doctor->id,
                'prescription_id' => $prescription->id,
                'title' => 'Ordonnance - ' . $appointment->booking_id,
                'summary' => $validated['prescription_text'],
                'record_date' => now()->toDateString(),
            ]);

            return [$prescription, $medicalRecord];
        });

        // Generate PDF
        $appointment->load('doctor.user');
        $pdf = Pdf::loadView('pdfs.prescription', compact('appointment', 'prescription'));

        // Send email with PDF attachment
        try {
            Mail::to($appointment->email)->send(new PrescriptionMail($appointment, $prescription, $pdf));
        } catch (\Exception $e) {
            Log::warning("Échec de l'envoi de l'e-mail d'ordonnance : " . $e->getMessage(), [
                'appointment_id' => $appointment->id,
                'prescription_id' => $prescription->id,
            ]);
        }

        return response()->json([
            'message' => 'Ordonnance ajoutée avec succès ! Un PDF a été envoyé au patient.',
            'prescription' => $prescription,
            'medical_record' => $medicalRecord,
        ], 201);
    }

    public function downloadPrescriptionPdf(Appointment $appointment, Prescription $prescription)
    {
        $user = Auth::user();
        $doctor = $user->doctor;

        if (!$doctor || $appointment->doctor_id !== $doctor->id || $prescription->appointment_id !== $appointment->id) {
            abort(403, 'Accès non autorisé');
        }

        // Load necessary relationships
        $appointment->load('doctor.user');

        // Generate PDF
        $pdf = Pdf::loadView('pdfs.prescription', compact('appointment', 'prescription'));

        return $pdf->download('prescription_' . $appointment->booking_id . '.pdf');
    }
}
