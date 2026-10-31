<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Mapel;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MapelController extends Controller
{
    /**
     * Display a listing of mapel.
     */
    public function index(): View
    {
        $mapels = Mapel::orderBy('name')->paginate(10);

        return view('pages.mapels.index', [
            'mapels' => $mapels,
        ]);
    }

    /**
     * Show the form for creating a new mapel.
     */
    public function create(): View
    {
        return view('pages.mapels.create');
    }

    /**
     * Store a newly created mapel in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:mapels,code'],
            'name' => ['required', 'string', 'max:255', 'unique:mapels,name'],
        ], [
            'code.unique' => 'Kode mapel sudah digunakan.',
            'name.unique' => 'Nama mapel sudah digunakan.',
        ]);

        $mapel = Mapel::create($validated);

        ActivityLog::record('mapel.created', "Mapel '{$mapel->name}' ditambahkan", $mapel);

        return redirect()->route('mapel.index')->with('success', "Mapel '{$validated['name']}' berhasil ditambahkan!");
    }

    /**
     * Show the form for editing the specified mapel.
     */
    public function edit(Mapel $mapel): View
    {
        return view('pages.mapels.edit', [
            'mapel' => $mapel,
        ]);
    }

    /**
     * Update the specified mapel in storage.
     */
    public function update(Request $request, Mapel $mapel): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:mapels,code,'.$mapel->id],
            'name' => ['required', 'string', 'max:255', 'unique:mapels,name,'.$mapel->id],
        ], [
            'code.unique' => 'Kode mapel sudah digunakan.',
            'name.unique' => 'Nama mapel sudah digunakan.',
        ]);

        $mapel->update($validated);

        ActivityLog::record('mapel.updated', "Mapel '{$mapel->name}' diperbarui", $mapel);

        return redirect()->route('mapel.index')->with('success', "Mapel '{$mapel->name}' berhasil diperbarui!");
    }

    /**
     * Remove the specified mapel from storage.
     */
    public function destroy(Mapel $mapel): RedirectResponse
    {
        $name = $mapel->name;
        ActivityLog::record('mapel.deleted', "Mapel '{$mapel->name}' dihapus", null);

        $mapel->delete();

        return redirect()->route('mapel.index')->with('success', "Mapel '{$name}' berhasil dihapus.");
    }
}
