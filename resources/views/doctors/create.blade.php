@extends("layouts.main")

<x-sweet-alert2.required/>

@section('yield-css')
    <style>
        .signature-preview {
            max-width: 300px;
            max-height: 150px;
            margin-top: 10px;
            border: 2px dashed #4CAF50;
            padding: 10px;
            border-radius: 8px;
            background: #f0f8f0;
        }
        .signature-preview img {
            width: 100%;
            height: auto;
            display: block;
        }
    </style>
@endsection

@section('content')
    @include('components.content-title', ['active' => 'Create', 'menus' => ['Doctors', 'Create']])
    
    <div class="row">
        <div class="col-xl-12">
            <form action="{{ route('doctor.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <div class="card-header card-no-border pb-2">
                        <h4 class="card-title mb-0">{{ $title }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">
                                        Nama Dokter <span class="text-danger">*</span>
                                    </label>
                                    <input class="form-control @error('name') is-invalid @enderror"
                                           type="text"
                                           id="name"
                                           name="name"
                                           value="{{ old('name') }}"
                                           placeholder="Masukkan nama dokter"
                                           required>
                                    @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="is_active" class="form-label">Status</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               id="is_active" 
                                               name="is_active"
                                               value="1"
                                               {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Aktif
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="signature" class="form-label">
                                        Tanda Tangan <span class="text-muted">(Opsional)</span>
                                    </label>
                                    <input class="form-control @error('signature') is-invalid @enderror"
                                           type="file"
                                           id="signature"
                                           name="signature"
                                           accept="image/jpeg,image/jpg,image/png"
                                           onchange="previewSignature(event)">
                                    <small class="text-muted">Format: JPG, JPEG, PNG. Max: 2MB</small>
                                    @error('signature')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    
                                    <div id="signature-preview-container" style="display: none;">
                                        <label class="form-label mt-2">Preview:</label>
                                        <div class="signature-preview">
                                            <img id="signature-preview-img" src="" alt="Preview">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <a class="btn btn-secondary" href="{{ route('doctor') }}">
                            <i class="fa fa-arrow-left"></i> Kembali
                        </a>
                        <button class="btn btn-success btn-submit" type="submit">
                            <i class="fa fa-save"></i> Simpan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('yield-js')
    <x-sweet-alert2.handler/>
    <x-prevent-force-submit/>
    
    <script>
        function previewSignature(event) {
            const file = event.target.files[0];
            const previewContainer = document.getElementById('signature-preview-container');
            const previewImg = document.getElementById('signature-preview-img');
            
            if (file) {
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Format file harus JPG, JPEG, atau PNG');
                    event.target.value = '';
                    previewContainer.style.display = 'none';
                    return;
                }
                
                const maxSize = 2 * 1024 * 1024; 
                if (file.size > maxSize) {
                    alert('Ukuran file maksimal 2MB');
                    event.target.value = '';
                    previewContainer.style.display = 'none';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    previewContainer.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                previewContainer.style.display = 'none';
                previewImg.src = '';
            }
        }
    </script>
@endsection