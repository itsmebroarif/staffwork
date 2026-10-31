<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\TeachingAssignment;
use App\Models\TeachingSchedule;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class TeachingScheduleController extends Controller
{
    private const DAYS = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'];

    /**
     * Display a listing of teaching schedules (jadwal pengajaran).
     */
    public function index(): View
    {
        $query = TeachingSchedule::with('assignment.teacher', 'assignment.mapel', 'assignment.kelas')
            ->orderByRaw("FIELD(day, 'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu')")
            ->orderBy('start_time');

        return view('pages.jadwal.index', [
            'schedules' => (clone $query)->paginate(15),
            'calendarSchedules' => $query->get(),
        ]);
    }

    /**
     * Show the form for creating a new teaching schedule.
     */
    public function create(): View
    {
        return view('pages.jadwal.create', [
            'assignments' => TeachingAssignment::with(['teacher', 'mapel', 'kelas'])->latest()->get(),
        ]);
    }

    /**
     * Store a newly created teaching schedule in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);

        $schedule = TeachingSchedule::create($validated);

        ActivityLog::record('schedule.created', "Jadwal {$schedule->day_name} pukul {$schedule->start_time} ditambahkan", $schedule);

        return redirect()->route('jadwal.index')->with('success', 'Jadwal pengajaran berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified teaching schedule.
     */
    public function edit(TeachingSchedule $jadwal): View
    {
        return view('pages.jadwal.edit', [
            'schedule' => $jadwal,
            'assignments' => TeachingAssignment::with(['teacher', 'mapel', 'kelas'])->latest()->get(),
        ]);
    }

    /**
     * Update the specified teaching schedule in storage.
     */
    public function update(Request $request, TeachingSchedule $jadwal): RedirectResponse
    {
        $validated = $this->validated($request, $jadwal);

        $jadwal->update($validated);

        ActivityLog::record('schedule.updated', "Jadwal {$jadwal->day_name} pukul {$jadwal->start_time} diperbarui", $jadwal);

        return redirect()->route('jadwal.index')->with('success', 'Jadwal pengajaran berhasil diperbarui!');
    }

    /**
     * Remove the specified teaching schedule from storage.
     */
    public function destroy(TeachingSchedule $jadwal): RedirectResponse
    {
        ActivityLog::record('schedule.deleted', "Jadwal {$jadwal->day_name} pukul {$jadwal->start_time} dihapus", null);

        $jadwal->delete();

        return redirect()->route('jadwal.index')->with('success', 'Jadwal pengajaran berhasil dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?TeachingSchedule $schedule = null): array
    {
        $uniqueRule = ['assignment_id', 'day', 'start_time'];
        if ($schedule !== null) {
            $uniqueRule[] = $schedule->id;
        }

        $validated = $request->validate([
            'assignment_id' => ['required', 'exists:teaching_assignments,id'],
            'day' => ['required', 'string', Rule::in(self::DAYS)],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
        ], [
            'day.in' => 'Hari harus salah satu dari: Senin, Selasa, Rabu, Kamis, Jumat, Sabtu.',
            'start_time.date_format' => 'Jam mulai harus berformat HH:MM.',
            'end_time.date_format' => 'Jam selesai harus berformat HH:MM.',
            'end_time.after' => 'Jam selesai harus setelah jam mulai.',
        ]);

        $exists = TeachingSchedule::where('assignment_id', $validated['assignment_id'])
            ->where('day', $validated['day'])
            ->where('start_time', $validated['start_time'])
            ->when($schedule !== null, fn ($query) => $query->where('id', '!=', $schedule->id))
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'start_time' => 'Jadwal untuk kelas ini di hari dan jam tersebut sudah ada.',
            ]);
        }

        return $validated;
    }
}
