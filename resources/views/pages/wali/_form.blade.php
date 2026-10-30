<div class="form-group">
    <label for="student_id" class="font-weight-bold text-gray-700">Siswa <span class="text-danger">*</span></label>
    <select name="student_id" id="student_id" class="form-control @error('student_id') is-invalid @enderror" required>
        <option value="" disabled {{ old('student_id', $record->student_id ?? null) ? '' : 'selected' }}>-- Pilih Siswa --</option>
        @foreach ($students as $student)
            <option value="{{ $student->id }}" {{ (string) old('student_id', $record->student_id ?? '') === (string) $student->id ? 'selected' : '' }}>
                {{ $student->name }} (NIS: {{ $student->nis ?: '-' }}{{ $student->kelas ? ', '.$student->kelas->name : '' }})
            </option>
        @endforeach
    </select>
    @error('student_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-row">
    <div class="form-group col-md-6">
        <label for="name" class="font-weight-bold text-gray-700">Nama Wali <span class="text-danger">*</span></label>
        <input type="text"
            name="name"
            id="name"
            class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name', $record->name ?? '') }}"
            placeholder="Contoh: Ahmad Fauzi"
            required>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="form-group col-md-6">
        <label for="hubungan" class="font-weight-bold text-gray-700">Hubungan <span class="text-danger">*</span></label>
        <select name="hubungan" id="hubungan" class="form-control @error('hubungan') is-invalid @enderror" required>
            <option value="" disabled {{ old('hubungan', $record->hubungan ?? null) ? '' : 'selected' }}>-- Pilih Hubungan --</option>
            @foreach (['Ayah', 'Ibu', 'Wali'] as $hubungan)
                <option value="{{ $hubungan }}" {{ old('hubungan', $record->hubungan ?? '') === $hubungan ? 'selected' : '' }}>
                    {{ $hubungan }}
                </option>
            @endforeach
        </select>
        @error('hubungan')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="form-row">
    <div class="form-group col-md-6">
        <label for="phone" class="font-weight-bold text-gray-700">No HP <span class="text-danger">*</span></label>
        <input type="text"
            name="phone"
            id="phone"
            class="form-control @error('phone') is-invalid @enderror"
            value="{{ old('phone', $record->phone ?? '') }}"
            placeholder="Contoh: 081111222333"
            required>
        @error('phone')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="form-group col-md-6">
        <label for="email" class="font-weight-bold text-gray-700">Email</label>
        <input type="email"
            name="email"
            id="email"
            class="form-control @error('email') is-invalid @enderror"
            value="{{ old('email', $record->email ?? '') }}"
            placeholder="wali@contoh.test">
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="form-group">
    <label for="alamat" class="font-weight-bold text-gray-700">Alamat</label>
    <textarea name="alamat" id="alamat" rows="2"
        class="form-control @error('alamat') is-invalid @enderror"
        placeholder="Alamat domisili wali">{{ old('alamat', $record->alamat ?? '') }}</textarea>
    @error('alamat')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
