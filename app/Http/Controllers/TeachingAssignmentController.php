<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
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
    public function index(Request $request): View
    {
        $query = TeachingAssignment::with(['teacher', 'mapel', 'kelas'])
            ->join('users', 'users.id', '=', 'teaching_assignments.teacher_id')
            ->join('mapels', 'mapels.id', '=', 'teaching_assignments.mapel_id')
            ->join('kelas', 'kelas.id', '=', 'teaching_assignments.kelas_id')
            ->select('teaching_assignments.*')
            ->orderBy('users.name')
            ->orderBy('mapels.name')
            ->orderBy('kelas.name');

        if ($request->filled('q')) {
            $query->where('users.name', 'like', '%'.$request->string('q').'%');
        }

        if ($request->filled('kelas')) {
            $query->where('teaching_assignments.kelas_id', $request->integer('kelas'));
        }

        return view('pages.penugasan.index', [
            'assignments' => $query->paginate(15)->withQueryString(),
            'kelasList' => Kelas::orderBy('tingkat')->orderBy('name')->get(),
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

        $assignment = TeachingAssignment::create($validated);

        ActivityLog::record('assignment.created', "Penugasan {$assignment->teacher?->name} - {$assignment->mapel?->name} ({$assignment->kelas?->name}) ditambahkan", $assignment);

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

        ActivityLog::record('assignment.updated', "Penugasan {$penugasan->teacher?->name} - {$penugasan->mapel?->name} ({$penugasan->kelas?->name}) diperbarui", $penugasan);

        return redirect()->route('penugasan.index')->with('success', 'Penugasan kelas diajar berhasil diperbarui!');
    }

    /**
     * Remove the specified teaching assignment from storage.
     */
    public function destroy(TeachingAssignment $penugasan): RedirectResponse
    {
        ActivityLog::record('assignment.deleted', 'Penugasan kelas diajar dihapus', null);

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
