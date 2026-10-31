<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('superadmin can start and stop a role simulation from the profile page', function () {
    $superadmin = User::factory()->superadmin()->create();

    $this->actingAs($superadmin)->post(route('profile.simulate.start'), ['simulate_role' => 'murid'])
        ->assertRedirect(route('profile.edit'));

    expect(session('simulated_role'))->toBe('murid');
    expect($superadmin->fresh()->isSimulating())->toBeTrue();

    $this->actingAs($superadmin)->delete(route('profile.simulate.stop'))
        ->assertRedirect(route('profile.edit'));

    expect(session('simulated_role'))->toBeNull();
});

test('simulating as murid makes superadmin look and behave like a student', function () {
    $superadmin = User::factory()->superadmin()->create();

    $this->actingAs($superadmin)->post(route('profile.simulate.start'), ['simulate_role' => 'murid']);

    expect($superadmin->isStudent())->toBeTrue();
    expect($superadmin->isTeacher())->toBeFalse();
    expect($superadmin->isSuperAdmin())->toBeFalse();
    expect($superadmin->role_name)->toBe('Murid');

    // Super admin only pages are no longer reachable while simulating.
    $this->actingAs($superadmin)->get(route('users.index'))->assertStatus(403);
    $this->actingAs($superadmin)->get(route('materis.bin'))->assertStatus(403);
});

test('simulating as guru unlocks teacher features but not superadmin pages', function () {
    $superadmin = User::factory()->superadmin()->create();

    $this->actingAs($superadmin)->post(route('profile.simulate.start'), ['simulate_role' => 'guru']);

    expect($superadmin->isTeacher())->toBeTrue();
    expect($superadmin->isStudent())->toBeFalse();
    expect($superadmin->role_name)->toBe('Guru');

    $this->actingAs($superadmin)->get(route('materis.create'))->assertOk();
    $this->actingAs($superadmin)->get(route('materis.bin'))->assertOk();
    $this->actingAs($superadmin)->get(route('users.index'))->assertStatus(403);
});

test('profile page shows the simulation panel and banner for superadmin', function () {
    $superadmin = User::factory()->superadmin()->create();

    $response = $this->actingAs($superadmin)->get(route('profile.edit'));
    $response->assertOk();
    $response->assertSee('Simulasi Tampilan Peran');
    $response->assertSee('Simulasikan sebagai Guru');
    $response->assertSee('Simulasikan sebagai Murid');

    $this->actingAs($superadmin)->post(route('profile.simulate.start'), ['simulate_role' => 'guru']);

    $response = $this->actingAs($superadmin)->get(route('profile.edit'));
    $response->assertSee('Mode Simulasi');
    $response->assertSee('Keluar dari Simulasi');
});

test('guru and murid cannot start a role simulation', function () {
    $guru = User::factory()->guru()->create();
    $murid = User::factory()->murid()->create();

    $this->actingAs($guru)->post(route('profile.simulate.start'), ['simulate_role' => 'murid'])->assertStatus(403);
    $this->actingAs($murid)->post(route('profile.simulate.start'), ['simulate_role' => 'guru'])->assertStatus(403);

    $this->actingAs($guru)->delete(route('profile.simulate.stop'))->assertStatus(403);
    $this->actingAs($murid)->delete(route('profile.simulate.stop'))->assertStatus(403);

    expect(session('simulated_role'))->toBeNull();
});

test('invalid simulation role is rejected', function () {
    $superadmin = User::factory()->superadmin()->create();

    $this->actingAs($superadmin)->post(route('profile.simulate.start'), ['simulate_role' => 'superadmin'])
        ->assertSessionHasErrors('simulate_role');

    expect(session('simulated_role'))->toBeNull();
});

test('profile and password updates are blocked while simulating', function () {
    $superadmin = User::factory()->superadmin()->create(['name' => 'Nama Asli']);

    $this->actingAs($superadmin)->post(route('profile.simulate.start'), ['simulate_role' => 'murid']);

    $this->actingAs($superadmin)->put(route('profile.update'), [
        'name' => 'Nama Berubah',
        'email' => $superadmin->email,
    ])->assertSessionHas('error');

    $this->actingAs($superadmin)->put(route('profile.password'), [
        'current_password' => 'password',
        'password' => 'rahasia123',
        'password_confirmation' => 'rahasia123',
    ])->assertSessionHas('error');

    expect($superadmin->fresh()->name)->toBe('Nama Asli');
});

test('role simulation is ignored for non superadmin accounts', function () {
    $murid = User::factory()->murid()->create();

    session(['simulated_role' => 'guru']);

    expect($murid->simulatedRole())->toBeNull();
    expect($murid->isStudent())->toBeTrue();
    expect($murid->isTeacher())->toBeFalse();
    expect($murid->isSimulating())->toBeFalse();
});

test('simulation ends automatically on logout', function () {
    $superadmin = User::factory()->superadmin()->create();

    $this->actingAs($superadmin)->post(route('profile.simulate.start'), ['simulate_role' => 'murid']);
    expect($superadmin->isSimulating())->toBeTrue();

    $this->post(route('logout'));

    expect(session('simulated_role'))->toBeNull();
});
