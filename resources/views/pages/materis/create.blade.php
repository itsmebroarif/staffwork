@extends('layouts.app')

@section('title', 'Upload Materi Baru - StaffWork LMS')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Upload Materi Baru</h1>
            <p class="text-muted small mb-0">Tulis artikel langsung atau upload file PDF/PPT, lalu pilih kelas target</p>
        </div>
        <a href="{{ route('materis.index') }}" class="btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50 mr-1"></i> Kembali ke Daftar Materi
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-file-upload mr-1"></i> Formulir Materi
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('materis.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        @include('pages.materis._form', ['record' => null])

                        <hr>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('materis.index') }}" class="btn btn-secondary mr-2">Batal</a>
                            <button type="submit" class="btn btn-primary font-weight-bold shadow-sm">
                                <i class="fas fa-save mr-1"></i> Simpan Materi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
