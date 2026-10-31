@extends('layouts.app')

@section('title', 'Laporkan Bug & Request Fitur - StaffWork')

@section('content')
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
        <div class="mr-3">
            <h1 class="h3 mb-1 text-gray-800">Laporan & Request Fitur</h1>
            <p class="text-muted small mb-0">
                Temukan bug atau punya ide fitur baru? Kirimkan langsung ke tim pengembang melalui WhatsApp.
            </p>
        </div>
        <a href="https://wa.me/6285817048266" target="_blank" rel="noopener"
            class="btn btn-sm btn-outline-success shadow-sm mt-2 mt-sm-0">
            <i class="fab fa-whatsapp mr-1"></i> Hubungi Langsung
        </a>
    </div>

    <div class="row">
        <div class="col-lg-7 mb-4">
            <div class="card shadow report-card" data-aos="fade-up">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-paper-plane mr-1"></i> Formulir Laporan
                    </h6>
                </div>
                <div class="card-body">
                    <form id="reportForm" novalidate>
                        <div class="form-group">
                            <label class="font-weight-bold text-gray-700">Jenis Laporan</label>
                            <label class="report-option is-selected">
                                <input type="radio" name="kategori" value="Bug" checked>
                                <span>
                                    <span class="report-option-title d-block">Laporan Bug</span>
                                    <span class="report-option-desc">Ada halaman, tombol, atau data yang tidak berjalan dengan benar.</span>
                                </span>
                            </label>
                            <label class="report-option">
                                <input type="radio" name="kategori" value="Request Fitur">
                                <span>
                                    <span class="report-option-title d-block">Request Fitur</span>
                                    <span class="report-option-desc">Usulan fitur atau penyempurnaan yang membuat aplikasi lebih mudah dipakai.</span>
                                </span>
                            </label>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="nama" class="font-weight-bold text-gray-700">Nama <span class="text-danger">*</span></label>
                                <input type="text" id="nama" name="nama" class="form-control"
                                    value="{{ Auth::user()->name }}" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="peran" class="font-weight-bold text-gray-700">Peran</label>
                                <input type="text" id="peran" name="peran" class="form-control"
                                    value="{{ Auth::user()->role_name }}" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="judul" class="font-weight-bold text-gray-700">Judul Laporan <span class="text-danger">*</span></label>
                            <input type="text" id="judul" name="judul" class="form-control"
                                placeholder="Contoh: Tombol simpan materi tidak merespons" required
                                maxlength="120">
                        </div>

                        <div class="form-group">
                            <label for="deskripsi" class="font-weight-bold text-gray-700">Deskripsi <span class="text-danger">*</span></label>
                            <textarea id="deskripsi" name="deskripsi" rows="6"
                                class="form-control @error('deskripsi') is-invalid @enderror"
                                placeholder="Ceritakan langkah yang dilakukan, apa yang diharapkan, dan apa yang terjadi."
                                required maxlength="2000"></textarea>
                            <small class="form-text text-muted">Semakin detail laporanmu, semakin cepat masalah terselesaikan.</small>
                        </div>

                        <div class="d-flex flex-wrap align-items-center justify-content-between">
                            <small class="text-muted mb-2 mb-sm-0">
                                <i class="fas fa-lock mr-1"></i> Laporan dikirim ke WhatsApp tim pengembang.
                            </small>
                            <button type="submit" class="btn btn-success font-weight-bold shadow-sm">
                                <i class="fab fa-whatsapp mr-1"></i> Kirim via WhatsApp
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-5 mb-4">
            <div class="card shadow mb-4" data-aos="fade-up" data-aos-delay="80">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle mr-1"></i> Panduan Melapor
                    </h6>
                </div>
                <div class="card-body">
                    <ol class="pl-3 mb-0 small text-gray-700">
                        <li class="mb-2">Pilih jenis laporan: bug atau request fitur.</li>
                        <li class="mb-2">Tulis judul singkat yang menggambarkan masalah.</li>
                        <li class="mb-2">Jelaskan langkah reproduksi bug atau kebutuhan fitur.</li>
                        <li class="mb-2">Tekan <strong>Kirim via WhatsApp</strong> lalu kirim pesan yang otomatis terisi.</li>
                        <li>Tim pengembang akan membalas melalui nomor yang sama.</li>
                    </ol>
                </div>
            </div>

            <div class="card border-left-primary shadow">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="icon-circle bg-primary mr-3">
                            <i class="fab fa-whatsapp text-white"></i>
                        </div>
                        <div>
                            <div class="small text-muted">Nomor tujuan laporan</div>
                            <div class="font-weight-bold text-gray-900">+62 858-1704-8266</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var form = document.getElementById('reportForm');

        form.querySelectorAll('input[name="kategori"]').forEach(function (radio) {
            radio.addEventListener('change', function () {
                form.querySelectorAll('.report-option').forEach(function (option) {
                    option.classList.toggle('is-selected', option.contains(radio) && radio.checked);
                });
            });
        });

        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
                form.classList.add('was-validated');
                return;
            }

            event.preventDefault();

            var kategori = form.querySelector('input[name="kategori"]:checked').value;
            var nama = document.getElementById('nama').value.trim();
            var peran = document.getElementById('peran').value.trim();
            var judul = document.getElementById('judul').value.trim();
            var deskripsi = document.getElementById('deskripsi').value.trim();

            var pesan = '*[' + kategori + '] StaffWork LMS*\n\n'
                + '*Judul:* ' + judul + '\n'
                + '*Nama:* ' + nama + '\n'
                + '*Peran:* ' + peran + '\n'
                + '*Waktu:* ' + new Date().toLocaleString('id-ID') + '\n\n'
                + '*Deskripsi:*\n' + deskripsi;

            window.open('https://wa.me/6285817048266?text=' + encodeURIComponent(pesan), '_blank');
        });
    });
</script>
@endpush
