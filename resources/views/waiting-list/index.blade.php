@extends('layouts.main')

@section('content')
    @include('components.content-title', [
        'active' => 'Pilih Jenjang',
        'menus' => ['Daftar Tunggu', 'Pilih Jenjang'],
    ])

    <div class="row">
        <div class="col-md-6 col-lg-3">
            <div class="card">
                <a href="{{ route('waiting-list.form', ['level' => 'dctk']) }}" class="stretched-link">
                    <div class="card-body text-center">
                        <img class="img-fluid" style="margin:20px;" width="200"
                             src="https://ppdb.sekolahteladan.sch.id/assets/img/logo_dc.png" alt="Logo Daycare/TK">
                    </div>
                </a>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card">
                <a href="{{ route('waiting-list.form', ['level' => 'sd']) }}" class="stretched-link">
                    <div class="card-body text-center">
                        <img class="img-fluid" style="margin:20px;" width="200"
                             src="https://ppdb.sekolahteladan.sch.id/assets/img/logo_sd.png" alt="Logo SD">
                    </div>
                </a>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card">
                <a href="{{ route('waiting-list.form', ['level' => 'smp']) }}" class="stretched-link">
                    <div class="card-body text-center">
                        <img class="img-fluid" style="margin:20px;" width="200"
                             src="https://ppdb.sekolahteladan.sch.id/assets/img/logo_smp.png" alt="Logo SMP">
                    </div>
                </a>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card">
                <a href="{{ route('waiting-list.form', ['level' => 'sma']) }}" class="stretched-link">
                    <div class="card-body text-center">
                        <img class="img-fluid" style="margin:20px;" width="200"
                             src="https://ppdb.sekolahteladan.sch.id/assets/img/logo_sma.png" alt="Logo SMA">
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection

@section('scripts-js')
    <x-sweet-alert2.handler />
@endsection