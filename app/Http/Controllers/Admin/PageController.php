<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class PageController extends Controller
{
    public function doctors()
    {
        return Inertia::render('Admin/Doctors/Index');
    }

    public function schedules()
    {
        return Inertia::render('Admin/Schedules/Index');
    }
}
