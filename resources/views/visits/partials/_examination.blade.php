<div class="row g-3">
    <div class="col-12">
        <label for="complaint" class="form-label">Keluhan <span class="text-danger">*</span></label>
        <textarea class="form-control @error('complaint') is-invalid @enderror" 
                  id="complaint" 
                  name="complaint" 
                  rows="4" 
                  required>{{ old('complaint', isset($visit) ? $visit->complaint : '') }}</textarea>
        @error('complaint')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <small class="text-muted">Jelaskan keluhan yang dialami siswa</small>
    </div>

    <div class="col-12">
        <label for="treatment" class="form-label">Penanganan <span class="text-danger">*</span></label>
        <textarea class="form-control @error('treatment') is-invalid @enderror" 
                  id="treatment" 
                  name="treatment" 
                  rows="4" 
                  required>{{ old('treatment', isset($visit) ? $visit->treatment : '') }}</textarea>
        @error('treatment')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <small class="text-muted">Jelaskan tindakan yang diberikan</small>
    </div>

    <div class="col-12">
        <label for="outcome_notes" class="form-label">Hasil <span class="text-danger">*</span></label>
        <textarea class="form-control @error('outcome_notes') is-invalid @enderror" 
                  id="outcome_notes" 
                  name="outcome_notes" 
                  rows="4" 
                  required>{{ old('outcome_notes', isset($visit) ? $visit->outcome_notes : '') }}</textarea>
        @error('outcome_notes')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <small class="text-muted">Jelaskan hasil dari penanganan</small>
    </div>
</div>