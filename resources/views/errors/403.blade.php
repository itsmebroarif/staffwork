@extends('layouts.app')

@section('title', '403 - Akses Terbatas - StaffWork')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow border-left-danger mb-4">
                <div class="card-body text-center py-5">
                    <i class="fas fa-ban fa-3x text-danger mb-3"></i>
                    <h1 class="h3 text-gray-800 mb-2">403 — Akses Terbatas</h1>
                    <p class="text-muted mb-4">
                        {{ $exception->getMessage() ?: 'Anda tidak memiliki akses ke halaman tersebut.' }}
                    </p>

                    @if (Auth::check() && Auth::user()->isSimulating())
                        <div class="alert alert-warning small text-left">
                            <i class="fas fa-theater-masks mr-1"></i>
                            Anda sedang dalam <strong>Mode Simulasi</strong> sebagai
                            <strong>{{ Auth::user()->role_name }}</strong>.
                            Halaman ini memang tidak tersedia untuk peran tersebut.
                        </div>
                        <form id="exit-simulation-form" action="{{ route('profile.simulate.stop') }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-dark font-weight-bold shadow-sm mr-2">
                                <i class="fas fa-sign-out-alt mr-1"></i> Keluar dari Simulasi
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('dashboard') }}" class="btn btn-primary font-weight-bold shadow-sm">
                        <i class="fas fa-tachometer-alt mr-1"></i> Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
