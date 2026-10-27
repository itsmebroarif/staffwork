@extends('layouts.app')

@section('title', 'Registrasi Akun Baru - Super Admin StaffWork')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Registrasi Akun Baru</h1>
            <p class="text-muted small mb-0">Daftarkan akun staf atau admin baru dengan role yang ditentukan</p>
        </div>
        <a href="{{ route('users.index') }}" class="btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50 mr-1"></i> Kembali ke Daftar Akun
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user-plus mr-1"></i> Formulir Pembuatan Akun
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label for="name" class="font-weight-bold text-gray-700">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text"
                                name="name"
                                id="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}"
                                placeholder="Contoh: Siti Rahma"
                                required
                                autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email" class="font-weight-bold text-gray-700">Alamat Email <span class="text-danger">*</span></label>
                            <input type="email"
                                name="email"
                                id="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}"
                                placeholder="nama@perusahaan.com"
                                required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="role" class="font-weight-bold text-gray-700">Role / Hak Akses Akun <span class="text-danger">*</span></label>
                            <select name="role" id="role" class="form-control @error('role') is-invalid @enderror" required>
                                <option value="" disabled {{ old('role') ? '' : 'selected' }}>-- Pilih Role Pengguna --</option>
                                <option value="superadmin" {{ old('role') === 'superadmin' ? 'selected' : '' }}>
                                    Super Admin (Akses Penuh Seluruh Sistem & Akun)
                                </option>
                                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>
                                    Admin (Akses Manajemen Data)
                                </option>
                                <option value="staff" {{ old('role', 'staff') === 'staff' ? 'selected' : '' }}>
                                    Staff (Anggota Karyawan Reguler)
                                </option>
                            </select>
                            <small class="form-text text-muted">Sesuaikan hak akses sesuai peran dan tanggung jawab pengguna.</small>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="password" class="font-weight-bold text-gray-700">Kata Sandi <span class="text-danger">*</span></label>
                                <input type="password"
                                    name="password"
                                    id="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Minimal 6 karakter"
                                    required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="password_confirmation" class="font-weight-bold text-gray-700">Konfirmasi Kata Sandi <span class="text-danger">*</span></label>
                                <input type="password"
                                    name="password_confirmation"
                                    id="password_confirmation"
                                    class="form-control"
                                    placeholder="Ulangi kata sandi"
                                    required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="avatar" class="font-weight-bold text-gray-700">Foto Profil (Opsional)</label>
                            <div class="custom-file">
                                <input type="file"
                                    name="avatar"
                                    class="custom-file-input @error('avatar') is-invalid @enderror"
                                    id="avatar"
                                    accept="image/*">
                                <label class="custom-file-label" for="avatar">Pilih file foto...</label>
                            </div>
                            @error('avatar')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('users.index') }}" class="btn btn-secondary mr-2">Batal</a>
                            <button type="submit" class="btn btn-primary font-weight-bold shadow-sm">
                                <i class="fas fa-user-plus mr-1"></i> Daftarkan Akun
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $('#avatar').on('change', function(e) {
        var fileName = e.target.files[0] ? e.target.files[0].name : "Pilih file foto...";
        $(this).next('.custom-file-label').html(fileName);
    });
</script>
@endpush

