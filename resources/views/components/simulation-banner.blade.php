@if (Auth::check() && Auth::user()->isSimulating())
    <div class="alert alert-warning d-flex align-items-center justify-content-between mb-3 shadow-sm" role="alert">
        <div>
            <i class="fas fa-eye mr-2"></i>
            <strong>Mode Simulasi:</strong> Anda sedang melihat tampilan
            <span class="badge {{ Auth::user()->role_badge_class }} ml-1">{{ Auth::user()->role_name }}</span>
            <span class="d-block small mt-1">
                Seluruh halaman (menu, dashboard, materi, profil) ditampilkan sesuai peran tersebut secara <em>read-only</em>.
            </span>
        </div>
        <form action="{{ route('profile.simulate.stop') }}" method="POST" class="ml-3 mb-0">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-dark font-weight-bold shadow-sm">
                <i class="fas fa-sign-out-alt fa-sm mr-1"></i> Keluar Simulasi
            </button>
        </form>
    </div>
@endif
