<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\TeachingAssignment;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TeachingAssignmentController extends Controller
{
    /**
     * Display a listing of teaching assignments (kelas diajar).
     */
    public function index(): View
    {
        $assignments = TeachingAssignment::with(['teacher', 'mapel', 'kelas'])
            ->latest()
            ->paginate(10);

        return view('pages.penugasan.index', [
            'assignments' => $assignments,
        ]);
    }

    /**
     * Show the form for creating a new teaching assignment.
     */
    public function create(): View
    {
        return view('pages.penugasan.create', [
            'teachers' => User::where('role', 'guru')->orderBy('name')->get(),
            'mapels' => Mapel::orderBy('name')->get(),
            'kelasList' => Kelas::orderBy('tingkat')->orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created teaching assignment in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);

        $exists = TeachingAssignment::where('teacher_id', $validated['teacher_id'])
            ->where('mapel_id', $validated['mapel_id'])
            ->where('kelas_id', $validated['kelas_id'])
            ->exists();

        if ($exists) {
            return back()->withErrors(['teacher_id' => 'Penugasan ini sudah terdaftar.'])->withInput();
        }

        TeachingAssignment::create($validated);

        return redirect()->route('penugasan.index')->with('success', 'Penugasan kelas diajar berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified teaching assignment.
     */
    public function edit(TeachingAssignment $penugasan): View
    {
        return view('pages.penugasan.edit', [
            'assignment' => $penugasan,
            'teachers' => User::where('role', 'guru')->orderBy('name')->get(),
            'mapels' => Mapel::orderBy('name')->get(),
            'kelasList' => Kelas::orderBy('tingkat')->orderBy('name')->get(),
        ]);
    }

    /**
     * Update the specified teaching assignment in storage.
     */
    public function update(Request $request, TeachingAssignment $penugasan): RedirectResponse
    {
        $validated = $this->validated($request);

        $exists = TeachingAssignment::where('teacher_id', $validated['teacher_id'])
            ->where('mapel_id', $validated['mapel_id'])
            ->where('kelas_id', $validated['kelas_id'])
            ->where('id', '!=', $penugasan->id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['teacher_id' => 'Penugasan ini sudah terdaftar.'])->withInput();
        }

        $penugasan->update($validated);

        return redirect()->route('penugasan.index')->with('success', 'Penugasan kelas diajar berhasil diperbarui!');
    }

    /**
     * Remove the specified teaching assignment from storage.
     */
    public function destroy(TeachingAssignment $penugasan): RedirectResponse
    {
        $penugasan->delete();

        return redirect()->route('penugasan.index')->with('success', 'Penugasan kelas diajar berhasil dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request): array
    {
        $validated = $request->validate([
            'teacher_id' => ['required', 'exists:users,id'],
            'mapel_id' => ['required', 'exists:mapels,id'],
            'kelas_id' => ['required', 'exists:kelas,id'],
        ], [
            'teacher_id.required' => 'Guru wajib dipilih.',
            'mapel_id.required' => 'Mapel wajib dipilih.',
            'kelas_id.required' => 'Kelas wajib dipilih.',
        ]);

        $teacher = User::where('id', $validated['teacher_id'])->where('role', 'guru')->first();
        if ($teacher === null) {
            throw ValidationException::withMessages(['teacher_id' => 'Pengguna yang dipilih bukan guru.']);
        }

        return $validated;
    }
}
