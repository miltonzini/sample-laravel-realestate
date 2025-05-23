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
        <meta name="robots" content="noindex">
        <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
        <meta http-equiv="Pragma" content="no-cache" />
        <meta http-equiv="Expires" content="0" />
    @endif

    <link rel="canonical" href="#">

    <link rel="stylesheet" href="{{ asset('public/css/app.css') . $assetVersionQueryString }}">
    <link rel="stylesheet" href="{{ asset('public/vendor/sencillo/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('public/vendor/sencillo/utilities.css') }}">
    <link rel="stylesheet" href="{{ asset('public/vendor/lightbox2/dist/css/lightbox.min.css') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    
    <title>Sample Real Estate | @yield('title')</title>
</head>
<body id="@yield('body-id', '')" class="@yield('body-class', '')">
    
    {{ $slot }}

    <!-- whatsapp floating icon -->
    <a class="floating-icon" href="https://wa.me/5491100000000?text=¡Hola!,%20quisiera%20hacer%20una%20consulta" target="_blank">
        <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M17.932 3.05293C16.9649 2.08126 15.8132 1.31091 14.544 0.786781C13.2749 0.26265 11.9136 -0.00478309 10.5395 6.47461e-05C4.78195 6.47461e-05 0.0896534 4.66993 0.0843797 10.4029C0.0843797 12.2391 0.566926 14.0254 1.47796 15.607L0 21L5.54269 19.5536C7.07603 20.3843 8.79385 20.8197 10.5395 20.8202H10.5448C16.3037 20.8202 20.9947 16.1503 21 10.4121C21.0013 9.04453 20.7308 7.69022 20.204 6.4272C19.6772 5.16417 18.9059 4.01739 17.932 3.05293ZM10.5395 19.0588C8.98248 19.0593 7.45403 18.6422 6.11489 17.8513L5.79847 17.6623L2.5103 18.5207L3.38837 15.3274L3.1827 14.998C2.31228 13.6203 1.85193 12.0252 1.85503 10.3977C1.85503 5.63855 5.75364 1.75618 10.5448 1.75618C11.6862 1.75415 12.8168 1.97704 13.8713 2.41203C14.9257 2.84701 15.8833 3.48548 16.6887 4.29061C17.497 5.09261 18.1377 6.04598 18.574 7.09572C19.0103 8.14546 19.2334 9.27081 19.2307 10.4068C19.2254 15.183 15.3268 19.0588 10.5395 19.0588ZM15.3057 12.583C15.046 12.453 13.7631 11.8243 13.5218 11.7351C13.2819 11.6498 13.1065 11.6052 12.9351 11.865C12.7598 12.1236 12.2588 12.7129 12.1085 12.8822C11.9582 13.0568 11.8026 13.0765 11.5416 12.9478C11.2818 12.8166 10.4393 12.5436 9.44261 11.655C8.66474 10.966 8.14396 10.1128 7.98838 9.85428C7.83808 9.59441 7.97388 9.45529 8.1044 9.32535C8.21911 9.20985 8.36414 9.02085 8.49466 8.87122C8.6265 8.7216 8.67001 8.61135 8.75571 8.4381C8.84141 8.26223 8.80054 8.1126 8.73593 7.98266C8.67001 7.85273 8.14923 6.57042 7.92905 6.0533C7.7181 5.54273 7.5032 5.61361 7.34235 5.60705C7.19205 5.59786 7.0167 5.59786 6.84135 5.59786C6.70893 5.60114 6.57862 5.63163 6.4586 5.68741C6.33858 5.7432 6.23143 5.82308 6.1439 5.92205C5.90394 6.18192 5.23286 6.81061 5.23286 8.09291C5.23286 9.37522 6.16895 10.6077 6.30079 10.7822C6.43 10.9568 8.13868 13.5805 10.761 14.7092C11.3807 14.9783 11.8685 15.1371 12.2496 15.2578C12.8758 15.4573 13.4414 15.4271 13.8923 15.3628C14.3933 15.2867 15.4362 14.7328 15.6564 14.1251C15.8726 13.5161 15.8726 12.9964 15.8067 12.8875C15.7421 12.7772 15.5667 12.7129 15.3057 12.583Z" fill="#FFFFFF"/>
        </svg>
    </a>

    <script type="text/javascript">
        var baseUrl = '{{ url("/") }}';
    </script>
    <script src="{{ asset('public/js/app.js') . $assetVersionQueryString }}"></script>
      
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
    <script>
        function showSendingMessageToast() {
            toastr.info('Enviando mensaje...');
        }
        function showLoggingInMessageToast() {
            toastr.info('Iniciando sesión...');
        }
    </script>

    @stack('scripts')

</body>
</html>