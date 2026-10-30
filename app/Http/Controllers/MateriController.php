<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
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
        $query = Material::with(['author', 'kelas'])->latest();

        $user = $request->user();
        if ($user->isStudent()) {
            $query->where('is_published', true)
                ->where(function ($q) use ($user) {
                    $q->whereNull('kelas_id');

                    if ($user->kelas_id !== null && $user->kelas !== null) {
                        $q->orWhereHas('kelas', fn ($k) => $k->where('tingkat', $user->kelas->tingkat));
                    }
                });
        }

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
        return view('pages.materis.create', [
            'kelasList' => Kelas::orderBy('tingkat')->orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created material in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);

        $filePath = null;
        $fileName = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('materials', 'public');
            $fileName = $request->file('file')->getClientOriginalName();
        }

        Material::create([
            'user_id' => $request->user()->id,
            'title' => $validated['title'],
            'slug' => $this->uniqueSlug($validated['title']),
            'type' => $validated['type'],
            'kelas_id' => $validated['kelas_id'] ?? null,
            'body' => $validated['body'] ?? null,
            'file_path' => $filePath,
            'file_name' => $fileName,
            'is_published' => $request->boolean('is_published', true),
        ]);

        return redirect()->route('materis.index')->with('success', 'Materi berhasil dipublikasikan!');
    }

    /**
     * Display the specified material.
     */
    public function show(Request $request, Material $materi): View
    {
        if ($request->user()->isStudent() && ! $materi->isAccessibleBy($request->user())) {
            abort(403, 'Anda tidak memiliki akses ke materi ini.');
        }

        $materi->load(['author', 'kelas']);

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
            'kelasList' => Kelas::orderBy('tingkat')->orderBy('name')->get(),
        ]);
    }

    /**
     * Update the specified material in storage.
     */
    public function update(Request $request, Material $materi): RedirectResponse
    {
        $validated = $this->validated($request, $materi);

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
            'type' => $validated['type'],
            'kelas_id' => $validated['kelas_id'] ?? null,
            'body' => $validated['body'] ?? null,
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

    /**
     * Validate material payload for both tab modes (artikel or file upload).
     *
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?Material $material = null): array
    {
        $request->merge(['type' => $request->input('type', 'artikel')]);

        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'in:artikel,file'],
            'body' => ['required_if:type,artikel', 'nullable', 'string'],
            'file' => ['nullable', 'file', 'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,rar,txt', 'max:10240'],
            'kelas_id' => ['nullable', 'exists:kelas,id'],
            'is_published' => ['nullable', 'boolean'],
        ];

        // A file-type material may keep its existing file when editing.
        if ($material === null || $material->file_path === null) {
            $rules['file'][] = 'required_if:type,file';
        }

        return $request->validate($rules, [
            'body.required_if' => 'Tulis isi materi pada tab artikel.',
            'file.required_if' => 'Upload file PDF/PPT pada tab upload file.',
            'file.mimes' => 'Format file tidak didukung.',
            'file.max' => 'Ukuran file maksimal 10MB.',
        ]);
    }

    private function uniqueSlug(string $title): string
    {
        $slug = Str::slug($title);

        if (Material::where('slug', $slug)->exists()) {
            $slug .= '-'.Str::random(5);
        }

        return $slug;
    }
}
