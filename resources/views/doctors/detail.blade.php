@extends('layouts.main')

<x-sweet-alert2.required />

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

        .current-signature {
            max-width: 250px;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 8px;
            background: #f8f9fa;
        }

        .current-signature img {
            width: 100%;
            height: auto;
        }
    </style>
@endsection

@section('content')
    @include('components.content-title', ['active' => 'Detail', 'menus' => ['Doctors', 'Detail']])

    <div class="modal fade" id="modal-create" tabindex="-1" role="dialog" aria-labelledby="modal-create" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create Doctor Account</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <form action="{{ route('doctor.account.store') }}" method="POST">
                    <div class="modal-body">
                        @csrf
                        <div class="row custom-input">
                            <div class="col-lg-12">
                                <b>Buat akun untuk dokter ini:</b>
                                <div class="mb-3 mt-3">
                                    <input type="hidden" name="doctor" value="{{ $doctor->id }}" />
                                    <label class="form-label">Nama Dokter</label>
                                    <input class="form-control" type="text" value="{{ $doctor->name }}" disabled>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">
                                        Role / Spesialisasi <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('role') is-invalid @enderror" name="role"
                                        id="role" required>
                                        <option value="">-- Pilih Role --</option>
                                        <option value="Doktor">Doktor</option>
                                        <option value="Doktor Gigi">Doktor Gigi</option>
                                    </select>
                                    @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">
                                        Username akan digenerate otomatis sesuai role
                                    </small>
                                </div>


                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">
                            <i class="fa fa-close"></i> Close
                        </button>
                        <button class="btn btn-success btn-submit" type="submit">
                            <i class="fa fa-add"></i> Buat Akun
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <form action="{{ route('doctor.update', $doctor) }}" method="POST" enctype="multipart/form-data">
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
                                    <input class="form-control @error('name') is-invalid @enderror" type="text"
                                        id="name" name="name" value="{{ old('name', $doctor->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="is_active" class="form-label">Status</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                            value="1" {{ old('is_active', $doctor->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Aktif
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <hr>
                                <h5 class="mb-3">Tanda Tangan</h5>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Tanda Tangan Saat Ini</label>
                                    @if ($doctor->signature)
                                        <div class="current-signature">
                                            <img src="{{ asset('storage/' . $doctor->signature) }}" alt="Current Signature"
                                                onerror="this.onerror=null; this.src='{{ asset('images/no-image.png') }}'; this.parentElement.innerHTML='<p class=\'text-danger\'>Gambar tidak ditemukan</p>';">
                                        </div>
                                    @else
                                        <p class="text-muted">Belum ada tanda tangan</p>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="signature" class="form-label">
                                        Upload Tanda Tangan Baru <span class="text-muted">(Opsional)</span>
                                    </label>
                                    <input class="form-control @error('signature') is-invalid @enderror" type="file"
                                        id="signature-input" name="signature" accept="image/jpeg,image/jpg,image/png"
                                        onchange="previewNewSignature(event)">
                                    <small class="text-muted">Format: JPG, JPEG, PNG. Max: 2MB</small>
                                    @error('signature')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror

                                    <div id="new-signature-preview-container" style="display: none;">
                                        <label class="form-label mt-2">Preview Upload Baru:</label>
                                        <div class="signature-preview">
                                            <img id="new-signature-preview-img" src="" alt="Preview">
                                        </div>
                                        <button type="button" class="btn btn-sm btn-danger mt-2"
                                            onclick="clearPreview()">
                                            <i class="fa fa-times"></i> Hapus Preview
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <hr class="my-4">
                                <h5 class="mb-3">
                                    Manajemen Akun
                                </h5>

                                @if (empty($account))
                                    <div class="alert alert-warning">
                                        <i class="fa fa-exclamation-triangle"></i>
                                        Dokter ini belum memiliki akun login.
                                    </div>
                                    @hasrole('SuperVisor')
                                        <a data-uid="{{ $doctor->id }}" class="btn btn-info waves-effect waves-light"
                                            data-bs-toggle="modal" data-bs-target="#modal-create">
                                            <i class="fa fa-plus"></i> Buat Akun
                                        </a>
                                    @endhasrole
                                @else
                                    <div class="alert alert-success">
                                        <i class="fa fa-check-circle"></i>
                                        Dokter ini sudah memiliki akun login dengan role:
                                        <strong>{{ $account->role }}</strong>
                                    </div>
                                    <a data-uid="{{ $doctor->id }}"
                                        class="btn btn-secondary waves-effect waves-light btn-show" href="#">
                                        <i class="fa fa-search-plus"></i> Lihat Akun
                                    </a>
                                    @hasrole('SuperVisor')
                                        <a data-uid="{{ $doctor->id }}"
                                            class="btn btn-warning waves-effect waves-light btn-reset" href="#">
                                            <i class="fa fa-sync-alt"></i> Reset Password
                                        </a>
                                    @endhasrole
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <a class="btn btn-secondary" href="{{ route('doctor') }}">
                            <i class="fa fa-arrow-left"></i> Kembali
                        </a>
                        @hasrole('SuperVisor')
                            <button class="btn btn-success btn-submit" type="submit">
                                <i class="fa fa-save"></i> Simpan
                            </button>
                        @endhasrole
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
        function previewNewSignature(event) {
            const file = event.target.files[0];
            const previewContainer = document.getElementById('new-signature-preview-container');
            const previewImg = document.getElementById('new-signature-preview-img');
            
            if (file) {
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                if (!allowedTypes.includes(file.type)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Format Salah',
                        text: 'Format file harus JPG, JPEG, atau PNG'
                    });
                    event.target.value = '';
                    previewContainer.style.display = 'none';
                    return;
                }
                
                const maxSize = 2 * 1024 * 1024; 
                if (file.size > maxSize) {
                    Swal.fire({
                        icon: 'error',
                        title: 'File Terlalu Besar',
                        text: 'Ukuran file maksimal 2MB'
                    });
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
        
        function clearPreview() {
            document.getElementById('signature-input').value = '';
            document.getElementById('new-signature-preview-container').style.display = 'none';
            document.getElementById('new-signature-preview-img').src = '';
        }
        
        $(".btn-show").on("click", function (e) {
            e.preventDefault();
            const id = $(this).data("uid");
            
            $.ajax({
                url: "{{ route('doctor.account.show', ':id') }}".replace(":id", id),
                method: 'GET',
                success: function (data) {
                    let user = data.data;
                    let msg = "<div style='text-align: left; max-width: 400px; margin: 0 auto;'>" +
                        "<table class='table table-bordered'>" +
                        "<tr><td><strong>Username</strong></td><td>" + user.username + "</td></tr>" +
                        "<tr><td><strong>Password</strong></td><td>" + user.uncrypted + "</td></tr>" +
                        "<tr><td><strong>Role</strong></td><td>" + user.role_name + "</td></tr>" +
                        "</table></div>";
                    
                    Swal.fire({
                        title: "Informasi Akun",
                        html: msg,
                        icon: "success",
                        allowOutsideClick: false,
                        showCancelButton: false,
                        confirmButtonText: 'OK',
                        width: '500px'
                    });
                },
                error: function () {
                    Swal.fire({
                        title: "Gagal!",
                        html: "Gagal mengambil data akun",
                        icon: "error",
                        allowOutsideClick: false,
                        showCancelButton: false,
                    });
                }
            });
        });
        
        $(".btn-reset").on("click", function (e) {
            e.preventDefault();
            const id = $(this).data("uid");
            
            Swal.fire({
                title: 'Konfirmasi',
                text: "Apakah Anda yakin ingin mereset password dokter ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Reset!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('doctor.account.reset', ':id') }}".replace(":id", id),
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: 'Password berhasil direset',
                                icon: 'success'
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function () {
                            Swal.fire({
                                title: 'Gagal!',
                                text: 'Gagal mereset password',
                                icon: 'error'
                            });
                        }
                    });
                }
            });
        });
    </script>
@endsection
