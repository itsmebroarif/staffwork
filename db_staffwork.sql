/*
 Navicat Premium Dump SQL

 Source Server         : Local Database
 Source Server Type    : MySQL
 Source Server Version : 80030 (8.0.30)
 Source Host           : localhost:3306
 Source Schema         : db_staffwork

 Target Server Type    : MySQL
 Target Server Version : 80030 (8.0.30)
 File Encoding         : 65001

 Date: 01/11/2026 03:44:18
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for activity_logs
-- ----------------------------
DROP TABLE IF EXISTS `activity_logs`;
CREATE TABLE `activity_logs`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NULL DEFAULT NULL,
  `action` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `subject_id` bigint UNSIGNED NULL DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `activity_logs_user_id_foreign`(`user_id` ASC) USING BTREE,
  INDEX `activity_logs_subject_type_subject_id_index`(`subject_type` ASC, `subject_id` ASC) USING BTREE,
  INDEX `activity_logs_action_index`(`action` ASC) USING BTREE,
  CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of activity_logs
-- ----------------------------

-- ----------------------------
-- Table structure for cache
-- ----------------------------
DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache`  (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`) USING BTREE,
  INDEX `cache_expiration_index`(`expiration` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cache
-- ----------------------------

-- ----------------------------
-- Table structure for cache_locks
-- ----------------------------
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks`  (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`) USING BTREE,
  INDEX `cache_locks_expiration_index`(`expiration` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cache_locks
-- ----------------------------

-- ----------------------------
-- Table structure for exams
-- ----------------------------
DROP TABLE IF EXISTS `exams`;
CREATE TABLE `exams`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'tugas',
  `duration_minutes` int UNSIGNED NULL DEFAULT NULL,
  `available_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `exams_user_id_foreign`(`user_id` ASC) USING BTREE,
  INDEX `exams_type_index`(`type` ASC) USING BTREE,
  CONSTRAINT `exams_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 15 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of exams
-- ----------------------------
INSERT INTO `exams` VALUES (5, 156, 'Tugas Harian Web Dasar: HTML & Struktur Halaman', 'Kerjakan soal berikut sebagai tugas harian Web Dasar.', 'tugas', 30, '2026-10-31 19:42:04', '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `exams` VALUES (6, 156, 'Ujian Harian Web Dasar: HTML & CSS', 'Ujian harian untuk mengukur pemahaman HTML dan CSS dasar.', 'ujian', 45, '2026-10-31 19:42:04', '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `exams` VALUES (7, 154, 'Tugas Harian Front-end: JavaScript Dasar', 'Tugas harian seputar variabel, fungsi, dan event di JavaScript.', 'tugas', 30, '2026-10-31 19:42:04', '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `exams` VALUES (8, 154, 'Ujian Harian Front-end: DOM & Fetch API', 'Ujian harian tentang manipulasi DOM dan pengambilan data dari server.', 'ujian', 45, '2026-10-31 19:42:04', '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `exams` VALUES (9, 153, 'Tugas Harian Back-end: Routing & Controller', 'Tugas harian tentang alur request, routing, dan controller.', 'tugas', 30, '2026-10-31 19:42:04', '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `exams` VALUES (10, 153, 'Ujian Harian Back-end: Database & Eloquent', 'Ujian harian tentang migrasi, relasi, dan query Eloquent.', 'ujian', 45, '2026-10-31 19:42:04', '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `exams` VALUES (11, 151, 'Tugas Harian Cyber Security: Keamanan Akun', 'Tugas harian tentang kata sandi, 2FA, dan phishing.', 'tugas', 30, '2026-10-31 19:42:04', '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `exams` VALUES (12, 151, 'Ujian Harian Cyber Security: OWASP Top 10', 'Ujian harian tentang risiko keamanan web dan pencegahannya.', 'ujian', 45, '2026-10-31 19:42:04', '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `exams` VALUES (13, 155, 'Tugas Harian UI/UX Design: User Flow', 'Tugas harian seputar prinsip desain dan alur pengguna.', 'tugas', 30, '2026-10-31 19:42:04', '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `exams` VALUES (14, 155, 'Ujian Harian UI/UX Design: Wireframe & Prototype', 'Ujian harian tentang wireframe, prototype, dan usability testing.', 'ujian', 45, '2026-10-31 19:42:04', '2026-10-31 19:42:04', '2026-10-31 19:42:04');

-- ----------------------------
-- Table structure for failed_jobs
-- ----------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `failed_jobs_uuid_unique`(`uuid` ASC) USING BTREE,
  INDEX `failed_jobs_connection_queue_failed_at_index`(`connection` ASC, `queue` ASC, `failed_at` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of failed_jobs
-- ----------------------------

-- ----------------------------
-- Table structure for job_batches
-- ----------------------------
DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches`  (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `cancelled_at` int NULL DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of job_batches
-- ----------------------------

-- ----------------------------
-- Table structure for jobs
-- ----------------------------
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED NULL DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `jobs_queue_index`(`queue` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of jobs
-- ----------------------------

-- ----------------------------
-- Table structure for kelas
-- ----------------------------
DROP TABLE IF EXISTS `kelas`;
CREATE TABLE `kelas`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tingkat` tinyint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `kelas_name_unique`(`name` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 35 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of kelas
-- ----------------------------
INSERT INTO `kelas` VALUES (29, 'X RPL 1', 10, '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `kelas` VALUES (30, 'XI RPL 1', 11, '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `kelas` VALUES (31, 'XII RPL 1', 12, '2026-10-31 20:31:39', '2026-10-31 20:31:39');
INSERT INTO `kelas` VALUES (32, 'X MM 1', 10, '2026-10-31 20:31:39', '2026-10-31 20:31:39');
INSERT INTO `kelas` VALUES (33, 'XI MM 1', 11, '2026-10-31 20:31:39', '2026-10-31 20:31:39');
INSERT INTO `kelas` VALUES (34, 'XII MM 1', 12, '2026-10-31 20:31:39', '2026-10-31 20:31:39');

-- ----------------------------
-- Table structure for mapels
-- ----------------------------
DROP TABLE IF EXISTS `mapels`;
CREATE TABLE `mapels`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `mapels_code_unique`(`code` ASC) USING BTREE,
  UNIQUE INDEX `mapels_name_unique`(`name` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 19 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of mapels
-- ----------------------------
INSERT INTO `mapels` VALUES (10, 'WEB', 'Web Dasar', '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `mapels` VALUES (11, 'FE', 'Front-end', '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `mapels` VALUES (12, 'BE', 'Back-end', '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `mapels` VALUES (13, 'CYBER', 'Cyber Security', '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `mapels` VALUES (14, 'UIUX', 'UI/UX Design', '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `mapels` VALUES (15, 'MB', 'Manajemen Bisnis', '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `mapels` VALUES (16, 'DM', 'Digital Marketing', '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `mapels` VALUES (17, 'FSD', 'Full Stack Developer', '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `mapels` VALUES (18, 'CICD', 'CI/CD', '2026-10-31 19:42:04', '2026-10-31 19:42:04');

-- ----------------------------
-- Table structure for materials
-- ----------------------------
DROP TABLE IF EXISTS `materials`;
CREATE TABLE `materials`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'artikel',
  `kelas_id` bigint UNSIGNED NULL DEFAULT NULL,
  `mapel_id` bigint UNSIGNED NULL DEFAULT NULL,
  `body` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `file_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `materials_slug_unique`(`slug` ASC) USING BTREE,
  INDEX `materials_user_id_foreign`(`user_id` ASC) USING BTREE,
  INDEX `materials_kelas_id_foreign`(`kelas_id` ASC) USING BTREE,
  INDEX `materials_mapel_id_foreign`(`mapel_id` ASC) USING BTREE,
  CONSTRAINT `materials_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `materials_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `materials_mapel_id_foreign` FOREIGN KEY (`mapel_id`) REFERENCES `mapels` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 61 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of materials
-- ----------------------------
INSERT INTO `materials` VALUES (33, 156, 'Web Dasar: Cara Kerja Website & Struktur HTML', 'web-dasar-cara-kerja-website-struktur-html', 'artikel', NULL, 10, '<h4>Cara Kerja Website</h4>\n<p>Ketika kamu membuka sebuah alamat web, browser mengirim permintaan (request) ke server. Server membalas dengan file HTML, CSS, dan JavaScript, lalu browser merendernya menjadi halaman yang bisa kamu lihat dan klik.</p>\n<ul>\n<li><strong>HTML</strong> &mdash; kerangka dan isi halaman.</li>\n<li><strong>CSS</strong> &mdash; tampilan, warna, dan tata letak.</li>\n<li><strong>JavaScript</strong> &mdash; interaksi dan logika di browser.</li>\n</ul>\n<h5>Struktur dasar HTML</h5>\n<pre><code>&lt;!DOCTYPE html&gt;\n&lt;html lang=\"id\"&gt;\n  &lt;head&gt;\n    &lt;meta charset=\"UTF-8\"&gt;\n    &lt;title&gt;Halaman Pertamaku&lt;/title&gt;\n  &lt;/head&gt;\n  &lt;body&gt;\n    &lt;h1&gt;Halo, Dunia!&lt;/h1&gt;\n    &lt;p&gt;Ini paragraf pertama saya.&lt;/p&gt;\n  &lt;/body&gt;\n&lt;/html&gt;</code></pre>\n<h5>Tag yang sering dipakai</h5>\n<ul>\n<li><code>&lt;h1&gt;</code> sampai <code>&lt;h6&gt;</code> untuk judul bertingkat.</li>\n<li><code>&lt;p&gt;</code> untuk paragraf dan <code>&lt;a href=\"...\"&gt;</code> untuk tautan.</li>\n<li><code>&lt;img&gt;</code> untuk gambar, <code>&lt;ul&gt;</code> dan <code>&lt;ol&gt;</code> untuk daftar.</li>\n</ul>\n<p><strong>Latihan:</strong> buat halaman profil sederhana berisi nama, foto, dan tiga tautan media sosial.</p>', NULL, NULL, 1, '2026-10-31 19:42:04', '2026-10-31 20:31:38', NULL);
INSERT INTO `materials` VALUES (34, 156, 'Web Dasar: CSS Dasar & Layout dengan Flexbox', 'web-dasar-css-dasar-layout-dengan-flexbox', 'artikel', NULL, 10, '<h4>Menghubungkan CSS ke HTML</h4>\n<p>CSS bisa ditulis langsung di dalam tag <code>&lt;style&gt;</code> atau di file terpisah yang dipanggil dengan <code>&lt;link rel=\"stylesheet\" href=\"style.css\"&gt;</code>.</p>\n<h5>Selector dan properti umum</h5>\n<pre><code>body {\n  font-family: Arial, sans-serif;\n  background: #f8f9fa;\n}\n\n.card {\n  padding: 16px;\n  border: 1px solid #dee2e6;\n  border-radius: 8px;\n  box-shadow: 0 2px 6px rgba(0, 0, 0, .08);\n}</code></pre>\n<ul>\n<li><code>.card</code> memilih elemen dengan class <em>card</em>, sedangkan <code>#header</code> memilih id.</li>\n<li><strong>Margin</strong> adalah jarak di luar elemen, <strong>padding</strong> adalah jarak di dalam elemen.</li>\n</ul>\n<h5>Layout dengan Flexbox</h5>\n<pre><code>.row {\n  display: flex;\n  gap: 16px;\n  align-items: center;\n  justify-content: space-between;\n}</code></pre>\n<p><strong>Latihan:</strong> ubah daftar kartu materi menjadi tiga kolom sejajar menggunakan Flexbox, lalu tambahkan media query agar menjadi satu kolom di layar ponsel.</p>', NULL, NULL, 1, '2026-10-31 19:42:04', '2026-10-31 20:31:38', NULL);
INSERT INTO `materials` VALUES (35, 154, 'Front-end: JavaScript Dasar untuk Interaksi Halaman', 'front-end-javascript-dasar-untuk-interaksi-halaman', 'artikel', NULL, 11, '<h4>Variabel dan Tipe Data</h4>\n<p>Gunakan <code>const</code> untuk nilai yang tidak berubah dan <code>let</code> untuk nilai yang bisa berubah. Hindari <code>var</code> pada kode baru.</p>\n<pre><code>const namaSiswa = \"Rani\";\nlet nilai = 90;\n\nconsole.log(`${namaSiswa} mendapat nilai ${nilai}`);</code></pre>\n<h5>Fungsi</h5>\n<pre><code>function hitungRataRata(a, b) {\n  return (a + b) / 2;\n}</code></pre>\n<h5>Event pada tombol</h5>\n<pre><code>const tombol = document.getElementById(\"simpan\");\n\ntombol.addEventListener(\"click\", () =&gt; {\n  alert(\"Data berhasil disimpan!\");\n});</code></pre>\n<p><strong>Latihan:</strong> buat tombol yang menambah counter setiap kali diklik dan menampilkan jumlah klik di halaman.</p>', NULL, NULL, 1, '2026-10-31 19:42:04', '2026-10-31 20:31:38', NULL);
INSERT INTO `materials` VALUES (36, 154, 'Front-end: Mengambil Data dengan Fetch API', 'front-end-mengambil-data-dengan-fetch-api', 'artikel', NULL, 11, '<h4>Request ke Server</h4>\n<p><code>fetch()</code> mengirim permintaan HTTP dan mengembalikan sebuah <em>Promise</em>. Gunakan <code>async/await</code> agar kodenya mudah dibaca.</p>\n<pre><code>async function muatMateri() {\n  const response = await fetch(\"/api/materi\");\n  const data = await response.json();\n\n  renderMateri(data);\n}</code></pre>\n<h5>Menampilkan hasil ke halaman</h5>\n<pre><code>function renderMateri(items) {\n  const list = document.getElementById(\"daftar-materi\");\n  list.innerHTML = \"\";\n\n  items.forEach((item) =&gt; {\n    const li = document.createElement(\"li\");\n    li.textContent = item.title;\n    list.appendChild(li);\n  });\n}</code></pre>\n<h5>Menangani error</h5>\n<pre><code>try {\n  await muatMateri();\n} catch (error) {\n  console.error(\"Gagal memuat materi:\", error);\n}</code></pre>\n<p><strong>Latihan:</strong> tampilkan daftar materi dari endpoint publik, sertakan pesan loading dan pesan error.</p>', NULL, NULL, 1, '2026-10-31 19:42:04', '2026-10-31 20:31:38', NULL);
INSERT INTO `materials` VALUES (37, 153, 'Back-end: Memahami Routing, Controller, dan Request', 'back-end-memahami-routing-controller-dan-request', 'artikel', NULL, 12, '<h4>Alur Request di Back-end</h4>\n<p>Browser mengirim request ke sebuah URL. Routing mencocokkan URL tersebut ke controller, controller memproses data, lalu mengembalikan view atau JSON.</p>\n<pre><code>// routes/web.php\nRoute::get(\'/materis\', [MateriController::class, \'index\'])-&gt;name(\'materis.index\');\nRoute::post(\'/materis\', [MateriController::class, \'store\'])-&gt;name(\'materis.store\');</code></pre>\n<h5>Controller</h5>\n<pre><code>public function store(Request $request)\n{\n    $validated = $request-&gt;validate([\n        \'title\' =&gt; [\'required\', \'string\', \'max:255\'],\n        \'body\'  =&gt; [\'required\', \'string\'],\n    ]);\n\n    Material::create($validated);\n\n    return redirect()-&gt;route(\'materis.index\');\n}</code></pre>\n<h5>Status code penting</h5>\n<ul>\n<li><code>200</code> berhasil, <code>201</code> data dibuat.</li>\n<li><code>302</code> redirect setelah form disimpan.</li>\n<li><code>403</code> tidak punya akses, <code>404</code> data tidak ditemukan, <code>422</code> validasi gagal.</li>\n</ul>\n<p><strong>Latihan:</strong> tambahkan validasi agar judul materi minimal 5 karakter dan tampilkan pesan error di form.</p>', NULL, NULL, 1, '2026-10-31 19:42:04', '2026-10-31 20:31:38', NULL);
INSERT INTO `materials` VALUES (38, 153, 'Back-end: Relasi Database & Query Eloquent', 'back-end-relasi-database-query-eloquent', 'artikel', NULL, 12, '<h4>Menyiapkan Tabel</h4>\n<pre><code>Schema::create(\'materials\', function (Blueprint $table) {\n    $table-&gt;id();\n    $table-&gt;foreignId(\'user_id\')-&gt;constrained();\n    $table-&gt;string(\'title\');\n    $table-&gt;text(\'body\');\n    $table-&gt;timestamps();\n});</code></pre>\n<p>Jalankan <code>php artisan migrate</code> untuk membuat tabelnya.</p>\n<h5>Relasi Eloquent</h5>\n<pre><code>class Material extends Model\n{\n    public function author()\n    {\n        return $this-&gt;belongsTo(User::class, \'user_id\');\n    }\n}</code></pre>\n<h5>Query yang sering dipakai</h5>\n<pre><code>$materials = Material::with(\'author\')\n    ->where(\'is_published\', true)\n    ->latest()\n    ->paginate(9);\n\n$jumlah = Material::count();\n\nMaterial::create([\n    \'user_id\' =&gt; auth()->id(),\n    \'title\'   =&gt; \'Materi Baru\',\n    \'body\'    =&gt; \'Isi materi\',\n]);</code></pre>\n<p><strong>Latihan:</strong> tampilkan jumlah materi per guru menggunakan relasi dan <code>withCount</code>.</p>', NULL, NULL, 1, '2026-10-31 19:42:04', '2026-10-31 20:31:38', NULL);
INSERT INTO `materials` VALUES (39, 151, 'Cyber Security: Mengamankan Akun & Kata Sandi', 'cyber-security-mengamankan-akun-kata-sandi', 'artikel', NULL, 13, '<h4>Mengapa Keamanan Akun Penting</h4>\n<p>Satu akun yang bocor bisa membuka data sekolah, nilai, hingga akun lain yang memakai kata sandi sama. Keamanan dimulai dari kebiasaan kecil.</p>\n<h5>Aturan kata sandi yang kuat</h5>\n<ul>\n<li>Minimal 12 karakter, gabungan huruf besar, kecil, angka, dan simbol.</li>\n<li>Jangan memakai tanggal lahir, nama, atau kata \"password\".</li>\n<li>Gunakan kata sandi berbeda untuk setiap layanan.</li>\n<li>Aktifkan autentikasi dua faktor (2FA) bila tersedia.</li>\n</ul>\n<h5>Penyimpanan kata sandi di server</h5>\n<p>Kata sandi tidak boleh disimpan sebagai teks biasa. Simpan hasil hash dengan algoritma lambat seperti bcrypt atau argon2.</p>\n<pre><code>$hash = password_hash($password, PASSWORD_BCRYPT);\n\nif (password_verify($input, $hash)) {\n    // kata sandi cocok\n}</code></pre>\n<h5>Mengenali phishing</h5>\n<p>Periksa domain pengirim, jangan klik tautan mencurigakan, dan jangan pernah membagikan kode OTP kepada siapa pun.</p>', NULL, NULL, 1, '2026-10-31 19:42:04', '2026-10-31 20:31:38', NULL);
INSERT INTO `materials` VALUES (40, 151, 'Cyber Security: Mengenal OWASP Top 10 & SQL Injection', 'cyber-security-mengenal-owasp-top-10-sql-injection', 'artikel', NULL, 13, '<h4>OWASP Top 10</h4>\n<p>OWASP Top 10 adalah daftar risiko keamanan aplikasi web yang paling sering terjadi, antara lain broken access control, kesalahan konfigurasi, dan injection.</p>\n<h5>Contoh SQL Injection yang rentan</h5>\n<pre><code>// JANGAN lakukan ini\n$email = $_GET[\'email\'];\n$query = \"SELECT * FROM users WHERE email = \'$email\'\";</code></pre>\n<p>Jika penyerang mengirim <code>\' OR \'1\'=\'1</code>, query berubah dan seluruh data bisa terbaca.</p>\n<h5>Cara aman dengan query binding</h5>\n<pre><code>$user = User::where(\'email\', $request-&gt;input(\'email\'))-&gt;first();\n\nDB::select(\'SELECT * FROM users WHERE email = ?\', [$email]);</code></pre>\n<h5>Checklist keamanan dasar</h5>\n<ul>\n<li>Validasi semua input dan gunakan prepared statement.</li>\n<li>Escape output untuk mencegah XSS.</li>\n<li>Terapkan otorisasi di setiap endpoint, bukan hanya menyembunyikan tombol.</li>\n<li>Perbarui dependency secara berkala.</li>\n</ul>\n<p><strong>Latihan:</strong> temukan satu form pada aplikasi latihan yang belum memvalidasi input, lalu perbaiki.</p>', NULL, NULL, 1, '2026-10-31 19:42:04', '2026-10-31 20:31:38', NULL);
INSERT INTO `materials` VALUES (41, 155, 'UI/UX Design: Prinsip Dasar Desain Antarmuka', 'uiux-design-prinsip-dasar-desain-antarmuka', 'artikel', NULL, 14, '<h4>UI dan UX</h4>\n<p><strong>UX</strong> berfokus pada pengalaman pengguna saat menyelesaikan tugas, sedangkan <strong>UI</strong> berfokus pada tampilan visual seperti warna, tipografi, dan ikon.</p>\n<h5>Prinsip desain yang wajib dipahami</h5>\n<ul>\n<li><strong>Hierarki</strong> &mdash; elemen terpenting terlihat pertama.</li>\n<li><strong>Kontras</strong> &mdash; teks harus mudah dibaca di atas latar.</li>\n<li><strong>Konsistensi</strong> &mdash; tombol dan warna memakai pola yang sama.</li>\n<li><strong>Feedback</strong> &mdash; pengguna tahu apa yang terjadi setelah menekan tombol.</li>\n<li><strong>Aksesibilitas</strong> &mdash; ukuran teks dan kontras memadai untuk semua pengguna.</li>\n</ul>\n<h5>Sistem desain sederhana</h5>\n<pre><code>Warna utama   : #2563eb\nWarna netral  : #f8f9fa, #6b7280, #111827\nJudul         : 24px / bold\nIsi           : 16px / regular\nRadius sudut  : 8px\nJarak antar bagian: 16px</code></pre>\n<p><strong>Latihan:</strong> susun ulang satu halaman form agar memiliki hierarki jelas dan pesan error yang mudah dipahami.</p>', NULL, NULL, 1, '2026-10-31 19:42:04', '2026-10-31 20:31:38', NULL);
INSERT INTO `materials` VALUES (42, 155, 'UI/UX Design: Dari Wireframe ke Prototype', 'uiux-design-dari-wireframe-ke-prototype', 'artikel', NULL, 14, '<h4>Alur Kerja Desain</h4>\n<ol>\n<li><strong>Riset</strong> &mdash; pahami pengguna dan masalahnya.</li>\n<li><strong>User flow</strong> &mdash; gambarkan langkah pengguna dari awal sampai tujuan.</li>\n<li><strong>Wireframe</strong> &mdash; sketsa kasar tata letak tanpa warna.</li>\n<li><strong>Prototype</strong> &mdash; versi interaktif untuk diuji.</li>\n<li><strong>Usability testing</strong> &mdash; amati pengguna nyata memakai desain.</li>\n</ol>\n<h5>Contoh user flow login</h5>\n<pre><code>Buka aplikasi -&gt; Isi email &amp; password -&gt; Klik Masuk\n  -&gt; Berhasil: Dashboard\n  -&gt; Gagal: Pesan error &amp; coba lagi</code></pre>\n<h5>Tips membuat wireframe</h5>\n<ul>\n<li>Fokus pada tata letak dan alur, bukan warna.</li>\n<li>Gunakan satu tujuan utama per halaman.</li>\n<li>Buat versi mobile dan desktop.</li>\n</ul>\n<p><strong>Latihan:</strong> buat wireframe tiga halaman LMS (login, daftar materi, detail materi) lalu hubungkan menjadi prototype di Figma.</p>', NULL, NULL, 1, '2026-10-31 19:42:04', '2026-10-31 20:31:38', NULL);
INSERT INTO `materials` VALUES (43, 156, 'Web Dasar: Form HTML & Validasi Input', 'web-dasar-form-html-validasi-input', 'artikel', NULL, 10, '<h4>Membuat Form yang Mudah Dipakai</h4>\n<p>Form adalah cara utama pengguna mengirim data ke server: login, pendaftaran, upload materi, dan lain-lain.</p>\n<pre><code>&lt;form action=\"/materis\" method=\"POST\"&gt;\n  &lt;label for=\"judul\"&gt;Judul Materi&lt;/label&gt;\n  &lt;input type=\"text\" id=\"judul\" name=\"title\" required minlength=\"5\"&gt;\n\n  &lt;label for=\"isi\"&gt;Isi Materi&lt;/label&gt;\n  &lt;textarea id=\"isi\" name=\"body\" rows=\"5\" required&gt;&lt;/textarea&gt;\n\n  &lt;button type=\"submit\"&gt;Simpan&lt;/button&gt;\n&lt;/form&gt;</code></pre>\n<h5>Atribut validasi bawaan browser</h5>\n<ul>\n<li><code>required</code> &mdash; kolom wajib diisi.</li>\n<li><code>type=\"email\"</code> &mdash; format email diperiksa otomatis.</li>\n<li><code>minlength</code> dan <code>maxlength</code> &mdash; batasi panjang teks.</li>\n<li><code>pattern</code> &mdash; validasi dengan ekspresi reguler.</li>\n</ul>\n<p>Validasi browser hanya membantu di sisi klien. Server tetap wajib memvalidasi ulang seluruh input.</p>\n<p><strong>Latihan:</strong> buat form kontak dengan nama, email, dan pesan, lalu tampilkan pesan error di bawah setiap kolom.</p>', NULL, NULL, 1, '2026-10-31 19:54:01', '2026-10-31 20:31:38', NULL);
INSERT INTO `materials` VALUES (44, 156, 'Web Dasar: Dasar Responsive Web Design', 'web-dasar-dasar-responsive-web-design', 'artikel', NULL, 10, '<h4>Mengapa Perlu Responsif</h4>\n<p>Pengguna membuka website dari ponsel, tablet, dan laptop. Desain responsif membuat tampilan tetap nyaman di semua ukuran layar.</p>\n<h5>Viewport meta tag</h5>\n<pre><code>&lt;meta name=\"viewport\" content=\"width=device-width, initial-scale=1\"&gt;</code></pre>\n<h5>Media query</h5>\n<pre><code>.grid-materi {\n  display: grid;\n  grid-template-columns: repeat(3, 1fr);\n  gap: 16px;\n}\n\n@media (max-width: 768px) {\n  .grid-materi {\n    grid-template-columns: 1fr;\n  }\n}</code></pre>\n<h5>Pendekatan mobile first</h5>\n<p>Tulis gaya untuk layar kecil terlebih dahulu, lalu tambahkan aturan untuk layar lebih besar dengan <code>min-width</code>.</p>\n<ul>\n<li>Gunakan satuan relatif seperti <code>rem</code> dan <code>%</code>.</li>\n<li>Pastikan ukuran tombol minimal 44px agar mudah disentuh.</li>\n<li>Jangan mengandalkan warna saja untuk menyampaikan informasi.</li>\n</ul>\n<p><strong>Latihan:</strong> buat kartu materi yang menjadi satu kolom di ponsel dan tiga kolom di desktop.</p>', NULL, NULL, 1, '2026-10-31 19:54:01', '2026-10-31 20:31:38', NULL);
INSERT INTO `materials` VALUES (45, 154, 'Front-end: Manipulasi DOM & State Sederhana', 'front-end-manipulasi-dom-state-sederhana', 'artikel', NULL, 11, '<h4>DOM dan State</h4>\n<p>DOM adalah representasi halaman di memori browser. JavaScript bisa mengubah isi elemen tanpa memuat ulang halaman.</p>\n<pre><code>const daftar = document.getElementById(\"daftar-tugas\");\nconst tombol = document.getElementById(\"tambah-tugas\");\n\nlet tugas = [];\n\ntombol.addEventListener(\"click\", () =&gt; {\n  tugas.push(\"Tugas \" + (tugas.length + 1));\n  render();\n});</code></pre>\n<h5>Render ulang dari state</h5>\n<pre><code>function render() {\n  daftar.innerHTML = \"\";\n\n  tugas.forEach((item, index) =&gt; {\n    const li = document.createElement(\"li\");\n    li.textContent = item;\n\n    const hapus = document.createElement(\"button\");\n    hapus.textContent = \"Hapus\";\n    hapus.addEventListener(\"click\", () =&gt; {\n      tugas.splice(index, 1);\n      render();\n    });\n\n    li.appendChild(hapus);\n    daftar.appendChild(li);\n  });\n}</code></pre>\n<p>Simpan data pada satu sumber kebenaran (<em>state</em>), lalu gambar ulang tampilan dari state tersebut. Pola ini mencegah tampilan dan data tidak sinkron.</p>', NULL, NULL, 1, '2026-10-31 19:54:01', '2026-10-31 20:31:38', NULL);
INSERT INTO `materials` VALUES (46, 154, 'Front-end: Styling Modern dengan Utility Class', 'front-end-styling-modern-dengan-utility-class', 'artikel', NULL, 11, '<h4>Utility Class</h4>\n<p>Utility class adalah class kecil dengan satu tugas, misalnya <code>mt-2</code> untuk margin atas atau <code>text-center</code> untuk rata tengah. Pola ini mempercepat penyusunan tampilan dan menjaga konsistensi.</p>\n<pre><code>&lt;div class=\"card p-3 mb-3 shadow-sm\"&gt;\n  &lt;h5 class=\"mb-1 font-weight-bold\"&gt;Judul Materi&lt;/h5&gt;\n  &lt;p class=\"text-muted small mb-0\"&gt;Ringkasan materi.&lt;/p&gt;\n&lt;/div&gt;</code></pre>\n<h5>Kapan memakai utility class</h5>\n<ul>\n<li>Untuk jarak, warna, ukuran teks, dan perataan.</li>\n<li>Untuk komponen kecil yang dipakai berulang.</li>\n<li>Pisahkan ke CSS khusus bila aturan mulai panjang dan kompleks.</li>\n</ul>\n<h5>Menjaga konsistensi</h5>\n<p>Tentukan skala jarak (4, 8, 12, 16, 24px), ukuran teks, dan palet warna. Hindari nilai acak yang tidak tercatat.</p>\n<p><strong>Latihan:</strong> ubah satu halaman yang memakai banyak inline style menjadi utility class yang seragam.</p>', NULL, NULL, 1, '2026-10-31 19:54:01', '2026-10-31 20:31:38', NULL);
INSERT INTO `materials` VALUES (47, 153, 'Back-end: Autentikasi & Otorisasi Dasar', 'back-end-autentikasi-otorisasi-dasar', 'artikel', NULL, 12, '<h4>Autentikasi vs Otorisasi</h4>\n<p><strong>Autentikasi</strong> memastikan siapa pengguna. <strong>Otorisasi</strong> memastikan apa yang boleh ia lakukan.</p>\n<h5>Middleware peran</h5>\n<pre><code>Route::middleware([\'auth\', \'guru\'])-&gt;group(function () {\n    Route::post(\'/materis\', [MateriController::class, \'store\']);\n});</code></pre>\n<h5>Pemeriksaan di level aksi</h5>\n<pre><code>public function destroy(Request $request, Material $material)\n{\n    abort_unless($request-&gt;user()-&gt;isTeacher(), 403);\n\n    $material-&gt;delete();\n}</code></pre>\n<h5>Aturan penting</h5>\n<ul>\n<li>Jangan hanya menyembunyikan tombol; tetap periksa hak akses di server.</li>\n<li>Gunakan HTTPS agar kredensial tidak dikirim terang-terangan.</li>\n<li>Regenerasi session setelah login untuk mencegah session fixation.</li>\n</ul>\n<p><strong>Latihan:</strong> tambahkan pengecekan agar guru hanya bisa mengubah materi miliknya sendiri.</p>', NULL, NULL, 1, '2026-10-31 19:54:01', '2026-10-31 20:31:38', NULL);
INSERT INTO `materials` VALUES (48, 153, 'Back-end: Validasi, Error Handling, dan Log', 'back-end-validasi-error-handling-dan-log', 'artikel', NULL, 12, '<h4>Validasi Input</h4>\n<pre><code>$validated = $request-&gt;validate([\n    \'title\' =&gt; [\'required\', \'string\', \'max:255\'],\n    \'body\'  =&gt; [\'required\', \'string\'],\n    \'kelas_id\' =&gt; [\'nullable\', \'exists:kelas,id\'],\n]);</code></pre>\n<h5>Menangani kegagalan</h5>\n<pre><code>try {\n    DB::transaction(function () use ($validated) {\n        Material::create($validated);\n    });\n} catch (Throwable $e) {\n    report($e);\n\n    return back()-&gt;with(\'error\', \'Materi gagal disimpan.\');\n}</code></pre>\n<h5>Log yang berguna</h5>\n<ul>\n<li>Catat konteks: ID pengguna, aksi, dan data penting (tanpa kata sandi).</li>\n<li>Gunakan level yang tepat: <code>info</code>, <code>warning</code>, <code>error</code>.</li>\n<li>Kembalikan pesan yang ramah pengguna, bukan detail teknis.</li>\n</ul>\n<p><strong>Latihan:</strong> tambahkan validasi dan pesan error yang jelas pada form materi.</p>', NULL, NULL, 1, '2026-10-31 19:54:01', '2026-10-31 20:31:38', NULL);
INSERT INTO `materials` VALUES (49, 151, 'Cyber Security: Keamanan Web & Pengelolaan Akses', 'cyber-security-keamanan-web-pengelolaan-akses', 'artikel', NULL, 13, '<h4>Prinsip Hak Akses Minimum</h4>\n<p>Setiap pengguna hanya boleh mendapat akses seperlunya. Guru mengelola materi, murid membaca dan mengerjakan tugas, super admin mengelola akun.</p>\n<h5>Kesalahan konfigurasi yang sering terjadi</h5>\n<ul>\n<li>Halaman admin hanya disembunyikan di menu, tanpa middleware.</li>\n<li>File konfigurasi atau backup dapat diunduh publik.</li>\n<li>Pesan error menampilkan detail teknis di produksi.</li>\n<li>Akun default masih memakai kata sandi bawaan.</li>\n</ul>\n<h5>Header keamanan</h5>\n<pre><code>Content-Security-Policy: default-src \'self\'\nX-Content-Type-Options: nosniff\nX-Frame-Options: SAMEORIGIN\nReferrer-Policy: strict-origin-when-cross-origin</code></pre>\n<h5>Checklist rilis</h5>\n<ol>\n<li>Matikan mode debug (<code>APP_DEBUG=false</code>).</li>\n<li>Gunakan HTTPS dan cookie <code>Secure</code> + <code>HttpOnly</code>.</li>\n<li>Perbarui dependency dan hapus akun uji yang tidak terpakai.</li>\n</ol>', NULL, NULL, 1, '2026-10-31 19:54:01', '2026-10-31 20:31:38', NULL);
INSERT INTO `materials` VALUES (50, 151, 'Cyber Security: Backup, Enkripsi, dan Respons Insiden', 'cyber-security-backup-enkripsi-dan-respons-insiden', 'artikel', NULL, 13, '<h4>Backup yang Berguna</h4>\n<p>Backup baru berguna bila bisa dipulihkan. Uji proses restore secara berkala, bukan hanya membuat file cadangan.</p>\n<ul>\n<li>Aturan 3-2-1: tiga salinan, dua media berbeda, satu di lokasi terpisah.</li>\n<li>Enkripsi file backup dan simpan kunci aksesnya terpisah.</li>\n<li>Catat jadwal backup dan siapa yang bertanggung jawab.</li>\n</ul>\n<h5>Enkripsi data sensitif</h5>\n<p>Data pribadi siswa dan guru sebaiknya dienkripsi saat disimpan maupun dikirim. Gunakan TLS untuk data yang bergerak dan enkripsi simetris untuk data yang disimpan.</p>\n<h5>Langkah respons insiden</h5>\n<ol>\n<li>Identifikasi dan hentikan penyebaran (cabut akses, ganti kredensial).</li>\n<li>Simpan bukti log sebelum membersihkan sistem.</li>\n<li>Pulihkan dari backup yang terverifikasi.</li>\n<li>Evaluasi penyebab dan perbaiki celahnya.</li>\n<li>Beri tahu pihak terkait sesuai kebutuhan.</li>\n</ol>', NULL, NULL, 1, '2026-10-31 19:54:01', '2026-10-31 20:31:38', NULL);
INSERT INTO `materials` VALUES (51, 155, 'UI/UX Design: Riset Pengguna & Persona', 'uiux-design-riset-pengguna-persona', 'artikel', NULL, 14, '<h4>Mengapa Riset Penting</h4>\n<p>Desain yang baik dimulai dari pemahaman masalah pengguna, bukan dari selera desainer. Riset membantu memilih fitur yang benar-benar dibutuhkan.</p>\n<h5>Metode riset sederhana</h5>\n<ul>\n<li><strong>Wawancara</strong> &mdash; ajukan pertanyaan terbuka kepada 3&ndash;5 pengguna.</li>\n<li><strong>Observasi</strong> &mdash; amati bagaimana pengguna memakai aplikasi saat ini.</li>\n<li><strong>Kuesioner</strong> &mdash; kumpulkan data dari banyak responden dengan cepat.</li>\n</ul>\n<h5>Persona</h5>\n<p>Persona adalah ringkasan pengguna sasaran yang mencakup tujuan, kebiasaan, dan hambatan.</p>\n<pre><code>Nama     : Rani, 16 tahun\nPeran    : Murid kelas X\nTujuan   : Menyelesaikan tugas sebelum tenggat\nHambatan : Sinyal lambat, sering membuka aplikasi lewat ponsel\nKebutuhan: Halaman tugas yang ringan dan mudah dipindai</code></pre>\n<p><strong>Latihan:</strong> buat satu persona murid dan satu persona guru, lalu turunkan tiga kebutuhan desain dari masing-masing persona.</p>', NULL, NULL, 1, '2026-10-31 19:54:01', '2026-10-31 20:31:38', NULL);
INSERT INTO `materials` VALUES (52, 155, 'UI/UX Design: Design System & Komponen Reusable', 'uiux-design-design-system-komponen-reusable', 'artikel', NULL, 14, '<h4>Apa Itu Design System</h4>\n<p>Design system adalah kumpulan aturan dan komponen yang dipakai ulang agar seluruh halaman terasa satu keluarga: warna, tipografi, jarak, tombol, form, dan pola navigasi.</p>\n<h5>Komponen dasar</h5>\n<ul>\n<li><strong>Button</strong> &mdash; primer, sekunder, dan bahaya.</li>\n<li><strong>Input</strong> &mdash; normal, fokus, error, dan nonaktif.</li>\n<li><strong>Card</strong> &mdash; wadah informasi dengan header dan aksi.</li>\n<li><strong>Badge &amp; alert</strong> &mdash; status dan pesan sistem.</li>\n<li><strong>Pagination</strong> &mdash; pola navigasi data yang konsisten.</li>\n</ul>\n<h5>Token desain</h5>\n<pre><code>--warna-primer   : #4e73df\n--warna-teks     : #1f2937\n--warna-garis    : #e6e9f2\n--radius-kartu   : 14px\n--jarak-dasar    : 16px</code></pre>\n<p>Dokumentasikan setiap komponen beserta contoh pemakaiannya. Ketika desain konsisten, pengguna lebih cepat mengenali pola dan aplikasi terasa lebih rapi.</p>\n<p><strong>Latihan:</strong> audit tiga halaman LMS dan temukan komponen yang tampil berbeda padahal fungsinya sama.</p>', NULL, NULL, 1, '2026-10-31 19:54:01', '2026-10-31 20:31:38', NULL);
INSERT INTO `materials` VALUES (53, 156, 'Full Stack Developer: Peran & Alur Kerja Pengembang', 'full-stack-developer-peran-alur-kerja-pengembang', 'artikel', NULL, 17, '<h4>Siapa Itu Full Stack Developer</h4>\n<p>Full stack developer mengerjakan sisi tampilan (front-end) sekaligus sisi server dan database (back-end). Perannya menjembatani desain, logika bisnis, dan penyimpanan data.</p>\n<h5>Alur kerja yang sehat</h5>\n<ol>\n<li>Pahami kebutuhan dan buat rancangan data.</li>\n<li>Bangun API atau controller di back-end.</li>\n<li>Hubungkan tampilan dengan data tersebut.</li>\n<li>Uji alur utama dan kasus gagal.</li>\n<li>Deploy lewat pipeline yang otomatis.</li>\n</ol>\n<h5>Keterampilan inti</h5>\n<ul>\n<li>HTML, CSS, dan JavaScript untuk antarmuka.</li>\n<li>Bahasa server seperti PHP, routing, dan validasi.</li>\n<li>Database relasional, migrasi, dan query yang efisien.</li>\n<li>Git, pengujian, dan dasar keamanan aplikasi.</li>\n</ul>\n<p><strong>Latihan:</strong> gambar alur request dari tombol &ldquo;simpan materi&rdquo; sampai data tersimpan di database.</p>', NULL, NULL, 1, '2026-10-31 20:31:38', '2026-10-31 20:31:38', NULL);
INSERT INTO `materials` VALUES (54, 156, 'Full Stack Developer: Menghubungkan Front-end dan Back-end', 'full-stack-developer-menghubungkan-front-end-dan-back-end', 'artikel', NULL, 17, '<h4>Kontrak Data (API)</h4>\n<p>Front-end dan back-end berkomunikasi lewat kontrak yang jelas: alamat endpoint, format request, dan format response.</p>\n<pre><code>GET /api/materis\n{\n  \"data\": [\n    { \"id\": 1, \"title\": \"Web Dasar\", \"mapel\": \"Web Dasar\" }\n  ]\n}</code></pre>\n<h5>Contoh pengambilan data di front-end</h5>\n<pre><code>async function muatMateri() {\n  const response = await fetch(\'/api/materis\');\n\n  if (!response.ok) {\n    throw new Error(\'Gagal memuat data\');\n  }\n\n  const { data } = await response.json();\n  renderMateri(data);\n}</code></pre>\n<h5>Menjaga kualitas integrasi</h5>\n<ul>\n<li>Sepakati nama field sejak awal.</li>\n<li>Tangani status loading, kosong, dan error di tampilan.</li>\n<li>Jangan menaruh logika rahasia di kode front-end.</li>\n</ul>', NULL, NULL, 1, '2026-10-31 20:31:38', '2026-10-31 20:31:38', NULL);
INSERT INTO `materials` VALUES (55, 150, 'Manajemen Bisnis: Dasar Perencanaan Usaha', 'manajemen-bisnis-dasar-perencanaan-usaha', 'artikel', NULL, 15, '<h4>Menyusun Rencana Usaha</h4>\n<p>Rencana usaha menjelaskan masalah yang diselesaikan, pelanggan sasaran, produk, dan cara memperoleh pendapatan.</p>\n<h5>Kerangka sederhana</h5>\n<ul>\n<li><strong>Masalah</strong> &mdash; keluhan yang dialami calon pelanggan.</li>\n<li><strong>Solusi</strong> &mdash; produk atau jasa yang ditawarkan.</li>\n<li><strong>Target pasar</strong> &mdash; siapa yang paling merasakan masalah tersebut.</li>\n<li><strong>Model pendapatan</strong> &mdash; bagaimana usaha memperoleh pemasukan.</li>\n<li><strong>Biaya utama</strong> &mdash; bahan, tenaga kerja, promosi, dan operasional.</li>\n</ul>\n<h5>Menghitung titik impas</h5>\n<pre><code>Titik impas (unit) = Biaya tetap / (Harga jual - Biaya variabel per unit)</code></pre>\n<p><strong>Latihan:</strong> susun rencana usaha kecil di sekitar sekolah dan hitung perkiraan titik impasnya.</p>', NULL, NULL, 1, '2026-10-31 20:31:38', '2026-10-31 20:31:38', NULL);
INSERT INTO `materials` VALUES (56, 150, 'Manajemen Bisnis: Analisis SWOT untuk Ide Bisnis', 'manajemen-bisnis-analisis-swot-untuk-ide-bisnis', 'artikel', NULL, 15, '<h4>Analisis SWOT</h4>\n<p>SWOT membantu menilai ide usaha dari empat sisi: kekuatan, kelemahan, peluang, dan ancaman.</p>\n<table class=\"table table-bordered\">\n<thead><tr><th>Internal</th><th>Eksternal</th></tr></thead>\n<tbody>\n<tr><td><strong>Strengths</strong> &mdash; keunggulan yang dimiliki</td><td><strong>Opportunities</strong> &mdash; peluang pasar yang terbuka</td></tr>\n<tr><td><strong>Weaknesses</strong> &mdash; keterbatasan internal</td><td><strong>Threats</strong> &mdash; risiko dari luar</td></tr>\n</tbody>\n</table>\n<h5>Contoh untuk usaha katering sehat</h5>\n<ul>\n<li>Kekuatan: bahan segar dan resep khas.</li>\n<li>Kelemahan: kapasitas produksi terbatas.</li>\n<li>Peluang: kebutuhan makan siang sehat di sekolah.</li>\n<li>Ancaman: pesaing dengan harga lebih murah.</li>\n</ul>\n<p><strong>Latihan:</strong> buat SWOT untuk satu ide bisnis dan tentukan dua strategi prioritas dari hasilnya.</p>', NULL, NULL, 1, '2026-10-31 20:31:38', '2026-10-31 20:31:38', NULL);
INSERT INTO `materials` VALUES (57, 152, 'Digital Marketing: Strategi Konten Media Sosial', 'digital-marketing-strategi-konten-media-sosial', 'artikel', NULL, 16, '<h4>Konten yang Berguna, Bukan Sekadar Ramai</h4>\n<p>Konten pemasaran harus menjawab kebutuhan audiens: menghibur, mengedukasi, atau membantu mengambil keputusan.</p>\n<h5>Pilar konten</h5>\n<ul>\n<li><strong>Edukasi</strong> &mdash; tips singkat dan penjelasan istilah.</li>\n<li><strong>Bukti sosial</strong> &mdash; testimoni dan studi kasus.</li>\n<li><strong>Hiburan</strong> &mdash; konten ringan yang relevan dengan brand.</li>\n<li><strong>Promosi</strong> &mdash; penawaran dengan ajakan bertindak yang jelas.</li>\n</ul>\n<h5>Kalender konten mingguan</h5>\n<pre><code>Senin : tips singkat\nRabu  : penjelasan produk\nJumat : testimoni pengguna\nMinggu: kuis / tanya jawab</code></pre>\n<h5>Ukuran keberhasilan</h5>\n<p>Pantau jangkauan, interaksi, klik tautan, dan konversi. Angka besar tidak berguna bila tidak menghasilkan tindakan.</p>', NULL, NULL, 1, '2026-10-31 20:31:38', '2026-10-31 20:31:38', NULL);
INSERT INTO `materials` VALUES (58, 152, 'Digital Marketing: Dasar SEO dan Iklan Digital', 'digital-marketing-dasar-seo-dan-iklan-digital', 'artikel', NULL, 16, '<h4>SEO Dasar</h4>\n<p>SEO membantu halaman ditemukan di mesin pencari melalui konten yang relevan dan struktur teknis yang rapi.</p>\n<ul>\n<li>Gunakan satu kata kunci utama per halaman.</li>\n<li>Tulis judul halaman di bawah 60 karakter.</li>\n<li>Buat meta description yang menjelaskan isi.</li>\n<li>Gunakan heading berjenjang (H1, H2, H3) dan tautan internal.</li>\n<li>Pastikan halaman cepat diakses di ponsel.</li>\n</ul>\n<h5>Struktur URL yang baik</h5>\n<pre><code>/materi/frontend/dasar-javascript</code></pre>\n<h5>Iklan digital</h5>\n<p>Tentukan tujuan kampanye, audiens, anggaran harian, dan ukuran keberhasilan sebelum mengaktifkan iklan. Uji beberapa variasi teks dan gambar, lalu hentikan yang tidak berhasil.</p>', NULL, NULL, 1, '2026-10-31 20:31:38', '2026-10-31 20:31:38', NULL);
INSERT INTO `materials` VALUES (59, 157, 'CI/CD: Pengenalan Otomasi Build dan Deploy', 'cicd-pengenalan-otomasi-build-dan-deploy', 'artikel', NULL, 18, '<h4>Apa Itu CI/CD</h4>\n<p><strong>Continuous Integration</strong> menjalankan pengujian dan pemeriksaan setiap kali kode berubah. <strong>Continuous Delivery/Deployment</strong> mengotomasi proses rilis ke server.</p>\n<h5>Manfaat</h5>\n<ul>\n<li>Kesalahan ditemukan lebih awal sebelum sampai ke pengguna.</li>\n<li>Proses rilis konsisten dan tidak bergantung pada satu orang.</li>\n<li>Riwayat perubahan mudah ditelusuri.</li>\n</ul>\n<h5>Tahapan pipeline</h5>\n<pre><code>checkout -&gt; install dependency -&gt; lint -&gt; test -&gt; build -&gt; deploy</code></pre>\n<h5>Kebiasaan yang mendukung</h5>\n<p>Commit kecil dan sering, gunakan branch fitur, serta jangan menggabungkan kode yang gagal di pengujian.</p>', NULL, NULL, 1, '2026-10-31 20:31:38', '2026-10-31 20:31:38', NULL);
INSERT INTO `materials` VALUES (60, 157, 'CI/CD: Pipeline Sederhana dengan GitHub Actions', 'cicd-pipeline-sederhana-dengan-github-actions', 'artikel', NULL, 18, '<h4>Menyiapkan Workflow</h4>\n<p>Buat file <code>.github/workflows/ci.yml</code> di repositori. Workflow berjalan otomatis saat ada push atau pull request.</p>\n<pre><code>name: CI\n\non:\n  push:\n    branches: [ main ]\n  pull_request:\n\njobs:\n  test:\n    runs-on: ubuntu-latest\n    steps:\n      - uses: actions/checkout@v4\n      - uses: shivammathur/setup-php@v2\n        with:\n          php-version: \'8.3\'\n      - run: composer install --no-interaction --prefer-dist\n      - run: cp .env.example .env\n      - run: php artisan key:generate\n      - run: php artisan test\n</code></pre>\n<h5>Rahasia dan environment</h5>\n<p>Simpan kredensial di menu Secrets repositori, jangan menuliskan kata sandi langsung di file workflow.</p>\n<h5>Langkah lanjutan</h5>\n<ul>\n<li>Tambahkan cache dependency agar pipeline lebih cepat.</li>\n<li>Jalankan formatter dan static analysis.</li>\n<li>Tambahkan job deploy dengan persetujuan manual.</li>\n</ul>', NULL, NULL, 1, '2026-10-31 20:31:38', '2026-10-31 20:31:38', NULL);

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 22 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES (1, '0001_01_01_000000_create_users_table', 1);
INSERT INTO `migrations` VALUES (2, '0001_01_01_000001_create_cache_table', 1);
INSERT INTO `migrations` VALUES (3, '0001_01_01_000002_create_jobs_table', 1);
INSERT INTO `migrations` VALUES (4, '2026_10_27_094639_add_role_and_avatar_to_users_table', 1);
INSERT INTO `migrations` VALUES (5, '2026_10_27_131117_create_materials_table', 1);
INSERT INTO `migrations` VALUES (6, '2026_10_27_131118_create_exams_table', 1);
INSERT INTO `migrations` VALUES (7, '2026_10_27_131119_create_questions_table', 1);
INSERT INTO `migrations` VALUES (8, '2026_10_30_085857_create_kelas_table', 1);
INSERT INTO `migrations` VALUES (9, '2026_10_30_085858_create_notifications_table', 1);
INSERT INTO `migrations` VALUES (10, '2026_10_30_085859_create_mapels_table', 1);
INSERT INTO `migrations` VALUES (11, '2026_10_30_085900_create_wali_murids_table', 1);
INSERT INTO `migrations` VALUES (12, '2026_10_30_085901_create_teaching_assignments_table', 1);
INSERT INTO `migrations` VALUES (13, '2026_10_30_085902_create_teaching_schedules_table', 1);
INSERT INTO `migrations` VALUES (14, '2026_10_30_085903_create_violations_table', 1);
INSERT INTO `migrations` VALUES (15, '2026_10_30_090020_add_nis_nip_phone_and_kelas_to_users_table', 1);
INSERT INTO `migrations` VALUES (16, '2026_10_30_090021_add_content_type_and_kelas_to_materials_table', 1);
INSERT INTO `migrations` VALUES (17, '2026_10_31_171018_add_deleted_at_to_materials_table', 1);
INSERT INTO `migrations` VALUES (18, '2026_10_31_193233_add_bidang_to_users_table', 2);
INSERT INTO `migrations` VALUES (19, '2026_11_01_000000_add_password_plain_to_users_table', 2);
INSERT INTO `migrations` VALUES (20, '2026_11_01_100000_create_activity_logs_table', 3);
INSERT INTO `migrations` VALUES (21, '2026_11_01_110000_add_mapel_id_to_materials_table', 3);

-- ----------------------------
-- Table structure for notifications
-- ----------------------------
DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications`  (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint UNSIGNED NOT NULL,
  `data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `notifications_notifiable_type_notifiable_id_index`(`notifiable_type` ASC, `notifiable_id` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of notifications
-- ----------------------------

-- ----------------------------
-- Table structure for password_reset_tokens
-- ----------------------------
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens`  (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of password_reset_tokens
-- ----------------------------

-- ----------------------------
-- Table structure for questions
-- ----------------------------
DROP TABLE IF EXISTS `questions`;
CREATE TABLE `questions`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `exam_id` bigint UNSIGNED NOT NULL,
  `question` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `option_a` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `option_b` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `option_c` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `option_d` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `correct_answer` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `order` int UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `questions_exam_id_foreign`(`exam_id` ASC) USING BTREE,
  INDEX `questions_correct_answer_index`(`correct_answer` ASC) USING BTREE,
  CONSTRAINT `questions_exam_id_foreign` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 47 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of questions
-- ----------------------------
INSERT INTO `questions` VALUES (7, 5, 'Apa kepanjangan dari HTML?', 'HyperText Markup Language', 'HyperText Machine Language', 'Home Tool Markup Language', 'Hyperlink Text Mode Language', 'A', 1, '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `questions` VALUES (8, 5, 'Tag HTML untuk membuat judul terbesar adalah...', '<p>', '<h1>', '<title>', '<head>', 'B', 2, '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `questions` VALUES (9, 5, 'Bagian HTML yang menyimpan metadata halaman adalah...', '<body>', '<head>', '<footer>', '<main>', 'B', 3, '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `questions` VALUES (10, 5, 'Atribut pada tag <a> untuk menentukan alamat tujuan adalah...', 'src', 'href', 'action', 'link', 'B', 4, '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `questions` VALUES (11, 6, 'CSS digunakan untuk...', 'menyimpan data', 'mengatur tampilan halaman', 'menghubungkan database', 'mengatur server', 'B', 1, '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `questions` VALUES (12, 6, 'Selector CSS untuk memilih elemen dengan class \"card\" adalah...', '#card', '.card', 'card', '*card', 'B', 2, '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `questions` VALUES (13, 6, 'Properti CSS untuk mengatur jarak di dalam elemen adalah...', 'margin', 'padding', 'border', 'gap', 'B', 3, '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `questions` VALUES (14, 6, 'Properti Flexbox untuk menyusun anak elemen secara horizontal adalah...', 'flex-direction: row', 'align-items: stretch', 'flex-wrap: wrap', 'display: block', 'A', 4, '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `questions` VALUES (15, 7, 'Keyword untuk mendeklarasikan variabel yang nilainya tidak bisa diubah adalah...', 'var', 'let', 'const', 'static', 'C', 1, '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `questions` VALUES (16, 7, 'Fungsi bawaan untuk menampilkan pesan di console browser adalah...', 'console.log()', 'print()', 'echo()', 'alert.log()', 'A', 2, '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `questions` VALUES (17, 7, 'Method untuk menambahkan event klik pada tombol adalah...', 'addEventListener(\'click\', ...)', 'onClick()', 'onclick.add()', 'listen(\'click\')', 'A', 3, '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `questions` VALUES (18, 7, 'Tipe data untuk nilai benar/salah di JavaScript adalah...', 'string', 'boolean', 'number', 'object', 'B', 4, '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `questions` VALUES (19, 8, 'Method DOM untuk memilih elemen berdasarkan id adalah...', 'querySelectorAll', 'getElementById', 'getElementsByClass', 'getElement', 'B', 1, '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `questions` VALUES (20, 8, 'Fungsi fetch() mengembalikan...', 'Promise', 'Array', 'String', 'Number', 'A', 2, '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `questions` VALUES (21, 8, 'Method untuk mengubah response fetch menjadi objek JSON adalah...', '.toJson()', '.json()', '.parseJson()', '.jsonParse()', 'B', 3, '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `questions` VALUES (22, 8, 'Template literal di JavaScript ditulis dengan...', '\'...\'', '\"...\"', '`...`', '{{{...}}}', 'C', 4, '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `questions` VALUES (23, 9, 'Routing berfungsi untuk...', 'menyimpan file gambar', 'memetakan URL ke aksi/controller', 'mengatur warna halaman', 'membuat database', 'B', 1, '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `questions` VALUES (24, 9, 'File di Laravel yang mendefinisikan daftar route web adalah...', 'routes/web.php', 'app/web.php', 'config/route.php', 'public/index.php', 'A', 2, '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `questions` VALUES (25, 9, 'HTTP method untuk mengirim data form baru adalah...', 'GET', 'POST', 'DELETE', 'HEAD', 'B', 3, '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `questions` VALUES (26, 9, 'Controller di Laravel disimpan pada direktori...', 'app/Http/Controllers', 'app/Controllers', 'routes/controllers', 'resources/controllers', 'A', 4, '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `questions` VALUES (27, 10, 'Perintah Laravel untuk membuat tabel database adalah...', 'php artisan serve', 'php artisan migrate', 'php artisan make:model', 'php artisan db:seed', 'B', 1, '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `questions` VALUES (28, 10, 'Relasi satu guru ke banyak jadwal menggunakan...', 'belongsTo', 'hasMany', 'belongsToMany', 'hasOne', 'B', 2, '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `questions` VALUES (29, 10, 'Method Eloquent untuk mengambil semua data adalah...', 'all()', 'find()', 'first()', 'save()', 'A', 3, '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `questions` VALUES (30, 10, 'Kolom yang menghubungkan tabel anak ke tabel induk disebut...', 'primary key', 'foreign key', 'index', 'unique', 'B', 4, '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `questions` VALUES (31, 11, 'Kata sandi yang kuat sebaiknya...', 'sama untuk semua akun', 'kombinasi panjang huruf, angka, dan simbol', 'menggunakan tanggal lahir', 'menggunakan nama depan', 'B', 1, '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `questions` VALUES (32, 11, 'Autentikasi dua faktor (2FA) menambah keamanan karena...', 'menghapus password', 'butuh faktor kedua selain password', 'membuat akun publik', 'mempercepat login', 'B', 2, '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `questions` VALUES (33, 11, 'Phishing adalah...', 'serangan mencuri data lewat pesan/tautan palsu', 'cara mempercepat jaringan', 'enkripsi file otomatis', 'backup otomatis', 'A', 3, '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `questions` VALUES (34, 11, 'Password manager berguna untuk...', 'menyimpan banyak kata sandi unik dengan aman', 'membagikan password ke semua orang', 'mematikan antivirus', 'menghapus akun', 'A', 4, '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `questions` VALUES (35, 12, 'SQL Injection terjadi ketika...', 'input pengguna tidak divalidasi dan masuk ke query', 'database terlalu besar', 'server mati', 'password terlalu panjang', 'A', 1, '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `questions` VALUES (36, 12, 'Cara mencegah SQL Injection di Laravel adalah...', 'menyusun query string manual', 'memakai query builder/Eloquent dengan binding', 'mematikan database', 'memberi akses admin ke semua user', 'B', 2, '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `questions` VALUES (37, 12, 'OWASP Top 10 adalah...', 'daftar risiko keamanan web paling umum', 'jenis bahasa pemrograman', 'framework CSS', 'aplikasi edit foto', 'A', 3, '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `questions` VALUES (38, 12, 'Data sensitif seperti password sebaiknya disimpan dalam bentuk...', 'teks biasa', 'hash dengan salt', 'nama pengguna', 'file gambar', 'B', 4, '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `questions` VALUES (39, 13, 'UX (User Experience) berfokus pada...', 'tampilan warna saja', 'pengalaman dan kemudahan pengguna', 'kode program', 'konfigurasi server', 'B', 1, '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `questions` VALUES (40, 13, 'Wireframe adalah...', 'desain akhir berwarna', 'sketsa kasar tata letak halaman', 'kode HTML', 'struktur database', 'B', 2, '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `questions` VALUES (41, 13, 'Prinsip kontras dalam desain berguna untuk...', 'membuat semua elemen sama', 'membedakan elemen penting', 'mengurangi keterbacaan', 'menambah animasi', 'B', 3, '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `questions` VALUES (42, 13, 'Persona pengguna membantu desainer untuk...', 'memahami kebutuhan pengguna sasaran', 'menghapus data', 'mempercepat server', 'membuat database', 'A', 4, '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `questions` VALUES (43, 14, 'Prototype pada Figma digunakan untuk...', 'menguji alur interaksi sebelum coding', 'menyimpan database', 'menjalankan server', 'mengelola jadwal', 'A', 1, '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `questions` VALUES (44, 14, 'User flow menggambarkan...', 'langkah-langkah pengguna menyelesaikan tugas', 'warna brand', 'struktur database', 'biaya produksi', 'A', 2, '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `questions` VALUES (45, 14, 'Prinsip konsistensi berarti...', 'setiap halaman memakai pola dan gaya yang seragam', 'semua tombol berbeda warna', 'halaman berubah setiap refresh', 'desain tanpa navigasi', 'A', 3, '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `questions` VALUES (46, 14, 'Usability testing dilakukan untuk...', 'mengamati pengguna nyata memakai produk', 'menambah jumlah server', 'menghapus akun', 'membuat logo', 'A', 4, '2026-10-31 19:42:04', '2026-10-31 19:42:04');

-- ----------------------------
-- Table structure for sessions
-- ----------------------------
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions`  (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED NULL DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `sessions_user_id_index`(`user_id` ASC) USING BTREE,
  INDEX `sessions_last_activity_index`(`last_activity` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sessions
-- ----------------------------
INSERT INTO `sessions` VALUES ('CDv5I8pZAMxEBbg8xzb1dHKeSVAY4WjMN7TgMW97', 149, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJiNHZTbzV3aVBFSUlYbUVJU1lqWDRmSWFtT2VaYUtCYTFMeE1MRTJLIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL2Rhc2hib2FyZCJ9LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL2d1cnUiLCJyb3V0ZSI6Imd1cnUuaW5kZXgifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MTQ5LCJwYXNzd29yZF9oYXNoX3dlYiI6ImRjODFkNzgzN2ExM2JhOWNlNjRkM2ZlOWM2ZjFiNjhlZTRmMWU4NGUxZjc5N2RlYTVmNmE0NzIxYWExYTZmZTcifQ==', 1793479376);

-- ----------------------------
-- Table structure for teaching_assignments
-- ----------------------------
DROP TABLE IF EXISTS `teaching_assignments`;
CREATE TABLE `teaching_assignments`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `teacher_id` bigint UNSIGNED NOT NULL,
  `mapel_id` bigint UNSIGNED NOT NULL,
  `kelas_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `teaching_assignments_teacher_id_mapel_id_kelas_id_unique`(`teacher_id` ASC, `mapel_id` ASC, `kelas_id` ASC) USING BTREE,
  INDEX `teaching_assignments_mapel_id_foreign`(`mapel_id` ASC) USING BTREE,
  INDEX `teaching_assignments_kelas_id_foreign`(`kelas_id` ASC) USING BTREE,
  CONSTRAINT `teaching_assignments_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `teaching_assignments_mapel_id_foreign` FOREIGN KEY (`mapel_id`) REFERENCES `mapels` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `teaching_assignments_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 24 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of teaching_assignments
-- ----------------------------
INSERT INTO `teaching_assignments` VALUES (8, 150, 15, 29, '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `teaching_assignments` VALUES (9, 150, 15, 30, '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `teaching_assignments` VALUES (10, 151, 13, 29, '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `teaching_assignments` VALUES (11, 151, 13, 30, '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `teaching_assignments` VALUES (12, 152, 16, 29, '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `teaching_assignments` VALUES (13, 152, 16, 30, '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `teaching_assignments` VALUES (14, 153, 12, 29, '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `teaching_assignments` VALUES (15, 153, 12, 30, '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `teaching_assignments` VALUES (16, 154, 11, 29, '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `teaching_assignments` VALUES (17, 154, 11, 30, '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `teaching_assignments` VALUES (18, 155, 14, 29, '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `teaching_assignments` VALUES (19, 155, 14, 30, '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `teaching_assignments` VALUES (20, 156, 17, 29, '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `teaching_assignments` VALUES (21, 156, 17, 30, '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `teaching_assignments` VALUES (22, 157, 18, 29, '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `teaching_assignments` VALUES (23, 157, 18, 30, '2026-10-31 19:42:04', '2026-10-31 19:42:04');

-- ----------------------------
-- Table structure for teaching_schedules
-- ----------------------------
DROP TABLE IF EXISTS `teaching_schedules`;
CREATE TABLE `teaching_schedules`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `assignment_id` bigint UNSIGNED NOT NULL,
  `day` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `teaching_schedules_assignment_id_day_start_time_unique`(`assignment_id` ASC, `day` ASC, `start_time` ASC) USING BTREE,
  CONSTRAINT `teaching_schedules_assignment_id_foreign` FOREIGN KEY (`assignment_id`) REFERENCES `teaching_assignments` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 22 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of teaching_schedules
-- ----------------------------
INSERT INTO `teaching_schedules` VALUES (6, 8, 'senin', '07:00:00', '08:30:00', '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `teaching_schedules` VALUES (7, 9, 'senin', '10:15:00', '11:45:00', '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `teaching_schedules` VALUES (8, 10, 'selasa', '07:00:00', '08:30:00', '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `teaching_schedules` VALUES (9, 11, 'selasa', '10:15:00', '11:45:00', '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `teaching_schedules` VALUES (10, 12, 'rabu', '07:00:00', '08:30:00', '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `teaching_schedules` VALUES (11, 13, 'rabu', '10:15:00', '11:45:00', '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `teaching_schedules` VALUES (12, 14, 'kamis', '07:00:00', '08:30:00', '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `teaching_schedules` VALUES (13, 15, 'kamis', '10:15:00', '11:45:00', '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `teaching_schedules` VALUES (14, 16, 'jumat', '07:00:00', '08:30:00', '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `teaching_schedules` VALUES (15, 17, 'jumat', '10:15:00', '11:45:00', '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `teaching_schedules` VALUES (16, 18, 'senin', '08:30:00', '10:00:00', '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `teaching_schedules` VALUES (17, 19, 'senin', '12:30:00', '14:00:00', '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `teaching_schedules` VALUES (18, 20, 'selasa', '08:30:00', '10:00:00', '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `teaching_schedules` VALUES (19, 21, 'selasa', '12:30:00', '14:00:00', '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `teaching_schedules` VALUES (20, 22, 'rabu', '08:30:00', '10:00:00', '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `teaching_schedules` VALUES (21, 23, 'rabu', '12:30:00', '14:00:00', '2026-10-31 19:42:04', '2026-10-31 19:42:04');

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nis` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `nip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `bidang` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `kelas_id` bigint UNSIGNED NULL DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_plain` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `role` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'staff',
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `users_email_unique`(`email` ASC) USING BTREE,
  UNIQUE INDEX `users_nis_unique`(`nis` ASC) USING BTREE,
  UNIQUE INDEX `users_nip_unique`(`nip` ASC) USING BTREE,
  INDEX `users_kelas_id_foreign`(`kelas_id` ASC) USING BTREE,
  CONSTRAINT `users_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 208 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (149, 'Arif Alexander', 'arifmail@mail.com', NULL, NULL, NULL, NULL, NULL, NULL, '$2y$12$YGMw2MX04KQNESyWMeJRuesHUzNiPAMaXrvqlHiS6j0BNUmI7MJCG', NULL, 'superadmin', 'avatars/LjRLm1k9fBUZLvArsLVDA2E0mgjUUSuKxKkbJaxZ.webp', NULL, '2026-10-31 19:06:35', '2026-10-31 19:14:29');
INSERT INTO `users` VALUES (150, 'Fahrul Saputra', 'fahrul-saputra@guru.staffwork.test', NULL, 'G-2001', NULL, 'Manajemen Bisnis', NULL, NULL, '$2y$12$fHOa4gNJUXo78ND3du3WF.mLeIYo04ucQ7N1K8oXSCc0laSTpv4Qy', 'eyJpdiI6ImRYWS9xWWo4K3g3R1ViWll5VTVpSFE9PSIsInZhbHVlIjoiUWpQK202czh5QWF2a29zZTNBQnhKZz09IiwibWFjIjoiMzk0ZGRmYjkyMThiNTFhZGVkNGNjYjI5YTVlOTVlYWQzY2RlZDNlMGNhMTUwZjMzZjY5Y2Q2NDIyY2NjNTZkYyIsInRhZyI6IiJ9', 'guru', NULL, NULL, '2026-10-31 19:42:02', '2026-10-31 19:42:02');
INSERT INTO `users` VALUES (151, 'Arif Permana Putrasuryana', 'arif-permana-putrasuryana@guru.staffwork.test', NULL, 'G-2002', NULL, 'Cyber Security', NULL, NULL, '$2y$12$xpr0DBx9WEylZcDHThZJduVZe53NdrFG39zF6JtITNYZWcD2M6zUS', 'eyJpdiI6InpwUUZIZFRTZUdPUGtYSzBrRU5zT0E9PSIsInZhbHVlIjoiL2tjYkdkM3dSbE5wTHNVNGV0Vm1kZz09IiwibWFjIjoiY2ZjOTI2YTFmNzk0ODc1OWM2NmM2Y2Q0Zjg1ZTM3YTRhMWZhNTc0ODIwOWVkZTExMmEwYTYxN2I4YTIyOGU1ZSIsInRhZyI6IiJ9', 'guru', NULL, NULL, '2026-10-31 19:42:02', '2026-10-31 19:54:00');
INSERT INTO `users` VALUES (152, 'Arief Ramadhan Al-Hazmi', 'arief-ramadhan-al-hazmi@guru.staffwork.test', NULL, 'G-2003', NULL, 'Digital Marketing', NULL, NULL, '$2y$12$l4npBpnl5QPz.Z8XEzFMHOUEthEFNVrytbqNdUoI76Zc/DALTCa9e', 'eyJpdiI6ImJXVkk1VUlmNDY5Y0RndGRuU1BuVFE9PSIsInZhbHVlIjoiMXNhSm9WQi9hVmlKNDM2aWdQYlNRQT09IiwibWFjIjoiZjZmZTNiMDkwMWE5MjAwOWY3ZDA1NzFjM2IyM2JmMzM1OWQzZTM3NWE1ZDRiZDM5YmJiMTYxZjlkNWIyM2Y0NiIsInRhZyI6IiJ9', 'guru', NULL, NULL, '2026-10-31 19:42:02', '2026-10-31 19:42:02');
INSERT INTO `users` VALUES (153, 'Hanif Wisanggeni Prabowo', 'hanif-wisanggeni-prabowo@guru.staffwork.test', NULL, 'G-2004', NULL, 'Backend', NULL, NULL, '$2y$12$SZtB.Jex7/yRKw5bHuaa/eMPB9T44DeaDMMmCMmcLiT9gTDHbe5IW', 'eyJpdiI6Ik1JbmVWcCs4eTMyRXlnSFVsZnNQNHc9PSIsInZhbHVlIjoiWXhybkE2ZXhyemJXc0F5ek9jdkxCQT09IiwibWFjIjoiMTYwNGNmNzhiZDk1ZDQ5ZTEyMTUzNTliODMyY2E4M2JlYWJjZjBlOWY1YjJmYTY3OWJkYTJiNzUxOWIzZmI3NSIsInRhZyI6IiJ9', 'guru', NULL, NULL, '2026-10-31 19:42:03', '2026-10-31 19:42:03');
INSERT INTO `users` VALUES (154, 'Alfarobby', 'alfarobby@guru.staffwork.test', NULL, 'G-2005', NULL, 'Frontend', NULL, NULL, '$2y$12$zFpNWJk0gu0hUzK5kB1xmOlHns27ioPtQBW02a4fajn.TgPaY9/sm', 'eyJpdiI6ImdtVktFQytPNk9DbllndWxONWM3b3c9PSIsInZhbHVlIjoiTU9BUEF2L05BM1Z0M0YyV2t5ME9kdz09IiwibWFjIjoiOTM3MjA4Y2Y2ZDNiZWNkMDI2NzQ4YTU0N2QwMmI3ZjAzNGJiOGYyMmYzODI0MWFlODY1ZmM3MTk1NDY3MjI2ZSIsInRhZyI6IiJ9', 'guru', NULL, NULL, '2026-10-31 19:42:03', '2026-10-31 19:42:03');
INSERT INTO `users` VALUES (155, 'Muhammad Nur Maulana', 'muhammad-nur-maulana@guru.staffwork.test', NULL, 'G-2006', NULL, 'UI/UX Design', NULL, NULL, '$2y$12$16wn72YUPOTCwFTEPjRzc.Mh/cAtdmd8ZxNuD1y9Yy55APolbt1mq', 'eyJpdiI6ImRDSk51UG1RYVlwZFU4Q2JWUzAyTUE9PSIsInZhbHVlIjoiSWM3V0lvajY5R0NwczNjblF6ZUs3UT09IiwibWFjIjoiYTgwNjE0OGZjNzAwYWI4MmE2MDEwNzkwNDA2YTJjZWM5ZmM4NzE2NjZjMDRiMDM0ZWVjYWRlNGFlNTE1MGRiZCIsInRhZyI6IiJ9', 'guru', NULL, NULL, '2026-10-31 19:42:03', '2026-10-31 19:42:03');
INSERT INTO `users` VALUES (156, 'Muhammad Bagus Harianto', 'muhammad-bagus-harianto@guru.staffwork.test', NULL, 'G-2007', NULL, 'Full Stack Developer', NULL, NULL, '$2y$12$hR3YefBacGPZDqjHPmtWVuK3scuT6jVK2BrP8bIdJeqbf5J1Ad6fK', 'eyJpdiI6ImRYbnZzQkNDUHdhMmNBUThMaTBWUEE9PSIsInZhbHVlIjoidzI3bitMS1kxMlhqVnNvSGphU1F1dz09IiwibWFjIjoiZDNhODQyY2RjZmQ5MTFmNTNjNjhiZGU0NmRiZDAzOTkxNzdhZjA0YjgzMmVjZTU3YTA0NzJkMjQ2Y2NjNzI5MiIsInRhZyI6IiJ9', 'guru', NULL, NULL, '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `users` VALUES (157, 'Fahrul Rozi', 'fahrul-rozi@guru.staffwork.test', NULL, 'G-2008', NULL, 'CI/CD', NULL, NULL, '$2y$12$.fhuzJ4d6ao7/UmW3e1xBeFY8FAL.G5HddV.8oio00vxz5YJXWNey', 'eyJpdiI6IkF0OURTUjZPZWtaR3V3WVRXWi9sWXc9PSIsInZhbHVlIjoiN1M5ajg0RmxnSnRlNmQ0YTFMYmw0Zz09IiwibWFjIjoiNzViMDgwN2IxYmY2N2VmZTZiNzY4NDJlZjUwYWU5MGFhY2ZjNGQ5M2NlYzA0MDY2OTA3N2YzZjBkY2ZmNTk1NyIsInRhZyI6IiJ9', 'guru', NULL, NULL, '2026-10-31 19:42:04', '2026-10-31 19:42:04');
INSERT INTO `users` VALUES (158, 'Luluh Martana Nashiruddin', 'luluh-martana-nashiruddin.1@siswa.staffwork.test', '2425001', NULL, '082793161641', NULL, 29, '2026-10-31 20:31:39', '$2y$12$iyJUw7ND1GMgO7ol6hpS4ecM/PJkXQZv0sLZWf2uRzGwrzzZc.Abi', 'eyJpdiI6InkxbjVwVlN2OEFWWTlDYzVsRHRqSUE9PSIsInZhbHVlIjoiZW9qM1VyNHdleWhJVzFpdWloUXNNQT09IiwibWFjIjoiNmU5ZTZhY2UzMTNhMGM3YTc3NDU4N2Q3OTYwNGFiMjk1MjY2NzQwZWUwMDg3ZTY4N2Q0ZjgxN2RjMzU3ZWI4OCIsInRhZyI6IiJ9', 'murid', NULL, NULL, '2026-10-31 20:31:39', '2026-10-31 20:31:39');
INSERT INTO `users` VALUES (159, 'Yuliana Carla Kuswandari M.M.', 'yuliana-carla-kuswandari-mm.2@siswa.staffwork.test', '2425002', NULL, '089854357549', NULL, 29, '2026-10-31 20:31:39', '$2y$12$IqgjQwnfywJZXKKfqqi18uiSKx2U1jOsuW3Mdo/uHiq3kSqxjpK0W', 'eyJpdiI6Im5yZERwTHZ2aDdxTDdKTjVYT0FBTlE9PSIsInZhbHVlIjoiKzhKWTk3RlEyZEJzSTBJVmdyUUJNUT09IiwibWFjIjoiMmY0MDlkZDMwMTIzNTE4NWU2YTQ3NTA2ZTM1ZjUzMTgyNTBjZTU1ODVlMGRjNmNlNDhkZDE4OGExNDFjM2RiOCIsInRhZyI6IiJ9', 'murid', NULL, NULL, '2026-10-31 20:31:39', '2026-10-31 20:31:39');
INSERT INTO `users` VALUES (160, 'Candra Wasita', 'candra-wasita.3@siswa.staffwork.test', '2425003', NULL, '086134421418', NULL, 29, '2026-10-31 20:31:40', '$2y$12$PkqTvGd/dDksq8qVO4ap4e/vOnFoDlToP0M33U/xMhhxqJxCENA9u', 'eyJpdiI6Ik8zaDcxMnp2NER1NFpiSkxFMkY5OUE9PSIsInZhbHVlIjoiMG84NVkxajV2WWRCdGFJK3AxeHZZZz09IiwibWFjIjoiNTE3Njg0YTM2MmZlOWRjNzA4YjlhNzMwODE2MDNiMWNlNWQxODk1NjFmMGZkOTBjODI3ODViZjEzZTA5NjE4YiIsInRhZyI6IiJ9', 'murid', NULL, NULL, '2026-10-31 20:31:40', '2026-10-31 20:31:40');
INSERT INTO `users` VALUES (161, 'Ellis Farida S.Gz', 'ellis-farida-sgz.4@siswa.staffwork.test', '2425004', NULL, '086977305144', NULL, 29, '2026-10-31 20:31:40', '$2y$12$/4qrlbpAX/FC5wYJ46pA2eDAVCCOBgYxLo5Oq1AXbolDcrClZXZSS', 'eyJpdiI6IitKcTBwcHdmQy9KcWFOZmlHR3JhRlE9PSIsInZhbHVlIjoiZzl2S2JlOHBrMDJDVDZtTWxaVzhYQT09IiwibWFjIjoiODQwNTBlMzEwZDUwNzFiOTY5ZmFiZWRkZTdkOGE3ZmFmN2I4ZTkwMjY3OGY3Y2VkMzM1YzU5ZDVhODIxMDNiZiIsInRhZyI6IiJ9', 'murid', NULL, NULL, '2026-10-31 20:31:40', '2026-10-31 20:31:40');
INSERT INTO `users` VALUES (162, 'Usman Saefullah', 'usman-saefullah.5@siswa.staffwork.test', '2425005', NULL, '089459009469', NULL, 29, '2026-10-31 20:31:40', '$2y$12$i2BxBI/XWLtFn7p0brqzq.rf1wx7KcOycqYwxAfFhUGt471f7MbZi', 'eyJpdiI6ImZyS0dCYUZ5VXVyWTBOUU84eGI5VkE9PSIsInZhbHVlIjoiWVlGTVB3dllmelYwZzRBMklmdUZaQT09IiwibWFjIjoiZTI4Y2E1YmM4M2JjMzQzY2EzYjlkY2QxYWY0ZjY3ZjgyYTRkMGZjMjUwMjVkYzQyZjM4MmJlZjFmODRiYTA5OCIsInRhZyI6IiJ9', 'murid', NULL, NULL, '2026-10-31 20:31:40', '2026-10-31 20:31:40');
INSERT INTO `users` VALUES (163, 'Ratih Utami', 'ratih-utami.6@siswa.staffwork.test', '2425006', NULL, '086702266191', NULL, 29, '2026-10-31 20:31:41', '$2y$12$vJ0tzvPorcqgrXBeyIBBLO05yZBz/8EyMdwukQ9FguUcnhmAJRoQO', 'eyJpdiI6ImpzVkVaS3Zxd2p0cHhWNy9QRjgrbmc9PSIsInZhbHVlIjoibUNVLzIxYkNRQkd0QUhUZmRLdjF5Zz09IiwibWFjIjoiYzQwOWFmN2ZiNDY5NDc0NGEzNTlmZmFlY2U5YjY5ODUzMjNiZDAyM2FjY2UxYzg5ZjQzNzU1ODI0MWU5ZDZiZiIsInRhZyI6IiJ9', 'murid', NULL, NULL, '2026-10-31 20:31:41', '2026-10-31 20:31:41');
INSERT INTO `users` VALUES (164, 'Ganjaran Lazuardi Sihombing', 'ganjaran-lazuardi-sihombing.7@siswa.staffwork.test', '2425007', NULL, '089733528653', NULL, 29, '2026-10-31 20:31:41', '$2y$12$5Bc4csax/10CuH.QHP4kDOmT.NxwYzapZpq3F6NGaFJTKMNGcAMcq', 'eyJpdiI6InZDSzZab3N1Mm1GYUxPeHdFeVEwS1E9PSIsInZhbHVlIjoiNHk3NmRXejMzZ3BqRk0yNHQ1SFROdz09IiwibWFjIjoiZjU5MWU1MjYxYmJmNGU2ODRjZjk1MmYxOWVjYzU0MjhkMTY0ZTg2NTBlM2M1YjE3OTY2NDZjMmI2YzA5Mzg0MSIsInRhZyI6IiJ9', 'murid', NULL, NULL, '2026-10-31 20:31:41', '2026-10-31 20:31:41');
INSERT INTO `users` VALUES (165, 'Chelsea Usada', 'chelsea-usada.8@siswa.staffwork.test', '2425008', NULL, '084409028221', NULL, 29, '2026-10-31 20:31:41', '$2y$12$NswPgWcuU0YQ4Uj91gK5we/Sok9VlVGJ2M6O.AMRUDQnQX3mJ5/YS', 'eyJpdiI6ImpXTFQxUkczMk1KOXU2OWFIUDZQWlE9PSIsInZhbHVlIjoiR3UvR3FKNzNCK2RKTWpGYmpRYkdvUT09IiwibWFjIjoiMzMyNDllZjUwOTM1YjE2MmFiZTQzZjc2ZjRmNTExMDUzMWY5MjM3Y2UwYWExYzE4MmMyZTJkY2U3NTc3ZWI5YiIsInRhZyI6IiJ9', 'murid', NULL, NULL, '2026-10-31 20:31:41', '2026-10-31 20:31:41');
INSERT INTO `users` VALUES (166, 'Ganjaran Salahudin', 'ganjaran-salahudin.9@siswa.staffwork.test', '2425009', NULL, '089225328807', NULL, 29, '2026-10-31 20:31:42', '$2y$12$Pri1nqY2zMwFdHtAC/ZCROrcdepG2CX8tE4wnbLk.hCQb2ntI5N9u', 'eyJpdiI6IkJLNitPWXIyekhiemRSWTI4NVZMUXc9PSIsInZhbHVlIjoiV2FTUk5mT0Q4VTErb29tR2NvczdMZz09IiwibWFjIjoiMDkxZTVmMmQzODk3YmIxZDFjYmUyMzczYjEzMDMwOGQ5YjZlY2RmYzZlMmY5YTNjYWU0N2Y5YTQ3NDU1ZjdlMSIsInRhZyI6IiJ9', 'murid', NULL, NULL, '2026-10-31 20:31:42', '2026-10-31 20:31:42');
INSERT INTO `users` VALUES (167, 'Ira Suartini M.M.', 'ira-suartini-mm.10@siswa.staffwork.test', '2425010', NULL, '087017776230', NULL, 30, '2026-10-31 20:31:42', '$2y$12$rTdvoKo8CInbGkSieDg/XOlK13RXl7g9DxDY2GPK9SwVxpOPRrQVu', 'eyJpdiI6IlFrNTFabllFRDZVMHFWZ0FhbmUydlE9PSIsInZhbHVlIjoiSnpwT3hiZUNjT2dXNE1XZFNCTm8wZz09IiwibWFjIjoiOTdhZTg0MDhjYjdmMTBiMjdjMDAwNTJkOThlYzk4N2M0Y2UzZDdiMjk5MjZhNWQ5ZDFmMDNlNjIzZGI1MWVhZiIsInRhZyI6IiJ9', 'murid', NULL, NULL, '2026-10-31 20:31:42', '2026-10-31 20:31:42');
INSERT INTO `users` VALUES (168, 'Daru Lazuardi', 'daru-lazuardi.11@siswa.staffwork.test', '2425011', NULL, '080697376661', NULL, 30, '2026-10-31 20:31:42', '$2y$12$Gmv7e6Uh7pa/sDa..GuIyuma1WNKRkvOJgCWl2DdJa7R1BoAyg8IS', 'eyJpdiI6IkoxSVhYanpEalQycUw0NHdTSEZoVEE9PSIsInZhbHVlIjoidWVjTE5Db21zNVdvK2VZYjMyaEpVdz09IiwibWFjIjoiZTU3YzExMTE2MjFkMWEzM2VhZTYzYTdkZjhhYWU0NjQzNzY5ZmMzZTZkMTk2MzU3ODY0Njc1NTZlN2EwZTY1MyIsInRhZyI6IiJ9', 'murid', NULL, NULL, '2026-10-31 20:31:42', '2026-10-31 20:31:42');
INSERT INTO `users` VALUES (169, 'Padma Hana Widiastuti', 'padma-hana-widiastuti.12@siswa.staffwork.test', '2425012', NULL, '080093818745', NULL, 30, '2026-10-31 20:31:42', '$2y$12$UNXyyjDO7Bkx5igVpxvg.OvXnrgxiz7jstR4vqAWmD78PsCzGNDfC', 'eyJpdiI6IjZOTEYyNVorajgrdXNaZU5GUVRwdVE9PSIsInZhbHVlIjoiSmJpWU0yaGRBQlgzR2EvYWhwQ3QwQT09IiwibWFjIjoiODMzODkyYjY5ZTI0YTc5YjRmODRiZWU1MjFlYTRkNzFlMDM3OWJiYTc2OWQxOGFiZGEyNDZhMmZlMDg3Y2I4ZCIsInRhZyI6IiJ9', 'murid', NULL, NULL, '2026-10-31 20:31:42', '2026-10-31 20:31:42');
INSERT INTO `users` VALUES (170, 'Ibrahim Saptono S.Gz', 'ibrahim-saptono-sgz.13@siswa.staffwork.test', '2425013', NULL, '080585931033', NULL, 30, '2026-10-31 20:31:43', '$2y$12$eCnSfGC0dHCr7968Dv8OvOmRnupQcaZSOVfUm5XO/aBwEz.GPjDKG', 'eyJpdiI6Im9hT3hFMVh3b3ArR2ZHYmhlZThneGc9PSIsInZhbHVlIjoiQ2NrWjhBMjhMb0tBMnE2SElIT0RoZz09IiwibWFjIjoiNzRiY2FiN2FhNDk5YzVmNjAzNmNiNzE2ZjQ3Mzg3ODM2NGYzZjI5NDZjMmIxZGU3Mzc0YzgzN2VlNjgyZGY2YSIsInRhZyI6IiJ9', 'murid', NULL, NULL, '2026-10-31 20:31:43', '2026-10-31 20:31:43');
INSERT INTO `users` VALUES (171, 'Mala Hassanah Siregar', 'mala-hassanah-siregar.14@siswa.staffwork.test', '2425014', NULL, '086677244688', NULL, 30, '2026-10-31 20:31:43', '$2y$12$B.a8.ZtYWsXjeRMoQp/S0u1QZvQGwZ8u/x93JbUNksCvke2IGUQI2', 'eyJpdiI6IktQZlFtLzhGalBlTHM4Tm4rS3NpeHc9PSIsInZhbHVlIjoibU9qTmVsSWdWSUJvYU5KTVVKM2pyUT09IiwibWFjIjoiYTU0MDZlYzY5OTAxNDAzMjhlNGNlYzljNTllM2FkY2JhZWUwMjliODY4OTdmNWZmYTVlZDZmYzliZWU4ZGEwZCIsInRhZyI6IiJ9', 'murid', NULL, NULL, '2026-10-31 20:31:43', '2026-10-31 20:31:43');
INSERT INTO `users` VALUES (172, 'Bancar Nababan', 'bancar-nababan.15@siswa.staffwork.test', '2425015', NULL, '088787358738', NULL, 30, '2026-10-31 20:31:43', '$2y$12$kNuQ/Oi1MtwRHyxfPS36I.3oNjlm3nn6eJX6T1OaaStLg.tlmy/k2', 'eyJpdiI6InBmaXhYQ1l2RUlhcGZ0cDMxRlNjemc9PSIsInZhbHVlIjoiQWdzaWJ0Q25zWTE5dXVhZDFkSG5rdz09IiwibWFjIjoiODQ0NzIwMjgzMzAwZjBlMjNjMjI3YzExMWMxNDBkNGM4NGUwOGIwMjFjZDUzNDQyZjBlZjgzMzRiZTZlYTBlMyIsInRhZyI6IiJ9', 'murid', NULL, NULL, '2026-10-31 20:31:43', '2026-10-31 20:31:43');
INSERT INTO `users` VALUES (173, 'Jelita Usada', 'jelita-usada.16@siswa.staffwork.test', '2425016', NULL, '083401029741', NULL, 30, '2026-10-31 20:31:44', '$2y$12$X2B.HwZ9vyN5qHrJHmqN6ucQhtYb5azYEnh72KOgVBkcBflpcudnG', 'eyJpdiI6IlFFOUpiSWZXeE5yWkluVHpXdlJHYmc9PSIsInZhbHVlIjoiV1Q5aXFKTWhPTkhUQ1V0ZW1HUGJBUT09IiwibWFjIjoiZGFiMTViZWEwYmE4YWUxMWZkOTliMzhiYTY0OTgzYjBiZmUyNTVmZDNhNjFiMTQ4YTU2YzRkN2E5ZGNkZmQ2NiIsInRhZyI6IiJ9', 'murid', NULL, NULL, '2026-10-31 20:31:44', '2026-10-31 20:31:44');
INSERT INTO `users` VALUES (174, 'Artanto Dwi Hidayanto S.E.', 'artanto-dwi-hidayanto-se.17@siswa.staffwork.test', '2425017', NULL, '087305358537', NULL, 30, '2026-10-31 20:31:44', '$2y$12$PUNYTPG.Z/8daZeZz/OPrOaGVuEYBRDGjjAjlU23QX6/8ICKQ4Nda', 'eyJpdiI6ImZ5UFBCWjgxNnR6OEI5YVhsZ0cwRkE9PSIsInZhbHVlIjoiNERqWi81UEplRGN0MGJWNXVNVzh4Zz09IiwibWFjIjoiMDM5MTA3NzU0Y2ZkZTNkZjEyYTI0NDA4NDA3YzM1NjkzM2E1YmQyYzU4NzI5NzBjNWI0OTViMzgyMjk2MzQ5ZiIsInRhZyI6IiJ9', 'murid', NULL, NULL, '2026-10-31 20:31:44', '2026-10-31 20:31:44');
INSERT INTO `users` VALUES (175, 'Samiah Rachel Puspasari', 'samiah-rachel-puspasari.18@siswa.staffwork.test', '2425018', NULL, '084466085759', NULL, 31, '2026-10-31 20:31:44', '$2y$12$lj7l07/70XigRzjdVtLbY.7DyMd5023QgSAYDT0AJ6pZSrNhtkHTu', 'eyJpdiI6ImVCZmNzZHFyUTliR3FKRmhiaDJOMWc9PSIsInZhbHVlIjoiaE5ab3pKbEJUK0RGRDJ5UHV0MEt1UT09IiwibWFjIjoiNWIzNzNiNDYxZDEzZjliNzE2OGY1MmE0N2U5OWY4Y2RkMzg2OTRmMzVjYWY4ZWUxZDVmODg0NmJmZDY4NzVmYSIsInRhZyI6IiJ9', 'murid', NULL, NULL, '2026-10-31 20:31:44', '2026-10-31 20:31:44');
INSERT INTO `users` VALUES (176, 'Bajragin Dacin Prasetya', 'bajragin-dacin-prasetya.19@siswa.staffwork.test', '2425019', NULL, '081424299309', NULL, 31, '2026-10-31 20:31:45', '$2y$12$ptUbwhbe5FoPTfJnKeXCq.JqtdEO7hMw/F/cVRf/.ueRZtX6QJ3fC', 'eyJpdiI6IjdGcUhEYnJoYlBTLzBWbTU1K21KRFE9PSIsInZhbHVlIjoiNnNUYTdqYWtwZFFmOHVWbGZaVk9BZz09IiwibWFjIjoiODgyOWU5OTRhMDM0NGNiNmM1MmYzMWE5ZDc0ZjVhNTU2YmI4YWQxYjNmZGYxNWFjMmJiOWM0NzMwMGQ5ZmJhYyIsInRhZyI6IiJ9', 'murid', NULL, NULL, '2026-10-31 20:31:45', '2026-10-31 20:31:45');
INSERT INTO `users` VALUES (177, 'Nadine Wijayanti', 'nadine-wijayanti.20@siswa.staffwork.test', '2425020', NULL, '083289008703', NULL, 31, '2026-10-31 20:31:45', '$2y$12$aiD2ikL3wsXdsDXFWzi8OeFXOoMuPNAiMgWRulyHAbLLjv6NEe8wa', 'eyJpdiI6IkUwKy9uZ01iUmdNejlQUC94QWZRcnc9PSIsInZhbHVlIjoiZmkyVGJiN2wyZndvMVdVQmJXc1dmZz09IiwibWFjIjoiMGNjN2Q1NGZlMGQ4NzkyODIwMjYyMmU2ZDc2N2Y2M2Q3NmI1MDE1MTBlYjVmMjI3ODQ2YWVjMGExYzMzNmVkYyIsInRhZyI6IiJ9', 'murid', NULL, NULL, '2026-10-31 20:31:45', '2026-10-31 20:31:45');
INSERT INTO `users` VALUES (178, 'Rafid Firgantoro Uyainah', 'rafid-firgantoro-uyainah.21@siswa.staffwork.test', '2425021', NULL, '084568087002', NULL, 31, '2026-10-31 20:31:45', '$2y$12$7vFBsgtuSZ72sy2PO56QI.viehviRGxEoohsetXuI9a4BxNh/ZHau', 'eyJpdiI6IlpzU0dvZFdGbUdpSDZuR2JTN0JVS3c9PSIsInZhbHVlIjoiVEU1MDd3OWN4b2tUdnJUNTZzYlJCQT09IiwibWFjIjoiNDlmYWVlODg0Yjg1ZGU0MWQzYzc0MTcyZDAyNzEzMGE2NjExMTkyMGRlYTdiMmU4ZTdlOTlmYzIxNjMyM2M2NyIsInRhZyI6IiJ9', 'murid', NULL, NULL, '2026-10-31 20:31:45', '2026-10-31 20:31:45');
INSERT INTO `users` VALUES (179, 'Titi Calista Aryani', 'titi-calista-aryani.22@siswa.staffwork.test', '2425022', NULL, '083697664849', NULL, 31, '2026-10-31 20:31:45', '$2y$12$zX5ktVL/DW3cJjvMkU0HeeXNYhfuDOKSXoRwDIBo/7ZbD04ZlA/N6', 'eyJpdiI6IkVRWFhnVVdFY0xIaUxEZnRTU0FlSHc9PSIsInZhbHVlIjoiT0c5cndyRlZJeVBJeW0zQUJlU0h1QT09IiwibWFjIjoiMWY0ZTQ3MjdmMzVhYWY4YmRiOTYzMWI5N2UzODM2NWYwYjgwYzIzZDdjYjNjMmU3ZmY2ZjMzNDRlYzM3ODIxMyIsInRhZyI6IiJ9', 'murid', NULL, NULL, '2026-10-31 20:31:45', '2026-10-31 20:31:45');
INSERT INTO `users` VALUES (180, 'Harjaya Darmana Pratama S.Sos', 'harjaya-darmana-pratama-ssos.23@siswa.staffwork.test', '2425023', NULL, '082226001956', NULL, 31, '2026-10-31 20:31:46', '$2y$12$mQBpDkyDgpbifMdRHESFAORLwUE3T2dMweRz9rDnUIZmA.iMFJtPy', 'eyJpdiI6InFvMXg1eXZZZXJRM2pDdDlld0lmWVE9PSIsInZhbHVlIjoieXRaSVZpZGEyamF0dFRNQXZISjVUdz09IiwibWFjIjoiZTJlYmQ1Y2EwYTY3YjVlYzkwZTFlM2I2YjBhZTFjZjljMjIwZTc1NWVhNTA4NTBiODhmZDY5ZGNmYWU4NDkwNCIsInRhZyI6IiJ9', 'murid', NULL, NULL, '2026-10-31 20:31:46', '2026-10-31 20:31:46');
INSERT INTO `users` VALUES (181, 'Amelia Yuniar S.Farm', 'amelia-yuniar-sfarm.24@siswa.staffwork.test', '2425024', NULL, '088130772380', NULL, 31, '2026-10-31 20:31:46', '$2y$12$nBJim62jXJz3VscqVSaYA.7QLWcrVR8HaTVSYVk.lpSEahtdhtznq', 'eyJpdiI6IjVxN2c0K2JZLzI5U2FrVVB2ajRZSFE9PSIsInZhbHVlIjoiamJ5djJ6T3VRMm5KdGZXTFYvT2hBQT09IiwibWFjIjoiOThmZjdmNTVhNzAwNDM0YjQ2NDkwMjgyMDBkOTM0MGQzOTBmYjQyODNjZTU3M2I4MTAwODA1ZTU4ZWU4YWVjNiIsInRhZyI6IiJ9', 'murid', NULL, NULL, '2026-10-31 20:31:46', '2026-10-31 20:31:46');
INSERT INTO `users` VALUES (182, 'Nrima Uwais M.Kom.', 'nrima-uwais-mkom.25@siswa.staffwork.test', '2425025', NULL, '081320239421', NULL, 31, '2026-10-31 20:31:46', '$2y$12$B4vWryf0Oc3LjeCOOAT3feS4WOZ1yUfX8pLbTYlKqGH2J9ljcAt7q', 'eyJpdiI6IjYzbFhGZDBVa3pqNGVSNWFjb09ZSnc9PSIsInZhbHVlIjoiYzRMcS8yTDdrak9PUmU0MkRmWTRQZz09IiwibWFjIjoiNzk5MDQxZjM2ODVjZjM5NDk4N2E0NWRhY2Q2ODdlM2U5NmVhNjkzODExMGRlNzZjOTUyMmVhOTUyZTYxMjdkOCIsInRhZyI6IiJ9', 'murid', NULL, NULL, '2026-10-31 20:31:46', '2026-10-31 20:31:46');
INSERT INTO `users` VALUES (183, 'Cinthia Aryani', 'cinthia-aryani.26@siswa.staffwork.test', '2425026', NULL, '085917951570', NULL, 32, '2026-10-31 20:31:47', '$2y$12$Dq8onOipjchAkOhPsOQIvu5KelVnnzvK7wWmcGo7TYilGhEPQh7Ne', 'eyJpdiI6ImdNbDZJMGtLeUlkekRjR3pNcjFJeWc9PSIsInZhbHVlIjoiOFRoY2ZKSUhBQzJ1MkR3c01vR3o5UT09IiwibWFjIjoiNGZlOGQxNjlmZTQ3NTI0Y2MyOTk3OTcyYjYwZDc4NjViNTJlODZhNzk2NzBkMDg2ZjVlNjA2NTgzNDhiYmRkNCIsInRhZyI6IiJ9', 'murid', NULL, NULL, '2026-10-31 20:31:47', '2026-10-31 20:31:47');
INSERT INTO `users` VALUES (184, 'Dasa Rangga Rajasa S.T.', 'dasa-rangga-rajasa-st.27@siswa.staffwork.test', '2425027', NULL, '088047410027', NULL, 32, '2026-10-31 20:31:47', '$2y$12$xhBqdifXcpEu59wRjHMzduf8TTG8Cw4lICa79uOW1CH/SI.5Ytcge', 'eyJpdiI6ImlQcjlVcThYN1lIL3cyS2UxWmI3Z2c9PSIsInZhbHVlIjoiamFSTy9sUW5SSHlsMytlZDhHNkxxUT09IiwibWFjIjoiMjEzZjVkOGM2MDUzMjRhNTIzMjk1ZWZiMzUwZmZkZTg0MDAxZDI3MDc3ODE3MGRlMDVmZTAzYzYyYjk4MTg2NSIsInRhZyI6IiJ9', 'murid', NULL, NULL, '2026-10-31 20:31:47', '2026-10-31 20:31:47');
INSERT INTO `users` VALUES (185, 'Halima Humaira Safitri S.IP Zulkarnain', 'halima-humaira-safitri-sip-zulkarnain.28@siswa.staffwork.test', '2425028', NULL, '080730046807', NULL, 32, '2026-10-31 20:31:47', '$2y$12$2DI15bPYfDejBOFV1y5gS.LYh7Fa6A0jC7bR0grgorzQ10T9fYuHm', 'eyJpdiI6IkFUY2JLSnB0TmdsZjV5R3c4ZU5STUE9PSIsInZhbHVlIjoianRwZkpHSm9ncXNXSlo4cDFXbktXZz09IiwibWFjIjoiNzdiZmEzZWQyYTZkMDhhMGI4MDQxMTdiMTAwOTQ0MjA0OTU3ODc0ZGEyMzBkOGE5MTE4MDBmOWU4YmQ3Y2IxMiIsInRhZyI6IiJ9', 'murid', NULL, NULL, '2026-10-31 20:31:47', '2026-10-31 20:31:47');
INSERT INTO `users` VALUES (186, 'Artanto Saputra', 'artanto-saputra.29@siswa.staffwork.test', '2425029', NULL, '085684720354', NULL, 32, '2026-10-31 20:31:48', '$2y$12$nQMUEwHwtzDEUuSzyj4fkO8EmnuS1p3haSV3yf8iD2oPD/Jtt54R6', 'eyJpdiI6IkV6S2E0SmJrTHo2NWplcnpIWXgzeXc9PSIsInZhbHVlIjoiOCt0RlFzZkQ5cTFhbXRxeE1IR3BiQT09IiwibWFjIjoiNDdjNTVmOGUwYjdlNjVlNTk5ZWM0ZjFiYmRjYTY4OWFmMDhlYzFmYzA0ODM1OWRmZWVjMWQ3OWM3OWM0ZmFlMSIsInRhZyI6IiJ9', 'murid', NULL, NULL, '2026-10-31 20:31:48', '2026-10-31 20:31:48');
INSERT INTO `users` VALUES (187, 'Rina Uyainah', 'rina-uyainah.30@siswa.staffwork.test', '2425030', NULL, '082406588130', NULL, 32, '2026-10-31 20:31:48', '$2y$12$tXVe/.fpU6s5HoKFnZqf0u6GXyffmo3TXFGXgQjAokYjIb7CKfdGq', 'eyJpdiI6Ik1PNTVLNU5sc0FFaEdxQ3hvR0QrUVE9PSIsInZhbHVlIjoiMmJZQ3FTRkhZOEtHL1ZFOWJNTzFpdz09IiwibWFjIjoiZmVlN2QwMzAwMTlkYjFmZjRhYzVjOWMxOTEyMjI1NjA4YjE0M2RkYjAxMTYxZDJhOTVlYzU5ZDgzOTMzOGYyMSIsInRhZyI6IiJ9', 'murid', NULL, NULL, '2026-10-31 20:31:48', '2026-10-31 20:31:48');
INSERT INTO `users` VALUES (188, 'Jarwi Salahudin', 'jarwi-salahudin.31@siswa.staffwork.test', '2425031', NULL, '082525597241', NULL, 32, '2026-10-31 20:31:48', '$2y$12$OOTB37GAQzr3t1RBCB1jJuHBqIJ4QeQxn8ysw7pjLm9PiJ/0pHTBu', 'eyJpdiI6Im5JekZnZEF4b0k2TXhMNjZBM2htTFE9PSIsInZhbHVlIjoiNTlrdVA5OUgveGdVbHJadmtXVFBJdz09IiwibWFjIjoiZDMxODVjZDM5YmQyNjFiZjE3ZDVmMzk2OGJmZmQ2OWM5ODJiOTc3ZTVhN2EwYzEzZjkyMzU1NDc5ODE1Nzk2OCIsInRhZyI6IiJ9', 'murid', NULL, NULL, '2026-10-31 20:31:48', '2026-10-31 20:31:48');
INSERT INTO `users` VALUES (189, 'Rahayu Lalita Novitasari', 'rahayu-lalita-novitasari.32@siswa.staffwork.test', '2425032', NULL, '085648769654', NULL, 32, '2026-10-31 20:31:49', '$2y$12$Uk0MeSOWW6RGSPBttiIOy.wwd9CCXXqDqyLqK3M0ozuuRZODIbhhi', 'eyJpdiI6Ik15Z2F4UklFa3RjelNUczZjdHB6Ync9PSIsInZhbHVlIjoiSGl5MmhSV2tvTStQdmRTcHRuUDJ6UT09IiwibWFjIjoiMWEwYmU5YTQ2ODFiZTQxMWYyYTJlNzlhOTlhNjk0NDA3MzQ1MmMxMjA1OGRlN2QxMDEwM2Q3OTE3ODUxYWMyMSIsInRhZyI6IiJ9', 'murid', NULL, NULL, '2026-10-31 20:31:49', '2026-10-31 20:31:49');
INSERT INTO `users` VALUES (190, 'Olga Sihombing', 'olga-sihombing.33@siswa.staffwork.test', '2425033', NULL, '084872166221', NULL, 32, '2026-10-31 20:31:49', '$2y$12$rvLVGJNEBj4BALej4Flqe.GnFfr41f0pQAnrthu4u6xYS2qLIJyz6', 'eyJpdiI6IkQ4VmE5cVd6djRUL1JQVUJ3c25IakE9PSIsInZhbHVlIjoiUkVSMlNhMjdVMzdTS0QrYmMrdUpaQT09IiwibWFjIjoiOGJkNmMwY2RmNThlZGU4MTg3YjkzYjI4MDFjMDJlMmY5M2I2MDlmMzZiMDVlNTAyMjU5MWQ1MzBiZjFiNjRiZSIsInRhZyI6IiJ9', 'murid', NULL, NULL, '2026-10-31 20:31:49', '2026-10-31 20:31:49');
INSERT INTO `users` VALUES (191, 'Violet Dalima Hasanah S.E.I', 'violet-dalima-hasanah-sei.34@siswa.staffwork.test', '2425034', NULL, '082340587840', NULL, 32, '2026-10-31 20:31:49', '$2y$12$x/bNFclYvVYvmcjpVmFOE.bG8GABahRkWrcDl1yNtWHHYVVsdxiN2', 'eyJpdiI6InJCRW9MTDhxbVhVUndQeE5wUnlwM0E9PSIsInZhbHVlIjoicjUzeGRuaWI3cy9kWWhBM0QvbUN2Zz09IiwibWFjIjoiNTI2M2FjMDQ5ZDg5OGIyNjhhNzhkMWYzOGI5NjM3ZmFiZTU3Mzk4MTFkNThiOWJkMzU2OGE5NDg4MTQ4N2JkMyIsInRhZyI6IiJ9', 'murid', NULL, NULL, '2026-10-31 20:31:49', '2026-10-31 20:31:49');
INSERT INTO `users` VALUES (192, 'Galih Thamrin Wahyudin', 'galih-thamrin-wahyudin.35@siswa.staffwork.test', '2425035', NULL, '081624844148', NULL, 33, '2026-10-31 20:31:49', '$2y$12$X4hj7XLnRjwPtR8bOSadouHt/23iJDCwOQLsZ0nbZx9yfqEp/SU/2', 'eyJpdiI6IkZmWEt1RWpRNWhyQ0RGZTY0WmlyRmc9PSIsInZhbHVlIjoiQ0RUZWR2elVXWmJvM3kzWlRCSFV3UT09IiwibWFjIjoiZTIwYmViMWUxMzNlZDFjN2JiNzk4Y2EzNWQ0YWExMTJkM2Y1YWI1OWI2NWQxOGQ5MTIwODMxYmE1NDBmMTc2MyIsInRhZyI6IiJ9', 'murid', NULL, NULL, '2026-10-31 20:31:49', '2026-10-31 20:31:49');
INSERT INTO `users` VALUES (193, 'Najwa Puspita', 'najwa-puspita.36@siswa.staffwork.test', '2425036', NULL, '084955633086', NULL, 33, '2026-10-31 20:31:50', '$2y$12$gduZ9nDiCft65kXqtPJlSO6OL.khi6zMGluz4/nF7NV61MT44L85K', 'eyJpdiI6ImVqM1lETUJta3A3eVl5TFhYeXVJeHc9PSIsInZhbHVlIjoickhqSzBzVnc3Y1JqTkNXSWpzSHZ6dz09IiwibWFjIjoiOWMxNjM2ZjRlZjdhMjBmMzk1ZDAwZjQ1YmUxNDQzMTczZmUzODA3NjA3YWI3M2FjYmQzZDBmZDU1M2U3Y2Y0YyIsInRhZyI6IiJ9', 'murid', NULL, NULL, '2026-10-31 20:31:50', '2026-10-31 20:31:50');
INSERT INTO `users` VALUES (194, 'Indra Iswahyudi S.Psi', 'indra-iswahyudi-spsi.37@siswa.staffwork.test', '2425037', NULL, '085132138548', NULL, 33, '2026-10-31 20:31:50', '$2y$12$9xqCIGpwdqP5GYLLm02J4eAYYyiZPCW5Dn.NFn7O.tFErOEm2fOZ.', 'eyJpdiI6Im1QbUhQZVdiZTdHNW9mcGRObEdEbWc9PSIsInZhbHVlIjoieFI4OE12OFJCSlZXS0tQQ3pMMUQ3QT09IiwibWFjIjoiMWIwYTg5NzMyOTI4NzIxMTEwM2JjNzZkODdjNDc0Mzk5YTg0YTgwOTViY2FlOWY1NTZkYmUwNTc2ZmY0Nzk3MyIsInRhZyI6IiJ9', 'murid', NULL, NULL, '2026-10-31 20:31:50', '2026-10-31 20:31:50');
INSERT INTO `users` VALUES (195, 'Anastasia Lailasari', 'anastasia-lailasari.38@siswa.staffwork.test', '2425038', NULL, '087696641723', NULL, 33, '2026-10-31 20:31:50', '$2y$12$ooZadLCMKThChae.DJ0m9.deHTMWjufgznNn3NkBZb4BZWMXBhDLq', 'eyJpdiI6IjJKYmZvVmx1MTBrOWd5OEtiaDVuS3c9PSIsInZhbHVlIjoiL1o5SEpndXJKV0luWDdyTFV0Ny8rQT09IiwibWFjIjoiY2Y4NzVhMjgwOTQ2ZDgxOTlhOTkzYjZmZmZkMDAxNjM0OWNiMzU5Y2YxMGFmODcwM2ExODI5ODU2ZmRhNzYxNCIsInRhZyI6IiJ9', 'murid', NULL, NULL, '2026-10-31 20:31:50', '2026-10-31 20:31:50');
INSERT INTO `users` VALUES (196, 'Nugraha Saragih S.E.I', 'nugraha-saragih-sei.39@siswa.staffwork.test', '2425039', NULL, '086906099801', NULL, 33, '2026-10-31 20:31:51', '$2y$12$etAl7wDhq0j7uxbScdiaZ.xhmIdSNRKJpOJbCTKuG24KYcb2lA1DO', 'eyJpdiI6IlhmZEVkVUFldGdjY0NORlZXNlljZ2c9PSIsInZhbHVlIjoiWU1rdGs3SHhGdjRCcUNIS3RPN0lZUT09IiwibWFjIjoiNjE2MThlODZiY2I4OTEwMDZiNGQ2Y2I3NjFiYzYyNDc5NjRhZTQ5MzEzNzI2OTQwNjNmMWQ3YzU3YTAyOGY2ZiIsInRhZyI6IiJ9', 'murid', NULL, NULL, '2026-10-31 20:31:51', '2026-10-31 20:31:51');
INSERT INTO `users` VALUES (197, 'Queen Prastuti', 'queen-prastuti.40@siswa.staffwork.test', '2425040', NULL, '080936875077', NULL, 33, '2026-10-31 20:31:51', '$2y$12$oqA5eiwgWt.cT5PL9d3PYezrjU3OFLAGU5w7Z9xUnP9cs4p7M9aN.', 'eyJpdiI6InhPalJ3L044aWhnWVVsN0tqd1lBbHc9PSIsInZhbHVlIjoiODZyVHhHN0JMMm1PTzBjb1g4UFR4Zz09IiwibWFjIjoiNDdlM2Q1ZGRhNmFmMWRjYzBkYTk4M2U5ODU3MDFkMDc4Yzc1OGYzNDU3YTM2YTVhNzc0Y2FiYzRlZTFjODAyYSIsInRhZyI6IiJ9', 'murid', NULL, NULL, '2026-10-31 20:31:51', '2026-10-31 20:31:51');
INSERT INTO `users` VALUES (198, 'Sabri Irawan', 'sabri-irawan.41@siswa.staffwork.test', '2425041', NULL, '082525425796', NULL, 33, '2026-10-31 20:31:51', '$2y$12$8QgF3W7sGKYq56ulBNHO7OidWeXgrF/wsO0tV4n5lWAPB/FhMCQUq', 'eyJpdiI6IkY5UUdEMWlLNzZaSXpGTWtLZmhQSWc9PSIsInZhbHVlIjoiTzZoZVJ4V1pJRXhVYStrZklxSGdRZz09IiwibWFjIjoiMWQ4MzA0NjZmMDViYTdkMmZmMThlY2U0OTBjOTViNDNkMDNkMTJiNWQ4ZTNkMjlkODVkZmQwYmRmMjU5MGI4NyIsInRhZyI6IiJ9', 'murid', NULL, NULL, '2026-10-31 20:31:51', '2026-10-31 20:31:51');
INSERT INTO `users` VALUES (199, 'Maida Widiastuti S.Ked Anggraini', 'maida-widiastuti-sked-anggraini.42@siswa.staffwork.test', '2425042', NULL, '086176089428', NULL, 33, '2026-10-31 20:31:52', '$2y$12$4FYeFg7yhuV414m1Ct4Hhe2st16qWro.E2/6mwsWqTZW8hQTReWVS', 'eyJpdiI6IlE1TXJnZE8vWDU4bW1RNkpjajVLWnc9PSIsInZhbHVlIjoiYVg0TEdIQU1IOTNOWWQ0YVU0SE5LQT09IiwibWFjIjoiYjM4YmQzNTZlZWU4Y2FlNWYzNjE3ODM3MTlhYWYxZTlmOTliNjYzZjJlYjljMWFkZjUzMDQyYmQ5ZmY2MzU2MSIsInRhZyI6IiJ9', 'murid', NULL, NULL, '2026-10-31 20:31:52', '2026-10-31 20:31:52');
INSERT INTO `users` VALUES (200, 'Maryadi Hidayanto S.I.Kom', 'maryadi-hidayanto-sikom.43@siswa.staffwork.test', '2425043', NULL, '085145682957', NULL, 34, '2026-10-31 20:31:52', '$2y$12$AKDJGn1OBG1G4EURgaAL2uAchIRaOiJNoEPGjvC2JScl5jGd5l/FW', 'eyJpdiI6ImFMVm90dzYwU1VpemJYQlpibHJEZVE9PSIsInZhbHVlIjoiWDZJYUZJeFdYNlNuTlpsa1hpbWRvZz09IiwibWFjIjoiM2ZlZmU2NzQ0MDg4NTFmNDNjMTBmNjRkYjg3YWE0YWRhYzc0NDBlMzFjNTYzZDM1ODYxYmJhMzcxZWM2ZTA2YSIsInRhZyI6IiJ9', 'murid', NULL, NULL, '2026-10-31 20:31:52', '2026-10-31 20:31:52');
INSERT INTO `users` VALUES (201, 'Hafshah Hasanah', 'hafshah-hasanah.44@siswa.staffwork.test', '2425044', NULL, '082112238386', NULL, 34, '2026-10-31 20:31:52', '$2y$12$mssyGiXHGkgZJilcz8.zdup7aKubsr9/UAjvckES/uW66ViOrk0l.', 'eyJpdiI6Im1URzN0WVBHdXpTdm9teXZOQ09pY3c9PSIsInZhbHVlIjoiQmVPMHlzQXJ5bXpXakN3eE1lK3hJZz09IiwibWFjIjoiZjIzYzA0MDBiMmIyN2Q4ZTZlZThhMDQxY2U4NDNmMzNjNDlkZjYzODM4OGQ4NzRhZWRkNjRhY2I4NWVjY2I3ZCIsInRhZyI6IiJ9', 'murid', NULL, NULL, '2026-10-31 20:31:52', '2026-10-31 20:31:52');
INSERT INTO `users` VALUES (202, 'Martani Bambang Tamba S.I.Kom', 'martani-bambang-tamba-sikom.45@siswa.staffwork.test', '2425045', NULL, '080385931657', NULL, 34, '2026-10-31 20:31:52', '$2y$12$LTEEbcYFn3ZhcLKZyyeBl.5bFtcAYsFeHUzqFh7xQvtWPYXN9p1DG', 'eyJpdiI6IkdoUDJkS0k3cFc2eTU5dEY5WXJGV3c9PSIsInZhbHVlIjoiYXppZmM3K2gyUXp3aUFCYnJ6WDV0UT09IiwibWFjIjoiYjZhZjQ1NzljOThmYmRiOGU2NTBiMDg5YWU4YWRlNTRiNmNiZjJkZWQzNjMyZWVlNDYzNDEzMzEwZmZmOGIwYiIsInRhZyI6IiJ9', 'murid', NULL, NULL, '2026-10-31 20:31:52', '2026-10-31 20:31:52');
INSERT INTO `users` VALUES (203, 'Kamaria Hartati', 'kamaria-hartati.46@siswa.staffwork.test', '2425046', NULL, '087126972284', NULL, 34, '2026-10-31 20:31:53', '$2y$12$zioVlzfU4PnSsA6HDuTFXOib6JgcGtrn2D7bgaNlG/wWW4TqtzbTW', 'eyJpdiI6Im9wWEc5RWNENW1QQkZEbDdZUlVxUWc9PSIsInZhbHVlIjoiYXhIRUkvcXNibUErc1pHQ1FPMzZDUT09IiwibWFjIjoiYTJlNmQwODExMzIxNGNkODFhZDFkMjQ2OGU5NjY0MzU4MWU5ZGU2MzFjYzAwODI3YWU0ZTg3ZDBkMzY2NjA1YyIsInRhZyI6IiJ9', 'murid', NULL, NULL, '2026-10-31 20:31:53', '2026-10-31 20:31:53');
INSERT INTO `users` VALUES (204, 'Carub Manullang', 'carub-manullang.47@siswa.staffwork.test', '2425047', NULL, '089176913941', NULL, 34, '2026-10-31 20:31:53', '$2y$12$Aw1tyqCmiKUWYJbPf/RrVO5T7bSAfABKpL6ddh121bk16maS7Iffm', 'eyJpdiI6IldoK3NlRG1TWkFrMThhcDNTdEdmVWc9PSIsInZhbHVlIjoiSngyb3ROZSs5cGlRZmZqbCs5TmFHdz09IiwibWFjIjoiZDYzYjg0Mjk1NDdhNTg3ZGQxNzFkODA4MGY3NzQxZmY0YzI4ZjRjODM5MWJiYTBmZTVlYzhhNDZlN2ViNzBiMiIsInRhZyI6IiJ9', 'murid', NULL, NULL, '2026-10-31 20:31:53', '2026-10-31 20:31:53');
INSERT INTO `users` VALUES (205, 'Olivia Pratiwi', 'olivia-pratiwi.48@siswa.staffwork.test', '2425048', NULL, '085646123210', NULL, 34, '2026-10-31 20:31:53', '$2y$12$ZghKUpmbpaZmzcpdNXaRn./7M5ujqnmMsxP2FJ2dTXVQa/wDeP1oq', 'eyJpdiI6IlgyZWIxUTdjQ0UyQldqS0xzbFRjQUE9PSIsInZhbHVlIjoickZUaUR2b3NRUmVoSjZ6R2lIRHZTUT09IiwibWFjIjoiYzlkY2I1NWE2NTE5OWIwM2E5ZWM3MTdlMWQ4MzQ4NjE2ZjY1OTVmMWY5NTMwMjA5NmU4ZDI3ODM3NmE2ZjJkOCIsInRhZyI6IiJ9', 'murid', NULL, NULL, '2026-10-31 20:31:53', '2026-10-31 20:31:53');
INSERT INTO `users` VALUES (206, 'Ajiman Vero Damanik Fujiati', 'ajiman-vero-damanik-fujiati.49@siswa.staffwork.test', '2425049', NULL, '084328741174', NULL, 34, '2026-10-31 20:31:54', '$2y$12$0/rlP2MIyAzmKN8V8cgpwuRiO4UV8U8myfYw/DqL0VmxFTIyieQHG', 'eyJpdiI6IkJBRmwwYndpWkYxQVlFODhJUmwxZ1E9PSIsInZhbHVlIjoiaHBVclJxb1QxU3ErOGZEYkRCWm4zdz09IiwibWFjIjoiMWVmNDRhYTk4YTczMDQ5M2ZmOTA2ZjBhOWEzNzkzNjcyZDY0NTZiZDFhMjQyMWU2NjdkNmYwYjU5OTUyM2QyNSIsInRhZyI6IiJ9', 'murid', NULL, NULL, '2026-10-31 20:31:54', '2026-10-31 20:31:54');
INSERT INTO `users` VALUES (207, 'Zelaya Mulyani S.E.', 'zelaya-mulyani-se.50@siswa.staffwork.test', '2425050', NULL, '086256027758', NULL, 34, '2026-10-31 20:31:54', '$2y$12$934DaY8ttFFcTu5SyUaVe.BU9ng4CEB9vst.d2KEYBbGTA1VTOYLe', 'eyJpdiI6IkR5WWVlY0JQN1VoUzltdVNkMmFqQXc9PSIsInZhbHVlIjoiK0xuU3ltYVJyK0FQM3dZckF2SFVvQT09IiwibWFjIjoiOGEyZjQ1MzgwMjYzOGUzYWUwODNlYjdlN2I4MDc1MjJkNmI4MTJjMzY1ZjU3MGY5MDAzMTc3ZDFmNDI3ZDQ0MiIsInRhZyI6IiJ9', 'murid', NULL, NULL, '2026-10-31 20:31:54', '2026-10-31 20:31:54');

-- ----------------------------
-- Table structure for violations
-- ----------------------------
DROP TABLE IF EXISTS `violations`;
CREATE TABLE `violations`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` bigint UNSIGNED NOT NULL,
  `reported_by` bigint UNSIGNED NULL DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `room` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'bk',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `violations_student_id_foreign`(`student_id` ASC) USING BTREE,
  INDEX `violations_reported_by_foreign`(`reported_by` ASC) USING BTREE,
  CONSTRAINT `violations_reported_by_foreign` FOREIGN KEY (`reported_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `violations_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of violations
-- ----------------------------

-- ----------------------------
-- Table structure for wali_murids
-- ----------------------------
DROP TABLE IF EXISTS `wali_murids`;
CREATE TABLE `wali_murids`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `hubungan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `alamat` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `wali_murids_student_id_foreign`(`student_id` ASC) USING BTREE,
  CONSTRAINT `wali_murids_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 54 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of wali_murids
-- ----------------------------
INSERT INTO `wali_murids` VALUES (4, 158, 'Daliman Waskita', 'Ayah', '087900526847', 'wali.2425001@staffwork.test', 'Ki. Rajiman No. 53, Tidore Kepulauan 70326, NTT', '2026-10-31 20:31:39', '2026-10-31 20:31:39');
INSERT INTO `wali_murids` VALUES (5, 159, 'Icha Rahayu', 'Ibu', '089201532038', 'wali.2425002@staffwork.test', 'Gg. Zamrud No. 225, Tomohon 46869, Sulbar', '2026-10-31 20:31:39', '2026-10-31 20:31:39');
INSERT INTO `wali_murids` VALUES (6, 160, 'Mahfud Tedi Prasetyo', 'Ayah', '081663728853', 'wali.2425003@staffwork.test', 'Kpg. Bass No. 703, Padangsidempuan 75420, Sultra', '2026-10-31 20:31:40', '2026-10-31 20:31:40');
INSERT INTO `wali_murids` VALUES (7, 161, 'Yance Puspasari', 'Ibu', '087951386277', 'wali.2425004@staffwork.test', 'Dk. Gegerkalong Hilir No. 674, Pematangsiantar 60286, NTT', '2026-10-31 20:31:40', '2026-10-31 20:31:40');
INSERT INTO `wali_murids` VALUES (8, 162, 'Daru Gunarto S.I.Kom', 'Ayah', '080192422462', 'wali.2425005@staffwork.test', 'Psr. Bakit  No. 564, Palembang 12436, Sumbar', '2026-10-31 20:31:40', '2026-10-31 20:31:40');
INSERT INTO `wali_murids` VALUES (9, 163, 'Ilsa Safitri', 'Ibu', '083779807479', 'wali.2425006@staffwork.test', 'Jr. Mulyadi No. 419, Banjar 62284, Pabar', '2026-10-31 20:31:41', '2026-10-31 20:31:41');
INSERT INTO `wali_murids` VALUES (10, 164, 'Dasa Bahuwarna Rajata', 'Ayah', '085347354356', 'wali.2425007@staffwork.test', 'Ki. Katamso No. 103, Bau-Bau 31879, Kepri', '2026-10-31 20:31:41', '2026-10-31 20:31:41');
INSERT INTO `wali_murids` VALUES (11, 165, 'Hana Hassanah', 'Ibu', '084930384848', 'wali.2425008@staffwork.test', 'Ki. Ahmad Dahlan No. 821, Solok 33013, Kaltara', '2026-10-31 20:31:41', '2026-10-31 20:31:41');
INSERT INTO `wali_murids` VALUES (12, 166, 'Kairav Simbolon', 'Ayah', '088798451398', 'wali.2425009@staffwork.test', 'Dk. Dipatiukur No. 823, Ternate 92936, Sultra', '2026-10-31 20:31:42', '2026-10-31 20:31:42');
INSERT INTO `wali_murids` VALUES (13, 167, 'Unjani Kuswandari M.Pd', 'Ibu', '088797632332', 'wali.2425010@staffwork.test', 'Kpg. Hang No. 820, Kupang 83217, DKI', '2026-10-31 20:31:42', '2026-10-31 20:31:42');
INSERT INTO `wali_murids` VALUES (14, 168, 'Vega Saefullah', 'Ayah', '088679365454', 'wali.2425011@staffwork.test', 'Ds. Raden No. 595, Mojokerto 81137, Kepri', '2026-10-31 20:31:42', '2026-10-31 20:31:42');
INSERT INTO `wali_murids` VALUES (15, 169, 'Ella Yolanda', 'Ibu', '089729645066', 'wali.2425012@staffwork.test', 'Jln. Qrisdoren No. 738, Samarinda 18739, NTB', '2026-10-31 20:31:42', '2026-10-31 20:31:42');
INSERT INTO `wali_murids` VALUES (16, 170, 'Taswir Utama', 'Ayah', '089614669379', 'wali.2425013@staffwork.test', 'Psr. Bak Mandi No. 66, Kotamobagu 18462, Lampung', '2026-10-31 20:31:43', '2026-10-31 20:31:43');
INSERT INTO `wali_murids` VALUES (17, 171, 'Titin Hassanah', 'Ibu', '086313868550', 'wali.2425014@staffwork.test', 'Psr. Astana Anyar No. 356, Malang 21942, Banten', '2026-10-31 20:31:43', '2026-10-31 20:31:43');
INSERT INTO `wali_murids` VALUES (18, 172, 'Viman Gunawan S.IP', 'Ayah', '088813187709', 'wali.2425015@staffwork.test', 'Jr. Gegerkalong Hilir No. 646, Depok 18174, DKI', '2026-10-31 20:31:43', '2026-10-31 20:31:43');
INSERT INTO `wali_murids` VALUES (19, 173, 'Silvia Andriani', 'Ibu', '086125908134', 'wali.2425016@staffwork.test', 'Ds. Qrisdoren No. 360, Palangka Raya 19245, Lampung', '2026-10-31 20:31:44', '2026-10-31 20:31:44');
INSERT INTO `wali_murids` VALUES (20, 174, 'Banawa Gara Pranowo M.Farm', 'Ayah', '088244635285', 'wali.2425017@staffwork.test', 'Jr. Moch. Toha No. 381, Padangsidempuan 29516, Sumsel', '2026-10-31 20:31:44', '2026-10-31 20:31:44');
INSERT INTO `wali_murids` VALUES (21, 175, 'Shania Lidya Yuniar M.TI.', 'Ibu', '086307028440', 'wali.2425018@staffwork.test', 'Kpg. Abdul Muis No. 560, Probolinggo 85518, Jateng', '2026-10-31 20:31:44', '2026-10-31 20:31:44');
INSERT INTO `wali_murids` VALUES (22, 176, 'Rama Zulkarnain', 'Ayah', '082252988462', 'wali.2425019@staffwork.test', 'Jln. Teuku Umar No. 468, Binjai 32276, Bengkulu', '2026-10-31 20:31:45', '2026-10-31 20:31:45');
INSERT INTO `wali_murids` VALUES (23, 177, 'Umi Dinda Purwanti M.Kom.', 'Ibu', '086834810572', 'wali.2425020@staffwork.test', 'Jln. Jaksa No. 123, Payakumbuh 76523, Kalbar', '2026-10-31 20:31:45', '2026-10-31 20:31:45');
INSERT INTO `wali_murids` VALUES (24, 178, 'Narji Manah Kuswoyo S.E.I', 'Ayah', '082244886391', 'wali.2425021@staffwork.test', 'Psr. Adisumarmo No. 482, Magelang 94831, Sumut', '2026-10-31 20:31:45', '2026-10-31 20:31:45');
INSERT INTO `wali_murids` VALUES (25, 179, 'Umi Haryanti', 'Ibu', '084405531888', 'wali.2425022@staffwork.test', 'Psr. Taman No. 831, Tarakan 12298, Sultra', '2026-10-31 20:31:45', '2026-10-31 20:31:45');
INSERT INTO `wali_murids` VALUES (26, 180, 'Kadir Kadir Wibisono S.Kom', 'Ayah', '083734988765', 'wali.2425023@staffwork.test', 'Kpg. Ters. Kiaracondong No. 924, Gorontalo 65516, NTT', '2026-10-31 20:31:46', '2026-10-31 20:31:46');
INSERT INTO `wali_murids` VALUES (27, 181, 'Silvia Rachel Purnawati S.T.', 'Ibu', '082990126850', 'wali.2425024@staffwork.test', 'Kpg. Gatot Subroto No. 448, Mataram 31830, Kaltara', '2026-10-31 20:31:46', '2026-10-31 20:31:46');
INSERT INTO `wali_murids` VALUES (28, 182, 'Karsa Prasetya', 'Ayah', '089203463737', 'wali.2425025@staffwork.test', 'Psr. Dipatiukur No. 528, Kediri 72015, Pabar', '2026-10-31 20:31:46', '2026-10-31 20:31:46');
INSERT INTO `wali_murids` VALUES (29, 183, 'Ajeng Laksmiwati', 'Ibu', '081276299029', 'wali.2425026@staffwork.test', 'Dk. Abdullah No. 9, Lhokseumawe 30936, Kaltim', '2026-10-31 20:31:47', '2026-10-31 20:31:47');
INSERT INTO `wali_murids` VALUES (30, 184, 'Empluk Manullang', 'Ayah', '086636566162', 'wali.2425027@staffwork.test', 'Gg. Pahlawan No. 694, Samarinda 61686, NTT', '2026-10-31 20:31:47', '2026-10-31 20:31:47');
INSERT INTO `wali_murids` VALUES (31, 185, 'Alika Safina Pertiwi S.Gz', 'Ibu', '081442777634', 'wali.2425028@staffwork.test', 'Ds. Rajawali No. 678, Sawahlunto 62781, Sulsel', '2026-10-31 20:31:47', '2026-10-31 20:31:47');
INSERT INTO `wali_murids` VALUES (32, 186, 'Pranawa Gaiman Wasita', 'Ayah', '081794821926', 'wali.2425029@staffwork.test', 'Ds. Ketandan No. 288, Blitar 67733, Kalbar', '2026-10-31 20:31:48', '2026-10-31 20:31:48');
INSERT INTO `wali_murids` VALUES (33, 187, 'Cindy Usada S.T.', 'Ibu', '088316504695', 'wali.2425030@staffwork.test', 'Ki. Tangkuban Perahu No. 591, Kediri 21303, Jateng', '2026-10-31 20:31:48', '2026-10-31 20:31:48');
INSERT INTO `wali_murids` VALUES (34, 188, 'Jono Dabukke', 'Ayah', '082444478209', 'wali.2425031@staffwork.test', 'Jln. Bhayangkara No. 48, Padangsidempuan 51749, Riau', '2026-10-31 20:31:48', '2026-10-31 20:31:48');
INSERT INTO `wali_murids` VALUES (35, 189, 'Anita Shakila Yulianti S.I.Kom', 'Ibu', '084541242851', 'wali.2425032@staffwork.test', 'Ki. Bayam No. 419, Kediri 39033, Jabar', '2026-10-31 20:31:49', '2026-10-31 20:31:49');
INSERT INTO `wali_murids` VALUES (36, 190, 'Balijan Uwais', 'Ayah', '087974607528', 'wali.2425033@staffwork.test', 'Ds. Ketandan No. 849, Bekasi 16366, Papua', '2026-10-31 20:31:49', '2026-10-31 20:31:49');
INSERT INTO `wali_murids` VALUES (37, 191, 'Nadia Padmasari', 'Ibu', '084230077253', 'wali.2425034@staffwork.test', 'Jr. Bahagia No. 718, Tual 58235, Sulsel', '2026-10-31 20:31:49', '2026-10-31 20:31:49');
INSERT INTO `wali_murids` VALUES (38, 192, 'Damar Zulkarnain', 'Ayah', '083660826304', 'wali.2425035@staffwork.test', 'Dk. Soekarno Hatta No. 39, Banda Aceh 95843, Jatim', '2026-10-31 20:31:49', '2026-10-31 20:31:49');
INSERT INTO `wali_murids` VALUES (39, 193, 'Elisa Widiastuti S.Psi', 'Ibu', '086952583770', 'wali.2425036@staffwork.test', 'Jln. Industri No. 89, Kupang 16588, Kalbar', '2026-10-31 20:31:50', '2026-10-31 20:31:50');
INSERT INTO `wali_murids` VALUES (40, 194, 'Baktianto Asirwada Maheswara S.Farm', 'Ayah', '081292467427', 'wali.2425037@staffwork.test', 'Kpg. Urip Sumoharjo No. 134, Sorong 92599, Sumbar', '2026-10-31 20:31:50', '2026-10-31 20:31:50');
INSERT INTO `wali_murids` VALUES (41, 195, 'Intan Mutia Hariyah S.Sos', 'Ibu', '084904394644', 'wali.2425038@staffwork.test', 'Jln. Umalas No. 211, Padangsidempuan 33545, Lampung', '2026-10-31 20:31:50', '2026-10-31 20:31:50');
INSERT INTO `wali_murids` VALUES (42, 196, 'Mahfud Haryanto', 'Ayah', '080039959811', 'wali.2425039@staffwork.test', 'Gg. Jamika No. 142, Tasikmalaya 18641, Sulsel', '2026-10-31 20:31:51', '2026-10-31 20:31:51');
INSERT INTO `wali_murids` VALUES (43, 197, 'Paulin Laksita', 'Ibu', '088130236909', 'wali.2425040@staffwork.test', 'Ds. Yos Sudarso No. 268, Cimahi 18299, Sulut', '2026-10-31 20:31:51', '2026-10-31 20:31:51');
INSERT INTO `wali_murids` VALUES (44, 198, 'Harimurti Irsad Suwarno', 'Ayah', '081294004232', 'wali.2425041@staffwork.test', 'Gg. R.E. Martadinata No. 134, Kotamobagu 43624, Jatim', '2026-10-31 20:31:51', '2026-10-31 20:31:51');
INSERT INTO `wali_murids` VALUES (45, 199, 'Vanya Astuti', 'Ibu', '089076834709', 'wali.2425042@staffwork.test', 'Ds. Siliwangi No. 47, Parepare 34841, Lampung', '2026-10-31 20:31:52', '2026-10-31 20:31:52');
INSERT INTO `wali_murids` VALUES (46, 200, 'Surya Situmorang', 'Ayah', '080461423782', 'wali.2425043@staffwork.test', 'Kpg. Salatiga No. 218, Bandung 39396, Riau', '2026-10-31 20:31:52', '2026-10-31 20:31:52');
INSERT INTO `wali_murids` VALUES (47, 201, 'Ayu Hartati', 'Ibu', '086786967552', 'wali.2425044@staffwork.test', 'Jln. Villa No. 152, Samarinda 41790, Malut', '2026-10-31 20:31:52', '2026-10-31 20:31:52');
INSERT INTO `wali_murids` VALUES (48, 202, 'Aditya Harjasa Jailani', 'Ayah', '089670468363', 'wali.2425045@staffwork.test', 'Dk. Acordion No. 872, Batu 77133, Jambi', '2026-10-31 20:31:52', '2026-10-31 20:31:52');
INSERT INTO `wali_murids` VALUES (49, 203, 'Zulaikha Wulan Wastuti M.Pd', 'Ibu', '089244951299', 'wali.2425046@staffwork.test', 'Kpg. Laksamana No. 238, Manado 49563, Papua', '2026-10-31 20:31:53', '2026-10-31 20:31:53');
INSERT INTO `wali_murids` VALUES (50, 204, 'Endra Napitupulu S.T.', 'Ayah', '080404179888', 'wali.2425047@staffwork.test', 'Ds. Dewi Sartika No. 428, Tegal 72453, Malut', '2026-10-31 20:31:53', '2026-10-31 20:31:53');
INSERT INTO `wali_murids` VALUES (51, 205, 'Nadia Zulaika', 'Ibu', '082167263591', 'wali.2425048@staffwork.test', 'Ki. Baranang Siang Indah No. 937, Tual 49171, Bengkulu', '2026-10-31 20:31:53', '2026-10-31 20:31:53');
INSERT INTO `wali_murids` VALUES (52, 206, 'Lurhur Darmana Sihotang S.Psi', 'Ayah', '089471063055', 'wali.2425049@staffwork.test', 'Kpg. Setia Budi No. 781, Pagar Alam 41209, Maluku', '2026-10-31 20:31:54', '2026-10-31 20:31:54');
INSERT INTO `wali_murids` VALUES (53, 207, 'Alika Anggraini', 'Ibu', '080354422635', 'wali.2425050@staffwork.test', 'Kpg. Ters. Buah Batu No. 640, Madiun 93826, DKI', '2026-10-31 20:31:54', '2026-10-31 20:31:54');

SET FOREIGN_KEY_CHECKS = 1;
