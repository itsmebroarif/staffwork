@php
    $importLabel = $importLabel ?? 'Data';
@endphp

<a href="{{ route('master.template', $importType) }}" class="btn btn-sm btn-outline-success shadow-sm font-weight-bold mr-1"
    title="Unduh template Excel {{ $importLabel }}">
    <i class="fas fa-file-excel fa-sm mr-1"></i> Unduh Template
</a>
<button type="button" class="btn btn-sm btn-info shadow-sm font-weight-bold" data-toggle="modal" data-target="#importModal{{ ucfirst($importType) }}"
    title="Import {{ $importLabel }} dari file Excel/CSV">
    <i class="fas fa-file-import fa-sm mr-1"></i> Import Excel
</button>

<!-- Import Modal -->
<div class="modal fade" id="importModal{{ ucfirst($importType) }}" tabindex="-1" role="dialog"
    aria-labelledby="importModalLabel{{ ucfirst($importType) }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('master.import', $importType) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel{{ ucfirst($importType) }}">
                        <i class="fas fa-file-import text-info mr-1"></i> Import {{ $importLabel }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="small text-muted mb-3">
                        Unduh template terlebih dahulu, isi datanya, lalu unggah file di sini.
                        Format yang didukung: <strong>.xlsx, .xls, .csv</strong> (maksimal 5MB).
                    </p>
                    <div class="form-group mb-0">
                        <label for="import-file" class="font-weight-bold text-gray-700">File Import <span class="text-danger">*</span></label>
                        <div class="custom-file mt-1">
                            <input type="file" name="file" id="import-file"
                                class="custom-file-input @error('file') is-invalid @enderror"
                                accept=".xlsx,.xls,.csv" required>
                            <label class="custom-file-label" for="import-file">Pilih file Excel/CSV...</label>
                        </div>
                        @error('file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-info font-weight-bold">
                        <i class="fas fa-upload mr-1"></i> Mulai Import
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@once
    @push('scripts')
        <script>
            $(document).on('change', '.custom-file-input', function() {
                var fileName = $(this).val() ? $(this).val().split('\\').pop() : 'Pilih file...';
                $(this).next('.custom-file-label').html(fileName);
            });
        </script>
    @endpush
@endonce
