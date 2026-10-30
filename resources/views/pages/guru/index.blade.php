@extends('layouts.app')

@section('title', 'Master Guru - StaffWork')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Master Guru</h1>
            <p class="text-muted small mb-0">Kelola akun guru beserta NIP dan kontaknya</p>
        </div>
        <div class="d-flex align-items-center">
            @include('pages.master._import-modal', ['importType' => 'guru', 'importLabel' => 'Guru'])
            <a href="{{ route('guru.create') }}" class="btn btn-sm btn-primary shadow-sm font-weight-bold ml-1">
                <i class="fas fa-user-tie fa-sm text-white-50 mr-1"></i> Tambah Guru
            </a>
        </div>
    </div>

    @include('pages.master._import-alerts')

    <!-- Filter -->
    <form action="{{ route('guru.index') }}" method="GET" class="mb-3">
        <div class="input-group input-group-sm" style="max-width: 400px;">
            <input type="text" name="q" class="form-control bg-white border-left-0"
                placeholder="Cari nama atau NIP..." value="{{ request('q') }}">
            <div class="input-group-append">
                <button class="btn btn-outline-primary border-left-0" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </form>

    <!-- Data Table Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-chalkboard-teacher mr-1"></i> Daftar Guru
            </h6>
            <span class="badge badge-light border">Total: {{ $teachers->total() }} Guru</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 60px;" class="text-center">No</th>
                            <th style="width: 120px;">NIP</th>
                            <th>Nama Lengkap</th>
                            <th>Email</th>
                            <th style="width: 140px;">No HP</th>
                            <th style="width: 140px;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($teachers as $index => $guru)
                            <tr>
                                <td class="text-center align-middle font-weight-bold text-muted">
                                    {{ $teachers->firstItem() + $index }}
                                </td>
                                <td class="align-middle">
                                    <span class="badge badge-secondary px-2 py-1">{{ $guru->nip ?: '-' }}</span>
                                </td>
                                <td class="align-middle font-weight-bold text-gray-900">{{ $guru->name }}</td>
                                <td class="align-middle text-muted">{{ $guru->email }}</td>
                                <td class="align-middle text-muted">{{ $guru->phone ?: '-' }}</td>
                                <td class="text-center align-middle">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('guru.edit', $guru) }}" class="btn btn-outline-info" title="Edit Guru">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('guru.destroy', $guru) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Hapus akun guru {{ $guru->name }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Hapus Guru">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Belum ada guru. Tambahkan guru atau import dari template.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end mt-3">
                {{ $teachers->links() }}
            </div>
        </div>
    </div>
@endsection
