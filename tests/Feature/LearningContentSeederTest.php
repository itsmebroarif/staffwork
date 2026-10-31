<?php

use App\Models\Exam;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Material;
use App\Models\TeachingAssignment;
use App\Models\TeachingSchedule;
use App\Models\User;
use Database\Seeders\LearningContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('seeder creates guru accounts with bidang, materials, and daily exams', function () {
    $this->seed(LearningContentSeeder::class);

    $teachers = User::where('role', 'guru')->get();

    expect($teachers)->toHaveCount(8);
    expect($teachers->pluck('bidang')->all())->toContain('Cyber Security', 'Frontend', 'Backend', 'UI/UX Design');
    expect(User::where('email', 'alfarobby@guru.staffwork.test')->value('bidang'))->toBe('Frontend');

    expect(Mapel::pluck('name')->all())->toContain('Web Dasar', 'Front-end', 'Back-end', 'Cyber Security', 'UI/UX Design');
    expect(Kelas::count())->toBeGreaterThan(0);
    expect(Material::count())->toBe(28);
    expect(Exam::where('type', 'tugas')->count())->toBe(5);
    expect(Exam::where('type', 'ujian')->count())->toBe(5);
    expect(TeachingAssignment::count())->toBe(16);
    expect(TeachingSchedule::count())->toBe(16);
});

test('seeder is idempotent and keeps existing users', function () {
    $existing = User::factory()->superadmin()->create([
        'name' => 'Arif Alexander',
        'email' => 'arifmail@mail.com',
    ]);

    $this->seed(LearningContentSeeder::class);
    $this->seed(LearningContentSeeder::class);

    expect(User::whereKey($existing->id)->exists())->toBeTrue();
    expect(User::count())->toBe(9);
    expect(Material::count())->toBe(28);
    expect(Exam::count())->toBe(10);
    expect(TeachingAssignment::count())->toBe(16);
    expect(TeachingSchedule::count())->toBe(16);
});
