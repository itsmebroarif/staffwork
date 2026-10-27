@extends('layouts.app')

@section('title', 'Edit Akun Pengguna - Super Admin StaffWork')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Edit Akun: {{ $user->name }}</h1>
            <p class="text-muted small mb-0">Ubah profil, email, peran (role), dan kata sandi akun pengguna</p>
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
                        <i class="fas fa-user-edit mr-1"></i> Formulir Perubahan Data Akun
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                            class="rounded-circle border p-1 shadow-sm"
                            style="width: 90px; height: 90px; object-fit: cover;">
                        <h6 class="font-weight-bold mt-2 mb-0">{{ $user->name }}</h6>
                        <span class="badge {{ $user->role_badge_class }}">{{ $user->role_name }}</span>
                    </div>

                    <form action="{{ route('users.update', $user) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="name" class="font-weight-bold text-gray-700">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text"
                                name="name"
                                id="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $user->name) }}"
                                required>
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
                                value="{{ old('email', $user->email) }}"
                                required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="role" class="font-weight-bold text-gray-700">Role / Hak Akses Akun <span class="text-danger">*</span></label>
                            <select name="role" id="role" class="form-control @error('role') is-invalid @enderror" required>
                                <option value="superadmin" {{ old('role', $user->role) === 'superadmin' ? 'selected' : '' }}>
                                    Super Admin (Akses Penuh Seluruh Sistem & Akun)
                                </option>
                                <option value="guru" {{ old('role', $user->role) === 'guru' ? 'selected' : '' }}>
                                    Guru (Dapat Upload Materi, Tugas & Ujian)
                                </option>
                                <option value="murid" {{ old('role', $user->role) === 'murid' ? 'selected' : '' }}>
                                    Murid (Akses Belajar & Mengerjakan Tugas)
                                </option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="avatar" class="font-weight-bold text-gray-700">Ganti Foto Profil (Opsional)</label>
                            <div class="custom-file">
                                <input type="file"
                                    name="avatar"
                                    class="custom-file-input @error('avatar') is-invalid @enderror"
                                    id="avatar"
                                    accept="image/*">
                                <label class="custom-file-label" for="avatar">Pilih foto baru...</label>
                            </div>
                            <small class="form-text text-muted">Biarkan kosong jika tidak ingin mengubah foto profil pengguna.</small>
                            @error('avatar')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="password" class="font-weight-bold text-gray-700">Kata Sandi Baru (Opsional)</label>
                                <input type="password"
                                    name="password"
                                    id="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Kosongkan jika tidak diubah">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="password_confirmation" class="font-weight-bold text-gray-700">Konfirmasi Kata Sandi Baru</label>
                                <input type="password"
                                    name="password_confirmation"
                                    id="password_confirmation"
                                    class="form-control"
                                    placeholder="Ulangi jika mengubah kata sandi">
                            </div>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('users.index') }}" class="btn btn-secondary mr-2">Batal</a>
                            <button type="submit" class="btn btn-primary font-weight-bold shadow-sm">
                                <i class="fas fa-save mr-1"></i> Simpan Perubahan
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
        var fileName = e.target.files[0] ? e.target.files[0].name : "Pilih foto baru...";
        $(this).next('.custom-file-label').html(fileName);
    });
</script>
@endpush

