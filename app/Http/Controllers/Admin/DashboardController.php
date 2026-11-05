<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\User;
use App\Models\Appointment;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $startOfLastMonth = $now->copy()->subMonth()->startOfMonth();
        $endOfLastMonth = $now->copy()->subMonth()->endOfMonth();

        // Helper function to calculate percentage change
        $calculatePercentage = function ($current, $lastMonth) {
            return $lastMonth > 0 ? round((($current - $lastMonth) / $lastMonth) * 100, 1) : 0;
        };

        // Doctors
        $doctorsCurrent = Doctor::count();
        $doctorsLastMonth = Doctor::whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->count();
        $doctorsPercentage = $calculatePercentage($doctorsCurrent, $doctorsLastMonth);

        // Patients
        $patientsCurrent = User::where('role', 'patient')->count();
        $patientsLastMonth = User::where('role', 'patient')->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->count();
        $patientsPercentage = $calculatePercentage($patientsCurrent, $patientsLastMonth);

        // Appointments today
        $appointmentsToday = Appointment::where('preferred_date', $now->toDateString())->count();

        // Recent Appointments
        $recentAppointments = Appointment::with('doctor.user')
            ->orderBy('preferred_date', 'desc')
            ->orderBy('preferred_time', 'desc')
            ->take(3)
            ->get()
            ->map(function ($appointment) {
                return [
                    'id' => $appointment->id,
                    'doctor_name' => $appointment->doctor_name,
                    'speciality' => $appointment->speciality,
                    'preferred_date' => $appointment->preferred_date,
                    'preferred_time' => $appointment->preferred_time,
                    'status' => $appointment->status,
                ];
            });

        $summaries = [
            'doctors' => $doctorsCurrent,
            'doctors_percentage' => $doctorsPercentage,
            'patients' => $patientsCurrent,
            'patients_percentage' => $patientsPercentage,
            'appointments' => Appointment::count(),
            'appointments_today' => $appointmentsToday,
            'pending_reviews' => 5, // Placeholder value, can be made dynamic based on actual logic
        ];

        return inertia('Admin/Dashboard', [
            'summaries' => $summaries,
            'recentAppointments' => $recentAppointments,
        ]);
    }
}
