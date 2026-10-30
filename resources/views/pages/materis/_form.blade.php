@php
    $currentType = old('type', $record->type ?? 'artikel');
    $isArtikel = $currentType === 'artikel';
@endphp

<div class="form-group">
    <label for="title" class="font-weight-bold text-gray-700">Judul Materi <span class="text-danger">*</span></label>
    <input type="text" name="title" id="title"
        class="form-control @error('title') is-invalid @enderror"
        value="{{ old('title', $record->title ?? '') }}"
        placeholder="Contoh: Pengenalan Bilangan Bulat"
        required autofocus>
    @error('title')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="kelas_id" class="font-weight-bold text-gray-700">Kelas Target</label>
    <select name="kelas_id" id="kelas_id" class="form-control @error('kelas_id') is-invalid @enderror">
        <option value="">Semua Kelas (Materi Umum)</option>
        @foreach ($kelasList as $kelas)
            <option value="{{ $kelas->id }}" {{ (string) old('kelas_id', $record->kelas_id ?? '') === (string) $kelas->id ? 'selected' : '' }}>
                {{ $kelas->name }} (Tingkat {{ $kelas->tingkat }})
            </option>
        @endforeach
    </select>
    <small class="form-text text-muted">Materi dengan kelas target hanya bisa dibuka oleh siswa pada tingkat tersebut.</small>
    @error('kelas_id')
        <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror
</div>

<!-- Tabs: Tulis Artikel / Upload File -->
<ul class="nav nav-tabs mb-3" id="materiTabs" role="tablist">
    <li class="nav-item">
        <a class="nav-link {{ $isArtikel ? 'active' : '' }}" id="tab-artikel" data-toggle="tab" href="#panel-artikel"
            role="tab" aria-controls="panel-artikel" aria-selected="{{ $isArtikel ? 'true' : 'false' }}">
            <i class="fas fa-pen-nib mr-1"></i> Tulis Artikel
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ ! $isArtikel ? 'active' : '' }}" id="tab-file" data-toggle="tab" href="#panel-file"
            role="tab" aria-controls="panel-file" aria-selected="{{ ! $isArtikel ? 'true' : 'false' }}">
            <i class="fas fa-file-upload mr-1"></i> Upload PDF/PPT
        </a>
    </li>
</ul>

<input type="hidden" name="type" id="materi-type-input" value="{{ $currentType }}">

<div class="tab-content mb-3">
    <div class="tab-pane fade {{ $isArtikel ? 'show active' : '' }}" id="panel-artikel" role="tabpanel" aria-labelledby="tab-artikel">
        <div class="form-group mb-0">
            <label for="body" class="font-weight-bold text-gray-700">Isi Materi <span class="text-danger">*</span></label>
            <textarea name="body" id="body" rows="12"
                class="form-control @error('body') is-invalid @enderror"
                placeholder="Tulis isi materi ajar di sini (mendukung HTML sederhana)...">{{ old('body', $record->body ?? '') }}</textarea>
            @error('body')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="tab-pane fade {{ ! $isArtikel ? 'show active' : '' }}" id="panel-file" role="tabpanel" aria-labelledby="tab-file">
        <div class="form-group mb-0">
            <label for="file" class="font-weight-bold text-gray-700">File Materi (PDF/PPT) <span class="text-danger">*</span></label>
            <div class="custom-file">
                <input type="file" name="file" class="custom-file-input @error('file') is-invalid @enderror"
                    id="file" accept=".pdf,.ppt,.pptx,.doc,.docx,.xls,.xlsx,.zip,.rar,.txt">
                <label class="custom-file-label" for="file">
                    @if ($record ?? null)
                        {{ $record->file_name ?? 'Pilih file baru (opsional jika sudah ada file)...' }}
                    @else
                        Pilih file PDF/PPT...
                    @endif
                </label>
            </div>
            <small class="form-text text-muted">Format: PDF, PPT/PPTX, Office, ZIP, TXT. Maksimal 10MB.</small>
            @error('file')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="custom-control custom-checkbox mb-3">
    <input type="hidden" name="is_published" value="0">
    <input type="checkbox" name="is_published" value="1" id="is_published"
        class="custom-control-input" {{ old('is_published', $record->is_published ?? 1) ? 'checked' : '' }}>
    <label class="custom-control-label" for="is_published">Publikasikan langsung</label>
</div>

@push('styles')
<style>
    #panel-artikel .invalid-feedback,
    #panel-file .invalid-feedback {
        display: block;
    }
</style>
@endpush

@push('scripts')
<script>
    (function() {
        var typeInput = document.getElementById('materi-type-input');

        $('#materiTabs a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
            typeInput.value = $(e.target).attr('id') === 'tab-file' ? 'file' : 'artikel';
        });

        // Re-open the failing tab after a validation error.
        @error('file')
            $('#tab-file').tab('show');
        @enderror
        @error('body')
            $('#tab-artikel').tab('show');
        @enderror

        $('#file').on('change', function(e) {
            var fileName = e.target.files[0] ? e.target.files[0].name : "Pilih file PDF/PPT...";
            $(this).next('.custom-file-label').html(fileName);
        });
    })();
</script>
@endpush
