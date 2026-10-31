@extends('layouts.app')

@section('title', 'Materi Pembelajaran - StaffWork LMS')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Materi Pembelajaran</h1>
            <p class="text-muted small mb-0">Blog materi ajar — dapat diunggah oleh Super Admin & Guru</p>
        </div>
        @if (Auth::user()->isTeacher())
            <div>
                <a href="{{ route('materis.bin') }}" class="btn btn-sm btn-outline-secondary shadow-sm mr-1">
                    <i class="fas fa-trash-restore fa-sm mr-1"></i> History Bin
                </a>
                <a href="{{ route('materis.create') }}" class="btn btn-sm btn-primary shadow-sm font-weight-bold">
                    <i class="fas fa-plus fa-sm text-white-50 mr-1"></i> Upload Materi Baru
                </a>
            </div>
        @endif
    </div>

    <!-- Search -->
    <div class="card shadow mb-4">
        <div class="card-body py-2">
            <form action="{{ route('materis.index') }}" method="GET" class="form-inline">
                <div class="input-group w-100">
                    <input type="text" name="q" class="form-control bg-light border-0 small"
                        placeholder="Cari judul atau isi materi..." value="{{ request('q') }}">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search fa-sm"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        @forelse ($materials as $material)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card shadow h-100">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="font-weight-bold text-gray-800 mb-0">
                                <a href="{{ route('materis.show', $material) }}" class="text-decoration-none">
                                    {{ $material->title }}
                                </a>
                            </h5>
                            <div>
                                @if (! $material->is_published)
                                    <span class="badge badge-secondary">Draft</span>
                                @endif
                                @if ($material->type === 'gambar')
                                    <span class="badge badge-warning">Gambar</span>
                                @endif
                                @if ($material->type === 'file')
                                    <span class="badge badge-info">File</span>
                                @endif
                            </div>
                        </div>
                        <p class="text-muted small mb-2">
                            <i class="fas fa-user mr-1"></i>{{ $material->author->name ?? '-' }}
                            <span class="mx-1">&bull;</span>
                            {{ $material->created_at->format('d M Y') }}
                            @if ($material->kelas)
                                <span class="badge badge-warning ml-1">
                                    <i class="fas fa-door-open mr-1"></i>{{ $material->kelas->name }}
                                </span>
                            @else
                                <span class="badge badge-light border ml-1">Semua Kelas</span>
                            @endif
                        </p>
                        <p class="text-muted small flex-grow-1">{{ $material->excerpt }}</p>
                        @if ($material->file_name)
                            <span class="badge badge-info mb-2 align-self-start">
                                <i class="fas fa-paperclip mr-1"></i>{{ $material->file_name }}
                            </span>
                        @endif
                        <div class="d-flex mt-auto">
                            <a href="{{ route('materis.show', $material) }}" class="btn btn-sm btn-outline-primary flex-grow-1">
                                Baca Selengkapnya <i class="fas fa-arrow-right fa-sm ml-1"></i>
                            </a>
                            @if (Auth::user()->isTeacher())
                                <form action="{{ route('materis.destroy', $material) }}" method="POST" class="ml-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                        title="Hapus materi (bisa dipulihkan dari History Bin)"
                                        onclick="return confirm('Pindahkan materi ini ke History Bin? Materi masih bisa dipulihkan di menu History Bin.');">
                                        <i class="fas fa-trash-alt fa-sm"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-body text-center text-muted py-5">
                        <i class="fas fa-book-open fa-3x mb-3 text-gray-300"></i>
                        <p class="mb-0">Belum ada materi yang dipublikasikan.</p>
                        @if (Auth::user()->isTeacher())
                            <a href="{{ route('materis.create') }}" class="btn btn-primary btn-sm mt-2 font-weight-bold">
                                <i class="fas fa-plus mr-1"></i> Upload Materi Pertama
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-end">
        {{ $materials->links() }}
    </div>
@endsection
