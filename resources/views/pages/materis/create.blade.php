@extends('layouts.app')

@section('title', 'Upload Materi Baru - StaffWork LMS')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Upload Materi Baru</h1>
            <p class="text-muted small mb-0">Tulis materi berformat blog, lampirkan file pendukung (opsional)</p>
        </div>
        <a href="{{ route('materis.index') }}" class="btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50 mr-1"></i> Kembali ke Daftar Materi
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-file-upload mr-1"></i> Formulir Materi
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('materis.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label for="title" class="font-weight-bold text-gray-700">Judul Materi <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title"
                                class="form-control @error('title') is-invalid @enderror"
                                value="{{ old('title') }}"
                                placeholder="Contoh: Pengenalan Bilangan Bulat"
                                required autofocus>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="body" class="font-weight-bold text-gray-700">Isi Materi <span class="text-danger">*</span></label>
                            <textarea name="body" id="body" rows="12"
                                class="form-control @error('body') is-invalid @enderror"
                                placeholder="Tulis isi materi ajar di sini (mendukung HTML sederhana)..."
                                required>{{ old('body') }}</textarea>
                            @error('body')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="file" class="font-weight-bold text-gray-700">Lampiran File (Opsional)</label>
                            <div class="custom-file">
                                <input type="file" name="file" class="custom-file-input @error('file') is-invalid @enderror"
                                    id="file" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar,.txt">
                                <label class="custom-file-label" for="file">Pilih file pendukung...</label>
                            </div>
                            <small class="form-text text-muted">Format: PDF, Office, ZIP, TXT. Maksimal 10MB.</small>
                            @error('file')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="custom-control custom-checkbox mb-3">
                            <input type="hidden" name="is_published" value="0">
                            <input type="checkbox" name="is_published" value="1" id="is_published"
                                class="custom-control-input" {{ old('is_published', 1) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="is_published">Publikasikan langsung</label>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('materis.index') }}" class="btn btn-secondary mr-2">Batal</a>
                            <button type="submit" class="btn btn-primary font-weight-bold shadow-sm">
                                <i class="fas fa-save mr-1"></i> Simpan Materi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $('#file').on('change', function(e) {
        var fileName = e.target.files[0] ? e.target.files[0].name : "Pilih file pendukung...";
        $(this).next('.custom-file-label').html(fileName);
    });
</script>
@endpush
