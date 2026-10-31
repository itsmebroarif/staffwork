<?php

namespace Database\Seeders;

use App\Models\Exam;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Material;
use App\Models\Question;
use App\Models\TeachingAssignment;
use App\Models\TeachingSchedule;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LearningContentSeeder extends Seeder
{
    /**
     * Default password for the seeded guru accounts.
     */
    private const TEACHER_PASSWORD = 'guru12345';

    /**
     * Guru accounts with the bidang (keahlian) each one teaches.
     *
     * @var array<int, array{name: string, bidang: string, mapel: string, nip: string}>
     */
    private const TEACHERS = [
        ['name' => 'Fahrul Saputra', 'bidang' => 'Manajemen Bisnis', 'mapel' => 'MB', 'nip' => 'G-2001'],
        ['name' => 'Arif Permana Putrasuryana', 'bidang' => 'Cyber Security', 'mapel' => 'CYBER', 'nip' => 'G-2002'],
        ['name' => 'Arief Ramadhan Al-Hazmi', 'bidang' => 'Digital Marketing', 'mapel' => 'DM', 'nip' => 'G-2003'],
        ['name' => 'Hanif Wisanggeni Prabowo', 'bidang' => 'Backend', 'mapel' => 'BE', 'nip' => 'G-2004'],
        ['name' => 'Alfarobby', 'bidang' => 'Frontend', 'mapel' => 'FE', 'nip' => 'G-2005'],
        ['name' => 'Muhammad Nur Maulana', 'bidang' => 'UI/UX Design', 'mapel' => 'UIUX', 'nip' => 'G-2006'],
        ['name' => 'Muhammad Bagus Harianto', 'bidang' => 'Full Stack Developer', 'mapel' => 'FSD', 'nip' => 'G-2007'],
        ['name' => 'Fahrul Rozi', 'bidang' => 'CI/CD', 'mapel' => 'CICD', 'nip' => 'G-2008'],
    ];

    /**
     * Mata pelajaran for every bidang, including the coding subjects.
     *
     * @var array<string, string>
     */
    private const MAPELS = [
        'WEB' => 'Web Dasar',
        'FE' => 'Front-end',
        'BE' => 'Back-end',
        'CYBER' => 'Cyber Security',
        'UIUX' => 'UI/UX Design',
        'MB' => 'Manajemen Bisnis',
        'DM' => 'Digital Marketing',
        'FSD' => 'Full Stack Developer',
        'CICD' => 'CI/CD',
    ];

    /**
     * Kelas used for teaching assignments and schedules.
     *
     * @var array<string, int>
     */
    private const KELAS = [
        'X RPL 1' => 10,
        'XI RPL 1' => 11,
    ];

    /**
     * Time slots per kelas, one slot per teacher (index based).
     *
     * @var array<string, array<int, array{0: string, 1: string, 2: string}>>
     */
    private const SLOTS = [
        'X RPL 1' => [
            ['senin', '07:00', '08:30'],
            ['selasa', '07:00', '08:30'],
            ['rabu', '07:00', '08:30'],
            ['kamis', '07:00', '08:30'],
            ['jumat', '07:00', '08:30'],
            ['senin', '08:30', '10:00'],
            ['selasa', '08:30', '10:00'],
            ['rabu', '08:30', '10:00'],
        ],
        'XI RPL 1' => [
            ['senin', '10:15', '11:45'],
            ['selasa', '10:15', '11:45'],
            ['rabu', '10:15', '11:45'],
            ['kamis', '10:15', '11:45'],
            ['jumat', '10:15', '11:45'],
            ['senin', '12:30', '14:00'],
            ['selasa', '12:30', '14:00'],
            ['rabu', '12:30', '14:00'],
        ],
    ];

    /**
     * Seed the learning content without deleting existing data.
     */
    public function run(): void
    {
        DB::transaction(function () {
            $teachers = $this->seedTeachers();
            $this->seedMapelsAndKelas();
            $this->seedAssignmentsAndSchedules($teachers);
            $this->seedMaterials($teachers);
            $this->seedExams($teachers);
        });
    }

    /**
     * Create the guru accounts (idempotent, existing accounts are left untouched).
     *
     * @return array<string, User>
     */
    private function seedTeachers(): array
    {
        $teachers = [];

        foreach (self::TEACHERS as $teacher) {
            $email = Str::slug($teacher['name']).'@guru.staffwork.test';

            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $teacher['name'],
                    'role' => 'guru',
                    'bidang' => $teacher['bidang'],
                    'nip' => $teacher['nip'],
                    'password' => Hash::make(self::TEACHER_PASSWORD),
                    'password_plain' => self::TEACHER_PASSWORD,
                ]
            );

            if (! $user->wasRecentlyCreated) {
                $user->update([
                    'name' => $teacher['name'],
                    'role' => 'guru',
                    'bidang' => $teacher['bidang'],
                    'nip' => $teacher['nip'],
                ]);
            }

            $teachers[$teacher['mapel']] = $user;
        }

        return $teachers;
    }

    private function seedMapelsAndKelas(): void
    {
        foreach (self::MAPELS as $code => $name) {
            Mapel::updateOrCreate(['code' => $code], ['name' => $name]);
        }

        foreach (self::KELAS as $name => $tingkat) {
            Kelas::firstOrCreate(['name' => $name], ['tingkat' => $tingkat]);
        }
    }

    /**
     * Assign every guru to the kelas they teach and give each one a schedule.
     *
     * @param  array<string, User>  $teachers
     */
    private function seedAssignmentsAndSchedules(array $teachers): void
    {
        $index = 0;

        foreach (self::TEACHERS as $teacher) {
            $user = $teachers[$teacher['mapel']];
            $mapel = Mapel::where('code', $teacher['mapel'])->first();

            if ($user === null || $mapel === null) {
                continue;
            }

            foreach (array_keys(self::KELAS) as $kelasName) {
                $kelas = Kelas::where('name', $kelasName)->first();

                if ($kelas === null) {
                    continue;
                }

                $assignment = TeachingAssignment::firstOrCreate([
                    'teacher_id' => $user->id,
                    'mapel_id' => $mapel->id,
                    'kelas_id' => $kelas->id,
                ]);

                [$day, $start, $end] = self::SLOTS[$kelasName][$index];

                TeachingSchedule::firstOrCreate([
                    'assignment_id' => $assignment->id,
                    'day' => $day,
                    'start_time' => $start,
                ], [
                    'end_time' => $end,
                ]);
            }

            $index++;
        }
    }

    /**
     * Seed materials for the coding subjects.
     *
     * @param  array<string, User>  $teachers
     */
    private function seedMaterials(array $teachers): void
    {
        foreach ($this->materials() as $subject => $materials) {
            $author = $teachers[$subject] ?? $teachers['FSD'] ?? null;
            $mapel = Mapel::where('code', $subject)->first();

            if ($author === null) {
                continue;
            }

            foreach ($materials as $material) {
                $record = Material::firstOrCreate([
                    'slug' => Str::slug($material['title']),
                ], [
                    'user_id' => $author->id,
                    'title' => $material['title'],
                    'type' => 'artikel',
                    'kelas_id' => null,
                    'mapel_id' => $mapel?->id,
                    'body' => $material['body'],
                    'file_path' => null,
                    'file_name' => null,
                    'is_published' => true,
                ]);

                if ($record->mapel_id === null && $mapel !== null) {
                    $record->update(['mapel_id' => $mapel->id]);
                }
            }
        }
    }

    /**
     * Seed daily tugas and ujian with multiple-choice questions.
     *
     * @param  array<string, User>  $teachers
     */
    private function seedExams(array $teachers): void
    {
        foreach ($this->exams() as $subject => $exams) {
            $author = $teachers[$subject] ?? null;

            if ($author === null) {
                continue;
            }

            foreach ($exams as $examData) {
                $exam = Exam::firstOrCreate([
                    'title' => $examData['title'],
                ], [
                    'user_id' => $author->id,
                    'description' => $examData['description'],
                    'type' => $examData['type'],
                    'duration_minutes' => $examData['duration_minutes'],
                    'available_at' => now(),
                ]);

                if ($exam->wasRecentlyCreated) {
                    foreach ($examData['questions'] as $order => $question) {
                        Question::create([
                            'exam_id' => $exam->id,
                            'question' => $question['question'],
                            'option_a' => $question['option_a'],
                            'option_b' => $question['option_b'],
                            'option_c' => $question['option_c'],
                            'option_d' => $question['option_d'],
                            'correct_answer' => $question['correct_answer'],
                            'order' => $order + 1,
                        ]);
                    }
                }
            }
        }
    }

    /**
     * Learning materials grouped by mapel code.
     *
     * @return array<string, array<int, array{title: string, body: string}>>
     */
    private function materials(): array
    {
        return [
            'WEB' => [
                [
                    'title' => 'Web Dasar: Cara Kerja Website & Struktur HTML',
                    'body' => <<<'HTML'
<h4>Cara Kerja Website</h4>
<p>Ketika kamu membuka sebuah alamat web, browser mengirim permintaan (request) ke server. Server membalas dengan file HTML, CSS, dan JavaScript, lalu browser merendernya menjadi halaman yang bisa kamu lihat dan klik.</p>
<ul>
<li><strong>HTML</strong> &mdash; kerangka dan isi halaman.</li>
<li><strong>CSS</strong> &mdash; tampilan, warna, dan tata letak.</li>
<li><strong>JavaScript</strong> &mdash; interaksi dan logika di browser.</li>
</ul>
<h5>Struktur dasar HTML</h5>
<pre><code>&lt;!DOCTYPE html&gt;
&lt;html lang="id"&gt;
  &lt;head&gt;
    &lt;meta charset="UTF-8"&gt;
    &lt;title&gt;Halaman Pertamaku&lt;/title&gt;
  &lt;/head&gt;
  &lt;body&gt;
    &lt;h1&gt;Halo, Dunia!&lt;/h1&gt;
    &lt;p&gt;Ini paragraf pertama saya.&lt;/p&gt;
  &lt;/body&gt;
&lt;/html&gt;</code></pre>
<h5>Tag yang sering dipakai</h5>
<ul>
<li><code>&lt;h1&gt;</code> sampai <code>&lt;h6&gt;</code> untuk judul bertingkat.</li>
<li><code>&lt;p&gt;</code> untuk paragraf dan <code>&lt;a href="..."&gt;</code> untuk tautan.</li>
<li><code>&lt;img&gt;</code> untuk gambar, <code>&lt;ul&gt;</code> dan <code>&lt;ol&gt;</code> untuk daftar.</li>
</ul>
<p><strong>Latihan:</strong> buat halaman profil sederhana berisi nama, foto, dan tiga tautan media sosial.</p>
HTML,
                ],
                [
                    'title' => 'Web Dasar: CSS Dasar & Layout dengan Flexbox',
                    'body' => <<<'HTML'
<h4>Menghubungkan CSS ke HTML</h4>
<p>CSS bisa ditulis langsung di dalam tag <code>&lt;style&gt;</code> atau di file terpisah yang dipanggil dengan <code>&lt;link rel="stylesheet" href="style.css"&gt;</code>.</p>
<h5>Selector dan properti umum</h5>
<pre><code>body {
  font-family: Arial, sans-serif;
  background: #f8f9fa;
}

.card {
  padding: 16px;
  border: 1px solid #dee2e6;
  border-radius: 8px;
  box-shadow: 0 2px 6px rgba(0, 0, 0, .08);
}</code></pre>
<ul>
<li><code>.card</code> memilih elemen dengan class <em>card</em>, sedangkan <code>#header</code> memilih id.</li>
<li><strong>Margin</strong> adalah jarak di luar elemen, <strong>padding</strong> adalah jarak di dalam elemen.</li>
</ul>
<h5>Layout dengan Flexbox</h5>
<pre><code>.row {
  display: flex;
  gap: 16px;
  align-items: center;
  justify-content: space-between;
}</code></pre>
<p><strong>Latihan:</strong> ubah daftar kartu materi menjadi tiga kolom sejajar menggunakan Flexbox, lalu tambahkan media query agar menjadi satu kolom di layar ponsel.</p>
HTML,
                ],
                [
                    'title' => 'Web Dasar: Form HTML & Validasi Input',
                    'body' => <<<'HTML'
<h4>Membuat Form yang Mudah Dipakai</h4>
<p>Form adalah cara utama pengguna mengirim data ke server: login, pendaftaran, upload materi, dan lain-lain.</p>
<pre><code>&lt;form action="/materis" method="POST"&gt;
  &lt;label for="judul"&gt;Judul Materi&lt;/label&gt;
  &lt;input type="text" id="judul" name="title" required minlength="5"&gt;

  &lt;label for="isi"&gt;Isi Materi&lt;/label&gt;
  &lt;textarea id="isi" name="body" rows="5" required&gt;&lt;/textarea&gt;

  &lt;button type="submit"&gt;Simpan&lt;/button&gt;
&lt;/form&gt;</code></pre>
<h5>Atribut validasi bawaan browser</h5>
<ul>
<li><code>required</code> &mdash; kolom wajib diisi.</li>
<li><code>type="email"</code> &mdash; format email diperiksa otomatis.</li>
<li><code>minlength</code> dan <code>maxlength</code> &mdash; batasi panjang teks.</li>
<li><code>pattern</code> &mdash; validasi dengan ekspresi reguler.</li>
</ul>
<p>Validasi browser hanya membantu di sisi klien. Server tetap wajib memvalidasi ulang seluruh input.</p>
<p><strong>Latihan:</strong> buat form kontak dengan nama, email, dan pesan, lalu tampilkan pesan error di bawah setiap kolom.</p>
HTML,
                ],
                [
                    'title' => 'Web Dasar: Dasar Responsive Web Design',
                    'body' => <<<'HTML'
<h4>Mengapa Perlu Responsif</h4>
<p>Pengguna membuka website dari ponsel, tablet, dan laptop. Desain responsif membuat tampilan tetap nyaman di semua ukuran layar.</p>
<h5>Viewport meta tag</h5>
<pre><code>&lt;meta name="viewport" content="width=device-width, initial-scale=1"&gt;</code></pre>
<h5>Media query</h5>
<pre><code>.grid-materi {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 16px;
}

@media (max-width: 768px) {
  .grid-materi {
    grid-template-columns: 1fr;
  }
}</code></pre>
<h5>Pendekatan mobile first</h5>
<p>Tulis gaya untuk layar kecil terlebih dahulu, lalu tambahkan aturan untuk layar lebih besar dengan <code>min-width</code>.</p>
<ul>
<li>Gunakan satuan relatif seperti <code>rem</code> dan <code>%</code>.</li>
<li>Pastikan ukuran tombol minimal 44px agar mudah disentuh.</li>
<li>Jangan mengandalkan warna saja untuk menyampaikan informasi.</li>
</ul>
<p><strong>Latihan:</strong> buat kartu materi yang menjadi satu kolom di ponsel dan tiga kolom di desktop.</p>
HTML,
                ],
            ],
            'FE' => [
                [
                    'title' => 'Front-end: JavaScript Dasar untuk Interaksi Halaman',
                    'body' => <<<'HTML'
<h4>Variabel dan Tipe Data</h4>
<p>Gunakan <code>const</code> untuk nilai yang tidak berubah dan <code>let</code> untuk nilai yang bisa berubah. Hindari <code>var</code> pada kode baru.</p>
<pre><code>const namaSiswa = "Rani";
let nilai = 90;

console.log(`${namaSiswa} mendapat nilai ${nilai}`);</code></pre>
<h5>Fungsi</h5>
<pre><code>function hitungRataRata(a, b) {
  return (a + b) / 2;
}</code></pre>
<h5>Event pada tombol</h5>
<pre><code>const tombol = document.getElementById("simpan");

tombol.addEventListener("click", () =&gt; {
  alert("Data berhasil disimpan!");
});</code></pre>
<p><strong>Latihan:</strong> buat tombol yang menambah counter setiap kali diklik dan menampilkan jumlah klik di halaman.</p>
HTML,
                ],
                [
                    'title' => 'Front-end: Mengambil Data dengan Fetch API',
                    'body' => <<<'HTML'
<h4>Request ke Server</h4>
<p><code>fetch()</code> mengirim permintaan HTTP dan mengembalikan sebuah <em>Promise</em>. Gunakan <code>async/await</code> agar kodenya mudah dibaca.</p>
<pre><code>async function muatMateri() {
  const response = await fetch("/api/materi");
  const data = await response.json();

  renderMateri(data);
}</code></pre>
<h5>Menampilkan hasil ke halaman</h5>
<pre><code>function renderMateri(items) {
  const list = document.getElementById("daftar-materi");
  list.innerHTML = "";

  items.forEach((item) =&gt; {
    const li = document.createElement("li");
    li.textContent = item.title;
    list.appendChild(li);
  });
}</code></pre>
<h5>Menangani error</h5>
<pre><code>try {
  await muatMateri();
} catch (error) {
  console.error("Gagal memuat materi:", error);
}</code></pre>
<p><strong>Latihan:</strong> tampilkan daftar materi dari endpoint publik, sertakan pesan loading dan pesan error.</p>
HTML,
                ],
                [
                    'title' => 'Front-end: Manipulasi DOM & State Sederhana',
                    'body' => <<<'HTML'
<h4>DOM dan State</h4>
<p>DOM adalah representasi halaman di memori browser. JavaScript bisa mengubah isi elemen tanpa memuat ulang halaman.</p>
<pre><code>const daftar = document.getElementById("daftar-tugas");
const tombol = document.getElementById("tambah-tugas");

let tugas = [];

tombol.addEventListener("click", () =&gt; {
  tugas.push("Tugas " + (tugas.length + 1));
  render();
});</code></pre>
<h5>Render ulang dari state</h5>
<pre><code>function render() {
  daftar.innerHTML = "";

  tugas.forEach((item, index) =&gt; {
    const li = document.createElement("li");
    li.textContent = item;

    const hapus = document.createElement("button");
    hapus.textContent = "Hapus";
    hapus.addEventListener("click", () =&gt; {
      tugas.splice(index, 1);
      render();
    });

    li.appendChild(hapus);
    daftar.appendChild(li);
  });
}</code></pre>
<p>Simpan data pada satu sumber kebenaran (<em>state</em>), lalu gambar ulang tampilan dari state tersebut. Pola ini mencegah tampilan dan data tidak sinkron.</p>
HTML,
                ],
                [
                    'title' => 'Front-end: Styling Modern dengan Utility Class',
                    'body' => <<<'HTML'
<h4>Utility Class</h4>
<p>Utility class adalah class kecil dengan satu tugas, misalnya <code>mt-2</code> untuk margin atas atau <code>text-center</code> untuk rata tengah. Pola ini mempercepat penyusunan tampilan dan menjaga konsistensi.</p>
<pre><code>&lt;div class="card p-3 mb-3 shadow-sm"&gt;
  &lt;h5 class="mb-1 font-weight-bold"&gt;Judul Materi&lt;/h5&gt;
  &lt;p class="text-muted small mb-0"&gt;Ringkasan materi.&lt;/p&gt;
&lt;/div&gt;</code></pre>
<h5>Kapan memakai utility class</h5>
<ul>
<li>Untuk jarak, warna, ukuran teks, dan perataan.</li>
<li>Untuk komponen kecil yang dipakai berulang.</li>
<li>Pisahkan ke CSS khusus bila aturan mulai panjang dan kompleks.</li>
</ul>
<h5>Menjaga konsistensi</h5>
<p>Tentukan skala jarak (4, 8, 12, 16, 24px), ukuran teks, dan palet warna. Hindari nilai acak yang tidak tercatat.</p>
<p><strong>Latihan:</strong> ubah satu halaman yang memakai banyak inline style menjadi utility class yang seragam.</p>
HTML,
                ],
            ],
            'BE' => [
                [
                    'title' => 'Back-end: Memahami Routing, Controller, dan Request',
                    'body' => <<<'HTML'
<h4>Alur Request di Back-end</h4>
<p>Browser mengirim request ke sebuah URL. Routing mencocokkan URL tersebut ke controller, controller memproses data, lalu mengembalikan view atau JSON.</p>
<pre><code>// routes/web.php
Route::get('/materis', [MateriController::class, 'index'])-&gt;name('materis.index');
Route::post('/materis', [MateriController::class, 'store'])-&gt;name('materis.store');</code></pre>
<h5>Controller</h5>
<pre><code>public function store(Request $request)
{
    $validated = $request-&gt;validate([
        'title' =&gt; ['required', 'string', 'max:255'],
        'body'  =&gt; ['required', 'string'],
    ]);

    Material::create($validated);

    return redirect()-&gt;route('materis.index');
}</code></pre>
<h5>Status code penting</h5>
<ul>
<li><code>200</code> berhasil, <code>201</code> data dibuat.</li>
<li><code>302</code> redirect setelah form disimpan.</li>
<li><code>403</code> tidak punya akses, <code>404</code> data tidak ditemukan, <code>422</code> validasi gagal.</li>
</ul>
<p><strong>Latihan:</strong> tambahkan validasi agar judul materi minimal 5 karakter dan tampilkan pesan error di form.</p>
HTML,
                ],
                [
                    'title' => 'Back-end: Relasi Database & Query Eloquent',
                    'body' => <<<'HTML'
<h4>Menyiapkan Tabel</h4>
<pre><code>Schema::create('materials', function (Blueprint $table) {
    $table-&gt;id();
    $table-&gt;foreignId('user_id')-&gt;constrained();
    $table-&gt;string('title');
    $table-&gt;text('body');
    $table-&gt;timestamps();
});</code></pre>
<p>Jalankan <code>php artisan migrate</code> untuk membuat tabelnya.</p>
<h5>Relasi Eloquent</h5>
<pre><code>class Material extends Model
{
    public function author()
    {
        return $this-&gt;belongsTo(User::class, 'user_id');
    }
}</code></pre>
<h5>Query yang sering dipakai</h5>
<pre><code>$materials = Material::with('author')
    ->where('is_published', true)
    ->latest()
    ->paginate(9);

$jumlah = Material::count();

Material::create([
    'user_id' =&gt; auth()->id(),
    'title'   =&gt; 'Materi Baru',
    'body'    =&gt; 'Isi materi',
]);</code></pre>
<p><strong>Latihan:</strong> tampilkan jumlah materi per guru menggunakan relasi dan <code>withCount</code>.</p>
HTML,
                ],
                [
                    'title' => 'Back-end: Autentikasi & Otorisasi Dasar',
                    'body' => <<<'HTML'
<h4>Autentikasi vs Otorisasi</h4>
<p><strong>Autentikasi</strong> memastikan siapa pengguna. <strong>Otorisasi</strong> memastikan apa yang boleh ia lakukan.</p>
<h5>Middleware peran</h5>
<pre><code>Route::middleware(['auth', 'guru'])-&gt;group(function () {
    Route::post('/materis', [MateriController::class, 'store']);
});</code></pre>
<h5>Pemeriksaan di level aksi</h5>
<pre><code>public function destroy(Request $request, Material $material)
{
    abort_unless($request-&gt;user()-&gt;isTeacher(), 403);

    $material-&gt;delete();
}</code></pre>
<h5>Aturan penting</h5>
<ul>
<li>Jangan hanya menyembunyikan tombol; tetap periksa hak akses di server.</li>
<li>Gunakan HTTPS agar kredensial tidak dikirim terang-terangan.</li>
<li>Regenerasi session setelah login untuk mencegah session fixation.</li>
</ul>
<p><strong>Latihan:</strong> tambahkan pengecekan agar guru hanya bisa mengubah materi miliknya sendiri.</p>
HTML,
                ],
                [
                    'title' => 'Back-end: Validasi, Error Handling, dan Log',
                    'body' => <<<'HTML'
<h4>Validasi Input</h4>
<pre><code>$validated = $request-&gt;validate([
    'title' =&gt; ['required', 'string', 'max:255'],
    'body'  =&gt; ['required', 'string'],
    'kelas_id' =&gt; ['nullable', 'exists:kelas,id'],
]);</code></pre>
<h5>Menangani kegagalan</h5>
<pre><code>try {
    DB::transaction(function () use ($validated) {
        Material::create($validated);
    });
} catch (Throwable $e) {
    report($e);

    return back()-&gt;with('error', 'Materi gagal disimpan.');
}</code></pre>
<h5>Log yang berguna</h5>
<ul>
<li>Catat konteks: ID pengguna, aksi, dan data penting (tanpa kata sandi).</li>
<li>Gunakan level yang tepat: <code>info</code>, <code>warning</code>, <code>error</code>.</li>
<li>Kembalikan pesan yang ramah pengguna, bukan detail teknis.</li>
</ul>
<p><strong>Latihan:</strong> tambahkan validasi dan pesan error yang jelas pada form materi.</p>
HTML,
                ],
            ],
            'CYBER' => [
                [
                    'title' => 'Cyber Security: Mengamankan Akun & Kata Sandi',
                    'body' => <<<'HTML'
<h4>Mengapa Keamanan Akun Penting</h4>
<p>Satu akun yang bocor bisa membuka data sekolah, nilai, hingga akun lain yang memakai kata sandi sama. Keamanan dimulai dari kebiasaan kecil.</p>
<h5>Aturan kata sandi yang kuat</h5>
<ul>
<li>Minimal 12 karakter, gabungan huruf besar, kecil, angka, dan simbol.</li>
<li>Jangan memakai tanggal lahir, nama, atau kata "password".</li>
<li>Gunakan kata sandi berbeda untuk setiap layanan.</li>
<li>Aktifkan autentikasi dua faktor (2FA) bila tersedia.</li>
</ul>
<h5>Penyimpanan kata sandi di server</h5>
<p>Kata sandi tidak boleh disimpan sebagai teks biasa. Simpan hasil hash dengan algoritma lambat seperti bcrypt atau argon2.</p>
<pre><code>$hash = password_hash($password, PASSWORD_BCRYPT);

if (password_verify($input, $hash)) {
    // kata sandi cocok
}</code></pre>
<h5>Mengenali phishing</h5>
<p>Periksa domain pengirim, jangan klik tautan mencurigakan, dan jangan pernah membagikan kode OTP kepada siapa pun.</p>
HTML,
                ],
                [
                    'title' => 'Cyber Security: Mengenal OWASP Top 10 & SQL Injection',
                    'body' => <<<'HTML'
<h4>OWASP Top 10</h4>
<p>OWASP Top 10 adalah daftar risiko keamanan aplikasi web yang paling sering terjadi, antara lain broken access control, kesalahan konfigurasi, dan injection.</p>
<h5>Contoh SQL Injection yang rentan</h5>
<pre><code>// JANGAN lakukan ini
$email = $_GET['email'];
$query = "SELECT * FROM users WHERE email = '$email'";</code></pre>
<p>Jika penyerang mengirim <code>' OR '1'='1</code>, query berubah dan seluruh data bisa terbaca.</p>
<h5>Cara aman dengan query binding</h5>
<pre><code>$user = User::where('email', $request-&gt;input('email'))-&gt;first();

DB::select('SELECT * FROM users WHERE email = ?', [$email]);</code></pre>
<h5>Checklist keamanan dasar</h5>
<ul>
<li>Validasi semua input dan gunakan prepared statement.</li>
<li>Escape output untuk mencegah XSS.</li>
<li>Terapkan otorisasi di setiap endpoint, bukan hanya menyembunyikan tombol.</li>
<li>Perbarui dependency secara berkala.</li>
</ul>
<p><strong>Latihan:</strong> temukan satu form pada aplikasi latihan yang belum memvalidasi input, lalu perbaiki.</p>
HTML,
                ],
                [
                    'title' => 'Cyber Security: Keamanan Web & Pengelolaan Akses',
                    'body' => <<<'HTML'
<h4>Prinsip Hak Akses Minimum</h4>
<p>Setiap pengguna hanya boleh mendapat akses seperlunya. Guru mengelola materi, murid membaca dan mengerjakan tugas, super admin mengelola akun.</p>
<h5>Kesalahan konfigurasi yang sering terjadi</h5>
<ul>
<li>Halaman admin hanya disembunyikan di menu, tanpa middleware.</li>
<li>File konfigurasi atau backup dapat diunduh publik.</li>
<li>Pesan error menampilkan detail teknis di produksi.</li>
<li>Akun default masih memakai kata sandi bawaan.</li>
</ul>
<h5>Header keamanan</h5>
<pre><code>Content-Security-Policy: default-src 'self'
X-Content-Type-Options: nosniff
X-Frame-Options: SAMEORIGIN
Referrer-Policy: strict-origin-when-cross-origin</code></pre>
<h5>Checklist rilis</h5>
<ol>
<li>Matikan mode debug (<code>APP_DEBUG=false</code>).</li>
<li>Gunakan HTTPS dan cookie <code>Secure</code> + <code>HttpOnly</code>.</li>
<li>Perbarui dependency dan hapus akun uji yang tidak terpakai.</li>
</ol>
HTML,
                ],
                [
                    'title' => 'Cyber Security: Backup, Enkripsi, dan Respons Insiden',
                    'body' => <<<'HTML'
<h4>Backup yang Berguna</h4>
<p>Backup baru berguna bila bisa dipulihkan. Uji proses restore secara berkala, bukan hanya membuat file cadangan.</p>
<ul>
<li>Aturan 3-2-1: tiga salinan, dua media berbeda, satu di lokasi terpisah.</li>
<li>Enkripsi file backup dan simpan kunci aksesnya terpisah.</li>
<li>Catat jadwal backup dan siapa yang bertanggung jawab.</li>
</ul>
<h5>Enkripsi data sensitif</h5>
<p>Data pribadi siswa dan guru sebaiknya dienkripsi saat disimpan maupun dikirim. Gunakan TLS untuk data yang bergerak dan enkripsi simetris untuk data yang disimpan.</p>
<h5>Langkah respons insiden</h5>
<ol>
<li>Identifikasi dan hentikan penyebaran (cabut akses, ganti kredensial).</li>
<li>Simpan bukti log sebelum membersihkan sistem.</li>
<li>Pulihkan dari backup yang terverifikasi.</li>
<li>Evaluasi penyebab dan perbaiki celahnya.</li>
<li>Beri tahu pihak terkait sesuai kebutuhan.</li>
</ol>
HTML,
                ],
            ],
            'UIUX' => [
                [
                    'title' => 'UI/UX Design: Prinsip Dasar Desain Antarmuka',
                    'body' => <<<'HTML'
<h4>UI dan UX</h4>
<p><strong>UX</strong> berfokus pada pengalaman pengguna saat menyelesaikan tugas, sedangkan <strong>UI</strong> berfokus pada tampilan visual seperti warna, tipografi, dan ikon.</p>
<h5>Prinsip desain yang wajib dipahami</h5>
<ul>
<li><strong>Hierarki</strong> &mdash; elemen terpenting terlihat pertama.</li>
<li><strong>Kontras</strong> &mdash; teks harus mudah dibaca di atas latar.</li>
<li><strong>Konsistensi</strong> &mdash; tombol dan warna memakai pola yang sama.</li>
<li><strong>Feedback</strong> &mdash; pengguna tahu apa yang terjadi setelah menekan tombol.</li>
<li><strong>Aksesibilitas</strong> &mdash; ukuran teks dan kontras memadai untuk semua pengguna.</li>
</ul>
<h5>Sistem desain sederhana</h5>
<pre><code>Warna utama   : #2563eb
Warna netral  : #f8f9fa, #6b7280, #111827
Judul         : 24px / bold
Isi           : 16px / regular
Radius sudut  : 8px
Jarak antar bagian: 16px</code></pre>
<p><strong>Latihan:</strong> susun ulang satu halaman form agar memiliki hierarki jelas dan pesan error yang mudah dipahami.</p>
HTML,
                ],
                [
                    'title' => 'UI/UX Design: Dari Wireframe ke Prototype',
                    'body' => <<<'HTML'
<h4>Alur Kerja Desain</h4>
<ol>
<li><strong>Riset</strong> &mdash; pahami pengguna dan masalahnya.</li>
<li><strong>User flow</strong> &mdash; gambarkan langkah pengguna dari awal sampai tujuan.</li>
<li><strong>Wireframe</strong> &mdash; sketsa kasar tata letak tanpa warna.</li>
<li><strong>Prototype</strong> &mdash; versi interaktif untuk diuji.</li>
<li><strong>Usability testing</strong> &mdash; amati pengguna nyata memakai desain.</li>
</ol>
<h5>Contoh user flow login</h5>
<pre><code>Buka aplikasi -&gt; Isi email &amp; password -&gt; Klik Masuk
  -&gt; Berhasil: Dashboard
  -&gt; Gagal: Pesan error &amp; coba lagi</code></pre>
<h5>Tips membuat wireframe</h5>
<ul>
<li>Fokus pada tata letak dan alur, bukan warna.</li>
<li>Gunakan satu tujuan utama per halaman.</li>
<li>Buat versi mobile dan desktop.</li>
</ul>
<p><strong>Latihan:</strong> buat wireframe tiga halaman LMS (login, daftar materi, detail materi) lalu hubungkan menjadi prototype di Figma.</p>
HTML,
                ],
                [
                    'title' => 'UI/UX Design: Riset Pengguna & Persona',
                    'body' => <<<'HTML'
<h4>Mengapa Riset Penting</h4>
<p>Desain yang baik dimulai dari pemahaman masalah pengguna, bukan dari selera desainer. Riset membantu memilih fitur yang benar-benar dibutuhkan.</p>
<h5>Metode riset sederhana</h5>
<ul>
<li><strong>Wawancara</strong> &mdash; ajukan pertanyaan terbuka kepada 3&ndash;5 pengguna.</li>
<li><strong>Observasi</strong> &mdash; amati bagaimana pengguna memakai aplikasi saat ini.</li>
<li><strong>Kuesioner</strong> &mdash; kumpulkan data dari banyak responden dengan cepat.</li>
</ul>
<h5>Persona</h5>
<p>Persona adalah ringkasan pengguna sasaran yang mencakup tujuan, kebiasaan, dan hambatan.</p>
<pre><code>Nama     : Rani, 16 tahun
Peran    : Murid kelas X
Tujuan   : Menyelesaikan tugas sebelum tenggat
Hambatan : Sinyal lambat, sering membuka aplikasi lewat ponsel
Kebutuhan: Halaman tugas yang ringan dan mudah dipindai</code></pre>
<p><strong>Latihan:</strong> buat satu persona murid dan satu persona guru, lalu turunkan tiga kebutuhan desain dari masing-masing persona.</p>
HTML,
                ],
                [
                    'title' => 'UI/UX Design: Design System & Komponen Reusable',
                    'body' => <<<'HTML'
<h4>Apa Itu Design System</h4>
<p>Design system adalah kumpulan aturan dan komponen yang dipakai ulang agar seluruh halaman terasa satu keluarga: warna, tipografi, jarak, tombol, form, dan pola navigasi.</p>
<h5>Komponen dasar</h5>
<ul>
<li><strong>Button</strong> &mdash; primer, sekunder, dan bahaya.</li>
<li><strong>Input</strong> &mdash; normal, fokus, error, dan nonaktif.</li>
<li><strong>Card</strong> &mdash; wadah informasi dengan header dan aksi.</li>
<li><strong>Badge &amp; alert</strong> &mdash; status dan pesan sistem.</li>
<li><strong>Pagination</strong> &mdash; pola navigasi data yang konsisten.</li>
</ul>
<h5>Token desain</h5>
<pre><code>--warna-primer   : #4e73df
--warna-teks     : #1f2937
--warna-garis    : #e6e9f2
--radius-kartu   : 14px
--jarak-dasar    : 16px</code></pre>
<p>Dokumentasikan setiap komponen beserta contoh pemakaiannya. Ketika desain konsisten, pengguna lebih cepat mengenali pola dan aplikasi terasa lebih rapi.</p>
<p><strong>Latihan:</strong> audit tiga halaman LMS dan temukan komponen yang tampil berbeda padahal fungsinya sama.</p>
HTML,
                ],
            ],
            'FSD' => [
                [
                    'title' => 'Full Stack Developer: Peran & Alur Kerja Pengembang',
                    'body' => <<<'HTML'
<h4>Siapa Itu Full Stack Developer</h4>
<p>Full stack developer mengerjakan sisi tampilan (front-end) sekaligus sisi server dan database (back-end). Perannya menjembatani desain, logika bisnis, dan penyimpanan data.</p>
<h5>Alur kerja yang sehat</h5>
<ol>
<li>Pahami kebutuhan dan buat rancangan data.</li>
<li>Bangun API atau controller di back-end.</li>
<li>Hubungkan tampilan dengan data tersebut.</li>
<li>Uji alur utama dan kasus gagal.</li>
<li>Deploy lewat pipeline yang otomatis.</li>
</ol>
<h5>Keterampilan inti</h5>
<ul>
<li>HTML, CSS, dan JavaScript untuk antarmuka.</li>
<li>Bahasa server seperti PHP, routing, dan validasi.</li>
<li>Database relasional, migrasi, dan query yang efisien.</li>
<li>Git, pengujian, dan dasar keamanan aplikasi.</li>
</ul>
<p><strong>Latihan:</strong> gambar alur request dari tombol &ldquo;simpan materi&rdquo; sampai data tersimpan di database.</p>
HTML,
                ],
                [
                    'title' => 'Full Stack Developer: Menghubungkan Front-end dan Back-end',
                    'body' => <<<'HTML'
<h4>Kontrak Data (API)</h4>
<p>Front-end dan back-end berkomunikasi lewat kontrak yang jelas: alamat endpoint, format request, dan format response.</p>
<pre><code>GET /api/materis
{
  "data": [
    { "id": 1, "title": "Web Dasar", "mapel": "Web Dasar" }
  ]
}</code></pre>
<h5>Contoh pengambilan data di front-end</h5>
<pre><code>async function muatMateri() {
  const response = await fetch('/api/materis');

  if (!response.ok) {
    throw new Error('Gagal memuat data');
  }

  const { data } = await response.json();
  renderMateri(data);
}</code></pre>
<h5>Menjaga kualitas integrasi</h5>
<ul>
<li>Sepakati nama field sejak awal.</li>
<li>Tangani status loading, kosong, dan error di tampilan.</li>
<li>Jangan menaruh logika rahasia di kode front-end.</li>
</ul>
HTML,
                ],
            ],
            'MB' => [
                [
                    'title' => 'Manajemen Bisnis: Dasar Perencanaan Usaha',
                    'body' => <<<'HTML'
<h4>Menyusun Rencana Usaha</h4>
<p>Rencana usaha menjelaskan masalah yang diselesaikan, pelanggan sasaran, produk, dan cara memperoleh pendapatan.</p>
<h5>Kerangka sederhana</h5>
<ul>
<li><strong>Masalah</strong> &mdash; keluhan yang dialami calon pelanggan.</li>
<li><strong>Solusi</strong> &mdash; produk atau jasa yang ditawarkan.</li>
<li><strong>Target pasar</strong> &mdash; siapa yang paling merasakan masalah tersebut.</li>
<li><strong>Model pendapatan</strong> &mdash; bagaimana usaha memperoleh pemasukan.</li>
<li><strong>Biaya utama</strong> &mdash; bahan, tenaga kerja, promosi, dan operasional.</li>
</ul>
<h5>Menghitung titik impas</h5>
<pre><code>Titik impas (unit) = Biaya tetap / (Harga jual - Biaya variabel per unit)</code></pre>
<p><strong>Latihan:</strong> susun rencana usaha kecil di sekitar sekolah dan hitung perkiraan titik impasnya.</p>
HTML,
                ],
                [
                    'title' => 'Manajemen Bisnis: Analisis SWOT untuk Ide Bisnis',
                    'body' => <<<'HTML'
<h4>Analisis SWOT</h4>
<p>SWOT membantu menilai ide usaha dari empat sisi: kekuatan, kelemahan, peluang, dan ancaman.</p>
<table class="table table-bordered">
<thead><tr><th>Internal</th><th>Eksternal</th></tr></thead>
<tbody>
<tr><td><strong>Strengths</strong> &mdash; keunggulan yang dimiliki</td><td><strong>Opportunities</strong> &mdash; peluang pasar yang terbuka</td></tr>
<tr><td><strong>Weaknesses</strong> &mdash; keterbatasan internal</td><td><strong>Threats</strong> &mdash; risiko dari luar</td></tr>
</tbody>
</table>
<h5>Contoh untuk usaha katering sehat</h5>
<ul>
<li>Kekuatan: bahan segar dan resep khas.</li>
<li>Kelemahan: kapasitas produksi terbatas.</li>
<li>Peluang: kebutuhan makan siang sehat di sekolah.</li>
<li>Ancaman: pesaing dengan harga lebih murah.</li>
</ul>
<p><strong>Latihan:</strong> buat SWOT untuk satu ide bisnis dan tentukan dua strategi prioritas dari hasilnya.</p>
HTML,
                ],
            ],
            'DM' => [
                [
                    'title' => 'Digital Marketing: Strategi Konten Media Sosial',
                    'body' => <<<'HTML'
<h4>Konten yang Berguna, Bukan Sekadar Ramai</h4>
<p>Konten pemasaran harus menjawab kebutuhan audiens: menghibur, mengedukasi, atau membantu mengambil keputusan.</p>
<h5>Pilar konten</h5>
<ul>
<li><strong>Edukasi</strong> &mdash; tips singkat dan penjelasan istilah.</li>
<li><strong>Bukti sosial</strong> &mdash; testimoni dan studi kasus.</li>
<li><strong>Hiburan</strong> &mdash; konten ringan yang relevan dengan brand.</li>
<li><strong>Promosi</strong> &mdash; penawaran dengan ajakan bertindak yang jelas.</li>
</ul>
<h5>Kalender konten mingguan</h5>
<pre><code>Senin : tips singkat
Rabu  : penjelasan produk
Jumat : testimoni pengguna
Minggu: kuis / tanya jawab</code></pre>
<h5>Ukuran keberhasilan</h5>
<p>Pantau jangkauan, interaksi, klik tautan, dan konversi. Angka besar tidak berguna bila tidak menghasilkan tindakan.</p>
HTML,
                ],
                [
                    'title' => 'Digital Marketing: Dasar SEO dan Iklan Digital',
                    'body' => <<<'HTML'
<h4>SEO Dasar</h4>
<p>SEO membantu halaman ditemukan di mesin pencari melalui konten yang relevan dan struktur teknis yang rapi.</p>
<ul>
<li>Gunakan satu kata kunci utama per halaman.</li>
<li>Tulis judul halaman di bawah 60 karakter.</li>
<li>Buat meta description yang menjelaskan isi.</li>
<li>Gunakan heading berjenjang (H1, H2, H3) dan tautan internal.</li>
<li>Pastikan halaman cepat diakses di ponsel.</li>
</ul>
<h5>Struktur URL yang baik</h5>
<pre><code>/materi/frontend/dasar-javascript</code></pre>
<h5>Iklan digital</h5>
<p>Tentukan tujuan kampanye, audiens, anggaran harian, dan ukuran keberhasilan sebelum mengaktifkan iklan. Uji beberapa variasi teks dan gambar, lalu hentikan yang tidak berhasil.</p>
HTML,
                ],
            ],
            'CICD' => [
                [
                    'title' => 'CI/CD: Pengenalan Otomasi Build dan Deploy',
                    'body' => <<<'HTML'
<h4>Apa Itu CI/CD</h4>
<p><strong>Continuous Integration</strong> menjalankan pengujian dan pemeriksaan setiap kali kode berubah. <strong>Continuous Delivery/Deployment</strong> mengotomasi proses rilis ke server.</p>
<h5>Manfaat</h5>
<ul>
<li>Kesalahan ditemukan lebih awal sebelum sampai ke pengguna.</li>
<li>Proses rilis konsisten dan tidak bergantung pada satu orang.</li>
<li>Riwayat perubahan mudah ditelusuri.</li>
</ul>
<h5>Tahapan pipeline</h5>
<pre><code>checkout -&gt; install dependency -&gt; lint -&gt; test -&gt; build -&gt; deploy</code></pre>
<h5>Kebiasaan yang mendukung</h5>
<p>Commit kecil dan sering, gunakan branch fitur, serta jangan menggabungkan kode yang gagal di pengujian.</p>
HTML,
                ],
                [
                    'title' => 'CI/CD: Pipeline Sederhana dengan GitHub Actions',
                    'body' => <<<'HTML'
<h4>Menyiapkan Workflow</h4>
<p>Buat file <code>.github/workflows/ci.yml</code> di repositori. Workflow berjalan otomatis saat ada push atau pull request.</p>
<pre><code>name: CI

on:
  push:
    branches: [ main ]
  pull_request:

jobs:
  test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - uses: shivammathur/setup-php@v2
        with:
          php-version: '8.3'
      - run: composer install --no-interaction --prefer-dist
      - run: cp .env.example .env
      - run: php artisan key:generate
      - run: php artisan test
</code></pre>
<h5>Rahasia dan environment</h5>
<p>Simpan kredensial di menu Secrets repositori, jangan menuliskan kata sandi langsung di file workflow.</p>
<h5>Langkah lanjutan</h5>
<ul>
<li>Tambahkan cache dependency agar pipeline lebih cepat.</li>
<li>Jalankan formatter dan static analysis.</li>
<li>Tambahkan job deploy dengan persetujuan manual.</li>
</ul>
HTML,
                ],
            ],
        ];
    }

    /**
     * Daily tugas and ujian grouped by mapel code.
     *
     * @return array<string, array<int, array{title: string, description: string, type: string, duration_minutes: int, questions: array<int, array<string, string>>}>>
     */
    private function exams(): array
    {
        return [
            'FSD' => [
                $this->exam('Tugas Harian Web Dasar: HTML & Struktur Halaman', 'tugas', 30, 'Kerjakan soal berikut sebagai tugas harian Web Dasar.', [
                    ['Apa kepanjangan dari HTML?', 'HyperText Markup Language', 'HyperText Machine Language', 'Home Tool Markup Language', 'Hyperlink Text Mode Language', 'A'],
                    ['Tag HTML untuk membuat judul terbesar adalah...', '<p>', '<h1>', '<title>', '<head>', 'B'],
                    ['Bagian HTML yang menyimpan metadata halaman adalah...', '<body>', '<head>', '<footer>', '<main>', 'B'],
                    ['Atribut pada tag <a> untuk menentukan alamat tujuan adalah...', 'src', 'href', 'action', 'link', 'B'],
                ]),
                $this->exam('Ujian Harian Web Dasar: HTML & CSS', 'ujian', 45, 'Ujian harian untuk mengukur pemahaman HTML dan CSS dasar.', [
                    ['CSS digunakan untuk...', 'menyimpan data', 'mengatur tampilan halaman', 'menghubungkan database', 'mengatur server', 'B'],
                    ['Selector CSS untuk memilih elemen dengan class "card" adalah...', '#card', '.card', 'card', '*card', 'B'],
                    ['Properti CSS untuk mengatur jarak di dalam elemen adalah...', 'margin', 'padding', 'border', 'gap', 'B'],
                    ['Properti Flexbox untuk menyusun anak elemen secara horizontal adalah...', 'flex-direction: row', 'align-items: stretch', 'flex-wrap: wrap', 'display: block', 'A'],
                ]),
            ],
            'FE' => [
                $this->exam('Tugas Harian Front-end: JavaScript Dasar', 'tugas', 30, 'Tugas harian seputar variabel, fungsi, dan event di JavaScript.', [
                    ['Keyword untuk mendeklarasikan variabel yang nilainya tidak bisa diubah adalah...', 'var', 'let', 'const', 'static', 'C'],
                    ['Fungsi bawaan untuk menampilkan pesan di console browser adalah...', 'console.log()', 'print()', 'echo()', 'alert.log()', 'A'],
                    ['Method untuk menambahkan event klik pada tombol adalah...', "addEventListener('click', ...)", 'onClick()', 'onclick.add()', "listen('click')", 'A'],
                    ['Tipe data untuk nilai benar/salah di JavaScript adalah...', 'string', 'boolean', 'number', 'object', 'B'],
                ]),
                $this->exam('Ujian Harian Front-end: DOM & Fetch API', 'ujian', 45, 'Ujian harian tentang manipulasi DOM dan pengambilan data dari server.', [
                    ['Method DOM untuk memilih elemen berdasarkan id adalah...', 'querySelectorAll', 'getElementById', 'getElementsByClass', 'getElement', 'B'],
                    ['Fungsi fetch() mengembalikan...', 'Promise', 'Array', 'String', 'Number', 'A'],
                    ['Method untuk mengubah response fetch menjadi objek JSON adalah...', '.toJson()', '.json()', '.parseJson()', '.jsonParse()', 'B'],
                    ['Template literal di JavaScript ditulis dengan...', "'...'", '"..."', '`...`', '{{{...}}}', 'C'],
                ]),
            ],
            'BE' => [
                $this->exam('Tugas Harian Back-end: Routing & Controller', 'tugas', 30, 'Tugas harian tentang alur request, routing, dan controller.', [
                    ['Routing berfungsi untuk...', 'menyimpan file gambar', 'memetakan URL ke aksi/controller', 'mengatur warna halaman', 'membuat database', 'B'],
                    ['File di Laravel yang mendefinisikan daftar route web adalah...', 'routes/web.php', 'app/web.php', 'config/route.php', 'public/index.php', 'A'],
                    ['HTTP method untuk mengirim data form baru adalah...', 'GET', 'POST', 'DELETE', 'HEAD', 'B'],
                    ['Controller di Laravel disimpan pada direktori...', 'app/Http/Controllers', 'app/Controllers', 'routes/controllers', 'resources/controllers', 'A'],
                ]),
                $this->exam('Ujian Harian Back-end: Database & Eloquent', 'ujian', 45, 'Ujian harian tentang migrasi, relasi, dan query Eloquent.', [
                    ['Perintah Laravel untuk membuat tabel database adalah...', 'php artisan serve', 'php artisan migrate', 'php artisan make:model', 'php artisan db:seed', 'B'],
                    ['Relasi satu guru ke banyak jadwal menggunakan...', 'belongsTo', 'hasMany', 'belongsToMany', 'hasOne', 'B'],
                    ['Method Eloquent untuk mengambil semua data adalah...', 'all()', 'find()', 'first()', 'save()', 'A'],
                    ['Kolom yang menghubungkan tabel anak ke tabel induk disebut...', 'primary key', 'foreign key', 'index', 'unique', 'B'],
                ]),
            ],
            'CYBER' => [
                $this->exam('Tugas Harian Cyber Security: Keamanan Akun', 'tugas', 30, 'Tugas harian tentang kata sandi, 2FA, dan phishing.', [
                    ['Kata sandi yang kuat sebaiknya...', 'sama untuk semua akun', 'kombinasi panjang huruf, angka, dan simbol', 'menggunakan tanggal lahir', 'menggunakan nama depan', 'B'],
                    ['Autentikasi dua faktor (2FA) menambah keamanan karena...', 'menghapus password', 'butuh faktor kedua selain password', 'membuat akun publik', 'mempercepat login', 'B'],
                    ['Phishing adalah...', 'serangan mencuri data lewat pesan/tautan palsu', 'cara mempercepat jaringan', 'enkripsi file otomatis', 'backup otomatis', 'A'],
                    ['Password manager berguna untuk...', 'menyimpan banyak kata sandi unik dengan aman', 'membagikan password ke semua orang', 'mematikan antivirus', 'menghapus akun', 'A'],
                ]),
                $this->exam('Ujian Harian Cyber Security: OWASP Top 10', 'ujian', 45, 'Ujian harian tentang risiko keamanan web dan pencegahannya.', [
                    ['SQL Injection terjadi ketika...', 'input pengguna tidak divalidasi dan masuk ke query', 'database terlalu besar', 'server mati', 'password terlalu panjang', 'A'],
                    ['Cara mencegah SQL Injection di Laravel adalah...', 'menyusun query string manual', 'memakai query builder/Eloquent dengan binding', 'mematikan database', 'memberi akses admin ke semua user', 'B'],
                    ['OWASP Top 10 adalah...', 'daftar risiko keamanan web paling umum', 'jenis bahasa pemrograman', 'framework CSS', 'aplikasi edit foto', 'A'],
                    ['Data sensitif seperti password sebaiknya disimpan dalam bentuk...', 'teks biasa', 'hash dengan salt', 'nama pengguna', 'file gambar', 'B'],
                ]),
            ],
            'UIUX' => [
                $this->exam('Tugas Harian UI/UX Design: User Flow', 'tugas', 30, 'Tugas harian seputar prinsip desain dan alur pengguna.', [
                    ['UX (User Experience) berfokus pada...', 'tampilan warna saja', 'pengalaman dan kemudahan pengguna', 'kode program', 'konfigurasi server', 'B'],
                    ['Wireframe adalah...', 'desain akhir berwarna', 'sketsa kasar tata letak halaman', 'kode HTML', 'struktur database', 'B'],
                    ['Prinsip kontras dalam desain berguna untuk...', 'membuat semua elemen sama', 'membedakan elemen penting', 'mengurangi keterbacaan', 'menambah animasi', 'B'],
                    ['Persona pengguna membantu desainer untuk...', 'memahami kebutuhan pengguna sasaran', 'menghapus data', 'mempercepat server', 'membuat database', 'A'],
                ]),
                $this->exam('Ujian Harian UI/UX Design: Wireframe & Prototype', 'ujian', 45, 'Ujian harian tentang wireframe, prototype, dan usability testing.', [
                    ['Prototype pada Figma digunakan untuk...', 'menguji alur interaksi sebelum coding', 'menyimpan database', 'menjalankan server', 'mengelola jadwal', 'A'],
                    ['User flow menggambarkan...', 'langkah-langkah pengguna menyelesaikan tugas', 'warna brand', 'struktur database', 'biaya produksi', 'A'],
                    ['Prinsip konsistensi berarti...', 'setiap halaman memakai pola dan gaya yang seragam', 'semua tombol berbeda warna', 'halaman berubah setiap refresh', 'desain tanpa navigasi', 'A'],
                    ['Usability testing dilakukan untuk...', 'mengamati pengguna nyata memakai produk', 'menambah jumlah server', 'menghapus akun', 'membuat logo', 'A'],
                ]),
            ],
        ];
    }

    /**
     * Build one exam definition with its multiple-choice questions.
     *
     * @param  array<int, array{0: string, 1: string, 2: string, 3: string, 4: string, 5: string}>  $questions
     * @return array{title: string, description: string, type: string, duration_minutes: int, questions: array<int, array<string, string>>}
     */
    private function exam(string $title, string $type, int $durationMinutes, string $description, array $questions): array
    {
        return [
            'title' => $title,
            'description' => $description,
            'type' => $type,
            'duration_minutes' => $durationMinutes,
            'questions' => array_map(fn (array $question) => [
                'question' => $question[0],
                'option_a' => $question[1],
                'option_b' => $question[2],
                'option_c' => $question[3],
                'option_d' => $question[4],
                'correct_answer' => $question[5],
            ], $questions),
        ];
    }
}
