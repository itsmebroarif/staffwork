# StaffWork

**LMS (Learning Management System) starter kit untuk sekolah** — mempermudah membangun sistem informasi managerial/pendidikan: auth lengkap dengan captcha, multi-role (superadmin, guru, murid), upload materi blog, serta tugas & ujian pilihan ganda.

## Fitur Utama

- **Auth lengkap dengan captcha** — login, register, logout, remember me, dan refresh captcha via AJAX ([mews/captcha](https://github.com/mewebstudio/captcha)).
- **Multi-role fleksibel** — `superadmin`, `guru`, `murid`; dapat ditambah/disesuaikan.
- **Manajemen user (Super Admin)** — daftar, buat, edit, hapus akun beserta role & avatar.
- **Materi (blog)** — Super Admin & Guru dapat menulis/upload materi + lampiran file; Murid dapat membaca.
- **Tugas & Ujian PG** — Super Admin & Guru dapat membuat tugas/ujian dengan soal pilihan ganda (opsi A–D + jawaban benar, durasi, jadwal).
- **Profil pengguna** — ubah data diri, password, dan avatar.
- **Dashboard** — ringkasan akun per role, jumlah materi & tugas/ujian.

## Role

| Role         | Keterangan |
|--------------|------------|
| `superadmin` | Akses penuh: kelola akun, upload materi, buat tugas/ujian |
| `guru`       | Upload materi (blog + file), buat & kelola tugas/ujian pilihan ganda |
| `murid`      | Akses belajar: baca materi, lihat tugas/ujian |

> Akun pertama via `/auth/register` otomatis `superadmin`. Role berikutnya dibuat Super Admin di menu **Users**. Middleware `teacher` = superadmin + guru.

## Kebutuhan Sistem

- PHP >= 8.3
- [Composer](https://getcomposer.org/)
- Node.js & npm (untuk Vite/Tailwind)
- Database (SQLite bawaan, atau MySQL/PostgreSQL)

## Instalasi

### 1. Clone & setup cepat

```bash
git clone <url-repo-staffwork> staffwork
cd staffwork

composer setup
```

Script `composer setup` otomatis menjalankan:

1. `composer install`
2. Salin `.env.example` → `.env` (jika belum ada)
3. `php artisan key:generate`
4. `php artisan migrate --force`
5. `npm install` & `npm run build`

### 2. Setup manual (opsional)

```bash
composer install
cp .env.example .env
php artisan key:generate

# Database: sesuaikan DB_* di .env (default SQLite)
php artisan migrate
php artisan db:seed   # opsional: buat user contoh

npm install
npm run build
```

### 3. Storage link (avatar & file materi)

```bash
php artisan storage:link
```

### 4. Menjalankan

```bash
composer dev
# atau
php artisan serve
npm run dev
```

Buka `http://localhost:8000`.

### 5. Akun pertama

Akses `/auth/register` — akun pertama otomatis **superadmin**. Buat akun **guru**/**murid** lewat menu **Users**.

## Struktur Project

```
staffwork/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php        # login, register, captcha
│   │   │   ├── MateriController.php      # CRUD materi blog
│   │   │   ├── ExamController.php        # CRUD tugas/ujian + soal PG
│   │   │   └── UserController.php        # manajemen user
│   │   └── Middleware/
│   │       ├── EnsureSuperAdmin.php
│   │       └── EnsureTeacher.php         # superadmin | guru
│   ├── Models/
│   │   ├── User.php                      # role, isTeacher(), isStudent()
│   │   ├── Material.php
│   │   ├── Exam.php
│   │   └── Question.php
├── database/
│   ├── migrations/                       # materials, exams, questions
│   └── factories/
├── resources/views/
│   ├── auth/                             # login, register
│   ├── pages/
│   │   ├── materis/                      # index, create, edit, show
│   │   ├── exams/                        # index, create, edit, show
│   │   └── users/
│   └── components/
├── routes/web.php
└── tests/Feature/                        # Auth, LMS, Profile, Users
```

## Pengembangan

### Menjalankan development

```bash
composer dev
# atau terpisah:
php artisan serve
npm run dev
php artisan test --compact
```

### Menyesuaikan Role

1. **Validasi** — `UserController` (`store` & `update`): `in:superadmin,guru,murid`
2. **Label & badge** — `app/Models/User.php` (`getRoleNameAttribute`, `getRoleBadgeClassAttribute`)
3. **Form** — opsi `<option>` di `pages/users/create.blade.php` & `edit.blade.php`
4. **Helper** — `isTeacher()`, `isStudent()`, `isSuperAdmin()` di model `User`
5. **Middleware** — `teacher` (`EnsureTeacher`) & `superadmin` (`EnsureSuperAdmin`) di `bootstrap/app.php`

### Menambah fitur LMS baru

1. **Migration/Model** — `php artisan make:model X --migration --factory --no-interaction`
2. **Controller** — `php artisan make:controller XController --no-interaction`
3. **Route** — daftarkan di `routes/web.php`; gunakan middleware `auth` + `teacher` bila hanya guru/superadmin
4. **View** — buat blade di `resources/views/pages/`, layout `layouts.app`, komponen `sidebar`/`navbar` yang sudah ada
5. **Sidebar** — tambahkan menu di `resources/views/components/sidebar.blade.php`

### Captcha

- Config: `config/captcha.php`
- Nonaktif (testing): `CAPTCHA_DISABLE=true` di `.env`

### Testing

```bash
php artisan test --compact
vendor/bin/pest tests/Feature/LmsContentTest.php
```

### Code style

```bash
vendor/bin/pint --dirty --format agent
```

## Environment

| Variabel | Keterangan |
|----------|------------|
| `APP_NAME` | Nama aplikasi |
| `APP_URL` | URL akses |
| `DB_*` | Koneksi database |
| `CAPTCHA_DISABLE` | `true` untuk menonaktifkan captcha |

## License

MIT.
