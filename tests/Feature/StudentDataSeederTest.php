<?php

use App\Models\Kelas;
use App\Models\User;
use App\Models\WaliMurid;
use Database\Seeders\StudentDataSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('student seeder creates 50 students and guardians across RPL and multimedia classes', function () {
    $this->seed(StudentDataSeeder::class);

    expect(User::where('role', 'murid')->count())->toBe(50);
    expect(User::where('role', 'murid')->whereNotNull('kelas_id')->count())->toBe(50);
    expect(WaliMurid::count())->toBe(50);
    expect(Kelas::count())->toBe(6);

    expect(Kelas::whereIn('name', ['X RPL 1', 'XI RPL 1', 'XII RPL 1', 'X MM 1', 'XI MM 1', 'XII MM 1'])->count())->toBe(6);

    $students = User::where('role', 'murid')->with('kelas')->get();

    expect($students->pluck('nis')->filter()->unique())->toHaveCount(50);
    expect($students->every(fn ($student) => in_array($student->kelas?->tingkat, [10, 11, 12], true)))->toBeTrue();
});

test('student seeder is idempotent and keeps existing student data', function () {
    $this->seed(StudentDataSeeder::class);

    $student = User::where('nis', '2425001')->first();
    $originalName = $student->name;

    $this->seed(StudentDataSeeder::class);

    expect(User::where('role', 'murid')->count())->toBe(50);
    expect(WaliMurid::count())->toBe(50);
    expect($student->fresh()->name)->toBe($originalName);
});
