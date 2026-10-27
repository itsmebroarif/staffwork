@extends('layouts.app')

@section('title', 'Dashboard - StaffWork')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Dashboard</h1>
            <p class="text-muted small mb-0">
                Selamat datang kembali, <strong>{{ $user->name }}</strong>! Anda masuk sebagai
                <span class="badge {{ $user->role_badge_class }}">{{ $user->role_name }}</span>
            </p>
        </div>
        <div class="mt-2 mt-sm-0">
            @if ($user->isSuperAdmin())
                <a href="{{ route('users.create') }}" class="btn btn-sm btn-primary shadow-sm mr-1 font-weight-bold">
                    <i class="fas fa-user-plus fa-sm text-white-50 mr-1"></i> Registrasi Akun Baru
                </a>
            @endif
            <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-outline-primary shadow-sm">
                <i class="fas fa-user-edit fa-sm mr-1"></i> Edit Profil & Foto
            </a>
        </div>
    </div>

    <!-- Content Row (Summary Cards) -->
    <div class="row">

        <!-- Total Users Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Seluruh Akun</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalUsers }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Super Admin Count Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Akun Super Admin</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalSuperAdmins }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-shield fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Role / Hak Akses Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Role Akses Anda</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <span class="badge {{ $user->role_badge_class }}">{{ $user->role_name }}</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-id-badge fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Staff Count Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Staff Reguler</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalStaff }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-friends fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Content Row -->
    <div class="row">

        <!-- Welcome Profile Card -->
        <div class="col-lg-5 mb-4">
            <div class="card shadow mb-4 h-100">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user-circle mr-1"></i> Informasi Profil Akun
                    </h6>
                    <a href="{{ route('profile.edit') }}" class="small font-weight-bold">Edit Profil &rarr;</a>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <img class="img-profile rounded-circle mb-2 border p-1 shadow-sm"
                            src="{{ $user->avatar_url }}"
                            alt="{{ $user->name }}" style="width: 110px; height: 110px; object-fit: cover;">
                        <h5 class="font-weight-bold text-gray-900 mb-0">{{ $user->name }}</h5>
                        <p class="text-muted small mb-2">{{ $user->email }}</p>
                        <span class="badge {{ $user->role_badge_class }} px-2 py-1 font-weight-normal">
                            {{ $user->role_name }}
                        </span>
                    </div>

                    <table class="table table-bordered table-sm mb-3">
                        <tbody>
                            <tr>
                                <th class="bg-light w-50">Nama Lengkap</th>
                                <td>{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Alamat Email</th>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Hak Akses (Role)</th>
                                <td><span class="badge {{ $user->role_badge_class }}">{{ $user->role_name }}</span></td>
                            </tr>
                            <tr>
                                <th class="bg-light">Terdaftar Pada</th>
                                <td>{{ $user->created_at ? $user->created_at->format('d F Y, H:i') : '-' }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-center">
                        <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary btn-sm mr-2">
                            <i class="fas fa-camera mr-1"></i> Ganti Foto Profil
                        </a>
                        <a href="{{ route('profile.edit') }}" class="btn btn-outline-warning btn-sm text-dark">
                            <i class="fas fa-key mr-1"></i> Ubah Password
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Registered Accounts / System Info Card -->
        <div class="col-lg-7 mb-4">
            <div class="card shadow mb-4 h-100">
                <div class="card-header py-3 d-flex align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-users mr-1"></i> Akun Pengguna Terbaru
                    </h6>
                    @if ($user->isSuperAdmin())
                        <a href="{{ route('users.index') }}" class="small font-weight-bold">Lihat Semua Akun &rarr;</a>
                    @endif
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width: 50px;">Foto</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th style="width: 100px;">Terdaftar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentUsers as $ru)
                                    <tr>
                                        <td>
                                            <img src="{{ $ru->avatar_url }}" alt="{{ $ru->name }}"
                                                class="rounded-circle border"
                                                style="width: 34px; height: 34px; object-fit: cover;">
                                        </td>
                                        <td class="align-middle font-weight-bold text-gray-900 small">
                                            {{ $ru->name }}
                                            @if ($ru->id === $user->id)
                                                <span class="badge badge-secondary ml-1">Saya</span>
                                            @endif
                                        </td>
                                        <td class="align-middle text-muted small">{{ $ru->email }}</td>
                                        <td class="align-middle">
                                            <span class="badge {{ $ru->role_badge_class }}">
                                                {{ $ru->role_name }}
                                            </span>
                                        </td>
                                        <td class="align-middle small text-muted">
                                            {{ $ru->created_at ? $ru->created_at->format('d M') : '-' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-3">Belum ada akun lain.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($user->isSuperAdmin())
                    <div class="card-footer bg-light d-flex justify-content-between align-items-center">
                        <span class="small text-muted">Super Admin dapat mendaftarkan banyak akun dengan role berbeda.</span>
                        <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm font-weight-bold">
                            <i class="fas fa-user-plus mr-1"></i> Tambah Akun Baru
                        </a>
                    </div>
                @endif
            </div>
        </div>

    </div>
@endsection
