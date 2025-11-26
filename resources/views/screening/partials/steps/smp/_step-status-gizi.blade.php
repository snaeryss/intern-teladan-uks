<div class="card">
    <div class="card-body">
        <h5 class="mb-3">Informasi Awal</h5>
        <div class="row mb-3">
            <div class="col-md-6 mt-3">
                <label for="tanggal_periksa" class="form-label">Tanggal Periksa <span class="text-danger">*</span></label>
                <input type="date" 
                    class="form-control" 
                    id="tanggal_periksa" 
                    name="tanggal_periksa" 
                    value="{{ $mcu && $mcu->date ? $mcu->date->format('Y-m-d') : date('Y-m-d') }}"
                    max="{{ date('Y-m-d') }}">
                <small class="text-muted">Default: Hari ini</small>
            </div>
            <div class="col-md-6 mt-3">
                <label for="umur" class="form-label">Umur Saat Periksa</label>
                <input type="text" class="form-control bg-light text-muted" id="umur" name="umur" value="13 Tahun 6 Bulan" readonly>
            </div>
        </div>

        <hr class="my-4">

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3 mt-3">
                    <label class="form-label" for="berat_badan">1. Berat Badan (kg)</label>
                    <input type="number" class="form-control" name="berat_badan" id="berat_badan" step="0.1" placeholder="Berat Badan">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="tinggi_badan">2. Tinggi Badan (cm)</label>
                    <input type="number" step="0.1" class="form-control" name="tinggi_badan" id="tinggi_badan" placeholder="Tinggi Badan">
                </div>
            </div>

            <div class="col-md-6">
                <h5 class="mb-3">Kategori Status Gizi</h5>
                <div class="mb-3 mt-3">
                    <label class="form-label" for="imt">6. IMT (BB/TB)<sup>2</sup></label>
                    <input type="text" class="form-control bg-light text-muted" name="imt" id="imt" placeholder="IMT" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="status_gizi">7. Status Gizi</label>
                    <input type="text" class="form-control bg-light text-muted" name="status_gizi" id="status_gizi" placeholder="Status Gizi" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">8. TB / U (Stunting)</label><br>
                    <div class="form-check form-check-inline mt-2">
                        <input class="form-check-input" type="radio" name="tb_u" id="tb_u_normal" value="normal" checked>
                        <label class="form-check-label" for="tb_u_normal">Normal</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="tb_u" id="tb_u_pendek" value="pendek">
                        <label class="form-check-label" for="tb_u_pendek">Pendek</label>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">9. Tanda Klinis Anemia</label>
                    <small class="d-block text-muted">
                        (Conjunctiva/kelopak mata bagian dalam bawah pucat, bibir, lidah, telapak tangan pucat)
                    </small>
                    <div class="form-check form-check-inline mt-2">
                        <input class="form-check-input" type="radio" name="anemia" id="anemia_tidak_smp" value="tidak" checked>
                        <label class="form-check-label" for="anemia_tidak_smp">Tidak</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="anemia" id="anemia_ya_smp" value="ya">
                        <label class="form-check-label" for="anemia_ya_smp">Ya</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>