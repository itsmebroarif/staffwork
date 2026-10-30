@if (session('import_errors'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle mr-2"></i>
        Berhasil mengimpor <strong>{{ session('imported_count', 0) }}</strong> data.
        <strong>{{ count(session('import_errors')) }}</strong> baris gagal:
        <ul class="mb-0 mt-2">
            @foreach (session('import_errors') as $importError)
                <li class="small">{{ $importError }}</li>
            @endforeach
        </ul>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
