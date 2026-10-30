<?php

use App\Models\Kelas;
use App\Models\TeachingAssignment;
use App\Models\TeachingSchedule;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

function importCsv(string $type, string $content)
{
    return test()->actingAs(test()->superadmin)->post(route('master.import', $type), [
        'file' => UploadedFile::fake()->createWithContent("{$type}.csv", $content),
    ]);
}

beforeEach(function () {
    $this->superadmin = User::factory()->superadmin()->create();
});

test('superadmin can download import templates', function (string $type) {
    $response = $this->actingAs($this->superadmin)->get(route('master.template', $type));

    $response->assertOk();
    $response->assertDownload("template-{$type}.xlsx");
})->with(['siswa', 'guru', 'mapel', 'kelas', 'wali', 'penugasan', 'jadwal']);

test('guru cannot import master data', function () {
    $guru = User::factory()->guru()->create();

    $this->actingAs($guru)->get(route('master.template', 'siswa'))->assertStatus(403);
    $this->actingAs($guru)->post(route('master.import', 'siswa'), [
        'file' => UploadedFile::fake()->createWithContent('siswa.csv', "NIS,NAMA,KELAS\n1,Budi,Kelas 1\n"),
    ])->assertStatus(403);
});

test('import siswa creates murid account and assigns kelas', function () {
    Kelas::factory()->create(['name' => 'Kelas 1', 'tingkat' => 1]);

    $response = importCsv('siswa', implode("\n", [
        'NIS,NAMA,EMAIL,KELAS,NO HP,PASSWORD',
        '1001,Budi Santoso,budi@contoh.test,Kelas 1,081234567890,rahasia123',
    ]));

    $response->assertRedirect();
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('users', [
        'nis' => '1001',
        'name' => 'Budi Santoso',
        'email' => 'budi@contoh.test',
        'role' => 'murid',
        'phone' => '081234567890',
    ]);

    $student = User::where('nis', '1001')->first();
    expect($student->kelas->name)->toBe('Kelas 1');
    expect(Hash::check('rahasia123', $student->password))->toBeTrue();
});

test('import siswa uses default password when blank', function () {
    Kelas::factory()->create(['name' => 'Kelas 2', 'tingkat' => 2]);

    importCsv('siswa', implode("\n", [
        'NIS,NAMA,EMAIL,KELAS,NO HP,PASSWORD',
        '2001,Sari Wijaya,,Kelas 2,0811,',
    ]))->assertSessionHas('success');

    $student = User::where('nis', '2001')->first();
    expect($student->email)->not->toBeEmpty();
    expect(Hash::check('password', $student->password))->toBeTrue();
});

test('import siswa reports error for unknown kelas', function () {
    $response = importCsv('siswa', implode("\n", [
        'NIS,NAMA,EMAIL,KELAS,NO HP,PASSWORD',
        '3001,Budi,Budi@contoh.test,Kelas 99,0812,',
    ]));

    $response->assertSessionHas('import_errors');
    expect(session('import_errors')[0])->toContain("Kelas 'Kelas 99' tidak ditemukan.");
    $this->assertDatabaseMissing('users', ['nis' => '3001']);
});

test('re-importing the same siswa updates instead of duplicating', function () {
    Kelas::factory()->create(['name' => 'Kelas 1', 'tingkat' => 1]);
    Kelas::factory()->create(['name' => 'Kelas 2', 'tingkat' => 2]);

    importCsv('siswa', "NIS,NAMA,EMAIL,KELAS,NO HP,PASSWORD\n1001,Budi Lama,,Kelas 1,,\n");
    importCsv('siswa', "NIS,NAMA,EMAIL,KELAS,NO HP,PASSWORD\n1001,Budi Baru,,Kelas 2,,\n");

    expect(User::where('nis', '1001')->count())->toBe(1);
    $this->assertDatabaseHas('users', ['nis' => '1001', 'name' => 'Budi Baru']);
});

test('import guru creates teacher account', function () {
    importCsv('guru', implode("\n", [
        'NIP,NAMA,EMAIL,NO HP,PASSWORD',
        'G-1001,Siti Aminah,siti@contoh.test,081298765432,rahasia123',
    ]))->assertSessionHas('success');

    $this->assertDatabaseHas('users', [
        'nip' => 'G-1001',
        'name' => 'Siti Aminah',
        'role' => 'guru',
    ]);
});

test('import mapel and kelas create master records', function () {
    importCsv('mapel', "KODE,NAMA MAPEL\nMP001,Matematika\n")->assertSessionHas('success');
    importCsv('kelas', "NAMA KELAS,TINGKAT\nKelas 1,1\n")->assertSessionHas('success');

    $this->assertDatabaseHas('mapels', ['code' => 'MP001', 'name' => 'Matematika']);
    $this->assertDatabaseHas('kelas', ['name' => 'Kelas 1', 'tingkat' => 1]);
});

test('import wali murid links guardian to student', function () {
    Kelas::factory()->create(['name' => 'Kelas 1', 'tingkat' => 1]);
    importCsv('siswa', "NIS,NAMA,EMAIL,KELAS,NO HP,PASSWORD\n1001,Budi,,Kelas 1,,\n");

    importCsv('wali', implode("\n", [
        'NIS SISWA,NAMA WALI,HUBUNGAN,NO HP,EMAIL,ALAMAT',
        '1001,Ahmad Fauzi,Ayah,081111222333,ahmad@contoh.test,Jl. Merdeka 1',
    ]))->assertSessionHas('success');

    $student = User::where('nis', '1001')->first();
    $this->assertDatabaseHas('wali_murids', [
        'student_id' => $student->id,
        'name' => 'Ahmad Fauzi',
        'hubungan' => 'Ayah',
    ]);
});

test('import penugasan and jadwal create assignments and schedules', function () {
    Kelas::factory()->create(['name' => 'Kelas 1', 'tingkat' => 1]);
    importCsv('guru', "NIP,NAMA,EMAIL,NO HP,PASSWORD\nG-1001,Siti,,,\n");
    importCsv('mapel', "KODE,NAMA MAPEL\nMP001,Matematika\n");

    importCsv('penugasan', "NIP GURU,KODE MAPEL,NAMA KELAS\nG-1001,MP001,Kelas 1\n")
        ->assertSessionHas('success');

    expect(TeachingAssignment::count())->toBe(1);

    importCsv('jadwal', implode("\n", [
        'NIP GURU,KODE MAPEL,NAMA KELAS,HARI,JAM MULAI,JAM SELESAI',
        'G-1001,MP001,Kelas 1,Senin,07:00,09:00',
    ]))->assertSessionHas('success');

    expect(TeachingSchedule::count())->toBe(1);
    $schedule = TeachingSchedule::first();
    expect($schedule->day)->toBe('senin');
    expect($schedule->start_time)->toContain('07:00');
});

test('import jadwal auto creates missing assignment', function () {
    Kelas::factory()->create(['name' => 'Kelas 1', 'tingkat' => 1]);
    importCsv('guru', "NIP,NAMA,EMAIL,NO HP,PASSWORD\nG-1001,Siti,,,\n");
    importCsv('mapel', "KODE,NAMA MAPEL\nMP001,Matematika\n");

    importCsv('jadwal', "NIP GURU,KODE MAPEL,NAMA KELAS,HARI,JAM MULAI,JAM SELESAI\nG-1001,MP001,Kelas 1,Rabu,10:00,11:00\n")
        ->assertSessionHas('success');

    expect(TeachingAssignment::count())->toBe(1);
    expect(TeachingSchedule::count())->toBe(1);
});

test('import rejects invalid file extension', function () {
    $response = $this->actingAs($this->superadmin)->post(route('master.import', 'siswa'), [
        'file' => UploadedFile::fake()->create('malware.exe', 10, 'application/octet-stream'),
    ]);

    $response->assertSessionHasErrors('file');
});

test('import rejects unknown master type with 404', function () {
    $this->actingAs($this->superadmin)->get(route('master.template', 'unknown'))->assertStatus(404);
});

test('import wali murid reports error for unknown student', function () {
    $response = importCsv('wali', "NIS SISWA,NAMA WALI,HUBUNGAN,NO HP\n9999,Ahmad,Ayah,0811\n");

    $response->assertSessionHas('import_errors');
    $this->assertDatabaseMissing('wali_murids', ['name' => 'Ahmad']);
});
