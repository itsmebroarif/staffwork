<div class="form-group">
    <label for="name" class="font-weight-bold text-gray-700">Nama Kelas <span class="text-danger">*</span></label>
    <input type="text"
        name="name"
        id="name"
        class="form-control @error('name') is-invalid @enderror"
        value="{{ old('name', $record->name ?? '') }}"
        placeholder="Contoh: Kelas 1"
        required>
    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="tingkat" class="font-weight-bold text-gray-700">Tingkat <span class="text-danger">*</span></label>
    <input type="number"
        name="tingkat"
        id="tingkat"
        class="form-control @error('tingkat') is-invalid @enderror"
        value="{{ old('tingkat', $record->tingkat ?? '') }}"
        min="1"
        max="20"
        placeholder="Contoh: 1"
        required>
    <small class="form-text text-muted">Angka tingkat dipakai untuk membatasi materi antar tingkat (misal: 1, 2, 3).</small>
    @error('tingkat')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
