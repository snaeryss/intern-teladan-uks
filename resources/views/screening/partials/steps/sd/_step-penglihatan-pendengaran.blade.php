<div class="card">
    <div class="card-body">
        <h5 class="mb-3">Penglihatan</h5>

        <div class="row mb-3">
            <div class="col-md-6 mt-2">
                <label class="form-label">1. Mata Luar</label><br>
                <div class="form-check form-check-inline mt-2">
                    <input class="form-check-input" type="radio" name="mata_luar" value="normal" checked>
                    <label class="form-check-label">Normal</label>
                </div>
                <div class="form-check form-check-inline">
                    <!-- ✅ UBAH: tidak → unhealthy -->
                    <input class="form-check-input" type="radio" name="mata_luar" value="unhealthy">
                    <label class="form-check-label">Tidak Sehat</label>
                </div>
                <textarea class="form-control mt-2" name="mata_luar_ket" placeholder="Penjelasan. isi dengan tanda '-' jika tidak ada."></textarea>
            </div>

            <div class="col-md-6 mt-2">
                <label class="form-label">2. Tajam Penglihatan</label><br>
                <div class="form-check form-check-inline mt-2">
                    <input class="form-check-input" type="radio" name="tajam_penglihatan" value="normal" checked>
                    <label class="form-check-label">Normal</label>
                </div>
                <div class="form-check form-check-inline">
                    <!-- ✅ UBAH: lowvision → low_vision -->
                    <input class="form-check-input" type="radio" name="tajam_penglihatan" value="low_vision">
                    <label class="form-check-label">Low Vision</label>
                </div>
                <div class="form-check form-check-inline">
                    <!-- ✅ UBAH: kebutaan → blindness -->
                    <input class="form-check-input" type="radio" name="tajam_penglihatan" value="blindness">
                    <label class="form-check-label">Kebutaan</label>
                </div>
                <textarea class="form-control mt-2" name="tajam_penglihatan_ket" placeholder="Penjelasan. isi dengan tanda '-' jika tidak ada."></textarea>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6 mt-2">
                <label class="form-label">3. Kacamata</label><br>
                <div class="form-check form-check-inline mt-2">
                    <!-- ✅ UBAH: tidak → no -->
                    <input class="form-check-input" type="radio" name="kacamata" value="no" checked>
                    <label class="form-check-label">Tidak</label>
                </div>
                <div class="form-check form-check-inline">
                    <!-- ✅ UBAH: ya → yes -->
                    <input class="form-check-input" type="radio" name="kacamata" value="yes">
                    <label class="form-check-label">Ya</label>
                </div>
                <textarea class="form-control mt-2" name="kacamata_ket" placeholder="Penjelasan. isi dengan tanda '-' jika tidak ada."></textarea>
            </div>

            <div class="col-md-6 mt-2">
                <label class="form-label">4. Infeksi Mata</label><br>
                <div class="form-check form-check-inline mt-2">
                    <!-- ✅ UBAH: tidak → no -->
                    <input class="form-check-input" type="radio" name="infeksi_mata" value="no" checked>
                    <label class="form-check-label">Tidak</label>
                </div>
                <div class="form-check form-check-inline">
                    <!-- ✅ UBAH: ya → yes -->
                    <input class="form-check-input" type="radio" name="infeksi_mata" value="yes">
                    <label class="form-check-label">Ya</label>
                </div>
                <textarea class="form-control mt-2" name="infeksi_mata_ket" placeholder="Penjelasan. isi dengan tanda '-' jika tidak ada."></textarea>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-12 mt-2">
                <label class="form-label">5. Masalah Lainnya</label>
                <textarea class="form-control mt-2" name="penglihatan_lainnya" placeholder="Penjelasan. isi dengan tanda '-' jika tidak ada."></textarea>
            </div>
        </div>

        <hr class="my-4">

        <h5 class="mb-3">Pendengaran</h5>
        <div class="row mb-3">
            <div class="col-md-6 mt-2">
                <label class="form-label">6. Telinga Luar</label><br>
                <div class="form-check form-check-inline mt-2">
                    <!-- ✅ UBAH: sehat → healthy -->
                    <input class="form-check-input" type="radio" name="telinga_luar" value="healthy" checked>
                    <label class="form-check-label">Sehat</label>
                </div>
                <div class="form-check form-check-inline">
                    <!-- ✅ UBAH: tidak → unhealthy -->
                    <input class="form-check-input" type="radio" name="telinga_luar" value="unhealthy">
                    <label class="form-check-label">Tidak Sehat</label>
                </div>
                <textarea class="form-control mt-2" name="telinga_luar_ket" placeholder="Penjelasan. isi dengan tanda '-' jika tidak ada."></textarea>
            </div>

            <div class="col-md-6 mt-2">
                <label class="form-label">7. Serumen</label><br>
                <div class="form-check form-check-inline mt-2">
                    <!-- ✅ UBAH: tidak → no -->
                    <input class="form-check-input" type="radio" name="serumen" value="no" checked>
                    <label class="form-check-label">Tidak</label>
                </div>
                <div class="form-check form-check-inline">
                    <!-- ✅ UBAH: ya → yes -->
                    <input class="form-check-input" type="radio" name="serumen" value="yes">
                    <label class="form-check-label">Ya</label>
                </div>
                <textarea class="form-control mt-2" name="serumen_ket" placeholder="Penjelasan. isi dengan tanda '-' jika tidak ada."></textarea>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6 mt-2">
                <label class="form-label">8. Infeksi Telinga</label><br>
                <div class="form-check form-check-inline mt-2">
                    <!-- ✅ UBAH: tidak → no -->
                    <input class="form-check-input" type="radio" name="infeksi_telinga" value="no" checked>
                    <label class="form-check-label">Tidak</label>
                </div>
                <div class="form-check form-check-inline">
                    <!-- ✅ UBAH: ya → yes -->
                    <input class="form-check-input" type="radio" name="infeksi_telinga" value="yes">
                    <label class="form-check-label">Ya</label>
                </div>
                <textarea class="form-control mt-2" name="infeksi_telinga_ket" placeholder="Penjelasan. isi dengan tanda '-' jika tidak ada."></textarea>
            </div>

            <div class="col-md-6 mt-2">
                <label class="form-label">9. Tajam Pendengaran</label><br>
                <div class="form-check form-check-inline mt-2">
                    <input class="form-check-input" type="radio" name="tajam_pendengaran" value="normal" checked>
                    <label class="form-check-label">Normal</label>
                </div>
                <div class="form-check form-check-inline">
                    <!-- ✅ UBAH: gangguan → impaired -->
                    <input class="form-check-input" type="radio" name="tajam_pendengaran" value="impaired">
                    <label class="form-check-label">Ada Gangguan</label>
                </div>
                <textarea class="form-control mt-2" name="tajam_pendengaran_ket" placeholder="Penjelasan. isi dengan tanda '-' jika tidak ada."></textarea>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-12 mt-2">
                <label class="form-label">10. Masalah Lainnya</label>
                <textarea class="form-control mt-2" name="pendengaran_lainnya" placeholder="Penjelasan. isi dengan tanda '-' jika tidak ada."></textarea>
            </div>
        </div>
    </div>
</div>