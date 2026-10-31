<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Exam;
use App\Models\Question;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ExamController extends Controller
{
    /**
     * Display a listing of exams (tugas & ujian).
     */
    public function index(Request $request): View
    {
        $query = Exam::withCount('questions')->with('author')->latest();

        if ($request->filled('type') && in_array($request->string('type'), ['tugas', 'ujian'], true)) {
            $query->where('type', $request->string('type'));
        }

        if ($request->filled('q')) {
            $query->where('title', 'like', '%'.$request->string('q').'%');
        }

        $exams = $query->paginate(10)->withQueryString();

        return view('pages.exams.index', [
            'exams' => $exams,
        ]);
    }

    /**
     * Show the form for creating a new exam with questions.
     */
    public function create(): View
    {
        return view('pages.exams.create');
    }

    /**
     * Store a newly created exam with its multiple-choice questions.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'type' => ['required', Rule::in(['tugas', 'ujian'])],
            'duration_minutes' => ['nullable', 'integer', 'min:1', 'max:600'],
            'available_at' => ['nullable', 'date'],
            'questions' => ['required', 'array', 'min:1'],
            'questions.*.question' => ['required', 'string'],
            'questions.*.option_a' => ['required', 'string', 'max:500'],
            'questions.*.option_b' => ['required', 'string', 'max:500'],
            'questions.*.option_c' => ['required', 'string', 'max:500'],
            'questions.*.option_d' => ['required', 'string', 'max:500'],
            'questions.*.correct_answer' => ['required', Rule::in(['A', 'B', 'C', 'D'])],
        ], [
            'questions.min' => 'Minimal harus ada 1 soal.',
            'questions.*.correct_answer.in' => 'Jawaban benar harus salah satu dari A, B, C, atau D.',
        ]);

        $exam = DB::transaction(function () use ($request, $validated) {
            $exam = Exam::create([
                'user_id' => $request->user()->id,
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'type' => $validated['type'],
                'duration_minutes' => $validated['duration_minutes'] ?? null,
                'available_at' => $validated['available_at'] ?? now(),
            ]);

            foreach ($validated['questions'] as $index => $question) {
                Question::create([
                    'exam_id' => $exam->id,
                    'question' => $question['question'],
                    'option_a' => $question['option_a'],
                    'option_b' => $question['option_b'],
                    'option_c' => $question['option_c'],
                    'option_d' => $question['option_d'],
                    'correct_answer' => $question['correct_answer'],
                    'order' => $index + 1,
                ]);
            }

            return $exam;
        });

        ActivityLog::record('exam.created', "{$exam->type_label} '{$exam->title}' dibuat dengan {$exam->questions()->count()} soal", $exam);

        return redirect()->route('exams.index')->with('success', 'Tugas/Ujian beserta soal berhasil disimpan!');
    }

    /**
     * Display the specified exam with questions.
     */
    public function show(Exam $exam): View
    {
        $exam->load(['author', 'questions']);

        return view('pages.exams.show', [
            'exam' => $exam,
        ]);
    }

    /**
     * Show the form for editing the specified exam.
     */
    public function edit(Exam $exam): View
    {
        $exam->load('questions');

        return view('pages.exams.edit', [
            'exam' => $exam,
        ]);
    }

    /**
     * Update the specified exam and its questions.
     */
    public function update(Request $request, Exam $exam): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'type' => ['required', Rule::in(['tugas', 'ujian'])],
            'duration_minutes' => ['nullable', 'integer', 'min:1', 'max:600'],
            'available_at' => ['nullable', 'date'],
            'questions' => ['required', 'array', 'min:1'],
            'questions.*.question' => ['required', 'string'],
            'questions.*.option_a' => ['required', 'string', 'max:500'],
            'questions.*.option_b' => ['required', 'string', 'max:500'],
            'questions.*.option_c' => ['required', 'string', 'max:500'],
            'questions.*.option_d' => ['required', 'string', 'max:500'],
            'questions.*.correct_answer' => ['required', Rule::in(['A', 'B', 'C', 'D'])],
        ], [
            'questions.min' => 'Minimal harus ada 1 soal.',
            'questions.*.correct_answer.in' => 'Jawaban benar harus salah satu dari A, B, C, atau D.',
        ]);

        DB::transaction(function () use ($exam, $validated) {
            $exam->update([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'type' => $validated['type'],
                'duration_minutes' => $validated['duration_minutes'] ?? null,
                'available_at' => $validated['available_at'] ?? $exam->available_at,
            ]);

            $exam->questions()->delete();

            foreach ($validated['questions'] as $index => $question) {
                Question::create([
                    'exam_id' => $exam->id,
                    'question' => $question['question'],
                    'option_a' => $question['option_a'],
                    'option_b' => $question['option_b'],
                    'option_c' => $question['option_c'],
                    'option_d' => $question['option_d'],
                    'correct_answer' => $question['correct_answer'],
                    'order' => $index + 1,
                ]);
            }
        });

        ActivityLog::record('exam.updated', "{$exam->type_label} '{$exam->title}' diperbarui", $exam);

        return redirect()->route('exams.index')->with('success', 'Tugas/Ujian berhasil diperbarui!');
    }

    /**
     * Remove the specified exam from storage.
     */
    public function destroy(Exam $exam): RedirectResponse
    {
        ActivityLog::record('exam.deleted', "{$exam->type_label} '{$exam->title}' dihapus", null);

        $exam->delete();

        return redirect()->route('exams.index')->with('success', 'Tugas/Ujian berhasil dihapus.');
    }
}
