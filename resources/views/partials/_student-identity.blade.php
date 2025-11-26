<div class="card mb-4">
    <div class="card-body">
        <h5 class="mb-3">Informasi Siswa</h5>
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label">NIS</label>
                <input type="text" class="form-control bg-light text-muted" value="{{ $student->nis }}" readonly>
            </div>
            <div class="col-md-3">
                <label class="form-label">Nama</label>
                <input type="text" class="form-control bg-light text-muted" value="{{ $student->name }}" readonly>
            </div>
            <div class="col-md-3">
                <label class="form-label">Jenis Kelamin</label>
                <input type="text" class="form-control bg-light text-muted" value="{{ $student->sex?->label() ?? 'Tidak Diketahui' }}" readonly>
            </div>
            <div class="col-md-3">
                <label class="form-label">Tanggal Lahir</label>
                @php
                    $monthName = \App\Enums\MonthEnum::fromNumber((int) $student->date_birth->format('n'));
                    $formattedDate = $student->date_birth->format('d') . ' ' . $monthName . ' ' . $student->date_birth->format('Y');
                @endphp
                <input type="text" class="form-control bg-light text-muted" value="{{ $formattedDate }}" readonly>
            </div>
            <div class="col-md-3">
                <label class="form-label">Kelas</label>
                <input type="text" class="form-control bg-light text-muted" value="{{ $current_class ? $student->school_level->getShortName() . ' - ' . $current_class->class_name : 'Belum ada kelas' }}" readonly>
            </div>
            <div class="col-md-3">
                <label class="form-label">Umur Hari Ini</label>
                <input type="text" class="form-control bg-light text-muted" value="{{ $age }}" readonly>
            </div>
        </div>
    </div>
</div>