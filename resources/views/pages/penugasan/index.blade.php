@extends('layouts.app')

@section('title', 'Kelas Diajar - StaffWork')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Kelas Diajar (Penugasan Guru)</h1>
            <p class="text-muted small mb-0">Kelola penugasan guru mengampu mapel pada kelas tertentu</p>
        </div>
        <div class="d-flex align-items-center">
            @if (Auth::user()->isSuperAdmin())
                @include('pages.master._import-modal', ['importType' => 'penugasan', 'importLabel' => 'Kelas Diajar'])
                <a href="{{ route('penugasan.create') }}" class="btn btn-sm btn-primary shadow-sm font-weight-bold ml-1">
                    <i class="fas fa-plus fa-sm text-white-50 mr-1"></i> Tambah Penugasan
                </a>
            @endif
        </div>
    </div>

    @include('pages.master._import-alerts')

    <!-- Data Table Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-chalkboard mr-1"></i> Daftar Penugasan
            </h6>
            <span class="badge badge-light border">Total: {{ $assignments->total() }} Penugasan</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 60px;" class="text-center">No</th>
                            <th>Guru</th>
                            <th>Mata Pelajaran</th>
                            <th>Kelas</th>
                            @if (Auth::user()->isSuperAdmin())
                                <th style="width: 140px;" class="text-center">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($assignments as $index => $assignment)
                            <tr>
                                <td class="text-center align-middle font-weight-bold text-muted">
                                    {{ $assignments->firstItem() + $index }}
                                </td>
                                <td class="align-middle font-weight-bold text-gray-900">
                                    {{ $assignment->teacher?->name ?? '-' }}
                                    @if ($assignment->teacher?->nip)
                                        <span class="badge badge-secondary ml-1">{{ $assignment->teacher->nip }}</span>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    {{ $assignment->mapel?->name ?? '-' }}
                                    <span class="badge badge-light border ml-1">{{ $assignment->mapel?->code }}</span>
                                </td>
                                <td class="align-middle">
                                    <span class="badge badge-info px-2 py-1">{{ $assignment->kelas?->name ?? '-' }}</span>
                                </td>
                                @if (Auth::user()->isSuperAdmin())
                                    <td class="text-center align-middle">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('penugasan.edit', $assignment) }}" class="btn btn-outline-info" title="Edit Penugasan">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('penugasan.destroy', $assignment) }}" method="POST" class="d-inline"
                                                onsubmit="return confirm('Hapus penugasan ini? Jadwal terkait ikut terhapus.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="Hapus Penugasan">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ Auth::user()->isSuperAdmin() ? 5 : 4 }}" class="text-center text-muted py-4">Belum ada penugasan. Tambahkan atau import dari template.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end mt-3">
                {{ $assignments->links() }}
            </div>
        </div>
    </div>
@endsection
