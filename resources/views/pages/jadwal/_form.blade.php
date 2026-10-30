@php
    $dayNames = [
        'senin' => 'Senin',
        'selasa' => 'Selasa',
        'rabu' => 'Rabu',
        'kamis' => 'Kamis',
        'jumat' => 'Jumat',
        'sabtu' => 'Sabtu',
    ];
@endphp

<div class="form-group">
    <label for="assignment_id" class="font-weight-bold text-gray-700">Penugasan (Guru - Mapel - Kelas) <span class="text-danger">*</span></label>
    <select name="assignment_id" id="assignment_id" class="form-control @error('assignment_id') is-invalid @enderror" required>
        <option value="" disabled {{ old('assignment_id', $record->assignment_id ?? null) ? '' : 'selected' }}>-- Pilih Penugasan --</option>
        @foreach ($assignments as $assignment)
            <option value="{{ $assignment->id }}" {{ (string) old('assignment_id', $record->assignment_id ?? '') === (string) $assignment->id ? 'selected' : '' }}>
                {{ $assignment->teacher?->name }} - {{ $assignment->mapel?->name }} - {{ $assignment->kelas?->name }}
            </option>
        @endforeach
    </select>
    <small class="form-text text-muted">Belum ada penugasan? Buat dulu pada menu Kelas Diajar.</small>
    @error('assignment_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="day" class="font-weight-bold text-gray-700">Hari <span class="text-danger">*</span></label>
    <select name="day" id="day" class="form-control @error('day') is-invalid @enderror" required>
        <option value="" disabled {{ old('day', $record->day ?? null) ? '' : 'selected' }}>-- Pilih Hari --</option>
        @foreach ($dayNames as $value => $label)
            <option value="{{ $value }}" {{ old('day', $record->day ?? '') === $value ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>
    @error('day')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-row">
    <div class="form-group col-md-6">
        <label for="start_time" class="font-weight-bold text-gray-700">Jam Mulai <span class="text-danger">*</span></label>
        <input type="time"
            name="start_time"
            id="start_time"
            class="form-control @error('start_time') is-invalid @enderror"
            value="{{ old('start_time', isset($record) && $record->start_time ? \Illuminate\Support\Carbon::parse($record->start_time)->format('H:i') : '') }}"
            required>
        @error('start_time')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="form-group col-md-6">
        <label for="end_time" class="font-weight-bold text-gray-700">Jam Selesai <span class="text-danger">*</span></label>
        <input type="time"
            name="end_time"
            id="end_time"
            class="form-control @error('end_time') is-invalid @enderror"
            value="{{ old('end_time', isset($record) && $record->end_time ? \Illuminate\Support\Carbon::parse($record->end_time)->format('H:i') : '') }}"
            required>
        @error('end_time')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
