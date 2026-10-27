<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

test('super admin can view user management list', function () {
    $superadmin = User::factory()->superadmin()->create();
    $otherUsers = User::factory()->count(3)->create();

    $response = $this->actingAs($superadmin)->get(route('users.index'));

    $response->assertStatus(200);
    $response->assertSee('Manajemen Pengguna');
    $response->assertSee('Registrasi Akun Baru');
});

test('guru cannot access user management', function () {
    $guru = User::factory()->guru()->create();

    $response = $this->actingAs($guru)->get(route('users.index'));

    $response->assertStatus(403);
});

test('super admin can register multiple accounts with custom roles', function () {
    Storage::fake('public');
    $superadmin = User::factory()->superadmin()->create();

    $file = UploadedFile::fake()->image('staff-photo.jpg');

    $response = $this->actingAs($superadmin)->post(route('users.store'), [
        'name' => 'Guru Andika',
        'email' => 'andika@example.com',
        'role' => 'guru',
        'password' => 'secret123',
        'password_confirmation' => 'secret123',
        'avatar' => $file,
    ]);

    $response->assertRedirect(route('users.index'));
    $this->assertDatabaseHas('users', [
        'name' => 'Guru Andika',
        'email' => 'andika@example.com',
        'role' => 'guru',
    ]);

    $createdUser = User::where('email', 'andika@example.com')->first();
    expect($createdUser->avatar)->not->toBeNull();
    Storage::disk('public')->assertExists($createdUser->avatar);
});

test('super admin can edit user details and role', function () {
    $superadmin = User::factory()->superadmin()->create();
    $user = User::factory()->create(['name' => 'Old Name', 'role' => 'murid']);

    $response = $this->actingAs($superadmin)->put(route('users.update', $user), [
        'name' => 'New Name',
        'email' => $user->email,
        'role' => 'guru',
    ]);

    $response->assertRedirect(route('users.index'));
    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'New Name',
        'role' => 'guru',
    ]);
});

test('user can view profile page and see their details', function () {
    $user = User::factory()->create([
        'name' => 'Rina Wijaya',
        'email' => 'rina@example.com',
    ]);

    $response = $this->actingAs($user)->get(route('profile.edit'));

    $response->assertStatus(200);
    $response->assertSee('Rina Wijaya');
    $response->assertSee('rina@example.com');
    $response->assertSee('Perbarui Profil');
});

test('user can update their profile and upload avatar', function () {
    Storage::fake('public');
    $user = User::factory()->create([
        'name' => 'Nama Lama',
        'email' => 'lama@example.com',
    ]);

    $avatar = UploadedFile::fake()->image('avatar.png');

    $response = $this->actingAs($user)->put(route('profile.update'), [
        'name' => 'Nama Baru',
        'email' => 'baru@example.com',
        'avatar' => $avatar,
    ]);

    $response->assertRedirect();
    $user->refresh();

    expect($user->name)->toBe('Nama Baru');
    expect($user->email)->toBe('baru@example.com');
    expect($user->avatar)->not->toBeNull();
    Storage::disk('public')->assertExists($user->avatar);
});

test('user can update password with valid current password', function () {
    $user = User::factory()->create([
        'password' => Hash::make('old-password-123'),
    ]);

    $response = $this->actingAs($user)->put(route('profile.password'), [
        'current_password' => 'old-password-123',
        'password' => 'new-password-456',
        'password_confirmation' => 'new-password-456',
    ]);

    $response->assertRedirect();
    $user->refresh();
    expect(Hash::check('new-password-456', $user->password))->toBeTrue();
});

test('user cannot update password with incorrect current password', function () {
    $user = User::factory()->create([
        'password' => Hash::make('correct-password'),
    ]);

    $response = $this->actingAs($user)->put(route('profile.password'), [
        'current_password' => 'wrong-password',
        'password' => 'new-password-456',
        'password_confirmation' => 'new-password-456',
    ]);

    $response->assertSessionHasErrors('current_password');
    $user->refresh();
    expect(Hash::check('correct-password', $user->password))->toBeTrue();
});

test('user can remove their custom avatar', function () {
    Storage::fake('public');
    $path = UploadedFile::fake()->image('photo.jpg')->store('avatars', 'public');
    $user = User::factory()->create(['avatar' => $path]);

    $response = $this->actingAs($user)->delete(route('profile.avatar.destroy'));

    $response->assertRedirect();
    $user->refresh();
    expect($user->avatar)->toBeNull();
    Storage::disk('public')->assertMissing($path);
});
