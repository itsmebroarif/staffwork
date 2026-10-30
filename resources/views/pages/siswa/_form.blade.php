<div class="form-group">
    <label for="name" class="font-weight-bold text-gray-700">Nama Lengkap <span class="text-danger">*</span></label>
    <input type="text"
        name="name"
        id="name"
        class="form-control @error('name') is-invalid @enderror"
        value="{{ old('name', $record->name ?? '') }}"
        placeholder="Contoh: Budi Santoso"
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
        placeholder="nama@siswa.staffwork.test"
        required>
    @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-row">
    <div class="form-group col-md-4">
        <label for="nis" class="font-weight-bold text-gray-700">NIS</label>
        <input type="text"
            name="nis"
            id="nis"
            class="form-control @error('nis') is-invalid @enderror"
            value="{{ old('nis', $record->nis ?? '') }}"
            placeholder="Contoh: 1001">
        @error('nis')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="form-group col-md-4">
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
    <div class="form-group col-md-4">
        <label for="kelas_id" class="font-weight-bold text-gray-700">Kelas <span class="text-danger">*</span></label>
        <select name="kelas_id" id="kelas_id" class="form-control @error('kelas_id') is-invalid @enderror" required>
            <option value="" disabled {{ old('kelas_id', $record->kelas_id ?? null) ? '' : 'selected' }}>-- Pilih Kelas --</option>
            @foreach ($kelasList as $kelas)
                <option value="{{ $kelas->id }}" {{ (string) old('kelas_id', $record->kelas_id ?? '') === (string) $kelas->id ? 'selected' : '' }}>
                    {{ $kelas->name }} (Tingkat {{ $kelas->tingkat }})
                </option>
            @endforeach
        </select>
        @error('kelas_id')
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
