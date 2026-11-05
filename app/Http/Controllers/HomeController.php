<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function welcome()
    {
        return Inertia::render('Welcome', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'laravelVersion' => app()->version(),
            'phpVersion' => PHP_VERSION,
        ]);
    }

    public function findDoctor()
    {
        return Inertia::render('FindDoctor', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'doctors' => Doctor::with('user')->get(),
        ]);
    }

    public function doctorDetail($id)
    {
        return Inertia::render('DoctorDetail', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'doctor' => Doctor::with('user')->findOrFail($id),
        ]);
    }
}
