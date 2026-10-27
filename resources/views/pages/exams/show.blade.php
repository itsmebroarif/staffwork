@extends('layouts.app')

@section('title', $exam->title . ' - StaffWork LMS')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">{{ $exam->title }}</h1>
            <p class="text-muted small mb-0">
                <span class="badge {{ $exam->type_badge_class }}">{{ $exam->type_label }}</span>
                <span class="mx-1">&bull;</span>
                {{ $exam->questions_count ?? $exam->questions->count() }} soal
                @if ($exam->duration_minutes)
                    <span class="mx-1">&bull;</span> {{ $exam->duration_minutes }} menit
                @endif
                <span class="mx-1">&bull;</span>
                <i class="fas fa-user mr-1"></i>{{ $exam->author->name ?? '-' }}
            </p>
        </div>
        <div>
            @if (Auth::user()->isTeacher())
                <a href="{{ route('exams.edit', $exam) }}" class="btn btn-sm btn-outline-primary shadow-sm mr-1">
                    <i class="fas fa-edit fa-sm mr-1"></i> Edit
                </a>
            @endif
            <a href="{{ route('exams.index') }}" class="btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50 mr-1"></i> Kembali
            </a>
        </div>
    </div>

    @if ($exam->description)
        <div class="alert alert-info">
            <i class="fas fa-info-circle mr-1"></i>
            {!! nl2br(e($exam->description)) !!}
        </div>
    @endif

    @if ($exam->available_at && $exam->available_at->isFuture())
        <div class="alert alert-warning">
            <i class="fas fa-clock mr-1"></i>
            Tersedia mulai {{ $exam->available_at->format('d M Y, H:i') }}.
        </div>
    @endif

    <div class="row">
        @forelse ($exam->questions as $i => $question)
            <div class="col-lg-8">
                <div class="card shadow mb-3">
                    <div class="card-header py-2 d-flex justify-content-between align-items-center">
                        <strong class="text-primary">Soal #{{ $i + 1 }}</strong>
                        @if (Auth::user()->isTeacher())
                            <span class="badge badge-success">
                                Jawaban: {{ $question->correct_answer }}
                            </span>
                        @endif
                    </div>
                    <div class="card-body">
                        <p class="font-weight-bold text-gray-800 mb-3">{!! nl2br(e($question->question)) !!}</p>
                        <ul class="list-unstyled mb-0">
                            @foreach ($question->options as $letter => $text)
                                <li class="mb-1">
                                    <span class="badge badge-light border mr-2">{{ $letter }}</span>
                                    {{ $text }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-body text-center text-muted py-5">
                        <i class="fas fa-clipboard fa-3x mb-3 text-gray-300"></i>
                        <p class="mb-0">Belum ada soal pada tugas/ujian ini.</p>
                    </div>
                </div>
            </div>
        @endforelse

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-clipboard-list mr-1"></i> Ringkasan
                    </h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-bordered mb-0">
                        <tr>
                            <th class="bg-light">Jenis</th>
                            <td><span class="badge {{ $exam->type_badge_class }}">{{ $exam->type_label }}</span></td>
                        </tr>
                        <tr>
                            <th class="bg-light">Jumlah Soal</th>
                            <td>{{ $exam->questions->count() }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Durasi</th>
                            <td>{{ $exam->duration_minutes ? $exam->duration_minutes . ' menit' : '-' }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Dibuat</th>
                            <td>{{ $exam->created_at->format('d M Y') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
