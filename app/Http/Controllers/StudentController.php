<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class StudentController extends Controller
{
    /**
     * Display a listing of murid accounts.
     */
    public function index(Request $request): View
    {
        $query = User::where('role', 'murid')->with('kelas')->latest();

        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->string('q').'%')
                    ->orWhere('nis', 'like', '%'.$request->string('q').'%');
            });
        }

        $students = $query->paginate(10)->withQueryString();

        return view('pages.siswa.index', [
            'students' => $students,
        ]);
    }

    /**
     * Show the form for creating a new murid account.
     */
    public function create(): View
    {
        return view('pages.siswa.create', [
            'kelasList' => Kelas::orderBy('tingkat')->orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created murid account in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'nis' => ['nullable', 'string', 'max:50', 'unique:users,nis'],
            'phone' => ['nullable', 'string', 'max:30'],
            'kelas_id' => ['required', 'exists:kelas,id'],
            'password' => ['required', 'string', Password::min(6)],
        ], [
            'nis.unique' => 'NIS sudah terdaftar.',
            'kelas_id.required' => 'Kelas wajib dipilih.',
        ]);

        $createdStudent = User::create([
            ...$validated,
            'role' => 'murid',
            'password' => Hash::make($validated['password']),
            'password_plain' => $validated['password'],
        ]);

        ActivityLog::record('user.created', "Akun murid '{$createdStudent->name}' didaftarkan", $createdStudent);

        return redirect()->route('siswa.index')->with('success', "Akun murid '{$validated['name']}' berhasil didaftarkan!");
    }

    /**
     * Show the form for editing the specified murid.
     */
    public function edit(User $siswa): View
    {
        return view('pages.siswa.edit', [
            'siswa' => $siswa,
            'kelasList' => Kelas::orderBy('tingkat')->orderBy('name')->get(),
        ]);
    }

    /**
     * Update the specified murid in storage.
     */
    public function update(Request $request, User $siswa): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($siswa->id)],
            'nis' => ['nullable', 'string', 'max:50', Rule::unique('users', 'nis')->ignore($siswa->id)],
            'phone' => ['nullable', 'string', 'max:30'],
            'kelas_id' => ['required', 'exists:kelas,id'],
            'password' => ['nullable', 'string', Password::min(6)],
        ], [
            'nis.unique' => 'NIS sudah terdaftar.',
            'kelas_id.required' => 'Kelas wajib dipilih.',
        ]);

        $siswa->name = $validated['name'];
        $siswa->email = $validated['email'];
        $siswa->nis = $validated['nis'];
        $siswa->phone = $validated['phone'];
        $siswa->kelas_id = $validated['kelas_id'];

        if (! empty($validated['password'])) {
            $siswa->password = Hash::make($validated['password']);
            $siswa->password_plain = $validated['password'];
        }

        $siswa->save();

        ActivityLog::record('user.updated', "Akun murid '{$siswa->name}' diperbarui", $siswa);

        return redirect()->route('siswa.index')->with('success', "Data murid '{$siswa->name}' berhasil diperbarui!");
    }

    /**
     * Remove the specified murid from storage.
     */
    public function destroy(Request $request, User $siswa): RedirectResponse
    {
        if ($request->user()->id === $siswa->id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri saat sedang login!');
        }

        ActivityLog::record('user.deleted', "Akun murid '{$siswa->name}' dihapus", null);

        $siswa->delete();

        return redirect()->route('siswa.index')->with('success', 'Akun murid berhasil dihapus.');
    }
}
