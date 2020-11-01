<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - {{ config('app.name', 'Laravel') }}</title>

    <!-- Favicon-->
    <link rel="icon" href="{{ asset('assets/backend/icon/favicon.ico') }}" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="{{ asset('assets/backend/css/fonts.googleapis.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/backend/css/fonts.googleapis.icon.css') }}" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="{{ asset('assets/backend/plugins/bootstrap/css/bootstrap.css') }}" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="{{ asset('assets/backend/plugins/node-waves/waves.css')}}" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="{{ asset('assets/backend/plugins/animate-css/animate.css') }}" rel="stylesheet" />

    <!-- Morris Chart Css-->
    <link href="{{ asset('assets/backend/plugins/morrisjs/morris.css') }}" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="{{ asset('assets/backend/css/style.css') }}" rel="stylesheet">

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="{{ asset('assets/backend/css/themes/all-themes.css') }}" rel="stylesheet" />

    <!--toastr-->
    <link href="{{ asset('assets/backend/toastr/toastr.min.css') }}" rel="stylesheet" />

    @stack('css')
    
</head>
<body class="theme-blue">
<!-- Page Loader -->
    <div class="page-loader-wrapper">
        <div class="loader">
            <div class="preloader">
                <div class="spinner-layer pl-red">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
            <p>Please wait...</p>
        </div>
    </div>
    <!-- #END# Page Loader -->
    <!-- Overlay For Sidebars -->
    <div class="overlay"></div>
    <!-- #END# Overlay For Sidebars -->
    <!-- Search Bar -->
    <div class="search-bar">
        <div class="search-icon">
            <i class="material-icons">search</i>
        </div>
        <input type="text" placeholder="START TYPING...">
        <div class="close-search">
            <i class="material-icons">close</i>
        </div>
    </div>
    <!-- #END# Search Bar -->
    <!-- Top Bar -->
        @include('layouts.backend.partial.topbar')
    <!-- #Top Bar -->
    <section>
        <!-- Left Sidebar -->
            @include('layouts.backend.partial.sidebar')
        <!-- #END# Left Sidebar -->
    </section>

    <section class="content">
        @yield('content')
    </section>


    <!-- Jquery Core Js -->
    <script src="{{ asset('assets/backend/plugins/jquery/jquery.min.js') }}"></script>

    <!-- Bootstrap Core Js -->
    <script src="{{ asset('assets/backend/plugins/bootstrap/js/bootstrap.js') }}"></script>

    <!-- Select Plugin Js -->
    <!-- <script src="{{ asset('assets/backend/plugins/bootstrap-select/js/bootstrap-select.js') }}"></script> -->

    <!-- Slimscroll Plugin Js -->
    <script src="{{ asset('assets/backend/plugins/jquery-slimscroll/jquery.slimscroll.js') }}"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="{{ asset('assets/backend/plugins/node-waves/waves.js') }}"></script>

    <!-- Custom Js -->
    <script src="{{ asset('assets/backend/js/admin.js') }}"></script>

    <!-- Demo Js -->
    <script src="{{ asset('assets/backend/js/demo.js') }}"></script>

    <!--toastr-->
    <script src="{{ asset('assets/backend/toastr/toastr.min.js') }}"></script>

      <!-- Toastr without composer code start -->
      @if(Session::has('success'))
        <script>
          toastr.success("{{ Session::get('success') }}", "success", {
            positionClass : "toast-top-right",
            closeButton: true,
            progressBar: true,
            timeOut : "3000",
          });
        </script>
      @endif
      @if(Session::has('info'))
        <script>
          toastr.info("{{ Session::get('info') }}");
        </script>
      @endif
      @if(Session::has('warning'))
        <script>
          toastr.warning("{{ Session::get('warning') }}");
        </script>
      @endif
      @if(Session::has('error'))
        <script>
          toastr.error("{{ Session::get('error') }}", "error",{
            positionClass : "toast-top-right",
            closeButton: true,
            progressBar: true,
            timeOut : "3000",
          });
        </script>
      @endif

      <!-- Toastr diye globaly error show korar code -->
      <script>
          @if($errors->any())
            @foreach($errors->all() as $error)
                toastr.error('{{ $error }}','Error',{
                    closeButton: true,
                    progressBar: true,
                });
            @endforeach
          @endif
      </script>
      <!-- Toastr diye globaly error show korar code end -->

      <!-- Toastr without composer code end -->

    @stack('js')
</body>
</html>
