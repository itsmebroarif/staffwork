<?php

namespace App\Http\Controllers;

use App\Exports\MasterTemplateExport;
use App\Imports\RowCollectionImport;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\TeachingAssignment;
use App\Models\TeachingSchedule;
use App\Models\User;
use App\Models\WaliMurid;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class MasterImportController extends Controller
{
    private const TYPES = [
        'siswa' => [
            'label' => 'Siswa',
            'headings' => ['NIS', 'NAMA', 'EMAIL', 'KELAS', 'NO HP', 'PASSWORD'],
            'example' => ['1001', 'Budi Santoso', 'budi@contoh.test', 'Kelas 1', '081234567890', ''],
        ],
        'guru' => [
            'label' => 'Guru',
            'headings' => ['NIP', 'NAMA', 'EMAIL', 'NO HP', 'PASSWORD'],
            'example' => ['G-1001', 'Siti Aminah', 'siti@contoh.test', '081298765432', ''],
        ],
        'mapel' => [
            'label' => 'Mata Pelajaran',
            'headings' => ['KODE', 'NAMA MAPEL'],
            'example' => ['MP001', 'Matematika'],
        ],
        'kelas' => [
            'label' => 'Kelas',
            'headings' => ['NAMA KELAS', 'TINGKAT'],
            'example' => ['Kelas 1', '1'],
        ],
        'wali' => [
            'label' => 'Wali Murid',
            'headings' => ['NIS SISWA', 'NAMA WALI', 'HUBUNGAN', 'NO HP', 'EMAIL', 'ALAMAT'],
            'example' => ['1001', 'Ahmad Fauzi', 'Ayah', '081111222333', 'ahmad@contoh.test', 'Jl. Merdeka No. 1'],
        ],
        'penugasan' => [
            'label' => 'Kelas Diajar',
            'headings' => ['NIP GURU', 'KODE MAPEL', 'NAMA KELAS'],
            'example' => ['G-1001', 'MP001', 'Kelas 1'],
        ],
        'jadwal' => [
            'label' => 'Jadwal Pengajaran',
            'headings' => ['NIP GURU', 'KODE MAPEL', 'NAMA KELAS', 'HARI', 'JAM MULAI', 'JAM SELESAI'],
            'example' => ['G-1001', 'MP001', 'Kelas 1', 'Senin', '07:00', '09:00'],
        ],
    ];

    private const DAYS = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'];

    /**
     * Download the import template for the given master type.
     */
    public function template(string $type)
    {
        abort_unless(array_key_exists($type, self::TYPES), 404);

        $definition = self::TYPES[$type];

        return Excel::download(
            new MasterTemplateExport($definition['headings'], [$definition['example']]),
            'template-'.$type.'.xlsx'
        );
    }

    /**
     * Process an uploaded master data file.
     */
    public function import(Request $request, string $type): RedirectResponse
    {
        abort_unless(array_key_exists($type, self::TYPES), 404);

        $request->validate([
            'file' => ['required', 'file', 'extensions:xlsx,xls,csv', 'max:5120'],
        ], [
            'file.extensions' => 'File harus berupa format Excel (.xlsx, .xls) atau CSV (.csv).',
            'file.max' => 'Ukuran file maksimal 5MB.',
        ]);

        $import = new RowCollectionImport;
        Excel::import($import, $request->file('file'));

        if ($import->rows === []) {
            return back()->with('error', 'File tidak berisi data.');
        }

        $errors = [];
        $imported = 0;

        foreach ($import->rows as $index => $row) {
            $line = $index + 2;

            $error = match ($type) {
                'siswa' => $this->importSiswa($row),
                'guru' => $this->importGuru($row),
                'mapel' => $this->importMapel($row),
                'kelas' => $this->importKelas($row),
                'wali' => $this->importWaliMurid($row),
                'penugasan' => $this->importPenugasan($row),
                'jadwal' => $this->importJadwal($row),
            };

            if ($error !== null) {
                $errors[] = "Baris {$line}: {$error}";

                continue;
            }

            $imported++;
        }

        if ($errors !== []) {
            return back()
                ->with('imported_count', $imported)
                ->with('import_errors', $errors);
        }

        return back()->with('success', "Berhasil mengimpor {$imported} data ".self::TYPES[$type]['label'].'.');
    }

    private function importSiswa(array $row): ?string
    {
        $nis = $this->value($row, 'nis');
        $name = $this->value($row, 'nama');
        $email = $this->value($row, 'email');
        $kelasName = $this->value($row, 'kelas');
        $phone = $this->value($row, 'no_hp');
        $password = $this->value($row, 'password') ?: 'password';

        if ($nis === '' || $name === '' || $kelasName === '') {
            return 'Kolom NIS, NAMA, dan KELAS wajib diisi.';
        }

        $kelas = Kelas::where('name', $kelasName)->first();
        if ($kelas === null) {
            return "Kelas '{$kelasName}' tidak ditemukan.";
        }

        if ($email === '') {
            $email = Str::slug($nis, '.').'@siswa.staffwork.test';
        }

        $user = User::where('nis', $nis)->first();

        if ($user === null && User::where('email', $email)->exists()) {
            return "Email '{$email}' sudah terdaftar.";
        }

        $user ??= new User(['role' => 'murid']);
        $user->role = 'murid';
        $user->nis = $nis;
        $user->name = $name;
        $user->email = $email;
        $user->phone = $phone ?: null;
        $user->kelas_id = $kelas->id;
        $user->password = Hash::make($password);
        $user->save();

        return null;
    }

    private function importGuru(array $row): ?string
    {
        $nip = $this->value($row, 'nip');
        $name = $this->value($row, 'nama');
        $email = $this->value($row, 'email');
        $phone = $this->value($row, 'no_hp');
        $password = $this->value($row, 'password') ?: 'password';

        if ($nip === '' || $name === '') {
            return 'Kolom NIP dan NAMA wajib diisi.';
        }

        if ($email === '') {
            $email = Str::slug($nip, '.').'@guru.staffwork.test';
        }

        $user = User::where('nip', $nip)->first();

        if ($user === null && User::where('email', $email)->exists()) {
            return "Email '{$email}' sudah terdaftar.";
        }

        $user ??= new User(['role' => 'guru']);
        $user->role = 'guru';
        $user->nip = $nip;
        $user->name = $name;
        $user->email = $email;
        $user->phone = $phone ?: null;
        $user->password = Hash::make($password);
        $user->save();

        return null;
    }

    private function importMapel(array $row): ?string
    {
        $code = $this->value($row, 'kode');
        $name = $this->value($row, 'nama_mapel');

        if ($code === '' || $name === '') {
            return 'Kolom KODE dan NAMA MAPEL wajib diisi.';
        }

        $mapel = Mapel::where('code', $code)->first();

        if ($mapel !== null && $mapel->name !== $name && Mapel::where('name', $name)->exists()) {
            return "Mapel '{$name}' sudah digunakan oleh kode lain.";
        }

        $mapel ??= new Mapel;
        $mapel->code = $code;
        $mapel->name = $name;
        $mapel->save();

        return null;
    }

    private function importKelas(array $row): ?string
    {
        $name = $this->value($row, 'nama_kelas');
        $tingkat = $this->value($row, 'tingkat');

        if ($name === '' || ! ctype_digit($tingkat) || (int) $tingkat < 1) {
            return 'Kolom NAMA KELAS wajib diisi dan TINGKAT harus angka lebih dari 0.';
        }

        $kelas = Kelas::where('name', $name)->first() ?? new Kelas(['name' => $name]);
        $kelas->name = $name;
        $kelas->tingkat = (int) $tingkat;
        $kelas->save();

        return null;
    }

    private function importWaliMurid(array $row): ?string
    {
        $nis = $this->value($row, 'nis_siswa');
        $name = $this->value($row, 'nama_wali');
        $hubungan = $this->value($row, 'hubungan');
        $phone = $this->value($row, 'no_hp');
        $email = $this->value($row, 'email');
        $alamat = $this->value($row, 'alamat');

        if ($nis === '' || $name === '' || $hubungan === '' || $phone === '') {
            return 'Kolom NIS SISWA, NAMA WALI, HUBUNGAN, dan NO HP wajib diisi.';
        }

        $student = User::where('nis', $nis)->where('role', 'murid')->first();
        if ($student === null) {
            return "Siswa dengan NIS '{$nis}' tidak ditemukan.";
        }

        WaliMurid::updateOrCreate(
            [
                'student_id' => $student->id,
                'name' => $name,
            ],
            [
                'hubungan' => $hubungan,
                'phone' => $phone,
                'email' => $email ?: null,
                'alamat' => $alamat ?: null,
            ]
        );

        return null;
    }

    private function importPenugasan(array $row): ?string
    {
        $assignment = $this->resolveAssignment($row);
        if (is_string($assignment)) {
            return $assignment;
        }

        TeachingAssignment::firstOrCreate([
            'teacher_id' => $assignment->teacher_id,
            'mapel_id' => $assignment->mapel_id,
            'kelas_id' => $assignment->kelas_id,
        ]);

        return null;
    }

    private function importJadwal(array $row): ?string
    {
        $assignment = $this->resolveAssignment($row);
        if (is_string($assignment)) {
            return $assignment;
        }

        $day = Str::lower($this->value($row, 'hari'));
        if (! in_array($day, self::DAYS, true)) {
            return 'Kolom HARI harus salah satu dari: Senin, Selasa, Rabu, Kamis, Jumat, Sabtu.';
        }

        $start = $this->normalizeTime($this->value($row, 'jam_mulai'));
        $end = $this->normalizeTime($this->value($row, 'jam_selesai'));
        if ($start === null || $end === null) {
            return 'Kolom JAM MULAI dan JAM SELESAI harus berformat HH:MM.';
        }

        $assignmentModel = TeachingAssignment::firstOrCreate([
            'teacher_id' => $assignment->teacher_id,
            'mapel_id' => $assignment->mapel_id,
            'kelas_id' => $assignment->kelas_id,
        ]);

        TeachingSchedule::updateOrCreate(
            [
                'assignment_id' => $assignmentModel->id,
                'day' => $day,
                'start_time' => $start,
            ],
            [
                'end_time' => $end,
            ]
        );

        return null;
    }

    /**
     * Resolve teacher, mapel and kelas from a row, or return the error message.
     */
    private function resolveAssignment(array $row): TeachingAssignment|string
    {
        $nip = $this->value($row, 'nip_guru');
        $code = $this->value($row, 'kode_mapel');
        $kelasName = $this->value($row, 'nama_kelas');

        if ($nip === '' || $code === '' || $kelasName === '') {
            return 'Kolom NIP GURU, KODE MAPEL, dan NAMA KELAS wajib diisi.';
        }

        $teacher = User::where('nip', $nip)->where('role', 'guru')->first();
        if ($teacher === null) {
            return "Guru dengan NIP '{$nip}' tidak ditemukan.";
        }

        $mapel = Mapel::where('code', $code)->first();
        if ($mapel === null) {
            return "Mapel dengan kode '{$code}' tidak ditemukan.";
        }

        $kelas = Kelas::where('name', $kelasName)->first();
        if ($kelas === null) {
            return "Kelas '{$kelasName}' tidak ditemukan.";
        }

        return new TeachingAssignment([
            'teacher_id' => $teacher->id,
            'mapel_id' => $mapel->id,
            'kelas_id' => $kelas->id,
        ]);
    }

    /**
     * @param  array<string, mixed>  $row
     */
    private function value(array $row, string $key): string
    {
        return trim((string) ($row[$key] ?? ''));
    }

    private function normalizeTime(string $value): ?string
    {
        if (! preg_match('/^(\d{1,2}):(\d{2})$/', $value, $matches)) {
            return null;
        }

        $hour = (int) $matches[1];
        $minute = (int) $matches[2];

        if ($hour > 23 || $minute > 59) {
            return null;
        }

        return sprintf('%02d:%02d', $hour, $minute);
    }
}
