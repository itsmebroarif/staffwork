<?php

use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\TeachingAssignment;
use App\Models\TeachingSchedule;
use App\Models\User;
use App\Models\WaliMurid;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('superadmin can create kelas', function () {
    $superadmin = User::factory()->superadmin()->create();

    $response = $this->actingAs($superadmin)->post(route('kelas.store'), [
        'name' => 'Kelas 1',
        'tingkat' => 1,
    ]);

    $response->assertRedirect(route('kelas.index'));
    $this->assertDatabaseHas('kelas', ['name' => 'Kelas 1', 'tingkat' => 1]);
});

test('kelas name must be unique', function () {
    Kelas::factory()->create(['name' => 'Kelas 1']);
    $superadmin = User::factory()->superadmin()->create();

    $response = $this->from(route('kelas.create'))->actingAs($superadmin)->post(route('kelas.store'), [
        'name' => 'Kelas 1',
        'tingkat' => 2,
    ]);

    $response->assertSessionHasErrors('name');
});

test('guru cannot access master data', function () {
    $guru = User::factory()->guru()->create();

    $this->actingAs($guru)->get(route('kelas.index'))->assertStatus(403);
    $this->actingAs($guru)->get(route('siswa.index'))->assertStatus(403);
    $this->actingAs($guru)->get(route('guru.index'))->assertStatus(403);
    $this->actingAs($guru)->get(route('mapel.index'))->assertStatus(403);
    $this->actingAs($guru)->get(route('wali.index'))->assertStatus(403);
});

test('murid cannot access master data', function () {
    $murid = User::factory()->murid()->create();

    $this->actingAs($murid)->get(route('siswa.index'))->assertStatus(403);
    $this->actingAs($murid)->get(route('jadwal.index'))->assertOk();
});

test('superadmin can create student with kelas', function () {
    $kelas = Kelas::factory()->create();
    $superadmin = User::factory()->superadmin()->create();

    $response = $this->actingAs($superadmin)->post(route('siswa.store'), [
        'name' => 'Budi Santoso',
        'email' => 'budi@contoh.test',
        'nis' => '1001',
        'phone' => '081234567890',
        'kelas_id' => $kelas->id,
        'password' => 'rahasia123',
    ]);

    $response->assertRedirect(route('siswa.index'));
    $this->assertDatabaseHas('users', [
        'name' => 'Budi Santoso',
        'email' => 'budi@contoh.test',
        'nis' => '1001',
        'role' => 'murid',
        'kelas_id' => $kelas->id,
    ]);
});

test('superadmin can create teacher account', function () {
    $superadmin = User::factory()->superadmin()->create();

    $response = $this->actingAs($superadmin)->post(route('guru.store'), [
        'name' => 'Siti Aminah',
        'email' => 'siti@contoh.test',
        'nip' => 'G-1001',
        'phone' => '081298765432',
        'password' => 'rahasia123',
    ]);

    $response->assertRedirect(route('guru.index'));
    $this->assertDatabaseHas('users', [
        'name' => 'Siti Aminah',
        'nip' => 'G-1001',
        'role' => 'guru',
    ]);
});

test('superadmin can create mapel', function () {
    $superadmin = User::factory()->superadmin()->create();

    $response = $this->actingAs($superadmin)->post(route('mapel.store'), [
        'code' => 'MP001',
        'name' => 'Matematika',
    ]);

    $response->assertRedirect(route('mapel.index'));
    $this->assertDatabaseHas('mapels', ['code' => 'MP001', 'name' => 'Matematika']);
});

test('superadmin can create wali murid', function () {
    $student = User::factory()->murid()->create(['nis' => '1001']);
    $superadmin = User::factory()->superadmin()->create();

    $response = $this->actingAs($superadmin)->post(route('wali.store'), [
        'student_id' => $student->id,
        'name' => 'Ahmad Fauzi',
        'hubungan' => 'Ayah',
        'phone' => '081111222333',
        'email' => 'ahmad@contoh.test',
        'alamat' => 'Jl. Merdeka No. 1',
    ]);

    $response->assertRedirect(route('wali.index'));
    $this->assertDatabaseHas('wali_murids', [
        'student_id' => $student->id,
        'name' => 'Ahmad Fauzi',
        'hubungan' => 'Ayah',
    ]);
});

test('superadmin can create teaching assignment and schedule', function () {
    $superadmin = User::factory()->superadmin()->create();
    $guru = User::factory()->guru()->create();
    $mapel = Mapel::factory()->create();
    $kelas = Kelas::factory()->create();

    $this->actingAs($superadmin)->post(route('penugasan.store'), [
        'teacher_id' => $guru->id,
        'mapel_id' => $mapel->id,
        'kelas_id' => $kelas->id,
    ])->assertRedirect(route('penugasan.index'));

    $assignment = $guru->teachingAssignments()->first();
    expect($assignment)->not->toBeNull();

    $this->actingAs($superadmin)->post(route('jadwal.store'), [
        'assignment_id' => $assignment->id,
        'day' => 'senin',
        'start_time' => '07:00',
        'end_time' => '09:00',
    ])->assertRedirect(route('jadwal.index'));

    $this->assertDatabaseHas('teaching_schedules', [
        'assignment_id' => $assignment->id,
        'day' => 'senin',
    ]);
});

test('duplicate teaching assignment is rejected', function () {
    $superadmin = User::factory()->superadmin()->create();
    $guru = User::factory()->guru()->create();
    $mapel = Mapel::factory()->create();
    $kelas = Kelas::factory()->create();

    TeachingSchedule::factory()->create();

    $response = $this->from(route('penugasan.create'))->actingAs($superadmin)->post(route('penugasan.store'), [
        'teacher_id' => $guru->id,
        'mapel_id' => $mapel->id,
        'kelas_id' => $kelas->id,
    ]);
    $response->assertSessionHasNoErrors();

    $duplicate = $this->from(route('penugasan.create'))->actingAs($superadmin)->post(route('penugasan.store'), [
        'teacher_id' => $guru->id,
        'mapel_id' => $mapel->id,
        'kelas_id' => $kelas->id,
    ]);

    $duplicate->assertSessionHasErrors('teacher_id');
});

test('jadwal end time must be after start time', function () {
    $superadmin = User::factory()->superadmin()->create();
    $guru = User::factory()->guru()->create();
    $mapel = Mapel::factory()->create();
    $kelas = Kelas::factory()->create();
    $assignment = TeachingAssignment::factory()->create([
        'teacher_id' => $guru->id,
        'mapel_id' => $mapel->id,
        'kelas_id' => $kelas->id,
    ]);

    $response = $this->from(route('jadwal.create'))->actingAs($superadmin)->post(route('jadwal.store'), [
        'assignment_id' => $assignment->id,
        'day' => 'senin',
        'start_time' => '09:00',
        'end_time' => '07:00',
    ]);

    $response->assertSessionHasErrors('end_time');
});

test('superadmin can view all master pages', function () {
    $superadmin = User::factory()->superadmin()->create();

    $kelas = Kelas::factory()->create();
    $mapel = Mapel::factory()->create();
    $siswa = User::factory()->murid()->create();
    $guru = User::factory()->guru()->create();
    $wali = WaliMurid::factory()->create(['student_id' => $siswa->id]);
    $assignment = TeachingAssignment::factory()->create([
        'teacher_id' => $guru->id,
        'mapel_id' => $mapel->id,
        'kelas_id' => $kelas->id,
    ]);
    $schedule = TeachingSchedule::factory()->create(['assignment_id' => $assignment->id]);

    foreach ([
        'kelas.index', 'kelas.create',
        'mapel.index', 'mapel.create',
        'siswa.index', 'siswa.create',
        'guru.index', 'guru.create',
        'wali.index', 'wali.create',
        'penugasan.index', 'penugasan.create',
        'jadwal.index', 'jadwal.create',
    ] as $routeName) {
        $this->actingAs($superadmin)->get(route($routeName))->assertOk();
    }

    $this->actingAs($superadmin)->get(route('kelas.edit', $kelas))->assertOk();
    $this->actingAs($superadmin)->get(route('mapel.edit', $mapel))->assertOk();
    $this->actingAs($superadmin)->get(route('siswa.edit', $siswa))->assertOk();
    $this->actingAs($superadmin)->get(route('guru.edit', $guru))->assertOk();
    $this->actingAs($superadmin)->get(route('wali.edit', $wali))->assertOk();
    $this->actingAs($superadmin)->get(route('penugasan.edit', $assignment))->assertOk();
    $this->actingAs($superadmin)->get(route('jadwal.edit', $schedule))->assertOk();
});

test('superadmin can update and delete kelas', function () {
    $kelas = Kelas::factory()->create();
    $superadmin = User::factory()->superadmin()->create();

    $this->actingAs($superadmin)->put(route('kelas.update', $kelas), [
        'name' => 'Kelas 1A',
        'tingkat' => 1,
    ])->assertRedirect(route('kelas.index'));

    $this->assertDatabaseHas('kelas', ['id' => $kelas->id, 'name' => 'Kelas 1A']);

    $this->actingAs($superadmin)->delete(route('kelas.destroy', $kelas))
        ->assertRedirect(route('kelas.index'));

    $this->assertDatabaseMissing('kelas', ['id' => $kelas->id]);
});

test('wali murid must reference a murid account', function () {
    $superadmin = User::factory()->superadmin()->create();
    $guru = User::factory()->guru()->create();

    $response = $this->from(route('wali.create'))->actingAs($superadmin)->post(route('wali.store'), [
        'student_id' => $guru->id,
        'name' => 'Budi',
        'hubungan' => 'Ayah',
        'phone' => '0812',
    ]);

    $response->assertSessionHasErrors('student_id');
    expect(WaliMurid::count())->toBe(0);
});
