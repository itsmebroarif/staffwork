@extends('layouts.app')

@section('title', $material->title . ' - StaffWork LMS')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">{{ $material->title }}</h1>
            <p class="text-muted small mb-0">
                <i class="fas fa-user mr-1"></i>{{ $material->author->name ?? '-' }}
                <span class="mx-1">&bull;</span>
                {{ $material->created_at->format('d F Y, H:i') }}
                @if (! $material->is_published)
                    <span class="badge badge-secondary ml-1">Draft</span>
                @endif
                @if ($material->type === 'gambar')
                    <span class="badge badge-warning ml-1">
                        <i class="fas fa-image mr-1"></i> Gambar
                    </span>
                @endif
                @if ($material->kelas)
                    <span class="badge badge-warning ml-1">
                        <i class="fas fa-door-open mr-1"></i> {{ $material->kelas->name }}
                    </span>
                @else
                    <span class="badge badge-light border ml-1">Semua Kelas</span>
                @endif
            </p>
        </div>
        <div>
            @if (Auth::user()->isTeacher())
                <a href="{{ route('materis.edit', $material) }}" class="btn btn-sm btn-outline-primary shadow-sm mr-1">
                    <i class="fas fa-edit fa-sm mr-1"></i> Edit
                </a>
                <form action="{{ route('materis.destroy', $material) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger shadow-sm mr-1"
                        onclick="return confirm('Pindahkan materi ini ke History Bin? Materi masih bisa dipulihkan dari menu History Bin.');">
                        <i class="fas fa-trash-alt fa-sm mr-1"></i> Hapus
                    </button>
                </form>
            @endif
            <a href="{{ route('materis.index') }}" class="btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50 mr-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card shadow mb-4">
                <div class="card-body">
                    @if ($material->body)
                        <div class="materi-body text-gray-800" style="line-height: 1.75;">
                            {!! $material->rendered_body !!}
                        </div>
                    @endif

                    @if ($material->type === 'gambar' && $material->file_path)
                        @if ($material->body)
                            <hr>
                        @endif
                        <div class="text-center">
                            <img src="{{ asset('storage/'.$material->file_path) }}"
                                alt="{{ $material->title }}"
                                class="img-fluid rounded shadow-sm border p-1" style="max-height: 520px;">
                            <div class="mt-2">
                                <a href="{{ asset('storage/'.$material->file_path) }}" class="btn btn-outline-primary btn-sm" download>
                                    <i class="fas fa-download mr-1"></i> Unduh Gambar: {{ $material->file_name ?? 'gambar-materi' }}
                                </a>
                            </div>
                        </div>
                    @endif

                    @if ($material->type === 'file' && $material->file_path && $material->file_name)
                        @if ($material->body)
                            <hr>
                        @endif
                        <a href="{{ asset('storage/' . $material->file_path) }}" class="btn btn-outline-primary btn-sm" download>
                            <i class="fas fa-download mr-1"></i> Unduh File Materi: {{ $material->file_name }}
                        </a>
                    @endif

                    @if (! $material->body && ! $material->file_path)
                        <p class="text-muted text-center mb-0">Materi ini belum memiliki isi maupun file.</p>
                    @endif
                </div>
                <div class="card-footer bg-light small text-muted">
                    Dipublikasikan pada {{ $material->created_at->format('d M Y, H:i') }}
                    @if ($material->updated_at && ! $material->updated_at->eq($material->created_at))
                        &bull; Diperbarui {{ $material->updated_at->format('d M Y, H:i') }}
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
