@extends('layouts.app')

@section('title', 'Master Mata Pelajaran - StaffWork')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Master Mata Pelajaran</h1>
            <p class="text-muted small mb-0">Kelola daftar mapel yang dipakai untuk penugasan guru dan jadwal</p>
        </div>
        <div class="d-flex align-items-center">
            @include('pages.master._import-modal', ['importType' => 'mapel', 'importLabel' => 'Mapel'])
            <a href="{{ route('mapel.create') }}" class="btn btn-sm btn-primary shadow-sm font-weight-bold ml-1">
                <i class="fas fa-plus fa-sm text-white-50 mr-1"></i> Tambah Mapel
            </a>
        </div>
    </div>

    @include('pages.master._import-alerts')

    <!-- Data Table Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-book mr-1"></i> Daftar Mata Pelajaran
            </h6>
            <span class="badge badge-light border">Total: {{ $mapels->total() }} Mapel</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 60px;" class="text-center">No</th>
                            <th style="width: 130px;">Kode</th>
                            <th>Nama Mapel</th>
                            <th style="width: 140px;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($mapels as $index => $mapel)
                            <tr>
                                <td class="text-center align-middle font-weight-bold text-muted">
                                    {{ $mapels->firstItem() + $index }}
                                </td>
                                <td class="align-middle">
                                    <span class="badge badge-secondary px-2 py-1">{{ $mapel->code }}</span>
                                </td>
                                <td class="align-middle font-weight-bold text-gray-900">{{ $mapel->name }}</td>
                                <td class="text-center align-middle">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('mapel.edit', $mapel) }}" class="btn btn-outline-info" title="Edit Mapel">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('mapel.destroy', $mapel) }}" method="POST" class="d-inline"
                                            data-confirm='Hapus mapel {{ $mapel->name }}?'>
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Hapus Mapel">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">Belum ada mapel. Tambahkan mapel atau import dari template.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end mt-3">
                {{ $mapels->links() }}
            </div>
        </div>
    </div>
@endsection
