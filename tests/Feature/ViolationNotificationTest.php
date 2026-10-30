<?php

use App\Models\User;
use App\Models\Violation;
use App\Notifications\StudentCalledNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guru can record violation and student receives call notification', function () {
    $guru = User::factory()->guru()->create();
    $student = User::factory()->murid()->create();

    $response = $this->actingAs($guru)->post(route('pelanggaran.store'), [
        'student_id' => $student->id,
        'description' => 'Terlambat masuk kelas 3 kali berturut-turut',
        'room' => 'bk',
    ]);

    $response->assertRedirect(route('pelanggaran.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('violations', [
        'student_id' => $student->id,
        'reported_by' => $guru->id,
        'room' => 'bk',
    ]);

    $this->assertDatabaseHas('notifications', [
        'notifiable_id' => $student->id,
        'notifiable_type' => User::class,
        'type' => StudentCalledNotification::class,
    ]);
});

test('notification message mentions the destination room', function () {
    $guru = User::factory()->guru()->create();
    $student = User::factory()->murid()->create();

    $this->actingAs($guru)->post(route('pelanggaran.store'), [
        'student_id' => $student->id,
        'description' => 'Membolos jam pelajaran',
        'room' => 'guru',
    ]);

    $notification = $student->notifications()->first();
    expect($notification->data['title'])->toBe('Anda dipanggil ke Ruang Guru');
    expect($notification->data['message'])->toBe('Membolos jam pelajaran');
});

test('student can see and mark call notification as read', function () {
    $violation = Violation::factory()->create();
    $student = $violation->student;
    $student->notify(new StudentCalledNotification($violation));

    $response = $this->actingAs($student)->get(route('notifications.index'));

    $response->assertOk();
    $response->assertSee('Anda dipanggil ke');
    $response->assertSee($violation->description);

    $notification = $student->notifications()->first();

    $this->actingAs($student)->patch(route('notifications.read', $notification->id))
        ->assertRedirect();

    expect($notification->refresh()->read_at)->not->toBeNull();
});

test('student can mark all notifications as read', function () {
    $violation = Violation::factory()->create();
    $student = $violation->student;
    $student->notify(new StudentCalledNotification($violation));
    $student->notify(new StudentCalledNotification($violation));

    $this->actingAs($student)->patch(route('notifications.readAll'))->assertRedirect();

    expect($student->unreadNotifications()->count())->toBe(0);
});

test('student cannot access violation management', function () {
    $murid = User::factory()->murid()->create();

    $this->actingAs($murid)->get(route('pelanggaran.index'))->assertStatus(403);
    $this->actingAs($murid)->get(route('pelanggaran.create'))->assertStatus(403);
    $this->actingAs($murid)->post(route('pelanggaran.store'), [
        'student_id' => $murid->id,
        'description' => 'Fake',
        'room' => 'bk',
    ])->assertStatus(403);
});

test('violation requires a valid room', function () {
    $guru = User::factory()->guru()->create();
    $student = User::factory()->murid()->create();

    $response = $this->from(route('pelanggaran.create'))->actingAs($guru)->post(route('pelanggaran.store'), [
        'student_id' => $student->id,
        'description' => 'Uji validasi ruang',
        'room' => 'kantin',
    ]);

    $response->assertSessionHasErrors('room');
    $this->assertDatabaseCount('violations', 0);
});

test('superadmin can record violation', function () {
    $superadmin = User::factory()->superadmin()->create();
    $student = User::factory()->murid()->create();

    $this->actingAs($superadmin)->post(route('pelanggaran.store'), [
        'student_id' => $student->id,
        'description' => 'Merusak fasilitas sekolah',
        'room' => 'bk',
    ])->assertRedirect(route('pelanggaran.index'));

    $this->assertDatabaseHas('violations', [
        'student_id' => $student->id,
        'reported_by' => $superadmin->id,
    ]);
});
