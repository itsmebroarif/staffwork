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

    <div class="card shadow mb-4">
        <div class="card-body py-3">
            <form action="{{ route('penugasan.index') }}" method="GET" class="form-row align-items-end">
                <div class="form-group col-lg-5 col-md-6 mb-2 mb-md-0">
                    <label for="q" class="small font-weight-bold text-gray-700 mb-1">Cari Guru</label>
                    <div class="input-group input-group-sm">
                        <input type="text" name="q" id="q" class="form-control bg-light"
                            placeholder="Nama guru..." value="{{ request('q') }}">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit"><i class="fas fa-search fa-sm"></i></button>
                        </div>
                    </div>
                </div>
                <div class="form-group col-lg-4 col-md-6 mb-2 mb-md-0">
                    <label for="kelas" class="small font-weight-bold text-gray-700 mb-1">Kelas</label>
                    <select name="kelas" id="kelas" class="form-control form-control-sm bg-light">
                        <option value="">Semua Kelas</option>
                        @foreach ($kelasList as $kelas)
                            <option value="{{ $kelas->id }}" {{ (string) request('kelas') === (string) $kelas->id ? 'selected' : '' }}>
                                {{ $kelas->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-lg-3 col-md-6 mb-0">
                    <button type="submit" class="btn btn-primary btn-sm btn-block">
                        <i class="fas fa-filter fa-sm mr-1"></i> Terapkan Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

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
                        @php
                            $groupedAssignments = $assignments->getCollection();
                            $groupCounts = $groupedAssignments->countBy(fn ($item) => $item->teacher_id.'-'.$item->mapel_id);
                            $renderedGroups = [];
                        @endphp
                        @forelse ($groupedAssignments as $index => $assignment)
                            @php $groupKey = $assignment->teacher_id.'-'.$assignment->mapel_id; @endphp
                            <tr>
                                <td class="text-center align-middle font-weight-bold text-muted">
                                    {{ $assignments->firstItem() + $index }}
                                </td>
                                @if (! isset($renderedGroups[$groupKey]))
                                    @php $renderedGroups[$groupKey] = true; @endphp
                                    <td class="align-middle font-weight-bold text-gray-900" rowspan="{{ $groupCounts[$groupKey] }}">
                                        {{ $assignment->teacher?->name ?? '-' }}
                                        @if ($assignment->teacher?->nip)
                                            <div><span class="badge badge-secondary mt-1">{{ $assignment->teacher->nip }}</span></div>
                                        @endif
                                    </td>
                                    <td class="align-middle" rowspan="{{ $groupCounts[$groupKey] }}">
                                        {{ $assignment->mapel?->name ?? '-' }}
                                        <div><span class="badge badge-light border mt-1">{{ $assignment->mapel?->code }}</span></div>
                                    </td>
                                @endif
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
                                                data-confirm='Hapus penugasan ini? Jadwal terkait ikut terhapus.'>
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
