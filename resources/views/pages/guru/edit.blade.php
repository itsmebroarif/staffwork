@extends('layouts.app')

@section('title', 'Edit Guru - StaffWork')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Edit Guru: {{ $guru->name }}</h1>
            <p class="text-muted small mb-0">Perbarui data akun guru</p>
        </div>
        <a href="{{ route('guru.index') }}" class="btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50 mr-1"></i> Kembali ke Daftar Guru
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chalkboard-teacher mr-1"></i> Formulir Guru
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('guru.update', $guru) }}" method="POST">
                        @csrf
                        @method('PUT')

                        @include('pages.guru._form', ['record' => $guru])

                        <hr>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('guru.index') }}" class="btn btn-secondary mr-2">Batal</a>
                            <button type="submit" class="btn btn-primary font-weight-bold shadow-sm">
                                <i class="fas fa-save mr-1"></i> Perbarui Guru
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
