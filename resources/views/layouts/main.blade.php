<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="https://my.sekolahteladan.sch.id/assets/images/logo/custom-logo-icon.png"
        type="image/x-icon">
    <link rel="shortcut icon" href="https://my.sekolahteladan.sch.id/assets/images/logo/custom-logo-icon.png"
        type="image/x-icon">
    <title>{{ $title . ' - ' . config('app.name') }}</title>
    <link href="{{ url('fonts/google_rubik.css') }}" rel="stylesheet">
    <link href="{{ url('fonts/google_roboto.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ url('css/fontawesome.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('css/vendors/icofont.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('css/vendors/themify.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('css/vendors/flag-icon.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('css/vendors/feather-icon.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('css/vendors/scrollbar.css') }}">"
    <link rel="stylesheet" type="text/css" href="{{ url('css/vendors/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('css/responsive.css') }}">
    <style>
        .form-control:focus {
            color: var(--bs-body-color);
            background-color: var(--bs-body-bg);
            border-color: #019114;
            outline: 0;
            box-shadow: 0 0 0 .25rem rgba(1, 145, 20, .25)
        }

        .icon {
            fill: none;
            stroke: currentColor;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .logo-icon-wrapper img {
            max-width: 50px;
            height: auto;
        }
    </style>
    @yield('yield-css')
    @stack('stack-css')
</head>

<body>
    <!-- loader starts-->
    <div class="loader-wrapper">
        <div class="loader-index"> <span></span></div>
        <svg>
            <defs></defs>
            <filter id="goo">
                <fegaussianblur in="SourceGraphic" stddeviation="11" result="blur"></fegaussianblur>
                <fecolormatrix in="blur" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 19 -9" result="goo">
                </fecolormatrix>
            </filter>
        </svg>
    </div>
    <!-- loader ends-->
    <!-- tap on top starts-->
    <div class="tap-top"><i data-feather="chevrons-up"></i></div>
    <!-- tap on tap ends-->
    <!-- page-wrapper Start-->
    <div class="page-wrapper compact-wrapper" id="pageWrapper">
        <!-- Page Header Start-->
        <div class="page-header">
            <div class="header-wrapper row m-0">
                <form class="form-inline search-full col" action="#" method="get">
                    <div class="form-group w-100">
                        <div class="Typeahead Typeahead--twitterUsers">
                            <div class="u-posRelative">
                                <input class="demo-input Typeahead-input form-control-plaintext w-100" type="text"
                                    placeholder="Search Anything Here..." name="q" title="" autofocus>
                                <div class="spinner-border Typeahead-spinner" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                                <i class="close-search" data-feather="x"></i>
                            </div>
                            <div class="Typeahead-menu"></div>
                        </div>
                    </div>
                </form>
                <div class="header-logo-wrapper col-auto p-0">
                    <div class="logo-wrapper">
                        <a href="{{ route('dashboard') }}">
                            <img class="img-fluid for-light" src="{{ url('images/logo/logo_besar.png') }}"
                                width="100" alt="">
                            <img class="img-fluid for-dark" src="{{ url('images/logo/logo_besar.png') }}"
                                width="100" alt="">
                        </a>
                    </div>
                    <div class="toggle-sidebar">
                        <i class="status_toggle middle sidebar-toggle" data-feather="align-center"></i>
                    </div>
                </div>
                <div class="nav-right col-xxl-7 col-xl-6 col-md-7 col-8 pull-right right-header p-0 ms-auto">
                    <ul class="nav-menus">
                        <li class="language-nav">
                            <div class="translate_wrapper">
                                <div class="current_lang">
                                    <div class="lang">
                                        <i class="flag-icon flag-icon-id"></i>
                                        <span class="lang-txt">ID</span>
                                    </div>
                                </div>
                                <div class="more_lang">
                                    <div class="lang selected" data-value="en">
                                        <i class="flag-icon flag-icon-us"></i>
                                        <span class="lang-txt">
                                            English<span> (US)</span>
                                        </span>
                                    </div>
                                    <div class="lang" data-value="id">
                                        <i class="flag-icon flag-icon-id"></i>
                                        <span class="lang-txt">Indonesia</span>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="fullscreen-body">
                            <span>
                                <svg id="maximize-screen">
                                    <use href="{{ url('images/icon-sprite.svg#full-screen') }}"></use>
                                </svg>
                            </span>
                        </li>
                        <li>
                            <div class="mode">
                                <svg>
                                    <use href="{{ url('images/icon-sprite.svg#moon') }}"></use>
                                </svg>
                            </div>
                        </li>
                        <li class="onhover-dropdown">
                            <div class="notification-box">
                                <svg>
                                    <use href="{{ url('images/icon-sprite.svg#notification') }}"></use>
                                </svg><span class="badge rounded-pill badge-success">4 </span>
                            </div>
                            <div class="onhover-show-div notification-dropdown">
                                <h6 class="f-18 mb-0 dropdown-title">
                                    Notifications
                                </h6>
                                <ul>
                                    <li class="b-l-primary border-4 toast default-show-toast align-items-center text-light border-0 fade show"
                                        aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
                                        <div class="d-flex justify-content-between">
                                            <div class="toast-body">
                                                <p>Delivery processing</p>
                                            </div>
                                            <button class="btn-close btn-close-white me-2 m-auto" type="button"
                                                data-bs-dismiss="toast" aria-label="Close"></button>
                                        </div>
                                    </li>
                                    <li class="b-l-success border-4 toast default-show-toast align-items-center text-light border-0 fade show"
                                        aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
                                        <div class="d-flex justify-content-between">
                                            <div class="toast-body">
                                                <p>Order Complete</p>
                                            </div>
                                            <button class="btn-close btn-close-white me-2 m-auto" type="button"
                                                data-bs-dismiss="toast" aria-label="Close"></button>
                                        </div>
                                    </li>
                                    <li class="b-l-secondary border-4 toast default-show-toast align-items-center text-light border-0 fade show"
                                        aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
                                        <div class="d-flex justify-content-between">
                                            <div class="toast-body">
                                                <p>Tickets Generated</p>
                                            </div>
                                            <button class="btn-close btn-close-white me-2 m-auto" type="button"
                                                data-bs-dismiss="toast" aria-label="Close"></button>
                                        </div>
                                    </li>
                                    <li class="b-l-warning border-4 toast default-show-toast align-items-center text-light border-0 fade show"
                                        aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
                                        <div class="d-flex justify-content-between">
                                            <div class="toast-body">
                                                <p>Delivery Complete</p>
                                            </div>
                                            <button class="btn-close btn-close-white me-2 m-auto" type="button"
                                                data-bs-dismiss="toast" aria-label="Close"></button>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="profile-nav onhover-dropdown pe-0 py-0">
                            <div class="d-flex profile-media">
                                <img class="b-r-10" src="{{ url('images/placeholder_profile.png') }}"
                                    alt="">
                                <div class="flex-grow-1">
                                    <span>{{ auth()->user()->name }}</span>
                                    <p class="mb-0">
                                        @foreach (auth()->user()->roles as $role)
                                            {{ $role->name . ', ' }}
                                        @endforeach
                                        <i class="middle fa-solid fa-angle-down"></i>
                                    </p>
                                </div>
                            </div>
                            <form id="logout-form" action="{{ route('auth.logout') }}" method="POST"
                                style="display: none;">
                                @csrf
                            </form>
                            <ul class="profile-dropdown onhover-show-div">
                                <li>
                                    <a href="{{ route('account') }}">
                                        <i data-feather="user"></i><span>Account </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" onclick="logOut()">
                                        <i data-feather="log-in"> </i><span>Log out</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Page Header Ends -->
        <!-- Page Body Start -->
        <div class="page-body-wrapper horizontal-menu">
            <!-- Page Sidebar Start -->
            <div class="sidebar-wrapper" data-sidebar-layout="stroke-svg">
                <div>
                    <div class="logo-wrapper">
                        <a href="{{ route('dashboard') }}">
                            <img class="img-fluid for-light" src="{{ url('images/logo/logo_besar.png') }}"
                                width="100" alt="">
                            <img class="img-fluid for-dark" src="{{ url('images/logo/logo_besar.png') }}"
                                width="100" alt="">
                        </a>
                        <div class="back-btn">
                            <i class="fa-solid fa-angle-left"></i>
                        </div>
                        <div class="toggle-sidebar">
                            <i class="status_toggle middle sidebar-toggle" data-feather="grid"></i>
                        </div>
                    </div>
                    <div class="logo-icon-wrapper">
                        <a href="{{ route('dashboard') }}">
                            <img class="img-fluid" src="{{ url('images/logo/logo-icon.png') }}" alt="">
                        </a>
                    </div>
                    <nav class="sidebar-main">
                        <div class="left-arrow" id="left-arrow">
                            <i data-feather="arrow-left"></i>
                        </div>
                        <div id="sidebar-menu">
                            @include('components.menu')
                        </div>
                        <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
                    </nav>
                </div>
            </div>
            <!-- Page Sidebar Ends-->
            <div class="page-body">
                <div class="container-fluid">
                    @yield('content')
                </div>
                <!-- Container-fluid Ends-->
            </div>
            <!-- footer start-->
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12 footer-copyright text-center">
                            <p class="mb-0">
                                <b>Sekolah Teladan Yogyakarta © <span class="year-update"> </span></b> - build by
                                <i>Sinai Teknologi Abadi</i>
                            </p>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="{{ url('js/jquery.min.js') }}"></script>
    <script src="{{ url('js/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ url('js/icons/feather-icon/feather.min.js') }}"></script>
    <script src="{{ url('js/icons/feather-icon/feather-icon.js') }}"></script>
    <script src="{{ url('js/config.js') }}"></script>
    <script src="{{ url('js/script.js') }}"></script>
    <script src="{{ url('js/script1.js') }}"></script>
    <!-- scrollbar js-->
    <script src="{{ url('js/scrollbar/simplebar.min.js') }}"></script>
    <script src="{{ url('js/scrollbar/custom.js') }}"></script>
    <!-- Plugins JS start-->
    <script src="{{ url('js/sidebar-menu.js') }}"></script>
    {{-- <script src="{{ url('js/form-wizard/form-wizard.js') }}"></script> --}}
    <script src="{{ url('js/hide-on-scroll.js') }}"></script>
    <script>
        function logOut() {
            document.getElementById('logout-form').submit();
        }
    </script>
    @stack('stack-js')
    @yield('yield-js')
    @yield('scripts')
</body>

</html>
