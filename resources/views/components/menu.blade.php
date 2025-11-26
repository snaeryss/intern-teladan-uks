<ul class="sidebar-links" id="simple-bar">
    <li class="back-btn">
        <div class="mobile-back text-end">
            <span>Back</span>
            <i class="fa-solid fa-angle-right ps-2" aria-hidden="true"></i>
        </div>
    </li>
    <li class="pin-title sidebar-main-title">
        <div>
            <h6>Pinned</h6>
        </div>
    </li>
    <li class="sidebar-list">
        <i class="fa-solid fa-thumbtack" style="visibility: {{ Route::is('dashboard') ? 'visible' : 'hidden' }}"></i>
        <a class="sidebar-link sidebar-title link-nav {{ Route::is('dashboard') ? 'active' : '' }}"
            href="{{ route('dashboard') }}">
            <svg class="stroke-icon">
                <use href="{{ url('images/icon-sprite.svg#stroke-home') }}"></use>
            </svg>
            <svg class="fill-icon">
                <use href="{{ url('images/icon-sprite.svg#fill-home') }}"></use>
            </svg>
            <span>Dashboard</span>
        </a>
    </li>
    @hasanyrole('Doktor|Doktor Gigi')
        <li class="sidebar-list">
            <i class="fa-solid fa-thumbtack"
                style="visibility: {{ Route::is('medical-record.all') ? 'visible' : 'hidden' }}"></i>
            <a class="sidebar-link sidebar-title link-nav {{ Route::is('medical-record.all') ? 'active' : '' }}"
                href="{{ route('medical-record.all') }}">
                <svg class="stroke-icon">
                    <use href="{{ url('images/icon-sprite.svg#stroke-task') }}"></use>
                </svg>
                <svg class="fill-icon">
                    <use href="{{ url('images/icon-sprite.svg#fill-task') }}"></use>
                </svg>
                <span>Daftar Tunggu</span>
            </a>
        </li>
        <li class="sidebar-list">
            <i class="fa-solid fa-thumbtack"
                style="visibility: {{ Route::is('record-histories*') ? 'visible' : 'hidden' }}"></i>
            <a class="sidebar-link sidebar-title link-nav {{ Route::is('record-histories*') ? 'active' : '' }}"
                href="{{ route('record-histories.index') }}">
                <svg class="stroke-icon">
                    <use href="{{ url('images/icon-sprite.svg#stroke-file') }}"></use>
                </svg>
                <svg class="fill-icon">
                    <use href="{{ url('images/icon-sprite.svg#fill-file') }}"></use>
                </svg>
                <span>Riwayat Pemeriksaan</span>
            </a>
        </li>
    @endhasrole
    @hasanyrole('SuperVisor|Perawat UKS')
     <li class="sidebar-list">
            <i class="fa-solid fa-thumbtack"
                style="visibility: {{ Route::is('waiting-list*', 'medical-record*', 'screening*', 'dcu*') ? 'visible' : 'hidden' }}"></i>
            <a
                class="sidebar-link sidebar-title {{ Route::is('waiting-list*', 'medical-record*', 'screening*', 'dcu*') ? 'active' : '' }}">
                <svg class="stroke-icon">
                    <use href="{{ url('images/icon-sprite.svg#stroke-to-do') }}"></use>
                </svg>
                <svg class="fill-icon">
                    <use href="{{ url('images/icon-sprite.svg#fill-to-do') }}"></use>
                </svg>
                <span>Daftar Tunggu</span>
                <div class="according-menu">
                    <i
                        class="fa-solid {{ Route::is('waiting-list*', 'medical-record*', 'screening*', 'dcu*') ? 'fa-angle-down' : 'fa-angle-right' }}"></i>
                </div>
            </a>
            <ul class="sidebar-submenu"
                style="{{ Route::is('waiting-list*', 'medical-record*', 'screening*', 'dcu*') ? 'display:block;' : 'display:none;' }}">
                <li>
                    <a href="{{ route('waiting-list.index') }}"
                        class="{{ Route::is('waiting-list.index') ? 'active' : '' }}">
                        Daftarkan Siswa
                    </a>
                </li>
                <li>
                    <a href="{{ route('medical-record.all') }}"
                        class="{{ Route::is('medical-record.all') ? 'active' : '' }}">
                        Semua Daftar Tunggu
                    </a>
                </li>
            </ul>
        </li>
    @endhasrole
    @hasrole('SuperVisor')
        <li class="sidebar-list">
            <i class="fa-solid fa-thumbtack"
                style="visibility: {{ Route::is('record-histories*') ? 'visible' : 'hidden' }}"></i>
            <a class="sidebar-link sidebar-title link-nav {{ Route::is('record-histories*') ? 'active' : '' }}"
                href="{{ route('record-histories.index') }}">
                <svg class="stroke-icon">
                    <use href="{{ url('images/icon-sprite.svg#stroke-file') }}"></use>
                </svg>
                <svg class="fill-icon">
                    <use href="{{ url('images/icon-sprite.svg#fill-file') }}"></use>
                </svg>
                <span>Riwayat Pemeriksaan</span>
            </a>
        </li>
        <li class="sidebar-list">
            <i class="fa-solid fa-thumbtack"
                style="visibility: {{ Route::is('visits') || Route::is('visits.*') ? 'visible' : 'hidden' }}"></i>
            <a class="sidebar-link sidebar-title link-nav {{ Route::is('visits') || Route::is('visits.*') ? 'active' : '' }}"
                href="{{ route('visits.index') }}">
                <svg class="stroke-icon">
                    <use href="{{ url('images/icon-sprite.svg#stroke-calendar') }}"></use>
                </svg>
                <svg class="fill-icon">
                    <use href="{{ url('images/icon-sprite.svg#fill-calender') }}"></use>
                </svg>
                <span>Kunjungan</span>
            </a>
        </li>
        <li class="sidebar-list">
            <i class="fa-solid fa-thumbtack"
                style="visibility: {{ Route::is('reports') || Route::is('reports.*') ? 'visible' : 'hidden' }}"></i>
            <a class="sidebar-link sidebar-title link-nav {{ Route::is('reports') || Route::is('reports.*') ? 'active' : '' }}"
                href="{{ route('reports') }}">
                <svg class="stroke-icon">
                    <use href="{{ url('images/icon-sprite.svg#stroke-charts') }}"></use>
                </svg>
                <svg class="fill-icon">
                    <use href="{{ url('images/icon-sprite.svg#fill-charts') }}"></use>
                </svg>
                <span>Laporan</span>
            </a>
        </li>
        <li class="sidebar-list">
            <i class="fa-solid fa-thumbtack"
                style="visibility: {{ Route::is('print-documents.*') ? 'visible' : 'hidden' }}"></i>

            <a class="sidebar-link sidebar-title {{ Route::is('print-documents.*') ? 'active' : '' }}">
                <svg class="stroke-icon">
                    <use href="{{ url('images/icon-sprite.svg#stroke-file') }}"></use>
                </svg>
                <svg class="fill-icon">
                    <use href="{{ url('images/icon-sprite.svg#fill-file') }}"></use>
                </svg>
                <span>Cetak Dokumen</span>
                <div class="according-menu">
                    <i class="fa-solid {{ Route::is('print-documents.*') ? 'fa-angle-down' : 'fa-angle-right' }}"></i>
                </div>
            </a>
            <ul class="sidebar-submenu" style="{{ Route::is('print-documents.*') ? 'display:block;' : 'display:none;' }}">
                <li>
                    <a href="{{ route('print-documents.mcu') }}"
                        class="{{ Route::is('print-documents.mcu') ? 'active' : '' }}">
                        MCU
                    </a>
                </li>
                <li>
                    <a href="{{ route('print-documents.dcu') }}"
                        class="{{ Route::is('print-documents.dcu') ? 'active' : '' }}">
                        DCU
                    </a>
                </li>
                <li>
                    <a href="{{ route('print-documents.visits') }}"
                        class="{{ Route::is('print-documents.visits') ? 'active' : '' }}">
                        Kunjungan
                    </a>
                </li>
            </ul>
        </li>
        <li class="sidebar-main-title">
            <div>
                <h6>Master Data</h6>
            </div>
        </li>
        <li class="sidebar-list">
            <i class="fa-solid fa-thumbtack"
                style="visibility: {{ Route::is('student') || Route::is('student.*') ? 'visible' : 'hidden' }}"></i>
            <a class="sidebar-link sidebar-title {{ Route::is('student') || Route::is('student.*') ? 'active' : '' }}">
                <svg class="stroke-icon">
                    <use href="{{ url('images/icon-sprite.svg#stroke-user') }}"></use>
                </svg>
                <svg class="fill-icon">
                    <use href="{{ url('images/icon-sprite.svg#fill-user') }}"></use>
                </svg>
                <span>Peserta Didik</span>
                <div class="according-menu">
                    <i
                        class="fa-solid {{ Route::is('student') || Route::is('student.*') ? 'fa-angle-down' : 'fa-angle-right' }}"></i>
                </div>
            </a>
            <ul class="sidebar-submenu"
                style="{{ Route::is('student') || Route::is('student.*') ? 'display:block;' : 'display:none;' }}">
                <li>
                    <a href="{{ route('student') }}" class="{{ Route::is('student') ? 'active' : '' }}">
                        Semua
                    </a>
                </li>
                <li>
                    <a href="{{ route('student.classes') }}"
                        class="{{ Route::is('student.classes') || Route::is('student.classes.*') ? 'active' : '' }}">
                        Kelas
                    </a>
                </li>
            </ul>
        </li>
        <li class="sidebar-list">
            <i class="fa-solid fa-thumbtack"
                style="visibility: {{ Route::is('doctor') || Route::is('doctor.*') ? 'visible' : 'hidden' }}"></i>
            <a class="sidebar-link sidebar-title link-nav {{ Route::is('doctor') || Route::is('doctor.*') ? 'active' : '' }}"
                href="{{ route('doctor') }}">
                <svg class="stroke-icon">
                    <use href="{{ url('images/icon-sprite.svg#stroke-user') }}"></use>
                </svg>
                <svg class="fill-icon">
                    <use href="{{ url('images/icon-sprite.svg#fill-user') }}"></use>
                </svg>
                <span>Dokter</span>
            </a>
        </li>
        <li class="sidebar-list">
            <i class="fa-solid fa-thumbtack"
                style="visibility: {{ Route::is('academic-year') ? 'visible' : 'hidden' }}"></i>
            <a @class([
                'sidebar-link',
                'sidebar-title',
                'link-nav',
                'active' => Route::is('academic-year'),
            ]) href="{{ route('academic-year') }}">
                <svg class="stroke-icon">
                    <use href="{{ url('images/icon-sprite.svg#stroke-calendar') }}"></use>
                </svg>
                <svg class="fill-icon">
                    <use href="{{ url('images/icon-sprite.svg#fill-calender') }}"></use>
                </svg>
                <span>Tahun Akademik</span>
            </a>
        </li>
        <li class="sidebar-list">
                <i class="fa-solid fa-thumbtack"
                    style="visibility: {{ Route::is('dental-diagnoses*') ? 'visible' : 'hidden' }}"></i>
                <a class="sidebar-link sidebar-title link-nav {{ Route::is('dental-diagnoses*') ? 'active' : '' }}"
                    href="{{ route('dental-diagnoses.index') }}">
                    <svg class="stroke-icon">
                        <use href="{{ url('images/icon-sprite.svg#stroke-form') }}"></use>
                    </svg>
                    <svg class="fill-icon">
                        <use href="{{ url('images/icon-sprite.svg#fill-form') }}"></use>
                    </svg>
                    <span>Diagnosis Gigi</span>
                </a>
            </li>

        <li class="sidebar-list">
            <i class="fa-solid fa-thumbtack"
                style="visibility: {{ Route::is('sync-students') || Route::is('sync-classes') ? 'visible' : 'hidden' }}"></i>
            <a
                class="sidebar-link sidebar-title link-nav {{ Route::is('sync-students') || Route::is('sync-classes') ? 'active' : '' }}">
                <svg class="stroke-icon">
                    <use href="{{ url('images/icon-sprite.svg#stroke-social') }}"></use>
                </svg>
                <svg class="fill-icon">
                    <use href="{{ url('images/icon-sprite.svg#fill-social') }}"></use>
                </svg>
                <span>Sinkronasi</span>
                <div class="according-menu">
                    <i class="fa-solid fa-angle-right"></i>
                </div>
            </a>
            <ul class="sidebar-submenu"
                style="{{ Route::is('sync-students') || Route::is('sync-classes') ? 'display:block;' : 'display:none;' }}">
                <li>
                    <a href="{{ route('sync-students') }}" class="{{ Route::is('sync-students') ? 'active' : '' }}">
                        Siswa
                    </a>
                </li>
                <li>
                    <a href="{{ route('sync-classes') }}" class="{{ Route::is('sync-classes') ? 'active' : '' }}">
                        Kelas
                    </a>
                </li>
            </ul>
        </li>
        <li class="sidebar-list">
            <i class="fa-solid fa-thumbtack"
                style="visibility: {{ Route::is('manage-account') ? 'visible' : 'hidden' }}"></i>
            <a class="sidebar-link sidebar-title link-nav {{ Route::is('manage-account') || Route::is('manage-account.*') ? 'active' : '' }}"
                href="{{ route('manage-account') }}">
                <svg class="stroke-icon">
                    <use href="{{ url('images/icon-sprite.svg#stroke-authenticate') }}"></use>
                </svg>
                <svg class="fill-icon">
                    <use href="{{ url('images/icon-sprite.svg#fill-authenticate') }}"></use>
                </svg>
                <span>Manajemen Akun</span>
            </a>
        </li>
    @endhasrole
    @hasrole('SuperVisor')
        <li class="sidebar-main-title">
            <div>
                <h6>SuperVisor</h6>
            </div>
        </li>
        <li class="sidebar-list">
            <i class="fa-solid fa-thumbtack" style="visibility: {{ Route::is('locations') ? 'visible' : 'hidden' }}"></i>
            <a class="sidebar-link sidebar-title link-nav {{ Route::is('locations') ? 'active' : '' }}"
                href="{{ route('locations') }}">
                <svg class="stroke-icon">
                    <use href="/images/icon-sprite.svg#stroke-maps"></use>
                </svg>
                <svg class="fill-icon">
                    <use href="/images/icon-sprite.svg#fill-maps"></use>
                </svg>
                <span>Manajemen Lokasi</span>
            </a>
        </li>
        <li class="sidebar-list">
            <i class="fa-solid fa-thumbtack" style="visibility: {{ Route::is('roles') ? 'visible' : 'hidden' }}"></i>
            <a class="sidebar-link sidebar-title link-nav {{ Route::is('roles') ? 'active' : '' }}"
                href="{{ route('roles') }}">
                <svg class="stroke-icon">
                    <use href="/images/icon-sprite.svg#stroke-api"></use>
                </svg>
                <svg class="fill-icon">
                    <use href="/images/icon-sprite.svg#fill-api"></use>
                </svg>
                <span>Roles</span>
            </a>
        </li>
        <li class="sidebar-list">
            <i class="fa-solid fa-thumbtack" style="visibility: {{ Route::is('artisan') ? 'visible' : 'hidden' }}"></i>
            <a class="sidebar-link sidebar-title link-nav {{ Route::is('artisan') ? 'active' : '' }}"
                href="{{ route('artisan') }}">
                <svg class="stroke-icon">
                    <use href="/images/icon-sprite.svg#stroke-starter-kit"></use>
                </svg>
                <svg class="fill-icon">
                    <use href="/images/icon-sprite.svg#fill-starter-kit"></use>
                </svg>
                <span>Artisan</span>
            </a>
        </li>
    @endhasrole
</ul>
