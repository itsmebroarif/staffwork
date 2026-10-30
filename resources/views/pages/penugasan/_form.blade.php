<div class="form-group">
    <label for="teacher_id" class="font-weight-bold text-gray-700">Guru <span class="text-danger">*</span></label>
    <select name="teacher_id" id="teacher_id" class="form-control @error('teacher_id') is-invalid @enderror" required>
        <option value="" disabled {{ old('teacher_id', $record->teacher_id ?? null) ? '' : 'selected' }}>-- Pilih Guru --</option>
        @foreach ($teachers as $teacher)
            <option value="{{ $teacher->id }}" {{ (string) old('teacher_id', $record->teacher_id ?? '') === (string) $teacher->id ? 'selected' : '' }}>
                {{ $teacher->name }}{{ $teacher->nip ? ' ('.$teacher->nip.')' : '' }}
            </option>
        @endforeach
    </select>
    @error('teacher_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="mapel_id" class="font-weight-bold text-gray-700">Mata Pelajaran <span class="text-danger">*</span></label>
    <select name="mapel_id" id="mapel_id" class="form-control @error('mapel_id') is-invalid @enderror" required>
        <option value="" disabled {{ old('mapel_id', $record->mapel_id ?? null) ? '' : 'selected' }}>-- Pilih Mapel --</option>
        @foreach ($mapels as $mapel)
            <option value="{{ $mapel->id }}" {{ (string) old('mapel_id', $record->mapel_id ?? '') === (string) $mapel->id ? 'selected' : '' }}>
                {{ $mapel->name }} ({{ $mapel->code }})
            </option>
        @endforeach
    </select>
    @error('mapel_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
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
