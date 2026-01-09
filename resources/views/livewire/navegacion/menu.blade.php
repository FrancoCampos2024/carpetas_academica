<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="225px" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">

    <div class="app-sidebar-logo px-6 mt-2" id="kt_app_sidebar_logo">
        <a href="{{ route('inicio.index') }}" class="m-auto">
            <img alt="Logo" src="{{ asset('assets/media/logo-unia.webp') }}" class="h-50px app-sidebar-logo-default" />
        </a>
    </div>

    <div class="app-sidebar-menu overflow-hidden flex-column-fluid">
        <div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper">
            <div id="kt_app_sidebar_menu_scroll" class="scroll-y my-5 mx-3" data-kt-scroll="true" data-kt-scroll-activate="{default: true, lg: false}" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_app_sidebar" data-kt-scroll-wrappers="#kt_app_sidebar_menu_wrapper" data-kt-scroll-offset="20px">

                <div class="menu menu-column menu-rounded menu-sub-indention fw-semibold fs-6" id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false">

                    <!-- INICIO -->
                    <div class="menu-item">
                        <a class="menu-link {{ request()->routeIs('inicio.index') ? 'active' : '' }}" href="{{ route('inicio.index') }}">
                            <span class="menu-icon">
                                <i class="ki-outline ki-home fs-2"></i>
                            </span>
                            <span class="menu-title">Inicio</span>
                        </a>
                    </div>

                    <!-- TÍTULO -->
                    <div class="menu-item pt-5">
                        <div class="menu-content">
                            <span class="menu-heading fw-bold text-uppercase fs-7">Opciones</span>
                        </div>
                    </div>

                    <!-- CARPETA -->
                    @can('autorizacion', ['LISTAR', 'CARPETAS'])
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('carpetas.index') ? 'active' : '' }}" href="{{ route('carpetas.index') }}">
                                <span class="menu-icon">
                                    <i class="ki-outline ki-folder fs-2"></i>
                                </span>
                                <span class="menu-title">Carpeta</span>
                            </a>
                        </div>
                    @endcan

                    @if(Gate::allows('autorizacion', ['LISTAR', 'USUARIOS']) ||
                        Gate::allows('autorizacion', ['LISTAR', 'ROLES']) ||
                        Gate::allows('autorizacion', ['LISTAR', 'PERSONAS']))

                        @php
                            // Verificamos si alguna de las rutas internas está activa
                            $seguridadActive = request()->routeIs('seguridad.usuarios.*') ||
                                            request()->routeIs('seguridad.roles.*') ||
                                            request()->routeIs('seguridad.personas.*');
                        @endphp

                        {{-- Añadimos 'here show' si estamos dentro de seguridad --}}
                        <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ $seguridadActive ? 'here show' : '' }}">
                            <span class="menu-link">
                                <span class="menu-icon">
                                    <i class="ki-outline ki-shield-tick fs-2"></i>
                                </span>
                                <span class="menu-title">Seguridad</span>
                                <span class="menu-arrow"></span>
                            </span>

                            <div class="menu-sub menu-sub-accordion">

                                @can('autorizacion', ['LISTAR', 'USUARIOS'])
                                <div class="menu-item">
                                    <a class="menu-link {{ request()->routeIs('seguridad.usuarios.*') ? 'active' : '' }}" href="{{ route('seguridad.usuarios.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Usuarios</span>
                                    </a>
                                </div>
                                @endcan

                                @can('autorizacion', ['LISTAR', 'ROLES'])
                                <div class="menu-item">
                                    <a class="menu-link {{ request()->routeIs('seguridad.roles.*') ? 'active' : '' }}" href="{{ route('seguridad.roles.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Roles</span>
                                    </a>
                                </div>
                                @endcan

                                @can('autorizacion', ['LISTAR', 'PERSONAS'])
                                <div class="menu-item">
                                    <a class="menu-link {{ request()->routeIs('seguridad.personas.*') ? 'active' : '' }}" href="{{ route('seguridad.personas.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Personas</span>
                                    </a>
                                </div>
                                @endcan

                            </div>
                        </div>
                    @endif



                    <!-- CATALOGO -->
                    @can('autorizacion', ['LISTAR', 'CATALOGO'])
                    <div class="menu-item">
                        <a class="menu-link {{ request()->routeIs('catalogos.index') ? 'active' : '' }}" href="{{ route('catalogos.index') }}">
                            <span class="menu-icon">
                                <i class="ki-outline ki-abstract-26 fs-2"></i>
                            </span>
                            <span class="menu-title">Catálogo</span>
                        </a>
                    </div>
                    @endcan

                    <!-- REPORTE -->
                    @can('autorizacion', ['LISTAR', 'REPORTE'])
                    <div class="menu-item">
                        <a class="menu-link {{ request()->routeIs('reportes.index') ? 'active' : '' }}" href="{{ route('reportes.index') }}">
                            <span class="menu-icon">
                                <i class="ki-outline ki-chart-line fs-2"></i>
                            </span>
                            <span class="menu-title">Reporte</span>
                        </a>
                    </div>
                    @endcan

                </div>

            </div>
        </div>
    </div>

</div>

