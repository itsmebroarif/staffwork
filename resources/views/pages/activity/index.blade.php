@extends('layouts.app')

@section('title', 'Activity Log - StaffWork')

@section('content')
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Activity Log</h1>
            <p class="text-muted small mb-0">Pantau aktivitas setiap akun untuk memudahkan penelusuran kendala.</p>
        </div>
        <div class="d-flex mt-2 mt-sm-0">
            <span class="badge badge-light border px-3 py-2 mr-2">
                <i class="fas fa-calendar-day mr-1 text-primary"></i> Hari ini: {{ $totalToday }}
            </span>
            <span class="badge badge-light border px-3 py-2">
                <i class="fas fa-database mr-1 text-primary"></i> Total: {{ $totalAll }}
            </span>
        </div>
    </div>

    <div class="card shadow mb-4" data-aos="fade-up">
        <div class="card-body py-3">
            <form action="{{ route('activity.index') }}" method="GET" class="form-row align-items-end">
                <div class="form-group col-lg-4 col-md-6 mb-2 mb-md-0">
                    <label for="q" class="small font-weight-bold text-gray-700 mb-1">Cari</label>
                    <input type="text" name="q" id="q" class="form-control form-control-sm bg-light"
                        placeholder="Deskripsi, aksi, IP, atau nama akun" value="{{ request('q') }}">
                </div>
                <div class="form-group col-lg-3 col-md-6 mb-2 mb-md-0">
                    <label for="user_id" class="small font-weight-bold text-gray-700 mb-1">Akun</label>
                    <select name="user_id" id="user_id" class="form-control form-control-sm bg-light">
                        <option value="">Semua Akun</option>
                        @foreach ($users as $activityUser)
                            <option value="{{ $activityUser->id }}" {{ (string) request('user_id') === (string) $activityUser->id ? 'selected' : '' }}>
                                {{ $activityUser->name }} ({{ $activityUser->role_name }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-lg-3 col-md-6 mb-2 mb-md-0">
                    <label for="action" class="small font-weight-bold text-gray-700 mb-1">Jenis Aksi</label>
                    <select name="action" id="action" class="form-control form-control-sm bg-light">
                        <option value="">Semua Aksi</option>
                        @foreach ($actions as $action)
                            <option value="{{ $action }}" {{ request('action') === $action ? 'selected' : '' }}>
                                {{ \App\Models\ActivityLog::make(['action' => $action])->action_label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-lg-2 col-md-6 mb-0">
                    <button type="submit" class="btn btn-primary btn-sm btn-block">
                        <i class="fas fa-filter fa-sm mr-1"></i> Terapkan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow mb-4" data-aos="fade-up" data-aos-delay="80">
        <div class="card-header py-3 d-flex align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-history mr-1"></i> Riwayat Aktivitas
            </h6>
            <span class="badge badge-light border">Total: {{ $logs->total() }} Aktivitas</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 150px;">Waktu</th>
                            <th style="width: 200px;">Akun</th>
                            <th style="width: 170px;">Aksi</th>
                            <th>Deskripsi</th>
                            <th style="width: 130px;">IP</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs as $log)
                            <tr>
                                <td class="align-middle small text-muted">
                                    {{ $log->created_at?->format('d M Y') }}<br>
                                    <span class="font-weight-bold text-gray-800">{{ $log->created_at?->format('H:i:s') }}</span>
                                </td>
                                <td class="align-middle">
                                    <div class="font-weight-bold text-gray-900 small">{{ $log->user->name ?? 'Sistem' }}</div>
                                    <div class="small text-muted">{{ $log->user->role_name ?? '-' }}</div>
                                </td>
                                <td class="align-middle">
                                    <span class="badge {{ $log->action_badge_class }} px-2 py-1">{{ $log->action_label }}</span>
                                </td>
                                <td class="align-middle small text-gray-700">{{ $log->description }}</td>
                                <td class="align-middle small text-muted">{{ $log->ip_address ?: '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    Belum ada aktivitas yang tercatat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end mt-3">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
@endsection
