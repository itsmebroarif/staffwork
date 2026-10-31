<?php

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

test('super admin can view the activity log page', function () {
    $superadmin = User::factory()->superadmin()->create();

    ActivityLog::record('material.created', 'Materi uji ditambahkan', null, $superadmin);

    $response = $this->actingAs($superadmin)->get(route('activity.index'));

    $response->assertStatus(200);
    $response->assertSee('Activity Log');
    $response->assertSee('Materi uji ditambahkan');
    $response->assertSee($superadmin->name);
});

test('guru cannot access the activity log page', function () {
    $guru = User::factory()->guru()->create();

    $response = $this->actingAs($guru)->get(route('activity.index'));

    $response->assertStatus(403);
});

test('login and logout activities are recorded', function () {
    $user = User::factory()->create([
        'password' => Hash::make('secret123'),
    ]);

    $this->post(url('/auth/login'), [
        'email' => $user->email,
        'password' => 'secret123',
    ])->assertRedirect(route('dashboard'));

    $this->assertDatabaseHas('activity_logs', [
        'user_id' => $user->id,
        'action' => 'login',
    ]);

    $this->post(route('logout'))->assertRedirect();

    $this->assertDatabaseHas('activity_logs', [
        'user_id' => $user->id,
        'action' => 'logout',
    ]);
});

test('activity log can be filtered by search keyword', function () {
    $superadmin = User::factory()->superadmin()->create();
    ActivityLog::record('exam.created', 'Ujian Matematika dibuat', null, $superadmin);
    ActivityLog::record('login', 'Masuk ke aplikasi', null, $superadmin);

    $response = $this->actingAs($superadmin)->get(route('activity.index', ['q' => 'Matematika']));

    $response->assertStatus(200);
    $response->assertSee('Ujian Matematika dibuat');
    $response->assertDontSee('Masuk ke aplikasi');
});
