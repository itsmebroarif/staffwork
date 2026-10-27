<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Daftar Akun - StaffWork</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('template/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('template/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <style>
        .bg-register-illustration {
            background-color: #f8f9fc;
            background-image: url('{{ asset('template/img/undraw_profile.svg') }}');
            background-position: center;
            background-repeat: no-repeat;
            background-size: 65%;
        }
        .captcha-container .captcha-img {
            cursor: pointer;
            background-color: #fff;
            min-height: 48px;
            display: inline-flex;
            align-items: center;
        }
        .captcha-container .captcha-img img {
            border-radius: 4px;
            max-height: 48px;
            width: auto;
            vertical-align: middle;
        }
    </style>
</head>

<body class="bg-gradient-primary d-flex align-items-center min-vh-100 py-4">

    <div class="container my-auto">

        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-3">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-5 d-none d-lg-block bg-register-illustration"></div>
                            <div class="col-lg-7">
                                <div class="p-4 p-md-5">
                                    <div class="text-center mb-4">
                                        <div class="sidebar-brand-icon rotate-n-15 text-primary mb-2" style="font-size: 2.2rem;">
                                            <i class="fas fa-user-plus"></i>
                                        </div>
                                        <h1 class="h4 text-gray-900 font-weight-bold">Buat Akun StaffWork</h1>
                                        <p class="text-muted small">Lengkapi data pendaftaran akun staf di bawah ini</p>
                                    </div>

                                    @if ($errors->any())
                                        <div class="alert alert-danger alert-dismissible fade show small" role="alert">
                                            <i class="fas fa-exclamation-triangle mr-1"></i> Mohon periksa kembali form pendaftaran Anda.
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    @endif

                                    <form class="user" method="POST" action="{{ route('register') }}">
                                        @csrf
                                        
                                        <!-- Nama -->
                                        <div class="form-group">
                                            <input type="text"
                                                name="name"
                                                class="form-control form-control-user @error('name') is-invalid @enderror"
                                                id="exampleName"
                                                value="{{ old('name') }}"
                                                placeholder="Nama Lengkap"
                                                required
                                                autofocus>
                                            @error('name')
                                                <div class="invalid-feedback ml-2">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Email -->
                                        <div class="form-group">
                                            <input type="email"
                                                name="email"
                                                class="form-control form-control-user @error('email') is-invalid @enderror"
                                                id="exampleInputEmail"
                                                value="{{ old('email') }}"
                                                placeholder="Alamat Email..."
                                                required>
                                            @error('email')
                                                <div class="invalid-feedback ml-2">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Password & Konfirmasi Password -->
                                        <div class="form-group row">
                                            <div class="col-sm-6 mb-3 mb-sm-0">
                                                <input type="password"
                                                    name="password"
                                                    class="form-control form-control-user @error('password') is-invalid @enderror"
                                                    id="exampleInputPassword"
                                                    placeholder="Kata Sandi (min. 6 karakter)"
                                                    required>
                                                @error('password')
                                                    <div class="invalid-feedback ml-2">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="password"
                                                    name="password_confirmation"
                                                    class="form-control form-control-user"
                                                    id="exampleRepeatPassword"
                                                    placeholder="Konfirmasi Kata Sandi"
                                                    required>
                                            </div>
                                        </div>

                                        <!-- Captcha -->
                                        <div class="form-group">
                                            <div class="d-flex align-items-center mb-2 captcha-container">
                                                <span class="captcha-img mr-2 rounded border overflow-hidden d-inline-block" title="Klik untuk memuat ulang captcha">
                                                    {!! captcha_img('flat') !!}
                                                </span>
                                                <button type="button" class="btn btn-outline-secondary btn-sm reload-captcha" title="Muat ulang kode captcha">
                                                    <i class="fas fa-sync-alt mr-1"></i> Refresh
                                                </button>
                                            </div>
                                            <input type="text"
                                                name="captcha"
                                                class="form-control form-control-user @error('captcha') is-invalid @enderror"
                                                id="exampleCaptcha"
                                                placeholder="Masukkan Kode Captcha di atas..."
                                                autocomplete="off"
                                                required>
                                            @error('captcha')
                                                <div class="invalid-feedback ml-2">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <button type="submit" class="btn btn-primary btn-user btn-block font-weight-bold">
                                            <i class="fas fa-user-check mr-1"></i> Daftarkan Akun
                                        </button>
                                    </form>

                                    <hr>

                                    <div class="text-center">
                                        <span class="small text-muted">Sudah memiliki akun?</span>
                                        <a class="small font-weight-bold ml-1" href="{{ route('login') }}">Masuk di sini!</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('template/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('template/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('template/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('template/js/sb-admin-2.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            function reloadCaptcha($container, $btn) {
                if ($btn) {
                    $btn.find('i').addClass('fa-spin');
                }
                $.ajax({
                    type: 'GET',
                    url: "{{ route('captcha.reload') }}",
                    success: function (data) {
                        $container.find('.captcha-img').html(data.captcha);
                    },
                    error: function () {
                        var $img = $container.find('.captcha-img img');
                        if ($img.length) {
                            var src = $img.attr('src').split('?')[0];
                            $img.attr('src', src + '?' + Math.random());
                        }
                    },
                    complete: function() {
                        if ($btn) {
                            $btn.find('i').removeClass('fa-spin');
                        }
                    }
                });
            }

            $('.reload-captcha').click(function () {
                var $btn = $(this);
                var $container = $btn.closest('.captcha-container');
                reloadCaptcha($container, $btn);
            });

            $(document).on('click', '.captcha-img img', function() {
                var $container = $(this).closest('.captcha-container');
                var $btn = $container.find('.reload-captcha');
                reloadCaptcha($container, $btn);
            });
        });
    </script>

</body>

</html>
