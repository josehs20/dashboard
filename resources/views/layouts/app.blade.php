<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">
    {{-- <meta http-equiv="X-UA-Compatible" content="IE=edge"> --}}
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    {{-- <meta name="description" content="dashboard">
    <meta name="author" content=""> --}}


    <link rel="icon" type="imagem/png" href="{{asset('img/logo2.png')}}" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    {{-- <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet"> --}}

    <!-- Custom styles for this template-->
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
</head>

<body id="page-top" class="sidebar-toggled">

    @if (auth()->user())
        <div style="display: flex !important;" id="wrapper">

            @include('templates.sidebar')

            <div id="content-wrapper" class="d-flex flex-column">

                <div id="content">

                    @include('templates.topbar')

                    @yield('content')
                </div>
                <!-- Footer -->
                <footer class="sticky-footer bg-white">
                    <div class="container my-auto">
                        <div class="copyright text-center my-auto">
                            <span>&copy; Alltech Sistemas</span>
                        </div>
                    </div>
                </footer>
                <!-- End of Footer -->
            </div>
        </div>
    @else
        @yield('content')
    @endif


    <style>
        #back-to-top {
            position: fixed;
            bottom: 50px;
            right: 50px;
        }
    </style>

    @if (Request::segment(1) != 'dashboard' and Request::segment(1) != 'admin')
        <a onclick="top_button()" class="btn btn-outline-primary d-none" id="back-to-top"><i
                class="ri-arrow-up-fill"></i></a>
    @endif

</body>
<script src="{{ asset('js/alerts.js') }}" defer></script>
<!-- Bootstrap core JavaScript-->
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- Core plugin JavaScript-->
{{-- <script src="{{ asset('plugins/jquery-easing/jquery.easing.min.js') }}"></script> --}}

<!-- Custom scripts for all pages-->
{{-- <script src="{{ asset('js/sb-admin-2.min.js') }}"></script> --}}
<script src="{{ asset('js/sb-admin-2.js') }}"></script>



<script>
    //   topbar
    $("#sidebarToggleTop").on("click", function() {
        var nametopBar = document.getElementById("userDropdown");
        nametopBar.children[0].classList.toggle("d-none");
    });

    var doc = document.documentElement

    window.addEventListener('scroll', function() {
        let value = parseInt(100 * doc.scrollTop / (doc.scrollHeight - doc.clientHeight))
        if (value > 30) {
            document.getElementById('back-to-top').classList.remove('d-none')
        } else {
            document.getElementById('back-to-top').classList.add('d-none')
        }
    })

    function top_button() {
        window.scrollTo(0, 0);
    }
</script>

</html>
