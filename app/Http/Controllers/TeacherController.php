<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class TeacherController extends Controller
{
    /**
     * Display a listing of guru accounts.
     */
    public function index(Request $request): View
    {
        $query = User::where('role', 'guru')->latest();

        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->string('q').'%')
                    ->orWhere('nip', 'like', '%'.$request->string('q').'%');
            });
        }

        $teachers = $query->paginate(10)->withQueryString();

        return view('pages.guru.index', [
            'teachers' => $teachers,
        ]);
    }

    /**
     * Show the form for creating a new guru account.
     */
    public function create(): View
    {
        return view('pages.guru.create');
    }

    /**
     * Store a newly created guru account in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'nip' => ['nullable', 'string', 'max:50', 'unique:users,nip'],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['required', 'string', Password::min(6)],
        ], [
            'nip.unique' => 'NIP sudah terdaftar.',
        ]);

        $createdGuru = User::create([
            ...$validated,
            'role' => 'guru',
            'password' => Hash::make($validated['password']),
            'password_plain' => $validated['password'],
        ]);

        ActivityLog::record('user.created', "Akun guru '{$createdGuru->name}' didaftarkan", $createdGuru);

        return redirect()->route('guru.index')->with('success', "Akun guru '{$validated['name']}' berhasil didaftarkan!");
    }

    /**
     * Show the form for editing the specified guru.
     */
    public function edit(User $guru): View
    {
        return view('pages.guru.edit', [
            'guru' => $guru,
        ]);
    }

    /**
     * Update the specified guru in storage.
     */
    public function update(Request $request, User $guru): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($guru->id)],
            'nip' => ['nullable', 'string', 'max:50', Rule::unique('users', 'nip')->ignore($guru->id)],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['nullable', 'string', Password::min(6)],
        ], [
            'nip.unique' => 'NIP sudah terdaftar.',
        ]);

        $guru->name = $validated['name'];
        $guru->email = $validated['email'];
        $guru->nip = $validated['nip'];
        $guru->phone = $validated['phone'];

        if (! empty($validated['password'])) {
            $guru->password = Hash::make($validated['password']);
            $guru->password_plain = $validated['password'];
        }

        $guru->save();

        ActivityLog::record('user.updated', "Akun guru '{$guru->name}' diperbarui", $guru);

        return redirect()->route('guru.index')->with('success', "Data guru '{$guru->name}' berhasil diperbarui!");
    }

    /**
     * Remove the specified guru from storage.
     */
    public function destroy(Request $request, User $guru): RedirectResponse
    {
        if ($request->user()->id === $guru->id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri saat sedang login!');
        }

        ActivityLog::record('user.deleted', "Akun guru '{$guru->name}' dihapus", null);

        $guru->delete();

        return redirect()->route('guru.index')->with('success', 'Akun guru berhasil dihapus.');
    }
}
