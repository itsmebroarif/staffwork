<?php

use App\Models\Kelas;
use App\Models\Material;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

function makeMaterialForTingkat(?int $tingkat, string $title): Material
{
    $kelas = $tingkat === null ? null : Kelas::factory()->create(['tingkat' => $tingkat]);

    return Material::factory()->create([
        'title' => $title,
        'slug' => Str::slug($title).'-'.uniqid(),
        'kelas_id' => $kelas?->id,
    ]);
}

test('murid only sees general materials and materials for their own grade level', function () {
    $kelas1 = Kelas::factory()->create(['name' => 'Kelas 1', 'tingkat' => 1]);
    $murid = User::factory()->murid()->create(['kelas_id' => $kelas1->id]);

    makeMaterialForTingkat(null, 'Materi Umum Semua Siswa');
    makeMaterialForTingkat(1, 'Materi Khusus Tingkat 1');
    makeMaterialForTingkat(2, 'Materi Rahasia Tingkat 2');

    $response = $this->actingAs($murid)->get(route('materis.index'));

    $response->assertOk();
    $response->assertSee('Materi Umum Semua Siswa');
    $response->assertSee('Materi Khusus Tingkat 1');
    $response->assertDontSee('Materi Rahasia Tingkat 2');
});

test('murid cannot open material from another grade level', function () {
    $material = makeMaterialForTingkat(2, 'Materi Kelas 2');
    $kelas1 = Kelas::factory()->create(['tingkat' => 1]);
    $murid = User::factory()->murid()->create(['kelas_id' => $kelas1->id]);

    $this->actingAs($murid)->get(route('materis.show', $material))->assertStatus(403);
});

test('murid can open general and same-grade material', function () {
    $kelas1 = Kelas::factory()->create(['tingkat' => 1]);
    $murid = User::factory()->murid()->create(['kelas_id' => $kelas1->id]);

    $umum = makeMaterialForTingkat(null, 'Materi Umum');
    $satu = makeMaterialForTingkat(1, 'Materi Tingkat 1');

    $this->actingAs($murid)->get(route('materis.show', $umum))->assertOk();
    $this->actingAs($murid)->get(route('materis.show', $satu))->assertOk();
});

test('murid without kelas only sees general materials', function () {
    $murid = User::factory()->murid()->create(['kelas_id' => null]);

    makeMaterialForTingkat(null, 'Materi Umum Bebas');
    makeMaterialForTingkat(1, 'Materi Terbatas Tingkat 1');

    $response = $this->actingAs($murid)->get(route('materis.index'));

    $response->assertSee('Materi Umum Bebas');
    $response->assertDontSee('Materi Terbatas Tingkat 1');
});

test('guru can open material from any grade level', function () {
    $material = makeMaterialForTingkat(3, 'Materi Kelas 3');
    $guru = User::factory()->guru()->create();

    $this->actingAs($guru)->get(route('materis.show', $material))->assertOk();
});

test('guru can view materi form tabs', function () {
    $guru = User::factory()->guru()->create();
    Kelas::factory()->create(['name' => 'Kelas 1', 'tingkat' => 1]);
    $material = Material::factory()->create(['user_id' => $guru->id]);

    $create = $this->actingAs($guru)->get(route('materis.create'));
    $create->assertOk();
    $create->assertSee('Tulis Artikel');
    $create->assertSee('Input Gambar');
    $create->assertSee('Upload PDF/PPT');
    $create->assertSee('materi-editor', false);
    $create->assertSee(asset('template/vendor/ckeditor/ckeditor.js'), false);

    $this->actingAs($guru)->get(route('materis.edit', $material))->assertOk();
});

test('guru can create file-type material without body', function () {
    Storage::fake('public');
    $guru = User::factory()->guru()->create();
    $kelas = Kelas::factory()->create();

    $response = $this->actingAs($guru)->post(route('materis.store'), [
        'title' => 'Modul PPT Bilangan',
        'type' => 'file',
        'kelas_id' => $kelas->id,
        'file' => UploadedFile::fake()->create('modul.pptx', 500, 'application/vnd.openxmlformats-officedocument.presentationml.presentation'),
        'is_published' => 1,
    ]);

    $response->assertRedirect(route('materis.index'));

    $material = Material::where('title', 'Modul PPT Bilangan')->first();
    expect($material->type)->toBe('file');
    expect($material->body)->toBeNull();
    expect($material->file_path)->not->toBeNull();
    expect($material->kelas_id)->toBe($kelas->id);
});

test('artikel material requires body', function () {
    $guru = User::factory()->guru()->create();

    $response = $this->from(route('materis.create'))->actingAs($guru)->post(route('materis.store'), [
        'title' => 'Artikel Kosong',
        'type' => 'artikel',
        'is_published' => 1,
    ]);

    $response->assertSessionHasErrors('body');
});

test('file material requires uploaded file on create', function () {
    $guru = User::factory()->guru()->create();

    $response = $this->from(route('materis.create'))->actingAs($guru)->post(route('materis.store'), [
        'title' => 'File Hilang',
        'type' => 'file',
        'is_published' => 1,
    ]);

    $response->assertSessionHasErrors('file');
});
