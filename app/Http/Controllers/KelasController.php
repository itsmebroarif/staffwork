<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    /**
     * Display a listing of kelas.
     */
    public function index(): View
    {
        $kelas = Kelas::withCount('students')
            ->orderBy('tingkat')
            ->orderBy('name')
            ->paginate(10);

        return view('pages.kelas.index', [
            'kelas' => $kelas,
        ]);
    }

    /**
     * Show the form for creating a new kelas.
     */
    public function create(): View
    {
        return view('pages.kelas.create');
    }

    /**
     * Store a newly created kelas in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:kelas,name'],
            'tingkat' => ['required', 'integer', 'min:1', 'max:20'],
        ], [
            'name.unique' => 'Nama kelas sudah digunakan.',
        ]);

        Kelas::create($validated);

        return redirect()->route('kelas.index')->with('success', "Kelas '{$validated['name']}' berhasil ditambahkan!");
    }

    /**
     * Show the form for editing the specified kelas.
     */
    public function edit(Kelas $kelas): View
    {
        return view('pages.kelas.edit', [
            'kelas' => $kelas,
        ]);
    }

    /**
     * Update the specified kelas in storage.
     */
    public function update(Request $request, Kelas $kelas): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:kelas,name,'.$kelas->id],
            'tingkat' => ['required', 'integer', 'min:1', 'max:20'],
        ], [
            'name.unique' => 'Nama kelas sudah digunakan.',
        ]);

        $kelas->update($validated);

        return redirect()->route('kelas.index')->with('success', "Kelas '{$kelas->name}' berhasil diperbarui!");
    }

    /**
     * Remove the specified kelas from storage.
     */
    public function destroy(Kelas $kelas): RedirectResponse
    {
        $name = $kelas->name;
        $kelas->delete();

        return redirect()->route('kelas.index')->with('success', "Kelas '{$name}' berhasil dihapus.");
    }
}
