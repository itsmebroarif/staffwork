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
            </p>
        </div>
        <div>
            @if (Auth::user()->isTeacher())
                <a href="{{ route('materis.edit', $material) }}" class="btn btn-sm btn-outline-primary shadow-sm mr-1">
                    <i class="fas fa-edit fa-sm mr-1"></i> Edit
                </a>
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
                    <article class="text-gray-800" style="line-height: 1.75;">
                        {!! nl2br(e($material->body)) !!}
                    </article>

                    @if ($material->file_path && $material->file_name)
                        <hr>
                        <a href="{{ asset('storage/' . $material->file_path) }}" class="btn btn-outline-primary btn-sm" download>
                            <i class="fas fa-download mr-1"></i> Unduh Lampiran: {{ $material->file_name }}
                        </a>
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
