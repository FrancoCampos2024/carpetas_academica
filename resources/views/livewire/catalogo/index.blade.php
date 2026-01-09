@section('breadcrumb')
<x-breadcrumb titulo="Catalogo de tablas">
    <x-breadcrumb.item titulo="Inicio" route="inicio.index" separator />
    <x-breadcrumb.item titulo="Catalogo" />
</x-breadcrumb>
@endsection
<div x-data="{ id_tabla: null }">
    @can('autorizacion', ['LISTAR', 'CATALOGO'])
    <div class="app-container container-fluid">
        <div class="row g-5 g-md-7">
            <div class="col-xl-4">
                <livewire:catalogo.lista-padre />
            </div>
            <div class="col-xl-8" x-on:seleccionar-tabla.window="id_tabla = $event.detail.id_tabla">
                <div x-show="$wire.id_tabla || id_tabla" x-cloak x-transition>
                    <div x-show="$wire.id_tabla === id_tabla || ($wire.id_tabla && id_tabla === null)" x-cloak>
                        <livewire:catalogo.lista-hijos :id_tabla="$id_tabla" />
                    </div>
                    <div x-show="$wire.id_tabla !== id_tabla && id_tabla !== null" x-cloak>
                        {{-- <x-configuracion.catalogo.lista-hijos-placeholder /> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
        {{-- ESTADO SIN PERMISO CON ILUSTRACIÓN --}}
        <div class="container-fluid h-100">
            <div class="row align-items-center mt-12">
                <div class="col-lg-4 text-center">
                    <img
                        src="{{ asset('/media/ilustraciones/5.png') }}"
                        style="transform: scaleX(-1); width: 320px;"
                        class="mb-7"
                        alt="Acceso restringido"
                    >
                </div>

                <div class="col-lg-6">
                    <h2 class="fw-bolder text-dark mb-4">
                        Menu de Catalogo Restringido
                    </h2>

                    <p class="text-muted fs-4 mb-6">
                        No cuentas con los permisos necesarios para visualizar el listado de tablas catalogo.
                        Por favor, utiliza las opciones permitidas en el menú lateral.
                    </p>

                    <div class="d-flex align-items-center gap-3 text-muted fs-5">
                        <i class="ki-outline ki-shield-cross fs-2 text-danger"></i>
                        <span>Si crees que esto es un error, solicita autorización al administrador del sistema.</span>
                    </div>
                </div>
            </div>
        </div>
    @endcan

</div>
