@php
    $currentType = old('type', $record->type ?? 'artikel');
    $isArtikel = $currentType === 'artikel';
    $isGambar = $currentType === 'gambar';
    $isFile = $currentType === 'file';
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

<!-- Tabs: Tulis Artikel (WYSIWYG) / Input Gambar / Upload File -->
<ul class="nav nav-tabs mb-3" id="materiTabs" role="tablist">
    <li class="nav-item">
        <a class="nav-link {{ $isArtikel ? 'active' : '' }}" id="tab-artikel" data-toggle="tab" href="#panel-artikel"
            role="tab" aria-controls="panel-artikel" aria-selected="{{ $isArtikel ? 'true' : 'false' }}">
            <i class="fas fa-pen-nib mr-1"></i> Tulis Artikel
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $isGambar ? 'active' : '' }}" id="tab-gambar" data-toggle="tab" href="#panel-gambar"
            role="tab" aria-controls="panel-gambar" aria-selected="{{ $isGambar ? 'true' : 'false' }}">
            <i class="fas fa-image mr-1"></i> Input Gambar
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $isFile ? 'active' : '' }}" id="tab-file" data-toggle="tab" href="#panel-file"
            role="tab" aria-controls="panel-file" aria-selected="{{ $isFile ? 'true' : 'false' }}">
            <i class="fas fa-file-upload mr-1"></i> Upload PDF/PPT
        </a>
    </li>
</ul>

<input type="hidden" name="type" id="materi-type-input" value="{{ $currentType }}">

<div class="tab-content mb-3">
    <!-- Tab 1: WYSIWYG Artikel (CKEditor 5) -->
    <div class="tab-pane fade {{ $isArtikel ? 'show active' : '' }}" id="panel-artikel" role="tabpanel" aria-labelledby="tab-artikel">
        <div class="form-group mb-0">
            <label for="body" class="font-weight-bold text-gray-700">Isi Materi <span class="text-danger">*</span></label>
            <textarea name="body" id="materi-editor" rows="12"
                class="form-control @error('body') is-invalid @enderror"
                placeholder="Tulis isi materi ajar di sini...">{{ old('body', $record->body ?? '') }}</textarea>
            <small class="form-text text-muted">
                Editor mendukung format teks, judul, daftar, kutipan, blok kode, serta penyisipan gambar
                (tombol gambar, paste, atau drag &amp; drop).
            </small>
            @error('body')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <!-- Tab 2: Input Gambar sebagai materi -->
    <div class="tab-pane fade {{ $isGambar ? 'show active' : '' }}" id="panel-gambar" role="tabpanel" aria-labelledby="tab-gambar">
        <div class="form-group mb-0">
            <label for="gambar" class="font-weight-bold text-gray-700">Gambar Materi <span class="text-danger">*</span></label>
            <div class="custom-file">
                <input type="file" name="gambar" class="custom-file-input @error('gambar') is-invalid @enderror"
                    id="gambar" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
                <label class="custom-file-label" for="gambar">
                    @if ($record ?? null)
                        {{ $record->type === 'gambar' ? ($record->file_name ?? 'Gambar saat ini') : 'Pilih file gambar...' }}
                    @else
                        Pilih file gambar...
                    @endif
                </label>
            </div>
            <small class="form-text text-muted">Format: JPG, JPEG, PNG, GIF, WEBP. Maksimal 5MB.</small>
            @error('gambar')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror

            <div class="mt-3 text-center {{ $isGambar && ($record->file_path ?? null) ? '' : 'd-none' }}" id="gambar-preview-wrap">
                <img id="gambar-preview" class="img-fluid rounded border p-1 shadow-sm"
                    style="max-height: 320px;"
                    src="{{ $isGambar && ($record->file_path ?? null) ? asset('storage/'.$record->file_path) : '' }}"
                    alt="Pratinjau gambar materi">
            </div>
        </div>
    </div>

    <!-- Tab 3: Upload File Dokumen -->
    <div class="tab-pane fade {{ $isFile ? 'show active' : '' }}" id="panel-file" role="tabpanel" aria-labelledby="tab-file">
        <div class="form-group mb-0">
            <label for="file" class="font-weight-bold text-gray-700">File Materi (PDF/PPT) <span class="text-danger">*</span></label>
            <div class="custom-file">
                <input type="file" name="file" class="custom-file-input @error('file') is-invalid @enderror"
                    id="file" accept=".pdf,.ppt,.pptx,.doc,.docx,.xls,.xlsx,.zip,.rar,.txt">
                <label class="custom-file-label" for="file">
                    @if ($record ?? null)
                        {{ $record->type === 'file' ? ($record->file_name ?? 'Pilih file baru (opsional jika sudah ada file)...') : 'Pilih file PDF/PPT...' }}
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
    #panel-gambar .invalid-feedback,
    #panel-file .invalid-feedback {
        display: block;
    }

    #materi-editor {
        min-height: 320px;
    }

    #panel-artikel .ck-editor__editable_inline {
        min-height: 320px;
    }

    /* Materi code block styling (CKEditor "codeBlock" output) */
    .materi-body pre,
    .ck-content pre {
        background: #1e1e2f;
        color: #eaeaea;
        padding: 1rem 1.25rem;
        border-radius: .5rem;
        overflow-x: auto;
        font-size: .9rem;
        line-height: 1.6;
    }

    .materi-body code,
    .ck-content code {
        font-family: "Courier New", Courier, monospace;
        background: #f3f4f6;
        color: #c7254e;
        padding: .15rem .35rem;
        border-radius: .25rem;
        font-size: .9em;
    }

    .materi-body pre code,
    .ck-content pre code {
        background: transparent;
        color: inherit;
        padding: 0;
    }

    .materi-body img,
    .ck-content img {
        max-width: 100%;
        height: auto;
        border-radius: .5rem;
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('template/vendor/ckeditor/ckeditor.js') }}"></script>
<script>
    (function() {
        var typeInput = document.getElementById('materi-type-input');

        $('#materiTabs a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
            var id = $(e.target).attr('id');
            typeInput.value = id === 'tab-gambar' ? 'gambar' : (id === 'tab-file' ? 'file' : 'artikel');
        });

        // Re-open the failing tab after a validation error.
        @error('file')
            $('#tab-file').tab('show');
        @enderror
        @error('gambar')
            $('#tab-gambar').tab('show');
        @enderror
        @error('body')
            $('#tab-artikel').tab('show');
        @enderror

        // File label + preview for the image tab.
        $('#file').on('change', function(e) {
            var fileName = e.target.files[0] ? e.target.files[0].name : "Pilih file PDF/PPT...";
            $(this).next('.custom-file-label').html(fileName);
        });

        $('#gambar').on('change', function(e) {
            var fileName = e.target.files[0] ? e.target.files[0].name : "Pilih file gambar...";
            $(this).next('.custom-file-label').html(fileName);

            if (e.target.files && e.target.files[0]) {
                var reader = new FileReader();
                reader.onload = function(event) {
                    $('#gambar-preview').attr('src', event.target.result);
                    $('#gambar-preview-wrap').removeClass('d-none');
                }
                reader.readAsDataURL(e.target.files[0]);
            }
        });

        // CKEditor 5 (classic build, local) with image upload + code block support.
        var editorElement = document.getElementById('materi-editor');

        if (editorElement && typeof ClassicEditor !== 'undefined') {
            var uploadUrl = @json(route('materis.images.upload'));
            var csrfToken = @json(csrf_token());

            class MateriUploadAdapter {
                constructor(loader) {
                    this.loader = loader;
                }

                upload() {
                    var loader = this.loader;

                    return loader.file.then(function(file) {
                        return new Promise(function(resolve, reject) {
                            var data = new FormData();
                            data.append('upload', file);

                            var xhr = new XMLHttpRequest();
                            xhr.open('POST', uploadUrl, true);
                            xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
                            xhr.responseType = 'json';

                            xhr.onload = function() {
                                var response = xhr.response || {};

                                if (xhr.status >= 200 && xhr.status < 300) {
                                    if (response && response.url) {
                                        resolve({ default: response.url });
                                    } else {
                                        reject('Respons server tidak valid saat mengunggah gambar.');
                                    }
                                } else {
                                    reject(response && response.message ? response.message : 'Gagal mengunggah gambar (HTTP ' + xhr.status + ').');
                                }
                            };

                            xhr.onerror = function() {
                                reject('Koneksi gagal saat mengunggah gambar.');
                            };

                            xhr.send(data);
                        });
                    });
                }

                abort() {
                    // Upload tidak dapat dibatalkan pada implementasi ini.
                }
            }

            function materiUploadAdapterPlugin(editor) {
                editor.plugins.get('FileRepository').createUploadAdapter = function(loader) {
                    return new MateriUploadAdapter(loader);
                };
            }

            ClassicEditor.create(editorElement, {
                extraPlugins: [materiUploadAdapterPlugin],
                toolbar: {
                    items: [
                        'heading', '|',
                        'bold', 'italic', '|',
                        'bulletedList', 'numberedList', '|',
                        'outdent', 'indent', '|',
                        'link', 'uploadImage', '|',
                        'codeBlock', 'blockQuote', '|',
                        'undo', 'redo'
                    ]
                },
                image: {
                    toolbar: [
                        'imageTextAlternative', 'toggleImageCaption', '|',
                        'imageStyle:inline', 'imageStyle:block', 'imageStyle:side'
                    ]
                },
                codeBlock: {
                    languages: [
                        { language: 'plaintext', label: 'Teks Biasa' },
                        { language: 'php', label: 'PHP' },
                        { language: 'js', label: 'JavaScript' },
                        { language: 'python', label: 'Python' },
                        { language: 'java', label: 'Java' },
                        { language: 'cpp', label: 'C++' },
                        { language: 'sql', label: 'SQL' },
                        { language: 'html', label: 'HTML' },
                        { language: 'css', label: 'CSS' },
                        { language: 'bash', label: 'Bash' },
                        { language: 'json', label: 'JSON' },
                        { language: 'xml', label: 'XML' }
                    ]
                }
            }).catch(function(error) {
                console.error('CKEditor gagal dimuat:', error);
            });
        }
    })();
</script>
@endpush
