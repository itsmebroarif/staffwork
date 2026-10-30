<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\WaliMurid;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class GuardianController extends Controller
{
    /**
     * Display a listing of wali murid.
     */
    public function index(): View
    {
        $guardians = WaliMurid::with('student')->latest()->paginate(10);

        return view('pages.wali.index', [
            'guardians' => $guardians,
        ]);
    }

    /**
     * Show the form for creating a new wali murid.
     */
    public function create(): View
    {
        return view('pages.wali.create', [
            'students' => User::where('role', 'murid')->orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created wali murid in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);

        $student = User::where('id', $validated['student_id'])->where('role', 'murid')->first();
        if ($student === null) {
            return back()->withErrors(['student_id' => 'Pengguna yang dipilih bukan murid.'])->withInput();
        }

        WaliMurid::create($validated);

        return redirect()->route('wali.index')->with('success', "Wali murid '{$validated['name']}' berhasil ditambahkan!");
    }

    /**
     * Show the form for editing the specified wali murid.
     */
    public function edit(WaliMurid $wali): View
    {
        return view('pages.wali.edit', [
            'wali' => $wali,
            'students' => User::where('role', 'murid')->orderBy('name')->get(),
        ]);
    }

    /**
     * Update the specified wali murid in storage.
     */
    public function update(Request $request, WaliMurid $wali): RedirectResponse
    {
        $validated = $this->validated($request);

        $student = User::where('id', $validated['student_id'])->where('role', 'murid')->first();
        if ($student === null) {
            return back()->withErrors(['student_id' => 'Pengguna yang dipilih bukan murid.'])->withInput();
        }

        $wali->update($validated);

        return redirect()->route('wali.index')->with('success', "Wali murid '{$wali->name}' berhasil diperbarui!");
    }

    /**
     * Remove the specified wali murid from storage.
     */
    public function destroy(WaliMurid $wali): RedirectResponse
    {
        $wali->delete();

        return redirect()->route('wali.index')->with('success', 'Wali murid berhasil dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request): array
    {
        return $request->validate([
            'student_id' => ['required', 'exists:users,id'],
            'name' => ['required', 'string', 'max:255'],
            'hubungan' => ['required', 'string', 'in:Ayah,Ibu,Wali'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'string', 'email', 'max:255'],
            'alamat' => ['nullable', 'string'],
        ], [
            'hubungan.in' => 'Hubungan harus Ayah, Ibu, atau Wali.',
        ]);
    }
}
