<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<!-- Mirrored from coderthemes.com/adminto/layouts/auth-login.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 27 Apr 2023 09:46:42 GMT -->

<head>
    <meta charset="utf-8" />
    <title>{{ 'Login Simakda Akuntansi' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('template/assets/images/favicon.ico') }}">

    <!-- App css -->

    <link href="{{ asset('template/assets/css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-style" />

    <!-- icons -->
    <link href="{{ asset('template/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />

</head>

<body class="loading authentication-bg authentication-bg-pattern">

    <div class="account-pages my-5">
        <div class="container">

            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-4">
                    <div class="card">
                        <div class="card-body p-4">

                            <div class="text-center mb-3">
                                <h4 class="text-uppercase mt-0">Login Aplikasi SIMAKDA AKUNTANSI</h4>
                                @if ($errors->any())
                                <div class="alert alert-danger" role="alert">
                                    {{ $errors->first() }}
                                </div>
                                @endif
                            </div>

                            <form method="POST" action="{{ route('login.index') }}">
                                @csrf
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan Username" required>
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required>
                                </div>

                                <div class="d-grid text-center">
                                    <button class="btn btn-primary" type="submit"> Login </button>
                                </div>
                            </form>

                        </div> <!-- end card-body -->
                    </div>
                    <!-- end card -->

                </div> <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end page -->

    <!-- Vendor -->
    <script src="{{ asset('template/assets/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('template/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('template/assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('template/assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('template/assets/libs/waypoints/lib/jquery.waypoints.min.js') }}"></script>
    <script src="{{ asset('template/assets/libs/jquery.counterup/jquery.counterup.min.js') }}"></script>
    <script src="{{ asset('template/assets/libs/feather-icons/feather.min.js') }}"></script>

    <!-- App js -->
    <script src="{{ asset('template/assets/js/app.min.js') }}"></script>

</body>

<!-- Mirrored from coderthemes.com/adminto/layouts/auth-login.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 27 Apr 2023 09:46:42 GMT -->

</html>