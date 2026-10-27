@php
    $isTemplate = $isTemplate ?? false;
    $q = $question ?? null;
    $options = ['A', 'B', 'C', 'D'];
@endphp

<div class="card border mb-3 question-card {{ $isTemplate ? 'd-none' : '' }}">
    <div class="card-header py-2 d-flex justify-content-between align-items-center bg-light">
        <strong class="text-primary small question-number">
            Soal #{{ is_numeric($index) ? $index + 1 : '' }}
        </strong>
        <button type="button" class="btn btn-sm btn-outline-danger remove-question" title="Hapus soal">
            <i class="fas fa-trash-alt"></i>
        </button>
    </div>
    <div class="card-body">
        <div class="form-group">
            <label class="font-weight-bold text-gray-700 small">Pertanyaan <span class="text-danger">*</span></label>
            <textarea name="questions[{{ $index }}][question]" rows="2"
                class="form-control @error("questions.{$index}.question") is-invalid @enderror"
                placeholder="Tulis pertanyaan..." required>{{ $q['question'] ?? '' }}</textarea>
            @error("questions.{$index}.question")
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-row">
            @foreach ($options as $opt)
                <div class="form-group col-md-6">
                    <label class="font-weight-bold text-gray-700 small">
                        Opsi {{ $opt }} <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="questions[{{ $index }}][option_{{ strtolower($opt) }}]"
                        class="form-control @error("questions.{$index}.option_" . strtolower($opt)) is-invalid @enderror"
                        value="{{ $q['option_' . strtolower($opt)] ?? '' }}"
                        placeholder="Opsi {{ $opt }}" required>
                    @error("questions.{$index}.option_" . strtolower($opt))
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            @endforeach
        </div>

        <div class="form-group mb-0">
            <label class="font-weight-bold text-gray-700 small">Jawaban Benar <span class="text-danger">*</span></label>
            <div class="d-flex flex-wrap">
                @foreach ($options as $opt)
                    <div class="custom-control custom-radio mr-3">
                        <input type="radio" class="custom-control-input"
                            name="questions[{{ $index }}][correct_answer]"
                            id="correct_{{ $index }}_{{ $opt }}"
                            value="{{ $opt }}"
                            {{ ($q['correct_answer'] ?? null) === $opt ? 'checked' : '' }} required>
                        <label class="custom-control-label" for="correct_{{ $index }}_{{ $opt }}">{{ $opt }}</label>
                    </div>
                @endforeach
            </div>
            @error("questions.{$index}.correct_answer")
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>
