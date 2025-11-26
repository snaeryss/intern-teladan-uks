<div class="row g-3">
    <div class="col-md-6">
        <label for="date" class="form-label">Tanggal Periksa <span class="text-danger">*</span></label>
        <input type="date" 
               class="form-control @error('date') is-invalid @enderror" 
               id="date" 
               name="date" 
               value="{{ old('date', isset($visit) ? $visit->date->format('Y-m-d') : '') }}"
               required>
        @error('date')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="day" class="form-label">Hari periksa</label>
        <input type="text" 
               class="form-control" 
               id="day" 
               name="day" 
               value="{{ old('day', isset($visit) ? $visit->day : '') }}"
               readonly>
    </div>

    <div class="col-md-6">
        <label for="arrival_time" class="form-label">Jam Datang <span class="text-danger">*</span></label>
        <input type="time" 
               class="form-control @error('arrival_time') is-invalid @enderror" 
               id="arrival_time" 
               name="arrival_time" 
               value="{{ old('arrival_time', isset($visit) ? \Carbon\Carbon::parse($visit->arrival_time)->format('H:i') : '') }}"
               required>
        @error('arrival_time')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="departure_time" class="form-label">Jam Keluar</label>
        <input type="time" 
               class="form-control @error('departure_time') is-invalid @enderror" 
               id="departure_time" 
               name="departure_time" 
               value="{{ old('departure_time', isset($visit) && $visit->departure_time ? \Carbon\Carbon::parse($visit->departure_time)->format('H:i') : '') }}">
        @error('departure_time')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="student_id" class="form-label">Nama Siswa <span class="text-danger">*</span></label>
        <select class="form-select @error('student_id') is-invalid @enderror" 
                id="student_id" 
                name="student_id" 
                required>
            <option value="">— Cari & Pilih Siswa —</option>
            @if(isset($visit) && $visit->student)
                <option value="{{ $visit->student->id }}" selected>
                    {{ $visit->student->nis }} - {{ $visit->student->name }}
                </option>
            @endif
        </select>
        @error('student_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="student_class" class="form-label">Kelas</label>
        <input type="text" 
               class="form-control" 
               id="student_class" 
               value="{{ old('student_class', isset($visit) && $visit->student ? $visit->student->class : '') }}"
               readonly>
    </div>
</div>