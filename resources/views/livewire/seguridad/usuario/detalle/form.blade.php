@section('breadcrumb')
<x-breadcrumb titulo="Detalle usuario">
    <x-breadcrumb.item titulo="Inicio"/>
    <x-breadcrumb.item titulo="/"/>
    <x-breadcrumb.item titulo="Seguridad"/>
    <x-breadcrumb.item titulo="/"/>
    <x-breadcrumb.item titulo="Usuario"/>
</x-breadcrumb>
@endsection
<div>
    <div class="app-container container-fluid">
        <div class="row">
            <!-- Detalle usuario -->
            <div class="col-md-4 mb-sm-6 mb-xs-6 mb-6">
                <div class="card">
                    <div class="card-body">
                        <!-- Usuario y Rol -->
                        <div class="d-flex align-items-center gap-2">
                            <h5 class="mb-0 text-uppercase fw-bold">Usuario</h5>
                            <div class="badge badge-lg badge-light-primary d-inline">{{ $usuario_nombre_rol }}</div>
                        </div>
                        <p class="text-muted fs-5 mt-1">{{ Str::title(strtolower($persona_user)) }}</p>

                        <!-- DNI -->
                        <h5 class="mt-3 mb-0 text-uppercase">Dni</h5>
                        <p class="text-muted mt-1">{{ $documento }}</p>
                    </div>
                </div>
            </div>

            <!-- Seguridad y eventos -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <!-- Tabs -->
                        <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-8">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#seguridad_tab">
                                    Seguridad
                                </a>
                            </li>
                            <li class="nav-item ms-auto">

                                <a
                                    href="{{ route('seguridad.usuarios.index') }}"
                                    class="btn btn-light btn-active-light-primary px-4 px-md-6 me-2 me-md-4"
                                >
                                    <i class="ki-outline ki-arrow-left fs-4 px-0"></i>
                                    <span class="d-none d-md-inline">
                                        Regresar
                                    </span>
                                </a>
                            </li>
                        </ul>

                        <!-- Contenido de las pestañas -->
                        <div class="tab-content">
                            <!-- Seguridad -->
                            <div class="tab-pane fade show active" id="seguridad_tab" role="tabpanel">
                                <div class="card-body pt-0 pb-5">
                                    <div class="table-responsive">
                                        <table class="table align-middle table-row-dashed gy-5">
                                            <tbody class="fs-6 fw-semibold text-gray-600">
                                                <tr>
                                                    <td class="text-uppercase"><strong>Usuario</strong></td>
                                                    <td class="text-uppercase">{{ $nombre_user }}</td>
                                                    <td class="text-end">
                                                        <button
                                                            type="button"
                                                            class="btn btn-icon btn-active-light-primary w-30px h-30px ms-auto"
                                                            x-data="{ cargando: false }"
                                                            @click="cargando = true; $dispatch('cargar_modal_modificar_usuario')"
                                                            @cargando.window="cargando = false"
                                                            :disabled="cargando"
                                                        >
                                                        <template x-if="!cargando">
                                                            <i class="ki-outline ki-pencil fs-3"></i>
                                                        </template>
                                                        <template x-if="cargando">
                                                            <span>
                                                                <x-spinner style="width: 20px; height: 20px;"/>
                                                            </span>
                                                        </template>

                                                        </button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-uppercase fw-bold">Contraseña</td>
                                                    <td class="text-uppercase">	******</td>
                                                    <td class="text-end">
                                                        <button
                                                            type="button"
                                                            class="btn btn-icon btn-active-light-primary w-30px h-30px ms-auto"
                                                            x-data="{ cargando: false }"
                                                            @click="cargando = true; $dispatch('cargar_modal_modificar_contrasenha')"
                                                            @cargando.window="cargando = false"
                                                            :disabled="cargando"
                                                        >
                                                        <template x-if="!cargando">
                                                            <i class="ki-outline ki-pencil fs-3"></i>
                                                        </template>
                                                        <template x-if="cargando">
                                                            <span>
                                                                <x-spinner style="width: 20px; height: 20px;"/>
                                                            </span>
                                                        </template>

                                                        </button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-uppercase fw-bold">Rol</td>
                                                    <td class="text-uppercase">{{ $usuario_nombre_rol }}</td>
                                                    <td class="text-end">
                                                        <button
                                                            type="button"
                                                            class="btn btn-icon btn-active-light-primary w-30px h-30px ms-auto"
                                                            x-data="{ cargando: false }"
                                                            @click="cargando = true; $dispatch('cargar_modal_modificar_rol')"
                                                            @cargando.window="cargando = false"
                                                            :disabled="cargando"
                                                        >
                                                        <template x-if="!cargando">
                                                            <i class="ki-outline ki-pencil fs-3"></i>
                                                        </template>
                                                        <template x-if="cargando">
                                                            <span>
                                                                <x-spinner style="width: 20px; height: 20px;"/>
                                                            </span>
                                                        </template>

                                                        </button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

    @include('livewire.seguridad.usuario.detalle.modal-modificar-usuario')
    @include('livewire.seguridad.usuario.detalle.modal-modificar-contrasenha')
    @include('livewire.seguridad.usuario.detalle.modal-modificar-rol')

</div>

@script
<script>

    const select2 = (campo, parametro, modal, multiple = false, esBuscador = false) => {
        var accion = @this.modo_modal; // Modo de acción del formulario

        // Verificar si es Registrar o Modificar
        if (accion === 1) {
            $('.'+campo).val(null).trigger('change');
        }
        else{
            const value = $('.' + campo).val();
            const esRequerido = $('.' + campo).siblings().hasClass('required');

            if ((!value || (Array.isArray(value) && value.length === 0)) && esRequerido) {
                $('.' + campo).addClass('is-invalid').removeClass('is-valid');
            } else if (!value || (Array.isArray(value) && value.length === 0)) {
                $('.' + campo).removeClass('is-valid');
            } else {
                $('.' + campo).removeClass('is-invalid').addClass('is-valid');
            }
        }

        $(`.${campo}`)
            .prop('multiple', multiple)
            .select2({
                placeholder: 'Abre esta selección',
                minimumResultsForSearch: parametro,
                allowClear: true,
                dropdownParent:$(`#${modal}`).length ? $(`#${modal}`) : $(document.body),
                language: {
                    errorLoading: function () { return 'No se pudieron encontrar los resultados'; },
                    loadingMore: function () { return 'Cargando más recursos...'; },
                    noResults: function() { return "No hay resultado"; },
                    searching: function() { return "Buscando..."; }
                }
            }).on('change', function(){
                const value = $(this).val();
                const esRequerido = $(this).siblings().hasClass('required');

                @this.set(campo, value);
                setTimeout(() => { $(this).next('.select2-container').removeClass('select2-container--focus'); }, 50);

                if ((!value || (Array.isArray(value) && value.length === 0)) && esRequerido) {
                    $(`.${campo}`).addClass('is-invalid').removeClass('is-valid');
                } else if (!value || (Array.isArray(value) && value.length === 0)) {
                    $(`.${campo}`).removeClass('is-valid');
                } else {
                    $(`.${campo}`).removeClass('is-invalid').addClass('is-valid');
                }
            })
            .on('select2:open', function() {
                $('.select2-results__options').addClass('custom-select2-results');
            })
            .each(function () {
                $(this).next().find('.select2-selection--single').addClass(`form-select ${campo}`);
        });
    }

    document.addEventListener('livewire:initialized', () => {
        select2('lista_rol', 8, 'modal-modificar-rol');
    });

    window.addEventListener('autocompletado', (e) => {
        var datos = {
            lista_rol: @this.lista_rol
        };

        for (var clave in datos) {
            if (datos.hasOwnProperty(clave)) {
                var select2 = $('.'+clave);
                select2.val(datos[clave]);
                select2.trigger('change');
            }
        }
    });

    window.addEventListener('errores_validacion', (e) => {
        Object.keys(e.detail.validacion).forEach(function(clave) {
            $(`.${clave}`).addClass('is-invalid');
        });
    });

</script>
@endscript

