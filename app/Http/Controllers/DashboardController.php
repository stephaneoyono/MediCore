<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(AdminDashboardController $adminDashboard)
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            return $adminDashboard->index();
        }

        if ($user->role === 'doctor') {
            return $this->doctor();
        }

        return Inertia::render('Patient/Dashboard');
    }

    public function doctor()
    {
        return Inertia::render('Doctor/Dashboard');
    }

    public function doctorSchedules()
    {
        return Inertia::render('Doctor/Schedules/Index');
    }
}
