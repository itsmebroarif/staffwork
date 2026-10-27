<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MateriController extends Controller
{
    /**
     * Display a listing of materials (blog-style).
     */
    public function index(Request $request): View
    {
        $query = Material::with('author')->latest();

        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%'.$request->string('q').'%')
                    ->orWhere('body', 'like', '%'.$request->string('q').'%');
            });
        }

        $materials = $query->paginate(9)->withQueryString();

        return view('pages.materis.index', [
            'materials' => $materials,
        ]);
    }

    /**
     * Show the form for creating a new material.
     */
    public function create(): View
    {
        return view('pages.materis.create');
    }

    /**
     * Store a newly created material in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'file' => ['nullable', 'file', 'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,rar,txt', 'max:10240'],
            'is_published' => ['nullable', 'boolean'],
        ], [
            'file.mimes' => 'Format file tidak didukung.',
            'file.max' => 'Ukuran file maksimal 10MB.',
        ]);

        $filePath = null;
        $fileName = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('materials', 'public');
            $fileName = $request->file('file')->getClientOriginalName();
        }

        $slug = Str::slug($validated['title']);
        if (Material::where('slug', $slug)->exists()) {
            $slug .= '-'.Str::random(5);
        }

        Material::create([
            'user_id' => $request->user()->id,
            'title' => $validated['title'],
            'slug' => $slug,
            'body' => $validated['body'],
            'file_path' => $filePath,
            'file_name' => $fileName,
            'is_published' => $request->boolean('is_published', true),
        ]);

        return redirect()->route('materis.index')->with('success', 'Materi berhasil dipublikasikan!');
    }

    /**
     * Display the specified material.
     */
    public function show(Material $materi): View
    {
        $materi->load('author');

        return view('pages.materis.show', [
            'material' => $materi,
        ]);
    }

    /**
     * Show the form for editing the specified material.
     */
    public function edit(Material $materi): View
    {
        return view('pages.materis.edit', [
            'material' => $materi,
        ]);
    }

    /**
     * Update the specified material in storage.
     */
    public function update(Request $request, Material $materi): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'file' => ['nullable', 'file', 'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,rar,txt', 'max:10240'],
            'is_published' => ['nullable', 'boolean'],
        ], [
            'file.mimes' => 'Format file tidak didukung.',
            'file.max' => 'Ukuran file maksimal 10MB.',
        ]);

        $filePath = $materi->file_path;
        $fileName = $materi->file_name;
        if ($request->hasFile('file')) {
            if ($materi->file_path && Storage::disk('public')->exists($materi->file_path)) {
                Storage::disk('public')->delete($materi->file_path);
            }
            $filePath = $request->file('file')->store('materials', 'public');
            $fileName = $request->file('file')->getClientOriginalName();
        }

        $slug = Str::slug($validated['title']);
        if (Material::where('slug', $slug)->where('id', '!=', $materi->id)->exists()) {
            $slug = $materi->slug;
        }

        $materi->update([
            'title' => $validated['title'],
            'slug' => $slug,
            'body' => $validated['body'],
            'file_path' => $filePath,
            'file_name' => $fileName,
            'is_published' => $request->boolean('is_published', $materi->is_published),
        ]);

        return redirect()->route('materis.index')->with('success', 'Materi berhasil diperbarui!');
    }

    /**
     * Remove the specified material from storage.
     */
    public function destroy(Material $materi): RedirectResponse
    {
        if ($materi->file_path && Storage::disk('public')->exists($materi->file_path)) {
            Storage::disk('public')->delete($materi->file_path);
        }

        $materi->delete();

        return redirect()->route('materis.index')->with('success', 'Materi berhasil dihapus.');
    }
}
