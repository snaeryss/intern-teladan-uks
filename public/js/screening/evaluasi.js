const ScreeningEvaluasiModule = {
    init() {
    },

    populateReviewData() {
        const form = document.getElementById('screening-form');
        if (!form) {
            return;
        }

        setTimeout(() => {
            this.populateBasicFormFields(form);
            this.populateNutritionalStatus();
            this.populateVitalSigns();
            this.populateEyeEarExamination();
            this.populateGeneralExamination();
            this.populateMouthExamination();
            this.populateHygieneExamination();
            this.populateConclusionData();
        }, 300); 
    },

    populateBasicFormFields(form) {
        const formData = new FormData(form);

        for (const [name, value] of formData.entries()) {
            const reviewElement = document.getElementById(`review-${name}`);

            if (reviewElement) {
                let displayValue = value.trim() === '' ? '-' : value;

                const radioInput = document.querySelector(`input[name="${name}"]:checked`);
                if (radioInput && radioInput.type === 'radio') {
                    const label = radioInput.nextElementSibling;
                    if (label && label.tagName === 'LABEL') {
                        displayValue = label.textContent.trim();
                    }
                }

                reviewElement.textContent = displayValue;
            }
        }
    },

    populateNutritionalStatus() {
        const beratBadan = document.getElementById('berat_badan')?.value || '-';
        const tinggiBadan = document.getElementById('tinggi_badan')?.value || '-';
        const imt = document.getElementById('imt')?.value || '-';
        const statusGizi = document.getElementById('status_gizi')?.value || '-';
        
        let anemia = '-';
        const anemiaRadio = document.querySelector('input[name="anemia"]:checked');
        const anemiRadio = document.querySelector('input[name="anemi"]:checked');
        
        if (anemiaRadio) {
            const label = anemiaRadio.nextElementSibling;
            anemia = label ? label.textContent.trim() : anemiaRadio.value;
        } else if (anemiRadio) {
            const label = anemiRadio.nextElementSibling;
            anemia = label ? label.textContent.trim() : anemiRadio.value;
        }

        this.setTextContent('review-berat_badan', beratBadan);
        this.setTextContent('review-tinggi_badan', tinggiBadan);
        this.setTextContent('review-imt', imt);
        this.setTextContent('review-status_gizi', statusGizi);
        this.setTextContent('review-anemia', anemia);
        this.setTextContent('review-anemi', anemia);

        const lingkarKepala = document.getElementById('lingkar_kepala')?.value;
        const lingkarLengan = document.getElementById('lingkar_lengan_atas')?.value;
        const lingkarPerut = document.getElementById('lingkar_perut')?.value;
        const bbU = document.getElementById('bb_u')?.value;
        const tbU = document.getElementById('tb_u')?.value;

        if (lingkarKepala) this.setTextContent('review-lingkar_kepala', lingkarKepala);
        if (lingkarLengan) this.setTextContent('review-lingkar_lengan_atas', lingkarLengan);
        if (lingkarPerut) this.setTextContent('review-lingkar_perut', lingkarPerut);
        if (bbU) this.setTextContent('review-bb_u', bbU);
        if (tbU) this.setTextContent('review-tb_u', tbU);
    },

    populateVitalSigns() {
        const sistolikInput = document.querySelector('input[name="tekanan_darah_sistolik"]');
        const diastolikInput = document.querySelector('input[name="tekanan_darah_diastolik"]');
        const nadiInput = document.querySelector('input[name="denyut_nadi"]');
        const nafasInput = document.querySelector('input[name="frekuensi_nafas"]');
        const suhuInput = document.querySelector('input[name="suhu"]');

        // Jika tidak ada field vital signs, skip (untuk DCTK/SD)
        if (!sistolikInput && !diastolikInput && !nadiInput && !nafasInput && !suhuInput) {
            return;
        }

        const sistolik = sistolikInput?.value?.trim();
        const diastolik = diastolikInput?.value?.trim();
        const nadi = nadiInput?.value?.trim();
        const nafas = nafasInput?.value?.trim();
        const suhu = suhuInput?.value?.trim();

        if (sistolik && diastolik) {
            this.setTextContent('review-tekanan_darah', `${sistolik}/${diastolik}`);
        } else if (sistolik || diastolik) {
            this.setTextContent('review-tekanan_darah', `${sistolik || '-'}/${diastolik || '-'}`);
        } else {
            this.setTextContent('review-tekanan_darah', '-');
        }

        this.setTextContent('review-denyut_nadi', nadi || '-');
        this.setTextContent('review-frekuensi_nafas', nafas || '-');
        this.setTextContent('review-suhu', suhu || '-');

        this.setRadioValue('bising_jantung');
        this.setRadioValue('bising_paru');
    },

    populateEyeEarExamination() {
        this.setRadioValue('mata_luar');
        this.setRadioValue('tajam_penglihatan');
        this.setRadioValue('kacamata');
        this.setRadioValue('infeksi_mata');
        this.setRadioValue('buta_warna');

        this.setInputValue('mata_luar_ket');
        this.setInputValue('tajam_penglihatan_ket');
        this.setInputValue('kacamata_ket');
        this.setInputValue('infeksi_mata_ket');
        this.setInputValue('penglihatan_lainnya');

        this.setRadioValue('telinga_luar');
        this.setRadioValue('serumen');
        this.setRadioValue('infeksi_telinga');
        this.setRadioValue('tajam_pendengaran');

        this.setInputValue('telinga_luar_ket');
        this.setInputValue('serumen_ket');
        this.setInputValue('infeksi_telinga_ket');
        this.setInputValue('tajam_pendengaran_ket');
        this.setInputValue('pendengaran_lainnya');
    },

    populateGeneralExamination() {
        const generalFields = [
            'mata', 'hidung', 'mulut', 'jantung', 'paru', 'neurologi',
            'general_rambut', 'general_kulit', 'general_kuku'
        ];

        generalFields.forEach(field => {
            this.setRadioValue(field);
            this.setInputValue(`${field}_ket`);
        });
    },


    populateMouthExamination() {
        const mouthFields = [
            'celah_bibir', 'luka_sudut_mulut', 'sariawan',
            'lidah_kotor', 'luka_lainnya', 'caries', 'gigi_depan'
        ];

        mouthFields.forEach(field => {
            this.setRadioValue(field);
        });

        this.setInputValue('mulut_lainnya');
        this.setInputValue('caries_ket');
        this.setInputValue('gigi_lainnya');
    },

    populateHygieneExamination() {
        const hygieneRambutInput = document.querySelector('input[name="hygiene_rambut"]');
        
        if (!hygieneRambutInput) {
            return;
        }

        const hygieneFields = [
            'hygiene_rambut', 'kulit_bercak', 'kulit_bersisik',
            'kulit_memar', 'kulit_sayatan', 'kulit_koreng',
            'luka_koreng_sukar', 'kulit_suntikan', 'hygiene_kuku'
        ];

        hygieneFields.forEach(field => {
            this.setRadioValue(field);
        });

        this.setInputValue('kulit_bercak_ket');
    },

    populateConclusionData() {
        const kesimpulanEl = document.querySelector('textarea[name="kesimpulan"]') || 
                            document.getElementById('kesimpulan') ||
                            document.querySelector('textarea[name="diagnosis"]') ||
                            document.getElementById('diagnosis');
        
        const saranEl = document.querySelector('textarea[name="saran"]') ||
                       document.getElementById('saran') ||
                       document.querySelector('textarea[name="treatment"]') ||
                       document.getElementById('treatment');
        
        const followUpEl = document.querySelector('textarea[name="follow_up"]') ||
                          document.getElementById('follow_up') ||
                          document.querySelector('textarea[name="notes"]') ||
                          document.getElementById('notes') ||
                          document.querySelector('textarea[name="catatan"]') ||
                          document.getElementById('catatan');

        const kesimpulan = kesimpulanEl?.value?.trim() || '-';
        const saran = saranEl?.value?.trim() || '-';
        const followUp = followUpEl?.value?.trim() || '-';

        this.setTextContent('review-kesimpulan', kesimpulan);
        this.setTextContent('review-diagnosis', kesimpulan);
        
        this.setTextContent('review-saran', saran);
        this.setTextContent('review-treatment', saran);
        
        this.setTextContent('review-follow_up', followUp);
        this.setTextContent('review-notes', followUp);
        this.setTextContent('review-catatan', followUp);
    },

    setTextContent(elementId, value) {
        const element = document.getElementById(elementId);
        if (element) {
            element.textContent = value || '-';
        }
    },

    setRadioValue(fieldName) {
        const radio = document.querySelector(`input[name="${fieldName}"]:checked`);
        const reviewElement = document.getElementById(`review-${fieldName}`);
        
        if (reviewElement) {
            if (radio) {
                const label = radio.nextElementSibling;
                const displayValue = label ? label.textContent.trim() : radio.value;
                reviewElement.textContent = displayValue;
            } else {
                reviewElement.textContent = '-';
            }
        }
    },

    setInputValue(fieldName) {
        const input = document.querySelector(`textarea[name="${fieldName}"]`) ||
                     document.getElementById(fieldName) ||
                     document.querySelector(`input[name="${fieldName}"]`);
        
        const reviewElement = document.getElementById(`review-${fieldName}`);
        
        if (reviewElement && input) {
            const value = input.value.trim();
            reviewElement.textContent = value || '-';
        }
    }
};


document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('screening-form')) {
        ScreeningEvaluasiModule.init();
        
        window.populateReviewData = () => ScreeningEvaluasiModule.populateReviewData();
        window.ScreeningEvaluasiModule = ScreeningEvaluasiModule;
    }
});