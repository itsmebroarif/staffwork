@extends('layouts.app')

@section('title', 'Profil & Pengaturan Akun - StaffWork')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Profil & Pengaturan Akun</h1>
            <p class="text-muted small mb-0">Kelola data pribadi, foto profil, dan kata sandi akun Anda</p>
        </div>
        <a href="{{ route('dashboard') }}" class="btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50 mr-1"></i> Kembali ke Dashboard
        </a>
    </div>

    <div class="row">

        <!-- User Summary & Avatar Card -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-id-card mr-1"></i> Kartu Profil
                    </h6>
                </div>
                <div class="card-body text-center">
                    <div class="position-relative d-inline-block mb-3">
                        <img id="avatar-preview-display"
                            src="{{ $user->avatar_url }}"
                            alt="{{ $user->name }}"
                            class="rounded-circle border p-1 shadow-sm"
                            style="width: 140px; height: 140px; object-fit: cover;">
                    </div>
                    <h5 class="font-weight-bold text-gray-900 mb-1">{{ $user->name }}</h5>
                    <p class="text-muted small mb-2">{{ $user->email }}</p>
                    <div class="mb-3">
                        <span class="badge {{ $user->role_badge_class }} px-3 py-1 font-weight-normal" style="font-size: 0.85rem;">
                            <i class="fas fa-shield-alt mr-1"></i> {{ $user->role_name }}
                        </span>
                    </div>

                    <table class="table table-bordered table-sm text-left small mb-3">
                        <tbody>
                            <tr>
                                <th class="bg-light w-50">Status Akun</th>
                                <td class="text-success font-weight-bold"><i class="fas fa-check-circle mr-1"></i>Aktif</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Terdaftar Pada</th>
                                <td>{{ $user->created_at ? $user->created_at->format('d M Y, H:i') : '-' }}</td>
                            </tr>
                        </tbody>
                    </table>

                    @if ($user->avatar)
                        <form action="{{ route('profile.avatar.destroy') }}" method="POST" class="mt-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus foto profil dan kembali ke bawaan?')">
                                <i class="fas fa-trash-alt mr-1"></i> Hapus Foto Profil
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Edit Profile & Avatar Form -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user-edit mr-1"></i> Perbarui Profil & Foto
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="name" class="font-weight-bold text-gray-700">Nama Lengkap</label>
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
                            <label for="email" class="font-weight-bold text-gray-700">Alamat Email</label>
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
                            <label for="avatar" class="font-weight-bold text-gray-700">Unggah Foto Profil Baru</label>
                            <div class="custom-file">
                                <input type="file"
                                    name="avatar"
                                    class="custom-file-input @error('avatar') is-invalid @enderror"
                                    id="avatar"
                                    accept="image/*">
                                <label class="custom-file-label" for="avatar">Pilih file foto (JPG, PNG, WEBP, maks 2MB)...</label>
                            </div>
                            <small class="form-text text-muted">Format yang didukung: JPG, JPEG, PNG, WEBP, SVG. Ukuran maksimal 2MB.</small>
                            @error('avatar')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary font-weight-bold shadow-sm">
                            <i class="fas fa-save mr-1"></i> Simpan Perubahan Profil
                        </button>
                    </form>
                </div>
            </div>

            <!-- Edit Password Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-key mr-1"></i> Ubah Kata Sandi (Password)
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="current_password" class="font-weight-bold text-gray-700">Kata Sandi Saat Ini</label>
                            <input type="password"
                                name="current_password"
                                id="current_password"
                                class="form-control @error('current_password') is-invalid @enderror"
                                placeholder="Masukkan kata sandi lama Anda"
                                required>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="new_password" class="font-weight-bold text-gray-700">Kata Sandi Baru</label>
                                <input type="password"
                                    name="password"
                                    id="new_password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Minimal 6 karakter"
                                    required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="password_confirmation" class="font-weight-bold text-gray-700">Ulangi Kata Sandi Baru</label>
                                <input type="password"
                                    name="password_confirmation"
                                    id="password_confirmation"
                                    class="form-control"
                                    placeholder="Konfirmasi kata sandi baru"
                                    required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-warning font-weight-bold shadow-sm text-dark">
                            <i class="fas fa-lock mr-1"></i> Perbarui Kata Sandi
                        </button>
                    </form>
                </div>
            </div>

        </div>

    </div>
@endsection

@push('scripts')
<script>
    // Preview avatar when file selected
    $('#avatar').on('change', function(e) {
        var fileName = e.target.files[0] ? e.target.files[0].name : "Pilih file foto...";
        $(this).next('.custom-file-label').html(fileName);

        if (e.target.files && e.target.files[0]) {
            var reader = new FileReader();
            reader.onload = function(event) {
                $('#avatar-preview-display').attr('src', event.target.result);
            }
            reader.readAsDataURL(e.target.files[0]);
        }
    });
</script>
@endpush

