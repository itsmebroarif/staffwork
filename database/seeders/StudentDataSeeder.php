<?php

namespace Database\Seeders;

use App\Models\Kelas;
use App\Models\User;
use App\Models\WaliMurid;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StudentDataSeeder extends Seeder
{
    /**
     * Default password for the seeded student accounts.
     */
    private const STUDENT_PASSWORD = 'siswa12345';

    /**
     * Total student accounts to seed.
     */
    private const TOTAL_STUDENTS = 50;

    /**
     * Kelas for RPL and Multimedia departments (X, XI, XII).
     *
     * @var array<string, int>
     */
    private const KELAS = [
        'X RPL 1' => 10,
        'XI RPL 1' => 11,
        'XII RPL 1' => 12,
        'X MM 1' => 10,
        'XI MM 1' => 11,
        'XII MM 1' => 12,
    ];

    /**
     * Seed students and their guardians without deleting existing data.
     */
    public function run(): void
    {
        DB::transaction(function () {
            $kelasList = $this->seedKelas();
            $distribution = $this->distribution($kelasList);

            foreach ($distribution as $index => $kelas) {
                $this->seedStudent($index + 1, $kelas);
            }
        });
    }

    /**
     * @return array<string, Kelas>
     */
    private function seedKelas(): array
    {
        $kelasList = [];

        foreach (self::KELAS as $name => $tingkat) {
            $kelasList[$name] = Kelas::firstOrCreate(['name' => $name], ['tingkat' => $tingkat]);
        }

        return $kelasList;
    }

    /**
     * Spread the students evenly across all classes.
     *
     * @param  array<string, Kelas>  $kelasList
     * @return array<int, Kelas>
     */
    private function distribution(array $kelasList): array
    {
        $counts = [9, 8, 8, 9, 8, 8];
        $distribution = [];
        $position = 0;

        foreach (array_values($kelasList) as $index => $kelas) {
            for ($i = 0; $i < $counts[$index]; $i++) {
                $distribution[$position++] = $kelas;
            }
        }

        return $distribution;
    }

    private function seedStudent(int $number, Kelas $kelas): void
    {
        $faker = fake('id_ID');
        $nis = '2425'.str_pad((string) $number, 3, '0', STR_PAD_LEFT);
        $gender = $number % 2 === 0 ? 'female' : 'male';
        $name = $faker->name($gender).($number % 7 === 0 ? ' '.$faker->lastName() : '');
        $email = Str::slug($name).'.'.$number.'@siswa.staffwork.test';

        $student = User::firstOrCreate(
            ['nis' => $nis],
            [
                'name' => $name,
                'email' => $email,
                'role' => 'murid',
                'kelas_id' => $kelas->id,
                'phone' => '08'.$faker->numerify('##########'),
                'password' => Hash::make(self::STUDENT_PASSWORD),
                'password_plain' => self::STUDENT_PASSWORD,
                'email_verified_at' => now(),
            ]
        );

        $hubungan = $number % 2 === 0 ? 'Ibu' : 'Ayah';
        $guardianName = $hubungan === 'Ibu'
            ? $faker->name('female')
            : $faker->name('male');

        WaliMurid::firstOrCreate(
            ['student_id' => $student->id],
            [
                'name' => $guardianName,
                'hubungan' => $hubungan,
                'phone' => '08'.$faker->numerify('##########'),
                'email' => 'wali.'.$nis.'@staffwork.test',
                'alamat' => $faker->address(),
            ]
        );
    }
}
