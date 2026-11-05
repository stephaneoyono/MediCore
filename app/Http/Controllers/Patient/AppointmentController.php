<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Prescription;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Barryvdh\DomPDF\Facade\Pdf;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $appointments = Appointment::where('email', $user->email)
            ->with(['doctor.user', 'prescriptions'])
            ->latest()
            ->get();

        return Inertia::render('Patient/Appointments/Index', [
            'appointments' => $appointments
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment)
    {
        // Ensure user can only view their own appointments
        if ($appointment->email !== auth()->user()->email) {
            abort(403);
        }

        return Inertia::render('Patient/Appointments/Show', [
            'appointment' => $appointment->load(['doctor.user', 'prescriptions', 'medicalRecords.prescription'])
        ]);
    }

    /**
     * Download PDF for the appointment.
     */
    public function downloadPdf(Appointment $appointment)
    {
        // Ensure user can only download their own appointment PDFs
        if ($appointment->email !== auth()->user()->email) {
            abort(403);
        }

        $pdf = Pdf::loadView('pdfs.appointment_details', ['appointment' => $appointment]);

        return $pdf->download('appointment_' . $appointment->booking_id . '.pdf');
    }

    public function downloadPrescriptionPdf(Appointment $appointment, Prescription $prescription)
    {
        if ($appointment->email !== auth()->user()->email || $prescription->appointment_id !== $appointment->id) {
            abort(403);
        }

        $appointment->load('doctor.user');
        $pdf = Pdf::loadView('pdfs.prescription', compact('appointment', 'prescription'));

        return $pdf->download('prescription_' . $appointment->booking_id . '_' . $prescription->id . '.pdf');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Appointment $appointment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Appointment $appointment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
        //
    }
}
