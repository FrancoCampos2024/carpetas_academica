<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
		<meta property="og:locale" content="es_PE" />
		<meta property="og:type" content="article" />
		<meta property="og:url" content="{{ env('APP_URL') }}" />

        <title>{{ $title ?? 'Carpetas Academicas' }}</title>

        <link rel="shortcut icon" href="{{ asset('assets/media/logo-unia.webp') }}" />

		<link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />

        <style>
            :root {
                --bs-font-sans-serif: 'Inter', sans-serif;
                --bs-body-font-family: 'Inter', sans-serif;
                --bs-body-font-size: 0.875rem; /* 14px */
                --bs-body-line-height: 1.5;
            }

            body {
                font-family: 'Inter', sans-serif;
            }
        </style>

        <script>
            // Frame-busting to prevent site from being loaded within a frame without permission (click-jacking)
            if (window.top != window.self) {
                window.top.location.replace(window.self.location.href);
            }
        </script>
    </head>
    <body
        id="kt_app_body"
        data-kt-app-layout="light-sidebar"
        data-kt-app-header-fixed="true"
        data-kt-app-sidebar-enabled="true"
        data-kt-app-sidebar-fixed="true"
        data-kt-app-sidebar-hoverable="true"
        data-kt-app-sidebar-push-header="true"
        data-kt-app-sidebar-push-toolbar="true"
        data-kt-app-sidebar-push-footer="true"
        data-kt-app-toolbar-enabled="true"
        class="app-default"
    >
		<script>
            var defaultThemeMode = "light";
            var themeMode;
            if ( document.documentElement ) {
                if ( document.documentElement.hasAttribute("data-bs-theme-mode")) {
                    themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
                } else {
                    if ( localStorage.getItem("data-bs-theme") !== null ) {
                        themeMode = localStorage.getItem("data-bs-theme");
                    } else {
                        themeMode = defaultThemeMode;
                    }
                }
                if (themeMode === "system") {
                    themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
                }
                document.documentElement.setAttribute("data-bs-theme", themeMode);
            }
        </script>

		<div class="d-flex flex-column flex-root app-root" id="kt_app_root">
			<div class="app-page flex-column flex-column-fluid" id="kt_app_page">

				<div
                    id="kt_app_header"
                    class="app-header"
                    data-kt-sticky="true"
                    data-kt-sticky-activate="{default: true, lg: true}"
                    data-kt-sticky-name="app-header-minimize"
                    data-kt-sticky-offset="{default: '200px', lg: '0'}"
                    data-kt-sticky-animation="false"
                >
					<div class="app-container container-fluid d-flex align-items-stretch justify-content-between" id="kt_app_header_container">
						<div class="d-flex align-items-center d-lg-none ms-n3 me-1 me-md-2" title="Show sidebar menu">
							<div class="btn btn-icon btn-active-color-primary w-35px h-35px" id="kt_app_sidebar_mobile_toggle">
								<i class="ki-outline ki-abstract-14 fs-2 fs-md-1"></i>
							</div>
						</div>
						<div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0">
							<a href="index.html" class="d-lg-none">
								<img alt="Logo" src="{{ asset('assets/media/logo-unia.webp') }}" class="h-45px" />
							</a>
						</div>
						<div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1" id="kt_app_header_wrapper">
                            <div data-kt-swapper="true" data-kt-swapper-mode="{default: 'prepend', lg: 'prepend'}" data-kt-swapper-parent="{default: '#kt_app_content_container', lg: '#kt_app_header_wrapper'}" class="page-title d-flex flex-column justify-content-center flex-wrap me-3 mb-5 mb-lg-0 mt-4 mt-lg-0">
                                @yield('breadcrumb')
                            </div>
							<div class="app-navbar flex-shrink-0">
								<div class="app-navbar-item ms-1 ms-md-4">
									<a href="#" class="btn btn-icon btn-custom btn-icon-muted btn-active-light btn-active-color-primary w-35px h-35px" data-kt-menu-trigger="{default:'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
										<i class="ki-outline ki-night-day theme-light-show fs-1"></i>
										<i class="ki-outline ki-moon theme-dark-show fs-1"></i>
									</a>
									<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-title-gray-700 menu-icon-gray-500 menu-active-bg menu-state-color fw-semibold py-4 fs-base w-150px" data-kt-menu="true" data-kt-element="theme-mode-menu">
										<div class="menu-item px-3 my-0">
											<a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="light">
												<span class="menu-icon" data-kt-element="icon">
													<i class="ki-outline ki-night-day fs-2"></i>
												</span>
												<span class="menu-title">Light</span>
											</a>
										</div>
										<div class="menu-item px-3 my-0">
											<a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="dark">
												<span class="menu-icon" data-kt-element="icon">
													<i class="ki-outline ki-moon fs-2"></i>
												</span>
												<span class="menu-title">Dark</span>
											</a>
										</div>
										<div class="menu-item px-3 my-0">
											<a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="system">
												<span class="menu-icon" data-kt-element="icon">
													<i class="ki-outline ki-screen fs-2"></i>
												</span>
												<span class="menu-title">System</span>
											</a>
										</div>
									</div>
								</div>
								<div class="app-navbar-item ms-1 ms-md-4" id="kt_header_user_menu_toggle">
									<div class="cursor-pointer symbol symbol-35px" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
										<img src="{{ asset('assets/media/avatars/300-23.jpg') }}" class="rounded-3" alt="user" />
									</div>
									<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px" data-kt-menu="true">
										<div class="menu-item px-3">
											<div class="menu-content d-flex align-items-center px-3">
												<div class="symbol symbol-50px me-5">
													<img alt="Logo" src="{{ asset('assets/media/avatars/300-23.jpg') }}" />
												</div>
												<div class="d-flex flex-column">
													<div class="fw-bold d-flex align-items-center fs-5">
                                                        {{Str::title(strtolower(Auth::user()->nombre_usuario)) }}
                                                    </div>
													<a href="#" class="fw-semibold text-muted text-hover-primary fs-7">
                                                        {{ strtolower(Auth::user()->persona->correo_persona) }}
                                                    </a>
												</div>
											</div>
										</div>
										<div class="separator my-2"></div>
										<div class="menu-item px-5">
											<a href="#" class="menu-link px-5" id="btnLogout" >Cerrar sesión</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
                    <livewire:navegacion.menu />

					<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
						<div class="d-flex flex-column flex-column-fluid">
							<div id="kt_app_content" class="app-content flex-column-fluid">
								<div id="kt_app_content_container" class="app-container container-fluid">

                                    {{ $slot }}

								</div>
							</div>
						</div>

						<div id="kt_app_footer" class="app-footer">
							<div class="app-container container-fluid d-flex flex-column flex-md-row flex-center flex-md-stack py-3">
								<div class="text-gray-900 order-2 order-md-1">
									<span class="text-muted fw-semibold me-1">{{ date('Y') }}&copy;</span>
									<span class="text-gray-800 text-hover-primary">Universidad Nacional Intercultural de la Amazonía</span>
								</div>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>

		<div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
			<i class="ki-outline ki-arrow-up"></i>
		</div>

        <script data-navigate-track>

            document.getElementById('btnLogout').addEventListener('click', function(e) {
                e.preventDefault();

                fetch("{{ route('logout') }}", {
                    method: "POST"
                    , headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    }
                }).then(() => {
                    window.location.href = "{{ route('login') }}";
                });
            });

            document.addEventListener('livewire:navigated', () => {
                window.addEventListener('modal', event => {
                    $(event.detail.nombre).modal(event.detail.accion);
                });

                function resetearSelects() {
                    const selects = document.querySelectorAll(`.form-select`);

                    if (selects.length > 0) {
                        selects.forEach(select => {
                            select.classList.remove('is-invalid', 'is-valid');
                            $(select).val(null).trigger('change'); // ← Esto reinicia y Select2 muestra el placeholder automáticamente
                        });
                    }
                }

                function resetearSelectModal(modal) {
                    const selects = modal.querySelectorAll('.form-select');
                    const botones_clear = modal.querySelectorAll('.select2-selection__clear');

                    if (selects.length > 0) {
                        selects.forEach(select => {
                            select.classList.remove('is-invalid', 'is-valid');
                            $(select).val(null).trigger('change'); // ← Esto reinicia y Select2 muestra el placeholder automáticamente
                        });
                    }

                    // Eliminar botones "×" de clear manualmente si quedan
                    if (botones_clear.length > 0) {
                        botones_clear.forEach(btn => btn.remove());
                    }
                }

                window.addEventListener('reset_select', resetearSelects);

                document.querySelectorAll('.modal').forEach(modal => {
                    modal.addEventListener('hidden.bs.modal', function () {
                        resetearSelectModal(this);
                    });
                });

                window.addEventListener('toastr', event => {
                    toastr.options = {
                        "closeButton": event.detail.boton_cerrar,
                        "debug": false,
                        "newestOnTop": false,
                        "progressBar": event.detail.progreso_avance,
                        "positionClass": "toastr-"+event.detail.posicion_y+"-"+event.detail.posicion_x,
                        "preventDuplicates": false,
                        "onclick": null,
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": event.detail.duracion,
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
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
                });

            });

        </script>

        <script>
            window.__modo_ui__ = localStorage.getItem('data-bs-theme-mode') || 'light';
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // Escuchar clics de cambio de tema
                document.querySelectorAll('[data-kt-element="mode"]').forEach(item => {
                    item.addEventListener('click', () => {
                        const modo = item.getAttribute('data-kt-value') || 'light';
                        Livewire.dispatch('modo_ui_detectado', { modo });
                    });
                });


                window.addEventListener('swal', event => {
                    Swal.fire({
                        title: event.detail.titulo,
                        html: event.detail.texto,
                        icon: event.detail.icono,
                        iconHtml: event.detail.icono_html || null,
                        width: '450px',
                        showConfirmButton: event.detail.mostrar_confirmar || true,
                        confirmButtonText: event.detail.confirmButtonText || 'Aceptar',
                        showCancelButton: event.detail.mostrar_cancelar || false,
                        cancelButtonText: event.detail.cancelButtonText || 'Cancelar',
                        reverseButtons: event.detail.reverseButtons || false,
                        customClass: {
                            icon: 'border-0',
                            confirmButton: event.detail.clase_confirm_button || '',
                            cancelButton: event.detail.clase_cancel_button || ''
                        },
                        buttonsStyling: false,
                        allowOutsideClick: false, // No permitir clics fuera del modal
                    }).then((result) => {
                        if (result.isConfirmed && event.detail.onConfirmed?.event) {
                            Livewire.dispatch(event.detail.onConfirmed.event, event.detail.onConfirmed.data || {});
                        }
                    });
                });

            });

            document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(function (row) {
                row.addEventListener('click', function () {
                    const icon = this.querySelector('.toggle-icon');
                    icon.classList.toggle('bi-chevron-right');
                    icon.classList.toggle('bi-chevron-down');
                });
            });

        </script>


		<script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
		<script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
	</body>
</html>
