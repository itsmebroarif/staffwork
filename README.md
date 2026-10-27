# StaffWork

**Starter kit sistem informasi managerial** untuk kantor, sekolah, rumah sakit, atau badan usaha lainnya.

StaffWork mempermudah Anda membangun sistem informasi managerial dari nol: autentikasi lengkap dengan captcha, manajemen multi-role yang fleksibel, profil pengguna, serta dasar pengembangan (dashboard, role, middleware) yang siap dikembangkan sesuai kebutuhan instansi.

## Fitur Utama

- **Auth lengkap dengan captcha** — login, register, logout, remember me, dan refresh captcha via AJAX ([mews/captcha](https://github.com/mewebstudio/captcha)).
- **Multi-role fleksibel** — default `superadmin`, `admin`, `staff`; dapat ditambah/disesuaikan.
- **Manajemen user (Super Admin)** — daftar, buat, edit, hapus akun beserta role & avatar.
- **Profil pengguna** — ubah data diri, password, dan avatar.
- **Dashboard** — ringkasan jumlah user per role.
- **Siap dikembangkan** — template admin, komponen Blade, Excel/PDF (`maatwebsite/excel`, `laravel-dompdf`) untuk kebutuhan laporan.

## Role

| Role       | Keterangan                                      |
|------------|-------------------------------------------------|
| `superadmin` | Akses penuh, termasuk manajemen user & register akun baru dengan role custom |
| `admin`      | Akses admin (sesuai kebutuhan pengembangan)     |
| `staff`      | Akses dasar karyawan/staf                       |

> Role pertama yang mendaftar via `/auth/register` otomatis menjadi `superadmin`. Role berikutnya dibuat oleh Super Admin melalui menu **Users**, dan daftar role dapat disesuaikan (lihat [Menyesuaikan Role](#menyesuaikan-role)).

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
# MySQL/PostgreSQL: isi DB_CONNECTION, DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD

php artisan migrate
php artisan db:seed   # opsional: buat user contoh

npm install
npm run build
```

### 3. Storage link (avatar)

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

Buka `http://localhost:8000` (atau `http://127.0.0.1:8000`).

### 5. Akun pertama

Akses `/auth/register` — akun pertama otomatis menjadi **superadmin**. Setelah login, buat akun lain (admin/staff) lewat menu **Users**.

## Struktur Project

```
staffwork/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php        # login, register, captcha
│   │   │   ├── DashboardController.php
│   │   │   ├── ProfileController.php
│   │   │   └── UserController.php        # manajemen user (superadmin)
│   │   └── Middleware/
│   │       └── EnsureSuperAdmin.php      # akses khusus superadmin
│   ├── Models/User.php                   # role, avatar, helper role
│   └── View/Components/                  # navbar, sidebar
├── config/captcha.php                    # konfigurasi captcha
├── database/
│   ├── migrations/
│   ├── factories/UserFactory.php         # state: superadmin, admin
│   └── seeders/
├── resources/views/
│   ├── auth/                             # login, register
│   ├── pages/                            # dashboard, profile, users
│   ├── components/
│   └── layouts/
├── routes/web.php
├── tests/Feature/                        # AuthTest, ProfileAndUserManagementTest
└── public/template/                      # template admin
```

## Pengembangan

### Menjalankan development

```bash
composer dev        # artisan dev + server + queue + Pail (log)
# atau terpisah:
php artisan serve
npm run dev         # Vite HMR
php artisan test    # atau: vendor/bin/pest
```

### Menyesuaikan Role

Role disimpan sebagai string di kolom `users.role` (default `staff`). Untuk menambah/mengubah role:

1. **Validasi** — tambahkan role di rule `in:` pada `UserController` (`store` & `update`):

   ```php
   'role' => ['required', 'string', 'in:superadmin,admin,staff,operator'],
   ```

2. **Label & badge** — `app/Models/User.php` (`getRoleNameAttribute`, `getRoleBadgeClassAttribute`):

   ```php
   'operator' => 'Operator',
   'operator' => 'badge-success',
   ```

3. **Form** — tambahkan opsi `<option>` di `resources/views/pages/users/create.blade.php` dan `edit.blade.php`.

4. **Akses per role** — gunakan middleware `superadmin` di `routes/web.php`, atau buat middleware baru lalu daftarkan alias di `bootstrap/app.php`:

   ```php
   $middleware->alias([
       'superadmin' => EnsureSuperAdmin::class,
       // 'operator' => EnsureOperator::class,
   ]);
   ```

### Menambah halaman / fitur baru

1. **Route** — tambahkan di `routes/web.php` (di dalam grup `auth` bila perlu login).
2. **Controller** — `php artisan make:controller PageController --no-interaction`
3. **View** — buat blade di `resources/views/pages/`, gunakan layout `layouts.app`.
4. **Model & migration** — bila butuh data:
   ```bash
   php artisan make:model Page --migration --no-interaction
   php artisan migrate
   ```
5. **Sidebar/navbar** — tautkan menu di `resources/views/components/sidebar.blade.php` (dan navbar bila perlu).

### Captcha

- Config: `config/captcha.php` (style `flat` dipakai saat login/register).
- Reload via AJAX: route `auth.reload-captcha` → `AuthController@reloadCaptcha`.
- Nonaktifkan captcha (testing): set `CAPTCHA_DISABLE=true` di `.env`.

### Testing

```bash
php artisan test --compact
# atau satu file:
vendor/bin/pest tests/Feature/AuthTest.php
```

Cakupan: auth + captcha, manajemen role & user, profile.

### Code style

Setelah mengubah file PHP:

```bash
vendor/bin/pint --dirty --format agent
```

### Export Excel / PDF (opsional)

Sudah terpasang `maatwebsite/excel` dan `barryvdh/laravel-dompdf` — gunakan saat butuh laporan:

```bash
php artisan make:export ReportExport --no-interaction   # via Excel
php artisan make:class Reports/ReportPdf                # via Dompdf
```

## Environment

Salin dari `.env.example`, minimal:

| Variabel | Keterangan |
|----------|------------|
| `APP_NAME` | Nama aplikasi |
| `APP_URL` | URL akses |
| `DB_*` | Koneksi database |
| `CAPTCHA_DISABLE` | `true` untuk menonaktifkan captcha |

## Test

```bash
composer test
# atau
php artisan test --compact
```

## License

MIT — lihat [LICENSE](LICENSE) bila tersedia.
