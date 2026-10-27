@extends('layouts.app')

@section('title', 'Edit Materi - StaffWork LMS')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Edit Materi</h1>
            <p class="text-muted small mb-0">{{ $material->title }}</p>
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
                        <i class="fas fa-edit mr-1"></i> Formulir Perubahan Materi
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('materis.update', $material) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="title" class="font-weight-bold text-gray-700">Judul Materi <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title"
                                class="form-control @error('title') is-invalid @enderror"
                                value="{{ old('title', $material->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="body" class="font-weight-bold text-gray-700">Isi Materi <span class="text-danger">*</span></label>
                            <textarea name="body" id="body" rows="12"
                                class="form-control @error('body') is-invalid @enderror" required>{{ old('body', $material->body) }}</textarea>
                            @error('body')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if ($material->file_name)
                            <div class="alert alert-info small">
                                <i class="fas fa-paperclip mr-1"></i>
                                File saat ini: <strong>{{ $material->file_name }}</strong>
                                — unggah file baru untuk menggantikan.
                            </div>
                        @endif

                        <div class="form-group">
                            <label for="file" class="font-weight-bold text-gray-700">Ganti Lampiran File (Opsional)</label>
                            <div class="custom-file">
                                <input type="file" name="file" class="custom-file-input @error('file') is-invalid @enderror"
                                    id="file" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar,.txt">
                                <label class="custom-file-label" for="file">Pilih file baru...</label>
                            </div>
                            @error('file')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="custom-control custom-checkbox mb-3">
                            <input type="hidden" name="is_published" value="0">
                            <input type="checkbox" name="is_published" value="1" id="is_published"
                                class="custom-control-input" {{ old('is_published', $material->is_published) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="is_published">Publikasikan</label>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('materis.index') }}" class="btn btn-secondary mr-2">Batal</a>
                            <button type="submit" class="btn btn-primary font-weight-bold shadow-sm">
                                <i class="fas fa-save mr-1"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>

                    <div class="text-right mt-3">
                        <form action="{{ route('materis.destroy', $material) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Yakin hapus materi ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                <i class="fas fa-trash-alt mr-1"></i> Hapus Materi
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $('#file').on('change', function(e) {
        var fileName = e.target.files[0] ? e.target.files[0].name : "Pilih file baru...";
        $(this).next('.custom-file-label').html(fileName);
    });
</script>
@endpush
