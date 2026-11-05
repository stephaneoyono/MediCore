<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\DoctorWelcomeMail;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        $rows = Doctor::with(['user:id,name,email,photo'])
            ->orderByDesc('id')
            ->get()
            ->map(fn ($doctor) => [
                'id' => $doctor->id,
                'user_id' => $doctor->user_id,
                'name' => $doctor->user?->name,
                'email' => $doctor->user?->email,
                'photo' => $doctor->user?->photo ? asset('storage/' . $doctor->user->photo) : null,
                'designation' => $doctor->designation,
                'speciality' => $doctor->speciality,
                'phone' => $doctor->phone,
                'about' => $doctor->about,
            ]);

        return response()->json($rows);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', Password::min(8)],
            'designation' => ['nullable', 'string', 'max:255'],
            'speciality' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'about' => ['nullable', 'string', 'max:2000'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $plainPassword = $data['password'];

        [$user, $doctor] = DB::transaction(function () use ($data) {
            $photoPath = isset($data['photo'])
                ? $data['photo']->store('doctors', 'public')
                : null;

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => 'doctor',
                'photo' => $photoPath,
                'email_verified_at' => now(),
            ]);

            $doctor = Doctor::create([
                'user_id' => $user->id,
                'designation' => $data['designation'] ?? null,
                'speciality' => $data['speciality'] ?? null,
                'phone' => $data['phone'] ?? null,
                'about' => $data['about'] ?? null,
            ]);

            return [$user, $doctor];
        });

        Mail::to($user->email)->send(new DoctorWelcomeMail(
            $user->name,
            $user->email,
            $plainPassword
        ));

        return response()->json([
            'message' => 'Médecin créé avec succès.',
            'doctor' => $this->doctorPayload($doctor->fresh('user')),
        ], 201);
    }

    public function update(Request $request, Doctor $doctor)
    {
        $user = $doctor->user;

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user?->id)],
            'password' => ['nullable', Password::min(8)],
            'designation' => ['nullable', 'string', 'max:255'],
            'speciality' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'about' => ['nullable', 'string', 'max:2000'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        DB::transaction(function () use ($data, $user, $doctor) {
            $user->name = $data['name'];
            $user->email = $data['email'];

            if (!empty($data['password'])) {
                $user->password = Hash::make($data['password']);
            }

            if (isset($data['photo'])) {
                if ($user->photo) {
                    Storage::disk('public')->delete($user->photo);
                }

                $user->photo = $data['photo']->store('doctors', 'public');
            }

            $user->save();

            $doctor->update([
                'designation' => $data['designation'] ?? null,
                'speciality' => $data['speciality'] ?? null,
                'phone' => $data['phone'] ?? null,
                'about' => $data['about'] ?? null,
            ]);
        });

        return response()->json([
            'message' => 'Médecin modifié avec succès.',
            'doctor' => $this->doctorPayload($doctor->fresh('user')),
        ]);
    }

    public function destroy(Doctor $doctor)
    {
        DB::transaction(function () use ($doctor) {
            $user = $doctor->user;
            $doctor->delete();

            if ($user) {
                if ($user->photo) {
                    Storage::disk('public')->delete($user->photo);
                }

                $user->delete();
            }
        });

        return response()->noContent();
    }

    private function doctorPayload(Doctor $doctor): array
    {
        return [
            'id' => $doctor->id,
            'user_id' => $doctor->user_id,
            'name' => $doctor->user?->name,
            'email' => $doctor->user?->email,
            'photo' => $doctor->user?->photo ? asset('storage/' . $doctor->user->photo) : null,
            'designation' => $doctor->designation,
            'speciality' => $doctor->speciality,
            'phone' => $doctor->phone,
            'about' => $doctor->about,
        ];
    }
}
