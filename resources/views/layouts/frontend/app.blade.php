<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - {{ config('app.name', 'Laravel') }}</title>

    <!-- Font -->

    <link href="{{ asset('assets/frontend/css/fonts.googleapis.css') }}" rel="stylesheet">


    <!-- Stylesheets -->

    <link href="{{ asset('assets/frontend/css/bootstrap.css') }}" rel="stylesheet">

    <link href="{{ asset('assets/frontend/css/swiper.css') }}" rel="stylesheet">

    <link href="{{ asset('assets/frontend/css/ionicons.css') }}" rel="stylesheet">

    <!--toastr-->
    <link href="{{ asset('assets/frontend/toastr/toastr.min.css') }}" rel="stylesheet" />

    @stack('css')
    
</head>
<body>
@include('layouts.frontend.partial.header')

    @yield('content')

@include('layouts.frontend.partial.footer')


<!-- SCIPTS -->

    <script src="{{ asset('assets/frontend/js/jquery-3.1.1.min.js') }}"></script>

    <script src="{{ asset('assets/frontend/js/tether.min.js') }}"></script>

    <script src="{{ asset('assets/frontend/js/bootstrap.js') }}"></script>

    <script src="{{ asset('assets/frontend/js/swiper.js') }}"></script>

    <script src="{{ asset('assets/frontend/js/scripts.js') }}"></script>

    <!--toastr-->
    <script src="{{ asset('assets/frontend/toastr/toastr.min.js') }}"></script>

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
