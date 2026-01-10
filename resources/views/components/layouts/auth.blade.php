<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>{{ $title ?? 'Login | Sistema Académico y Recursos Administrativos | SARA' }}</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('media/logo-unia.webp') }}" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
</head>

<body id="kt_body" class="app-blank">
    <div class="d-flex flex-column flex-root" id="kt_app_root">
        <!-- Authentication - Login -->
        <div class="d-flex flex-column flex-lg-row flex-column-fluid">
            <!--begin::Body-->
            <div class="d-flex flex-column flex-lg-row-fluid w-lg-50 p-10 order-2 order-lg-1">
                <!--begin::Form-->
                <div class="d-flex flex-center flex-column flex-lg-row-fluid">
                    <!--begin::Wrapper-->
                    {{ $slot }}
                    <!--end::Wrapper-->
                </div>
                <!--end::Form-->

            </div>
            <!--end::Body-->

            <!--begin::Aside-->
            <div class="d-flex flex-lg-row-fluid w-lg-50 bgi-size-cover bgi-position-center order-1 order-lg-2">
                <!--begin::Content-->
                <div class="d-flex flex-column flex-center py-7 py-lg-15 px-5 px-md-15 w-100 bg-primary">

                    <!--begin::Logo Unia -->
                    <img class="d-none d-lg-block mx-auto w-275px w-md-50 w-xl-450px mb-10 mb-lg-20" src="{{ asset('media/logo-unia.webp') }}" alt="Logo UNIA">
                    <!--end::Logo Unia-->

                    <!--begin::Title-->
                    <h1 class="d-none d-lg-block text-white fs-2qx fw-bolder text-center mb-7">
                        SGDA - DSA - UNIA
                    </h1>
                    <!--end::Title-->

                    <!--begin::Text-->
                    <div class="d-none d-lg-block fs-2 text-white fs-base text-center">
                        Descubre todas las funcionalidades que hemos diseñado para tu aprendizaje y crecimiento
                    </div>
                    <!--end::Text-->

                </div>
                <!--end::Content-->
            </div>
            <!--end::Aside-->
        </div>
    </div>

    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/custom/authentication/sign-in/general.js') }}"></script>

</body>
<script data-navigate-track>
    window.addEventListener('toastr', event => {
        toastr.options = {
            "closeButton": event.detail.boton_cerrar
            , "debug": false
            , "newestOnTop": false
            , "progressBar": event.detail.progreso_avance
            , "positionClass": "toastr-" + event.detail.posicion_y + "-" + event.detail.posicion_x
            , "preventDuplicates": false
            , "onclick": null
            , "showDuration": "300"
            , "hideDuration": "1000"
            , "timeOut": event.detail.duracion
            , "extendedTimeOut": "1000"
            , "showEasing": "swing"
            , "hideEasing": "linear"
            , "showMethod": "fadeIn"
            , "hideMethod": "fadeOut"
        };

        // Obtener el tipo de toastr del evento
        const tipo = event.detail.tipo;

        // Mostrar el toastr según el tipo
        switch (tipo) {
            case 'success':
                toastr.success(event.detail.mensaje, event.detail.titulo);
                break;
            case 'warning':
                toastr.warning(event.detail.mensaje, event.detail.titulo);
                break;
            case 'error':
                toastr.error(event.detail.mensaje, event.detail.titulo);
                break;
            case 'info':
                toastr.info(event.detail.mensaje, event.detail.titulo);
                break;
        }
    })
</script>
</html>
