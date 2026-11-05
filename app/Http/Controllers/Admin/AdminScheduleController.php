<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminScheduleController extends Controller
{
    public function index(Request $request)
    {
    	$q = Schedule::query()->with(['doctor.user:id,name,email'])->orderByRaw("FIELD(day_of_week, 'sat','sun','mon','tue','wed','thu','fri')")->orderBy('start_time');

    	if($s = $request->get('search'))
    	{
    		$q->whereHas('doctor.user', function($qq) use ($s){

    			$qq->where('name','like',"%$s%")->orWhere('email','like',"%$s%");
    		});
    	}

    	$dayLabels = [
    		'sat' => 'Samedi',
    		'sun' => 'Dimanche',
    		'mon' => 'Lundi',
    		'tue' => 'Mardi',
    		'wed' => 'Mercredi',
    		'thu' => 'Jeudi',
    		'fri' => 'Vendredi',
    		'saturday' => 'Samedi',
    		'sunday' => 'Dimanche',
    		'monday' => 'Lundi',
    		'tuesday' => 'Mardi',
    		'wednesday' => 'Mercredi',
    		'thursday' => 'Jeudi',
    		'friday' => 'Vendredi',
    	];

    	$formatDays = function ($days) use ($dayLabels) {
    		$days = is_array($days) ? $days : [$days];

    		return collect($days)
    			->filter(fn ($day) => filled($day))
    			->map(function ($day) use ($dayLabels) {
    				$normalizedDay = strtolower((string) $day);

    				return $dayLabels[$normalizedDay] ?? $day;
    			})
    			->implode(', ');
    	};

    	$formatTime = fn ($time) => $time ? Carbon::parse($time)->format('H\hi') : '';

    	$rows = $q->get()->map(fn($s) => [

    		'id' => $s->id,
    		'doctor_id' => $s->doctor_id,
    		'doctor' => $s->doctor?->user?->name,
    		'email' => $s->doctor?->user?->email,
    		'day' => $formatDays($s->day_of_week),
    		'time' => $formatTime($s->start_time) . ' - ' . $formatTime($s->end_time),
    		'slot' => $s->slot_minutes,
    		'max' => $s->max_patients_per_day,
    		'fee' => $s->fee,
    	]);

    	return response()->json($rows, 200);
    }

}
