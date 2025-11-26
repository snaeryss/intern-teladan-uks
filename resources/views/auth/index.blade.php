<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="https://my.sekolahteladan.sch.id/assets/images/logo/custom-logo-icon.png"
          type="image/x-icon">
    <link rel="shortcut icon" href="https://my.sekolahteladan.sch.id/assets/images/logo/custom-logo-icon.png"
          type="image/x-icon">
    <title>
        {{ $title . " - " . config('app.name') }}
    </title>
    <!-- Google font-->
    <link href="{{ url('fonts/google_rubik.css') }}" rel="stylesheet">
    <link href="{{ url('fonts/google_roboto.css') }}" rel="stylesheet">
    <!-- Font Awesome-->
    <link rel="stylesheet" type="text/css" href="{{ url('css/fontawesome.css') }}">
    <!-- ico-font-->
    <link rel="stylesheet" type="text/css" href="{{ url('css/vendors/icofont.css') }}">
    <!-- Themify icon-->
    <link rel="stylesheet" type="text/css" href="{{ url('css/vendors/themify.css') }}">
    <!-- Flag icon-->
    <link rel="stylesheet" type="text/css" href="{{ url('css/vendors/flag-icon.css') }}">
    <!-- Feather icon-->
    <link rel="stylesheet" type="text/css" href="{{ url('css/vendors/feather-icon.css') }}">
    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="{{ url('css/vendors/bootstrap.css') }}">
    <!-- App css-->
    <link rel="stylesheet" type="text/css" href="{{ url('css/style.css') }}">
    <link id="color" rel="stylesheet" href="{{ url('css/color-1.css') }}" media="screen">
    <!-- Responsive css-->
    <link rel="stylesheet" type="text/css" href="{{ url('css/responsive.css') }}">
    @stack('stack-css')
    <x-sweet-alert2.required/>
</head>
<body>
<!-- login page start-->
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-7">
            <img class="bg-img-cover bg-center"
                 src="https://my.sekolahteladan.sch.id/assets/images/login/2.jpg" alt="looginpage">
        </div>
        <div class="col-xl-5 p-0">
            <div class="login-card login-dark">
                <div>
                    <div>
                        <a class="logo text-start" href="{{ url('') }}">
                            <img class="img-fluid for-light"
                                 src="{{ url('images/logo/logo_besar.png') }}"
                                 style="height:100px;" alt="looginpage">
                            <img class="img-fluid for-dark"
                                 src="{{ url('images/logo/logo_besar.png') }}"
                                 style="height:100px;" alt="looginpage">
                        </a>
                    </div>
                    <div class="login-main">
                        <form class="theme-form"
                              action="{{ route('auth.login') }}"
                              method="POST">
                            @csrf
                            <h4>Login Teladan UKS</h4>
                            <p>Masukan Username dan Password</p>
                            <div class="form-group">
                                <label class="col-form-label">
                                    Username
                                </label>
                                <input class="form-control"
                                       type="text"
                                       name="username"
                                       value="{{ session('username') ?? '' }}"
                                       required
                                       placeholder="username...">
                            </div>
                            <div class="form-group">
                                <label class="col-form-label">
                                    Password
                                </label>
                                <div class="form-input position-relative">
                                    <input class="form-control"
                                           type="password"
                                           name="password"
                                           required=""
                                           placeholder="*********">
                                    <div class="show-hide">
                                        <span class="show"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-0">
                                <button class="btn btn-primary btn-block w-100" type="submit">
                                    <i class="fa fa-sign-in-alt"></i> Login
                                </button>
                            </div>
                            <h6 class="text-muted mt-4 or">
                                Lupa Password?
                            </h6>
                            <p class="mt-4 mb-0 text-center">
                                Hubungi <b><i>{place_holder}</i></b> untuk mendapatkan<br/><a class="ms-2" href="#">password baru</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ url('js/jquery.min.js') }}"></script>
    <script src="{{ url('js/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ url('js/icons/feather-icon/feather.min.js') }}"></script>
    <script src="{{ url('js/icons/feather-icon/feather-icon.js') }}"></script>
    <script src="{{ url('js/config.js') }}"></script>
    <script src="{{ url('js/script.js') }}"></script>
    <script src="{{ url('js/script1.js') }}"></script>
    @stack('stack-js')
    <x-sweet-alert2.handler/>
</div>
</body>
</html>
