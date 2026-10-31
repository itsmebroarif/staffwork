@extends('layouts.app')

@section('title', 'Jadwal Pengajaran - StaffWork')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Jadwal Pengajaran</h1>
            <p class="text-muted small mb-0">Jadwal mengajar guru per kelas, hari, dan jam</p>
        </div>
        <div class="d-flex align-items-center">
            @if (Auth::user()->isSuperAdmin())
                @include('pages.master._import-modal', ['importType' => 'jadwal', 'importLabel' => 'Jadwal Pengajaran'])
                <a href="{{ route('jadwal.create') }}" class="btn btn-sm btn-primary shadow-sm font-weight-bold ml-1">
                    <i class="fas fa-plus fa-sm text-white-50 mr-1"></i> Tambah Jadwal
                </a>
            @endif
        </div>
    </div>

    @include('pages.master._import-alerts')

    <ul class="nav nav-tabs mb-3" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="tab-jadwal-tabel" data-toggle="tab" href="#jadwal-tabel" role="tab"
                aria-controls="jadwal-tabel" aria-selected="true">
                <i class="fas fa-table mr-1"></i> Tabel Jadwal
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="tab-jadwal-kalender" data-toggle="tab" href="#jadwal-kalender" role="tab"
                aria-controls="jadwal-kalender" aria-selected="false">
                <i class="fas fa-calendar-week mr-1"></i> Kalender Mingguan
            </a>
        </li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane fade show active" id="jadwal-tabel" role="tabpanel" aria-labelledby="tab-jadwal-tabel">

    <!-- Data Table Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-calendar-alt mr-1"></i> Daftar Jadwal
            </h6>
            <span class="badge badge-light border">Total: {{ $schedules->total() }} Jadwal</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 60px;" class="text-center">No</th>
                            <th style="width: 110px;">Hari</th>
                            <th style="width: 150px;">Jam</th>
                            <th>Guru</th>
                            <th>Mata Pelajaran</th>
                            <th style="width: 120px;">Kelas</th>
                            @if (Auth::user()->isSuperAdmin())
                                <th style="width: 140px;" class="text-center">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($schedules as $index => $schedule)
                            <tr>
                                <td class="text-center align-middle font-weight-bold text-muted">
                                    {{ $schedules->firstItem() + $index }}
                                </td>
                                <td class="align-middle">
                                    <span class="badge badge-primary px-2 py-1">{{ $schedule->day_name }}</span>
                                </td>
                                <td class="align-middle font-weight-bold text-gray-900">
                                    {{ \Illuminate\Support\Carbon::parse($schedule->start_time)->format('H:i') }}
                                    -
                                    {{ \Illuminate\Support\Carbon::parse($schedule->end_time)->format('H:i') }}
                                </td>
                                <td class="align-middle">
                                    {{ $schedule->assignment?->teacher?->name ?? '-' }}
                                </td>
                                <td class="align-middle">
                                    {{ $schedule->assignment?->mapel?->name ?? '-' }}
                                </td>
                                <td class="align-middle">
                                    <span class="badge badge-info px-2 py-1">{{ $schedule->assignment?->kelas?->name ?? '-' }}</span>
                                </td>
                                @if (Auth::user()->isSuperAdmin())
                                    <td class="text-center align-middle">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('jadwal.edit', $schedule) }}" class="btn btn-outline-info" title="Edit Jadwal">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('jadwal.destroy', $schedule) }}" method="POST" class="d-inline"
                                                data-confirm='Hapus jadwal ini?'>
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="Hapus Jadwal">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ Auth::user()->isSuperAdmin() ? 7 : 6 }}" class="text-center text-muted py-4">Belum ada jadwal. Tambahkan atau import dari template.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end mt-3">
                {{ $schedules->links() }}
            </div>
        </div>
    </div>

        </div>
        <div class="tab-pane fade" id="jadwal-kalender" role="tabpanel" aria-labelledby="tab-jadwal-kalender">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-calendar-week mr-1"></i> Kalender Mingguan
                    </h6>
                    <span class="badge badge-light border">{{ $calendarSchedules->count() }} Jadwal</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach (['senin' => 'Senin', 'selasa' => 'Selasa', 'rabu' => 'Rabu', 'kamis' => 'Kamis', 'jumat' => 'Jumat', 'sabtu' => 'Sabtu'] as $day => $label)
                            <div class="col-xl col-lg-4 col-md-6 mb-3">
                                <div class="card h-100 schedule-day-card">
                                    <div class="card-header py-2 text-center bg-light">
                                        <strong class="small text-gray-800">{{ $label }}</strong>
                                    </div>
                                    <div class="card-body p-2">
                                        @forelse ($calendarSchedules->where('day', $day) as $schedule)
                                            <div class="schedule-chip mb-2">
                                                <div class="small font-weight-bold text-primary">
                                                    {{ \Illuminate\Support\Carbon::parse($schedule->start_time)->format('H:i') }}
                                                    &ndash;
                                                    {{ \Illuminate\Support\Carbon::parse($schedule->end_time)->format('H:i') }}
                                                </div>
                                                <div class="font-weight-bold small text-gray-900">
                                                    {{ $schedule->assignment?->mapel?->name ?? '-' }}
                                                </div>
                                                <div class="small text-muted">
                                                    <i class="fas fa-user fa-sm mr-1"></i>{{ $schedule->assignment?->teacher?->name ?? '-' }}
                                                </div>
                                                <span class="badge badge-light border mt-1">{{ $schedule->assignment?->kelas?->name ?? '-' }}</span>
                                            </div>
                                        @empty
                                            <div class="text-muted small text-center py-2">Tidak ada jadwal</div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
