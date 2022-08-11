<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@if( isset($setting->title)) {{ $setting->title }} @endif </title>
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon_1.ico') }}">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->


    <script src="{{ asset('assets/js/lib/jquery/jquery-3.2.1.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/lib/bootstrap-sweetalert/sweetalert.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/css/separate/vendor/sweet-alert-animations.min.css')}}">


    <link rel="stylesheet" href="{{ asset('assets/css/lib/font-awesome/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/lib/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/lib/datatables-net/datatables.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/css/separate/vendor/datatables-net.min.css')}}">
</head>

<body class="with-side-menu">
<input type="hidden" id="delete_link" value="{{url('delete')}}" >
<header class="site-header">
    <div class="container-fluid">
        <a href="#" class="site-logo">
            <img class="hidden-md-down" src="{{ asset('assets/img/logo-2.png') }}" alt="">
            <img class="hidden-lg-down" src="{{ asset('assets/img/logo-2-mob.png') }}" alt="">
        </a>

        <button id="show-hide-sidebar-toggle" class="show-hide-sidebar">
            <span>toggle menu</span>
        </button>

        <button class="hamburger hamburger--htla">
            <span>toggle menu</span>
        </button>
        <div class="site-header-content">
            <div class="site-header-content-in">
                <div class="site-header-shown">

                    <div class="dropdown user-menu">
                        <button class="dropdown-toggle" id="dd-user-menu" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="{{ asset('assets/img/avatar-2-64.png') }}" alt="">
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dd-user-menu">
                            <a onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="dropdown-item" href="#"><span class="font-icon glyphicon glyphicon-log-out"></span>Logout</a>
                        </div>
                    </div>

                    <button type="button" class="burger-right">
                        <i class="font-icon-menu-addl"></i>
                    </button>
                </div>

            </div>
        </div>
    </div>
</header>

<div class="mobile-menu-left-overlay"></div>

    @include('layouts.left_nav')

<div class="page-content">
    @yield('content')
</div>

<div style=" top: 0px; bottom: 0px; left: 0px; position: fixed; width: 100%; z-index: 999999; display: none; background: rgba(0,0,0,0.5);" id="loading">
    <div style="margin: 20% 45%; text-align: center;">
        <img src="{!! asset('assets/img/loader1.gif') !!}" alt=""  class="loading"><br />
        <span style="color: mintcream;"> Processing...</span>
    </div>
</div>
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>


<script src="{{ asset('assets/js/lib/popper/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/lib/tether/tether.min.js') }}"></script>
<script src="{{ asset('assets/js/lib/bootstrap/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins.js') }}"></script>

<script src="{{ asset('assets/js/app.js')}}"></script>

<script src="{{ asset('assets/js/lib/datatables-net/datatables.min.js')}}"></script>
<script src="{{ asset('assets/js/lib/bootstrap-sweetalert/sweetalert.min.js')}}"></script>
<script>
    /* ajax post setup for csrf token */
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var base_url = '<?= url('/') ?>';
</script>
@yield('script')

<script src="{{ asset('assets/js/common.js')}}"></script>

<script>
    $(document).ready(function () {
        @if (session('success'))
            swal('success', '{{ session('success') }}', 'success');
        @endif

        @if (session('error'))
            swal('Error', '{{ session('error') }}', 'error');
        @endif
    })
</script>

</body>
</html>
