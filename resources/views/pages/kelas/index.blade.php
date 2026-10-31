@extends('layouts.app')

@section('title', 'Master Kelas - StaffWork')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Master Kelas</h1>
            <p class="text-muted small mb-0">Kelola daftar kelas/tingkat yang dipakai untuk siswa, penugasan, dan materi</p>
        </div>
        <div class="d-flex align-items-center">
            @include('pages.master._import-modal', ['importType' => 'kelas', 'importLabel' => 'Kelas'])
            <a href="{{ route('kelas.create') }}" class="btn btn-sm btn-primary shadow-sm font-weight-bold ml-1">
                <i class="fas fa-plus fa-sm text-white-50 mr-1"></i> Tambah Kelas
            </a>
        </div>
    </div>

    @include('pages.master._import-alerts')

    <!-- Data Table Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-door-open mr-1"></i> Daftar Kelas
            </h6>
            <span class="badge badge-light border">Total: {{ $kelas->total() }} Kelas</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 60px;" class="text-center">No</th>
                            <th>Nama Kelas</th>
                            <th style="width: 110px;" class="text-center">Tingkat</th>
                            <th style="width: 130px;" class="text-center">Jumlah Siswa</th>
                            <th style="width: 140px;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kelas as $index => $k)
                            <tr>
                                <td class="text-center align-middle font-weight-bold text-muted">
                                    {{ $kelas->firstItem() + $index }}
                                </td>
                                <td class="align-middle font-weight-bold text-gray-900">{{ $k->name }}</td>
                                <td class="text-center align-middle">
                                    <span class="badge badge-primary px-2 py-1">Tingkat {{ $k->tingkat }}</span>
                                </td>
                                <td class="text-center align-middle">{{ $k->students_count }} Siswa</td>
                                <td class="text-center align-middle">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('kelas.edit', $k) }}" class="btn btn-outline-info" title="Edit Kelas">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('kelas.destroy', $k) }}" method="POST" class="d-inline"
                                            data-confirm='Hapus kelas {{ $k->name }}? Siswa pada kelas ini akan kehilangan kelas.'>
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Hapus Kelas">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Belum ada kelas. Tambahkan kelas atau import dari template.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end mt-3">
                {{ $kelas->links() }}
            </div>
        </div>
    </div>
@endsection
