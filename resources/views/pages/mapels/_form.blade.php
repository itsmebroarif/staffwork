<div class="form-group">
    <label for="code" class="font-weight-bold text-gray-700">Kode Mapel <span class="text-danger">*</span></label>
    <input type="text"
        name="code"
        id="code"
        class="form-control @error('code') is-invalid @enderror"
        value="{{ old('code', $record->code ?? '') }}"
        placeholder="Contoh: MP001"
        required>
    @error('code')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="name" class="font-weight-bold text-gray-700">Nama Mapel <span class="text-danger">*</span></label>
    <input type="text"
        name="name"
        id="name"
        class="form-control @error('name') is-invalid @enderror"
        value="{{ old('name', $record->name ?? '') }}"
        placeholder="Contoh: Matematika"
        required>
    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
