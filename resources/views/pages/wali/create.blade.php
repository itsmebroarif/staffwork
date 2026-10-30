@extends('layouts.app')

@section('title', 'Tambah Wali Murid - StaffWork')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Tambah Wali Murid</h1>
            <p class="text-muted small mb-0">Tambahkan data wali/orang tua untuk siswa</p>
        </div>
        <a href="{{ route('wali.index') }}" class="btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50 mr-1"></i> Kembali ke Daftar Wali
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user-friends mr-1"></i> Formulir Wali Murid
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('wali.store') }}" method="POST">
                        @csrf

                        @include('pages.wali._form', ['record' => null])

                        <hr>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('wali.index') }}" class="btn btn-secondary mr-2">Batal</a>
                            <button type="submit" class="btn btn-primary font-weight-bold shadow-sm">
                                <i class="fas fa-save mr-1"></i> Simpan Wali
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
