<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/png" href="{{ asset('public/img/favicon/favicon-96x96.png')}}" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="{{ asset('public/img/favicon/favicon.svg')}}" />
    <link rel="shortcut icon" href="{{ asset('public/img/favicon/favicon.ico')}}" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('public/img/favicon/apple-touch-icon.png')}}" />
    <meta name="msapplication-TileColor" content="#F0F0F0">
    <meta name="theme-color" content="##F0F0F0">
    <meta name="robots" content="noindex">

    @if (env('APP_DEV_STATUS') == 'development')
        <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
        <meta http-equiv="Pragma" content="no-cache" />
        <meta http-equiv="Expires" content="0" />
    @endif

    <link rel="canonical" href="#">

    <link rel="stylesheet" href="{{ asset('public/css/app.css?v=2.02') }}">
    <link rel="stylesheet" href="{{ asset('public/vendor/sencillo/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('public/vendor/sencillo/utilities.css') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Begin Sencillo Admin styles and CDN´s-->
    <link href="{{ asset('public/vendor/sencillo-panel/vendor/cssreset.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('public/vendor/sencillo-panel/css/app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;0,800;1,300;1,400;1,600;1,700;1,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- End Sencillo Admin -->

    <title>Sencillo Studio | Admin: @yield('title')</title>
</head>
<body id="@yield('body-id', '')" class="@yield('body-class', '')">
    
    {{ $slot }}

    <script type="text/javascript">
        var baseUrl = '{{ url("/") }}';
    </script>
    <script src="{{ asset('public/js/app.js?v=1') }}"></script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": false,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "1500",
            "hideDuration": "15000",
            "timeOut": "4000",
            "extendedTimeOut": "4000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }



        @if(Session::has('message'))
            toastr.success("{{ Session('message') }}");
        @endif

        @if(Session::has('error'))
            toastr.error("{{ Session('error') }}");
        @endif

        @if($errors->any())
            @foreach ($errors->all() as $error)
                toastr.error("{{ $error }}");
            @endforeach
        @endif
    </script>

    <!-- Begin Sencillo Panel Scripts -->
    <script src="{{ asset('public/vendor/sencillo-panel/js/app.js?v=1') }}"></script>

    <script>
      function toggleAside() {
        document.body.classList.toggle('collapsed');
        const button = document.querySelector('.aside-toggle-button');
        if (document.body.classList.contains('collapsed')) {
          button.innerHTML = '<i class="bi bi-list"></i>';
          button.style.right = '1rem';
          button.style.left = 'auto';
        } else {
          button.innerHTML = '<i class="bi bi-chevron-left"></i>';
          button.style.left = '12rem';
          button.style.right = 'auto';
        }
      }
    </script>
    <!-- End Sencillo Panel Scripts -->
    
    @stack('scripts')

</body>
</html>