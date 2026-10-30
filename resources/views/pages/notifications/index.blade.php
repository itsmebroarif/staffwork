@extends('layouts.app')

@section('title', 'Notifikasi Saya - StaffWork')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Notifikasi Saya</h1>
            <p class="text-muted small mb-0">Daftar notifikasi, termasuk panggilan ke ruang BK/ruang guru</p>
        </div>
        @if ($notifications->total() > 0)
            <form action="{{ route('notifications.readAll') }}" method="POST">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-sm btn-secondary shadow-sm">
                    <i class="fas fa-check-double fa-sm text-white-50 mr-1"></i> Tandai Semua Sudah Dibaca
                </button>
            </form>
        @endif
    </div>

    <!-- Notifications Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-bell mr-1"></i> Daftar Notifikasi
            </h6>
            <span class="badge badge-light border">Total: {{ $notifications->total() }} Notifikasi</span>
        </div>
        <div class="card-body p-0">
            @forelse ($notifications as $notification)
                @php $data = $notification->data; @endphp
                <div class="border-bottom px-4 py-3 d-sm-flex align-items-start justify-content-between {{ $notification->read_at ? 'bg-white' : 'bg-light' }}">
                    <div class="mr-3">
                        <div class="font-weight-bold text-gray-900">
                            {{ $data['title'] ?? 'Notifikasi' }}
                            @if (! $notification->read_at)
                                <span class="badge badge-primary ml-1">Baru</span>
                            @endif
                        </div>
                        <div class="text-muted small mb-1">{{ $data['message'] ?? '-' }}</div>
                        <div class="small text-muted">
                            <i class="far fa-clock mr-1"></i>{{ $notification->created_at?->format('d M Y, H:i') }}
                        </div>
                    </div>
                    @if (! $notification->read_at)
                        <form action="{{ route('notifications.read', $notification->id) }}" method="POST" class="flex-shrink-0">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-check fa-sm mr-1"></i> Tandai Dibaca
                            </button>
                        </form>
                    @endif
                </div>
            @empty
                <div class="text-center text-muted py-5">
                    <i class="fas fa-bell-slash fa-2x mb-2"></i>
                    <p class="mb-0">Belum ada notifikasi.</p>
                </div>
            @endforelse
        </div>

        @if ($notifications->total() > 0)
            <div class="card-footer d-flex justify-content-end">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
@endsection
