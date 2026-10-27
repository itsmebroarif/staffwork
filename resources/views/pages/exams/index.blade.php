@extends('layouts.app')

@section('title', 'Tugas & Ujian - StaffWork LMS')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Tugas & Ujian</h1>
            <p class="text-muted small mb-0">Soal pilihan ganda — dapat diunggah oleh Super Admin & Guru</p>
        </div>
        @if (Auth::user()->isTeacher())
            <a href="{{ route('exams.create') }}" class="btn btn-sm btn-primary shadow-sm font-weight-bold">
                <i class="fas fa-plus fa-sm text-white-50 mr-1"></i> Buat Tugas/Ujian Baru
            </a>
        @endif
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-body py-2">
            <form action="{{ route('exams.index') }}" method="GET" class="form-inline">
                <div class="input-group mr-2 mb-2 mb-sm-0">
                    <input type="text" name="q" class="form-control bg-light border-0 small"
                        placeholder="Cari judul..." value="{{ request('q') }}">
                </div>
                <div class="input-group mr-2 mb-2 mb-sm-0">
                    <select name="type" class="form-control bg-light border-0 small">
                        <option value="">Semua Jenis</option>
                        <option value="tugas" {{ request('type') === 'tugas' ? 'selected' : '' }}>Tugas</option>
                        <option value="ujian" {{ request('type') === 'ujian' ? 'selected' : '' }}>Ujian</option>
                    </select>
                </div>
                <button class="btn btn-primary btn-sm mb-2 mb-sm-0" type="submit">
                    <i class="fas fa-filter fa-sm"></i> Filter
                </button>
            </form>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-clipboard-list mr-1"></i> Daftar Tugas & Ujian
            </h6>
            <span class="badge badge-light border">Total: {{ $exams->total() }}</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 60px;" class="text-center">No</th>
                            <th>Judul</th>
                            <th style="width: 90px;" class="text-center">Jenis</th>
                            <th style="width: 90px;" class="text-center">Jumlah Soal</th>
                            <th style="width: 110px;" class="text-center">Durasi</th>
                            <th style="width: 120px;">Pembuat</th>
                            <th style="width: 140px;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($exams as $index => $exam)
                            <tr>
                                <td class="text-center align-middle font-weight-bold text-muted">
                                    {{ $exams->firstItem() + $index }}
                                </td>
                                <td class="align-middle font-weight-bold text-gray-900">
                                    <a href="{{ route('exams.show', $exam) }}">{{ $exam->title }}</a>
                                    @if ($exam->description)
                                        <br><small class="text-muted font-weight-normal">{{ \Str::limit($exam->description, 60) }}</small>
                                    @endif
                                </td>
                                <td class="text-center align-middle">
                                    <span class="badge {{ $exam->type_badge_class }}">{{ $exam->type_label }}</span>
                                </td>
                                <td class="text-center align-middle">
                                    <span class="badge badge-light border">{{ $exam->questions_count }} soal</span>
                                </td>
                                <td class="text-center align-middle small">
                                    {{ $exam->duration_minutes ? $exam->duration_minutes . ' menit' : '-' }}
                                </td>
                                <td class="align-middle small text-muted">{{ $exam->author->name ?? '-' }}</td>
                                <td class="text-center align-middle">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('exams.show', $exam) }}" class="btn btn-outline-secondary" title="Lihat">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if (Auth::user()->isTeacher())
                                            <a href="{{ route('exams.edit', $exam) }}" class="btn btn-outline-info" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('exams.destroy', $exam) }}" method="POST" class="d-inline"
                                                onsubmit="return confirm('Yakin hapus tugas/ujian ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="Hapus">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Belum ada tugas atau ujian.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end mt-3">
                {{ $exams->links() }}
            </div>
        </div>
    </div>
@endsection
