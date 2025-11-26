<div class="card">
    <div class="card-body">
        <h5 class="mb-3">Pemeriksaan Tanda-tanda Vital</h5>

        <hr class="my-4">
        
        <div class="row mb-3">
            <div class="col-md-6 mt-2">
                <label class="form-label">1. Tekanan Darah (mm/Hg)</label>
                <div class="d-flex align-items-center">
                    <input type="number" class="form-control me-2" name="tekanan_darah_sistolik" placeholder="mm">
                    <span class="me-2">/</span>
                    <input type="number" class="form-control me-2" name="tekanan_darah_diastolik" placeholder="hg">
                    <span>mmHg</span>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6 mt-2">
                <label class="form-label">2. Denyut Nadi (permenit)</label>
                <input type="number" class="form-control" name="denyut_nadi" placeholder="Denyut Nadi">
            </div>
            <div class="col-md-6 mt-2">
                <label class="form-label">3. Frekuensi Nafas (permenit)</label>
                <input type="number" class="form-control" name="frekuensi_nafas" placeholder="Frekuensi Nafas">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6 mt-2">
                <label class="form-label">4. Suhu (°C)</label>
                <input type="number" step="0.1" class="form-control" name="suhu" placeholder="Suhu">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6 mt-2">
                <label class="form-label">5. Bising Jantung</label><br>
                <div class="form-check form-check-inline mt-2">
                    <input class="form-check-input" type="radio" name="bising_jantung" value="no" checked>
                    <label class="form-check-label">Tidak</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="bising_jantung" value="yes">
                    <label class="form-check-label">Ya</label>
                </div>
            </div>

            <div class="col-md-6 mt-2">
                <label class="form-label">6. Bising Paru</label><br>
                <div class="form-check form-check-inline mt-2">
                    <input class="form-check-input" type="radio" name="bising_paru" value="no" checked>
                    <label class="form-check-label">Tidak</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="bising_paru" value="yes">
                    <label class="form-check-label">Ya</label>
                </div>
            </div>
        </div>
    </div>
</div>