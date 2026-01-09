@section('breadcrumb')
<x-breadcrumb titulo="Gestionar Carpeta">
    <x-breadcrumb.item titulo="Inicio" route="inicio.index" separator />
    <x-breadcrumb.item titulo="Carpeta" />
</x-breadcrumb>
@endsection

<div>
    @can('autorizacion', ['LISTAR', 'GESTION CARPETA'])
        <div class="row">
            @include('livewire.documento.card-informacion-alumno')

            <div class="col-lg-8 col-md-12">

                @include('livewire.documento.tabla-documentos-unicos-alumno')

                @include('livewire.documento.tabla-documentos-no-unicos-alumno')

            </div>

            @include('livewire.documento.modal-guardar-documento')
            @include('livewire.documento.modal-eliminar-documento')

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
                        Gestion Carpeta Restringido
                    </h2>

                    <p class="text-muted fs-4 mb-6">
                        No cuentas con los permisos necesarios para visualizar el listado de documentos académicos.
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

