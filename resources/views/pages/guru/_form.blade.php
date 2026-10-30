<div class="form-group">
    <label for="name" class="font-weight-bold text-gray-700">Nama Lengkap <span class="text-danger">*</span></label>
    <input type="text"
        name="name"
        id="name"
        class="form-control @error('name') is-invalid @enderror"
        value="{{ old('name', $record->name ?? '') }}"
        placeholder="Contoh: Siti Aminah"
        required>
    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="email" class="font-weight-bold text-gray-700">Alamat Email <span class="text-danger">*</span></label>
    <input type="email"
        name="email"
        id="email"
        class="form-control @error('email') is-invalid @enderror"
        value="{{ old('email', $record->email ?? '') }}"
        placeholder="nama@guru.staffwork.test"
        required>
    @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-row">
    <div class="form-group col-md-6">
        <label for="nip" class="font-weight-bold text-gray-700">NIP</label>
        <input type="text"
            name="nip"
            id="nip"
            class="form-control @error('nip') is-invalid @enderror"
            value="{{ old('nip', $record->nip ?? '') }}"
            placeholder="Contoh: G-1001">
        @error('nip')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="form-group col-md-6">
        <label for="phone" class="font-weight-bold text-gray-700">No HP</label>
        <input type="text"
            name="phone"
            id="phone"
            class="form-control @error('phone') is-invalid @enderror"
            value="{{ old('phone', $record->phone ?? '') }}"
            placeholder="Contoh: 081234567890">
        @error('phone')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="form-group">
    <label for="password" class="font-weight-bold text-gray-700">
        Kata Sandi @if ($record ?? null) <span class="text-muted font-weight-normal">(kosongkan jika tidak diganti)</span> @else <span class="text-danger">*</span> @endif
    </label>
    <input type="password"
        name="password"
        id="password"
        class="form-control @error('password') is-invalid @enderror"
        placeholder="Minimal 6 karakter"
        {{ $record ?? null ? '' : 'required' }}>
    @error('password')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
