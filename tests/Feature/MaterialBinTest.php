<?php

use App\Models\Material;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

test('deleting a material moves it to the history bin instead of removing it', function () {
    $guru = User::factory()->guru()->create();
    $material = Material::factory()->create(['user_id' => $guru->id]);

    $this->actingAs($guru)->delete(route('materis.destroy', $material))
        ->assertRedirect(route('materis.index'));

    expect(Material::find($material->id))->toBeNull();
    expect(Material::withTrashed()->find($material->id))->not->toBeNull();
    expect(Material::onlyTrashed()->count())->toBe(1);
});

test('soft deleted material disappears from index and detail pages', function () {
    $guru = User::factory()->guru()->create();
    $material = Material::factory()->create(['user_id' => $guru->id, 'title' => 'Materi Dihapus']);

    $this->actingAs($guru)->delete(route('materis.destroy', $material));

    $this->actingAs($guru)->get(route('materis.index'))->assertDontSee('Materi Dihapus');
    $this->actingAs($guru)->get(route('materis.show', $material))->assertStatus(404);
});

test('guru can open the history bin and see trashed materials', function () {
    $guru = User::factory()->guru()->create();
    $material = Material::factory()->create(['user_id' => $guru->id, 'title' => 'Materi Ke Bin']);
    $this->actingAs($guru)->delete(route('materis.destroy', $material));

    $response = $this->actingAs($guru)->get(route('materis.bin'));

    $response->assertOk();
    $response->assertSee('History Bin Materi');
    $response->assertSee('Materi Ke Bin');
});

test('murid cannot access the history bin', function () {
    $murid = User::factory()->murid()->create();

    $this->actingAs($murid)->get(route('materis.bin'))->assertStatus(403);
});

test('guru can restore a trashed material', function () {
    $guru = User::factory()->guru()->create();
    $material = Material::factory()->create(['user_id' => $guru->id, 'title' => 'Materi Dipulihkan']);
    $this->actingAs($guru)->delete(route('materis.destroy', $material));

    $this->actingAs($guru)->post(route('materis.restore', $material))
        ->assertRedirect(route('materis.bin'));

    expect(Material::onlyTrashed()->count())->toBe(0);
    $this->actingAs($guru)->get(route('materis.index'))->assertSee('Materi Dipulihkan');
});

test('guru can permanently delete a trashed material along with its file', function () {
    Storage::fake('public');
    $guru = User::factory()->guru()->create();
    $material = Material::factory()->create([
        'user_id' => $guru->id,
        'type' => 'file',
        'file_path' => 'materials/modul.pdf',
        'file_name' => 'modul.pdf',
    ]);
    Storage::disk('public')->put('materials/modul.pdf', 'isi-file');
    $this->actingAs($guru)->delete(route('materis.destroy', $material));

    $this->actingAs($guru)->delete(route('materis.force', $material))
        ->assertRedirect(route('materis.bin'));

    expect(Material::withTrashed()->find($material->id))->toBeNull();
    Storage::disk('public')->assertMissing('materials/modul.pdf');
});

test('permanently deleting a material also removes images embedded in the body', function () {
    Storage::fake('public');
    $guru = User::factory()->guru()->create();
    $material = Material::factory()->create([
        'user_id' => $guru->id,
        'body' => '<p>Lihat gambar berikut:</p><img src="'.asset('storage/materials/images/ilustrasi.png').'" alt="ilustrasi">',
    ]);
    Storage::disk('public')->put('materials/images/ilustrasi.png', 'isi-gambar');
    $this->actingAs($guru)->delete(route('materis.destroy', $material));

    $this->actingAs($guru)->delete(route('materis.force', $material));

    Storage::disk('public')->assertMissing('materials/images/ilustrasi.png');
});

test('restore and force delete routes are not reachable for trashed materials by murid', function () {
    $guru = User::factory()->guru()->create();
    $murid = User::factory()->murid()->create();
    $material = Material::factory()->create(['user_id' => $guru->id]);
    $this->actingAs($guru)->delete(route('materis.destroy', $material));

    $this->actingAs($murid)->post(route('materis.restore', $material))->assertStatus(403);
    $this->actingAs($murid)->delete(route('materis.force', $material))->assertStatus(403);
});

test('creating a material cannot reuse the slug of a trashed material', function () {
    $guru = User::factory()->guru()->create();
    $material = Material::factory()->create(['user_id' => $guru->id, 'title' => 'Slug Unik']);
    $this->actingAs($guru)->delete(route('materis.destroy', $material));

    $this->actingAs($guru)->post(route('materis.store'), [
        'title' => 'Slug Unik',
        'type' => 'artikel',
        'body' => '<p>Isi materi baru.</p>',
        'is_published' => 1,
    ])->assertRedirect(route('materis.index'));

    expect(Material::where('title', 'Slug Unik')->count())->toBe(1);
    expect(Material::withTrashed()->where('title', 'Slug Unik')->count())->toBe(2);
});
