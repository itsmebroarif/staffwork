@extends('layouts.app')

@section('title', 'History Bin Materi - StaffWork LMS')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">History Bin Materi</h1>
            <p class="text-muted small mb-0">Materi yang dihapus tidak hilang permanen — pulihkan atau hapus selamanya di sini</p>
        </div>
        <a href="{{ route('materis.index') }}" class="btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50 mr-1"></i> Kembali ke Daftar Materi
        </a>
    </div>

    <!-- Search -->
    <div class="card shadow mb-4">
        <div class="card-body py-2">
            <form action="{{ route('materis.bin') }}" method="GET" class="form-inline">
                <div class="input-group w-100">
                    <input type="text" name="q" class="form-control bg-light border-0 small"
                        placeholder="Cari judul materi di sampah..." value="{{ request('q') }}">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search fa-sm"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0">Judul Materi</th>
                            <th class="border-0">Penulis</th>
                            <th class="border-0">Tipe</th>
                            <th class="border-0">Kelas Target</th>
                            <th class="border-0">Tanggal Dihapus</th>
                            <th class="border-0 text-center" style="width: 220px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($materials as $material)
                            <tr>
                                <td class="font-weight-bold text-gray-800 align-middle">
                                    {{ $material->title }}
                                    @if (! $material->is_published)
                                        <span class="badge badge-secondary ml-1">Draft</span>
                                    @endif
                                </td>
                                <td class="align-middle">{{ $material->author->name ?? '-' }}</td>
                                <td class="align-middle">
                                    @if ($material->type === 'gambar')
                                        <span class="badge badge-warning">Gambar</span>
                                    @elseif ($material->type === 'file')
                                        <span class="badge badge-info">File</span>
                                    @else
                                        <span class="badge badge-primary">Artikel</span>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    {{ $material->kelas->name ?? 'Semua Kelas' }}
                                </td>
                                <td class="align-middle small text-muted">
                                    {{ $material->deleted_at?->format('d M Y, H:i') ?? '-' }}
                                </td>
                                <td class="text-center align-middle">
                                    <form action="{{ route('materis.restore', $material) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success shadow-sm mr-1"
                                            title="Pulihkan materi">
                                            <i class="fas fa-trash-restore fa-sm mr-1"></i> Pulihkan
                                        </button>
                                    </form>
                                    <form action="{{ route('materis.force', $material) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger shadow-sm"
                                            title="Hapus permanen beserta file pendukungnya"
                                            data-confirm='HAPUS PERMANEN? Materi beserta file pendukungnya tidak dapat dipulihkan lagi.'>
                                            <i class="fas fa-times fa-sm mr-1"></i> Hapus Permanen
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    <i class="fas fa-check-circle fa-3x mb-3 text-gray-300"></i>
                                    <p class="mb-0">History Bin kosong — tidak ada materi yang sedang dihapus.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($materials->hasPages())
            <div class="card-footer bg-white d-flex justify-content-end">
                {{ $materials->links() }}
            </div>
        @endif
    </div>
@endsection
