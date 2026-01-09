@section('breadcrumb')
<x-breadcrumb titulo="Lista de Usuarios">
    <x-breadcrumb.item titulo="Inicio" route="inicio.index" separator />
    <x-breadcrumb.item titulo="Seguridad" separator />
    <x-breadcrumb.item titulo="Usuario" />
</x-breadcrumb>
@endsection

<div>

    @can('autorizacion', ['LISTAR', 'USUARIOS'])

        <livewire:seguridad.usuario.tabla lazy/>

        {{-- Modales --}}
        @include('livewire.seguridad.usuario.modal-usuario')
        @include('livewire.seguridad.usuario.modal-estado-usuario')
        @include('livewire.seguridad.usuario.modal-eliminar-usuario')

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
                        Menu de Usuarios Restringido
                    </h2>

                    <p class="text-muted fs-4 mb-6">
                        No cuentas con los permisos necesarios para visualizar el listado de carpetas académicas.
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

@script
<script>

    const inicializarSelect2 = (campo, placeholder = 'Abre esta selección', buscar = false, livewireProp = null) => {
        const minimoBusqueda = buscar ? 0 : Infinity;

        $(`.${campo}`)
            .select2({
                placeholder: placeholder,
                minimumResultsForSearch: minimoBusqueda,
                allowClear: true,
                width: '100%',
                dropdownParent: $('#modal-usuario'),
                language: {
                    errorLoading: () => 'No se pudieron encontrar los resultados',
                    loadingMore: () => 'Cargando más recursos...',
                    noResults: () => 'No hay resultado',
                    searching: () => 'Buscando...'
                }
            })
            .on('change', function () {
                const valor = $(this).val();
                const esRequerido = $(this).siblings().hasClass('required');
                const propiedad = livewireProp ?? campo;

                // Sincroniza con Livewire
                @this.set(propiedad, valor);

                // Efecto visual
                setTimeout(() => {
                    $(this).next('.select2-container').removeClass('select2-container--focus');
                }, 50);

                // Validación visual
                if ((!valor || valor.length === 0) && esRequerido) {
                    $(`.${campo}`).addClass('is-invalid').removeClass('is-valid');
                } else if (!valor || valor.length === 0) {
                    $(`.${campo}`).removeClass('is-valid');
                } else {
                    $(`.${campo}`).removeClass('is-invalid').addClass('is-valid');
                }


            })
            .on('select2:open', function () {
                $('.select2-results__options').addClass('custom-select2-results');
            });
    };

    const select2 = (campo, parametro, modal, multiple = false) => {
        const accion = @this.modo_modal; // 1 = Registrar, 2 = Modificar
        const $campo = $(`.${campo}`);

        if (accion === 1) {
            $campo.val(null).trigger('change');
        } else {
            const value = $campo.val();
            const esRequerido = $campo.siblings().hasClass('required');

            if ((!value || (Array.isArray(value) && value.length === 0)) && esRequerido) {
                $campo.addClass('is-invalid').removeClass('is-valid');
            } else if (!value || (Array.isArray(value) && value.length === 0)) {
                $campo.removeClass('is-valid');
            } else {
                $campo.removeClass('is-invalid').addClass('is-valid');
            }
        }

        $campo
            .prop('multiple', multiple)
            .select2({
                placeholder: 'Abre esta selección',
                minimumResultsForSearch: parametro,
                allowClear: true,
                dropdownParent: $(`#${modal}`).length ? $(`#${modal}`) : $(document.body),
                language: {
                    errorLoading: () => 'No se pudieron encontrar los resultados',
                    loadingMore: () => 'Cargando más recursos...',
                    noResults: () => 'No hay resultado',
                    searching: () => 'Buscando...'
                }
            })
            .on('change', function () {
                const value = $(this).val();
                const esRequerido = $(this).siblings().hasClass('required');
                @this.set(campo, value);

                setTimeout(() => {
                    $(this).next('.select2-container').removeClass('select2-container--focus');
                }, 50);

                if ((!value || (Array.isArray(value) && value.length === 0)) && esRequerido) {
                    $(`.${campo}`).addClass('is-invalid').removeClass('is-valid');
                } else if (!value || (Array.isArray(value) && value.length === 0)) {
                    $(`.${campo}`).removeClass('is-valid');
                } else {
                    $(`.${campo}`).removeClass('is-invalid').addClass('is-valid');
                }
            })
            .on('select2:open', function () {
                $('.select2-results__options').addClass('custom-select2-results');
            })
            .each(function () {
                $(this).next().find('.select2-selection--single').addClass(`form-select ${campo}`);
            });
    };

    $('#modal-usuario').on('shown.bs.modal', function () {
    inicializarSelect2('lista_persona', 'Abre esta selección', true, 'lista_persona');
    inicializarSelect2('lista_rol', 'Abre esta selección', true, 'lista_rol');

    $('#modal-usuario').on('hidden.bs.modal', function () {
    $('.lista_persona, .lista_rol')
        .removeClass('is-invalid is-valid')
        .val(null)
        .trigger('change');
    });

    $('.select2-selection')
        .removeClass('is-valid is-invalid');

});


    window.addEventListener('errores_validacion', (e) => {
        Object.keys(e.detail.validacion).forEach(function(clave) {
            $(`.${clave}`).addClass('is-invalid');
        });
    });

    window.addEventListener('autocompletarUsuario', (e) => {

    if (e.detail.lista_persona !== undefined) {
        $('.lista_persona')
            .val(e.detail.lista_persona)
            .trigger('change');
    }

    if (e.detail.lista_rol !== undefined) {
        $('.lista_rol')
            .val(e.detail.lista_rol)
            .trigger('change');
    }

});


</script>
@endscript





