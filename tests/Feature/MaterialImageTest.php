<?php

use App\Models\Material;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

test('guru can create a gambar-type material through the image tab', function () {
    Storage::fake('public');
    $guru = User::factory()->guru()->create();

    $response = $this->actingAs($guru)->post(route('materis.store'), [
        'title' => 'Diagram Algoritma',
        'type' => 'gambar',
        'gambar' => UploadedFile::fake()->image('diagram.png'),
        'is_published' => 1,
    ]);

    $response->assertRedirect(route('materis.index'));

    $material = Material::where('title', 'Diagram Algoritma')->first();
    expect($material->type)->toBe('gambar');
    expect($material->body)->toBeNull();
    expect($material->file_path)->not->toBeNull();
    expect($material->file_name)->toBe('diagram.png');
    Storage::disk('public')->assertExists($material->file_path);
});

test('gambar-type material requires an uploaded image on create', function () {
    $guru = User::factory()->guru()->create();

    $response = $this->from(route('materis.create'))->actingAs($guru)->post(route('materis.store'), [
        'title' => 'Gambar Kosong',
        'type' => 'gambar',
        'is_published' => 1,
    ]);

    $response->assertSessionHasErrors('gambar');
});

test('gambar tab rejects non image files', function () {
    $guru = User::factory()->guru()->create();

    $response = $this->actingAs($guru)->post(route('materis.store'), [
        'title' => 'Gambar Palsu',
        'type' => 'gambar',
        'gambar' => UploadedFile::fake()->create('bukan-gambar.png', 100, 'application/pdf'),
        'is_published' => 1,
    ]);

    $response->assertSessionHasErrors('gambar');
});

test('gambar-type material can keep its existing image when editing without a new upload', function () {
    Storage::fake('public');
    $guru = User::factory()->guru()->create();
    $material = Material::factory()->create([
        'user_id' => $guru->id,
        'type' => 'gambar',
        'body' => null,
        'file_path' => 'materials/lama.png',
        'file_name' => 'lama.png',
    ]);

    $response = $this->actingAs($guru)->put(route('materis.update', $material), [
        'title' => 'Gambar Diperbarui',
        'type' => 'gambar',
        'is_published' => 1,
    ]);

    $response->assertRedirect(route('materis.index'));

    $material->refresh();
    expect($material->title)->toBe('Gambar Diperbarui');
    expect($material->file_path)->toBe('materials/lama.png');
});

test('guru can upload an image from the ckeditor editor', function () {
    Storage::fake('public');
    $guru = User::factory()->guru()->create();

    $response = $this->actingAs($guru)->postJson(route('materis.images.upload'), [
        'upload' => UploadedFile::fake()->image('artikel-ilustrasi.png'),
    ]);

    $response->assertOk();
    $url = $response->json('url');
    expect($url)->not->toBeNull();
    expect($url)->toContain('/storage/materials/images/');
    Storage::disk('public')->assertExists(Str::after($url, '/storage/'));
});

test('ckeditor image upload requires an image file', function () {
    $guru = User::factory()->guru()->create();

    $response = $this->actingAs($guru)->postJson(route('materis.images.upload'), [
        'upload' => UploadedFile::fake()->create('catatan.txt', 5, 'text/plain'),
    ]);

    $response->assertStatus(422)->assertJsonValidationErrors('upload');
});

test('murid cannot upload images through the editor endpoint', function () {
    $murid = User::factory()->murid()->create();

    $response = $this->actingAs($murid)->postJson(route('materis.images.upload'), [
        'upload' => UploadedFile::fake()->image('ilegal.png'),
    ]);

    $response->assertStatus(403);
});

test('material detail renders html body and embedded images', function () {
    Storage::fake('public');
    $guru = User::factory()->guru()->create();
    Storage::disk('public')->put('materials/images/ilustrasi.png', 'isi-gambar');

    $this->actingAs($guru)->post(route('materis.store'), [
        'title' => 'Materi HTML',
        'type' => 'artikel',
        'body' => '<p>Gunakan blok kode berikut:</p><pre class="language-php"><code class="language-php">echo "Halo";</code></pre><img src="'.asset('storage/materials/images/ilustrasi.png').'" alt="ilustrasi">',
        'is_published' => 1,
    ]);

    $material = Material::where('title', 'Materi HTML')->first();

    $response = $this->actingAs($guru)->get(route('materis.show', $material));

    $response->assertOk();
    $response->assertSee('<pre class="language-php">', false);
    $response->assertSee('materials/images/ilustrasi.png', false);
});

test('legacy plain text materials still render with line breaks', function () {
    $guru = User::factory()->guru()->create();
    $material = Material::factory()->create([
        'user_id' => $guru->id,
        'body' => "Baris pertama\nBaris kedua",
    ]);

    $response = $this->actingAs($guru)->get(route('materis.show', $material));

    $response->assertOk();
    $response->assertSee('Baris pertama<br />', false);
});

test('gambar-type material shows the image on the detail page', function () {
    Storage::fake('public');
    $guru = User::factory()->guru()->create();
    Storage::disk('public')->put('materials/diagram.png', 'isi-gambar');

    $material = Material::factory()->create([
        'user_id' => $guru->id,
        'type' => 'gambar',
        'body' => null,
        'file_path' => 'materials/diagram.png',
        'file_name' => 'diagram.png',
    ]);

    $response = $this->actingAs($guru)->get(route('materis.show', $material));

    $response->assertOk();
    $response->assertSee('storage/materials/diagram.png', false);
    $response->assertSee('Unduh Gambar');
});
