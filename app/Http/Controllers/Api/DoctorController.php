<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Support\Str;

class DoctorController extends Controller
{
    public function index()
    {
        $doctors = Doctor::with('user')->latest()->get();

        $doctorsTransformed = $doctors->map(function ($item) {
            if ($item->user && $item->user->photo) {
                $item->user->photo = $this->mediaUrl($item->user->photo);
            }

            return $item;
        });

        return response()->json($doctorsTransformed);
    }

    private function mediaUrl(string $path): string
    {
        if (Str::startsWith($path, ['http://', 'https://', '/storage/', '/images/'])) {
            return $path;
        }

        $path = Str::after($path, 'public/');
        $path = Str::after($path, 'storage/');

        return asset('storage/' . ltrim($path, '/'));
    }
}
