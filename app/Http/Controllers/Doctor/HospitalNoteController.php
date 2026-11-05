<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\HospitalNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class HospitalNoteController extends Controller
{
    public function index()
    {
        $doctor = Auth::user()->doctor;

        if (!$doctor) {
            abort(403, 'Accès non autorisé.');
        }

        return Inertia::render('Doctor/HospitalNotes/Index', [
            'notes' => HospitalNote::where('doctor_id', $doctor->id)
                ->latest('note_date')
                ->latest()
                ->get(),
            'author' => Auth::user()->name,
        ]);
    }

    public function store(Request $request)
    {
        $doctor = Auth::user()->doctor;

        if (!$doctor) {
            abort(403, 'Accès non autorisé.');
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'note_date' => ['required', 'date'],
        ]);

        $note = HospitalNote::create([
            'doctor_id' => $doctor->id,
            'title' => $validated['title'],
            'content' => $validated['content'],
            'note_date' => $validated['note_date'],
        ]);

        return response()->json([
            'message' => 'Note d\'hôpital créée avec succès.',
            'note' => $note,
        ], 201);
    }

    public function update(Request $request, HospitalNote $hospitalNote)
    {
        $this->authorizeNote($hospitalNote);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'note_date' => ['required', 'date'],
        ]);

        $hospitalNote->update($validated);

        return response()->json([
            'message' => 'Note d\'hôpital modifiée avec succès.',
            'note' => $hospitalNote->refresh(),
        ]);
    }

    public function destroy(HospitalNote $hospitalNote)
    {
        $this->authorizeNote($hospitalNote);
        $hospitalNote->delete();

        return response()->json([
            'message' => 'Note d\'hôpital supprimée avec succès.',
        ]);
    }

    private function authorizeNote(HospitalNote $hospitalNote): void
    {
        $doctor = Auth::user()->doctor;

        if (!$doctor || $hospitalNote->doctor_id !== $doctor->id) {
            abort(403, 'Accès non autorisé.');
        }
    }
}
