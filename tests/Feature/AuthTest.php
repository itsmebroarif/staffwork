<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

test('login screen can be rendered with captcha', function () {
    $response = $this->get(route('login'));

    $response->assertStatus(200);
    $response->assertSee('StaffWork');
    $response->assertSee('Masuk (Login)');
    $response->assertSee('captcha');
});

test('register screen can be rendered with proper fields', function () {
    $response = $this->get(route('register'));

    $response->assertStatus(200);
    $response->assertSee('Buat Akun StaffWork');
    $response->assertSee('Nama Lengkap');
    $response->assertSee('Alamat Email');
    $response->assertSee('Kata Sandi');
    $response->assertSee('Konfirmasi Kata Sandi');
    $response->assertSee('captcha');
});

test('new users can register with name, email, password, and confirmation', function () {
    $response = $this->post(url('/auth/register'), [
        'name' => 'Budi Santoso',
        'email' => 'budi@example.com',
        'password' => 'secret123',
        'password_confirmation' => 'secret123',
        'captcha' => 'test12',
    ]);

    $response->assertRedirect(route('dashboard'));
    $this->assertAuthenticated();
    $this->assertDatabaseHas('users', [
        'name' => 'Budi Santoso',
        'email' => 'budi@example.com',
        'role' => 'superadmin',
    ]);
});

test('users cannot register if password confirmation does not match', function () {
    $response = $this->from(route('register'))->post(url('/auth/register'), [
        'name' => 'Budi Santoso',
        'email' => 'budi@example.com',
        'password' => 'secret123',
        'password_confirmation' => 'mismatch456',
        'captcha' => 'test12',
    ]);

    $this->assertGuest();
    $response->assertSessionHasErrors('password');
});

test('users can authenticate using login screen', function () {
    $user = User::factory()->create([
        'email' => 'jane@example.com',
        'password' => Hash::make('password123'),
    ]);

    $response = $this->post(url('/auth/login'), [
        'email' => 'jane@example.com',
        'password' => 'password123',
        'captcha' => 'test12',
    ]);

    $response->assertRedirect(route('dashboard'));
    $this->assertAuthenticatedAs($user);
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create([
        'email' => 'jane@example.com',
        'password' => Hash::make('password123'),
    ]);

    $response = $this->from(route('login'))->post(url('/auth/login'), [
        'email' => 'jane@example.com',
        'password' => 'wrong-password',
        'captcha' => 'test12',
    ]);

    $this->assertGuest();
    $response->assertSessionHasErrors('email');
});

test('authenticated users can access dashboard and see their info', function () {
    $user = User::factory()->create([
        'name' => 'Ahmad Dahlan',
        'email' => 'ahmad@example.com',
    ]);

    $response = $this->actingAs($user)->get(route('dashboard'));

    $response->assertStatus(200);
    $response->assertSee('Ahmad Dahlan');
    $response->assertSee('ahmad@example.com');
    $response->assertSee('Dashboard');
});

test('unauthenticated users are redirected to login when visiting dashboard', function () {
    $response = $this->get(route('dashboard'));

    $response->assertRedirect(route('login'));
});

test('users can log out', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('logout'));

    $this->assertGuest();
    $response->assertRedirect(route('login'));
});

test('captcha can be reloaded via json response', function () {
    $response = $this->get(route('captcha.reload'));

    $response->assertStatus(200);
    $response->assertJsonStructure(['captcha']);
});
