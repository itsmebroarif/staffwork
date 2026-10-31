@extends('layouts.app')

@section('title', 'Master Wali Murid - StaffWork')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Master Wali Murid</h1>
            <p class="text-muted small mb-0">Kelola data wali/orang tua dari setiap siswa</p>
        </div>
        <div class="d-flex align-items-center">
            @include('pages.master._import-modal', ['importType' => 'wali', 'importLabel' => 'Wali Murid'])
            <a href="{{ route('wali.create') }}" class="btn btn-sm btn-primary shadow-sm font-weight-bold ml-1">
                <i class="fas fa-user-friends fa-sm text-white-50 mr-1"></i> Tambah Wali
            </a>
        </div>
    </div>

    @include('pages.master._import-alerts')

    <!-- Data Table Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-users mr-1"></i> Daftar Wali Murid
            </h6>
            <span class="badge badge-light border">Total: {{ $guardians->total() }} Wali</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 60px;" class="text-center">No</th>
                            <th>Nama Wali</th>
                            <th style="width: 110px;">Hubungan</th>
                            <th>Siswa</th>
                            <th style="width: 140px;">No HP</th>
                            <th style="width: 140px;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($guardians as $index => $guardian)
                            <tr>
                                <td class="text-center align-middle font-weight-bold text-muted">
                                    {{ $guardians->firstItem() + $index }}
                                </td>
                                <td class="align-middle font-weight-bold text-gray-900">{{ $guardian->name }}</td>
                                <td class="align-middle">
                                    <span class="badge badge-warning px-2 py-1">{{ $guardian->hubungan }}</span>
                                </td>
                                <td class="align-middle">
                                    {{ $guardian->student?->name ?? '-' }}
                                    @if ($guardian->student?->kelas)
                                        <span class="badge badge-info ml-1">{{ $guardian->student->kelas->name }}</span>
                                    @endif
                                </td>
                                <td class="align-middle text-muted">{{ $guardian->phone }}</td>
                                <td class="text-center align-middle">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('wali.edit', $guardian) }}" class="btn btn-outline-info" title="Edit Wali">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('wali.destroy', $guardian) }}" method="POST" class="d-inline"
                                            data-confirm='Hapus data wali {{ $guardian->name }}?'>
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Hapus Wali">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Belum ada wali murid. Tambahkan atau import dari template.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end mt-3">
                {{ $guardians->links() }}
            </div>
        </div>
    </div>
@endsection
