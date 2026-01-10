@section('breadcrumb')
<x-breadcrumb titulo="Inicio del Sistema">
    <x-breadcrumb.item titulo="Inicio" />
</x-breadcrumb>
@endsection

<div class="container-fluid h-100">

        @if ($puedeVerInicio)

            <div class="card shadow-sm mb-8">
                <div class="card-body p-6">
                    <div class="w-100" style="max-width: 980px;">

                        <h4 class="fw-bold text-dark mb-5 d-flex align-items-center">
                            <i class="ki-outline ki-search fs-2 me-2 text-primary"></i>
                            Búsqueda rápida de documentos
                        </h4>

                        <form wire:submit.prevent="buscar">
                            <div class="row g-4 align-items-end">

                                {{-- ESTUDIANTE --}}
                                <div class="col-12 col-lg-6 d-grid align-self-start">
                                    <div class="form-floating" wire:ignore>
                                        <select class="form-select lista_estudiante" id="lista_estudiante"></select>
                                        <label for="lista_estudiante" class="required">Estudiante</label>
                                    </div>

                                    @error('id_estudiante')
                                        <div class="invalid-feedback d-block">Ingresar estudiante</div>
                                    @enderror
                                </div>

                                {{-- TIPO DOCUMENTO --}}
                                <div class="col-12 col-lg-4 d-grid align-self-start">
                                    <div class="form-floating" wire:ignore>
                                        <select class="form-select lista_tipo_documento" id="lista_tipo_documento">
                                            <option></option>
                                            @foreach ($this->tiposDocumentos as $tipo)
                                                <option value="{{ $tipo->id_catalogo }}">
                                                    {{ $tipo->descripcion_catalogo }}
                                                    {{ $tipo->unico_catalogo ? '(Único)' : '(Múltiple)' }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <label for="lista_tipo_documento" class="required">Tipo Documento</label>
                                    </div>

                                    @error('id_tipo_documento')
                                        <div class="invalid-feedback d-block">Ingresar el tipo de documento</div>
                                    @enderror
                                </div>

                                {{-- BOTÓN --}}
                                <div class="col-12 col-lg-2 d-grid align-self-start">
                                    <button type="submit" class="btn btn-primary" style="height: calc(3.6rem + 2px);">
                                        Buscar
                                    </button>
                                </div>

                            </div>
                        </form>

                    </div>
                </div>
            </div>

            {{-- RESULTADOS --}}
            @if ($busquedaRealizada)
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="fw-bold mb-4">Resultados</h5>

                        @if (empty($documentos) || count($documentos) === 0)
                            <div x-data="{ cargado: false }" x-init="cargado = true">
                                <template x-if="cargado">
                                    <x-blank-state-table mensaje="No se encontraron registros"/>
                                </template>
                            </div>
                        @else
                            <table class="table table-row-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">N°</th>
                                        <th class="text-center">Fecha</th>
                                        <th class="text-center">Ver documento</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($documentos as $doc)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td class="text-center">{{ optional($doc->au_fechacr)->format('d/m/Y') }}</td>
                                            <td class="text-center">
                                                <a
                                                    href="{{ route('archivos.ver', [
                                                        'disco' => encriptar(4),
                                                        'id_documento_hash' => encriptar($doc->id_documento)
                                                    ]) }}"
                                                    target="_blank"
                                                    class="btn btn-sm btn-icon btn-active-light-primary"
                                                    data-bs-toggle="tooltip"
                                                    title="Ver documento"
                                                >
                                                    <i class="ki-outline ki-eye fs-1 text-muted"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            @endif

            {{-- ESTADO INICIAL --}}
            @if (!$busquedaRealizada)
                <div class="row align-items-center mt-12">
                    <div class="col-lg-4 text-center">
                        <img
                            src="{{ asset('/media/ilustraciones/5.png') }}"
                            style="transform: scaleX(-1); width: 320px;"
                            class="mb-7"
                            alt="Inicio"
                        >
                    </div>

                    <div class="col-lg-6">
                        <h2 class="fw-bolder text-dark mb-4">
                            Bienvenido al sistema de Carpeta Académica
                        </h2>

                        <p class="text-muted fs-4 mb-6">
                            Usa el buscador superior para localizar documentos de un estudiante
                            por tipo de documento, código, DNI o nombre.
                        </p>

                        <div class="d-flex align-items-center gap-3 text-muted fs-5">
                            <i class="ki-outline ki-arrow-up fs-2 text-primary"></i>
                            <span>Empieza buscando arriba o navega desde el menú</span>
                        </div>
                    </div>
                </div>
            @endif

        @else

            {{-- SIN PERMISO: SOLO MENSAJE --}}
            <div class="row align-items-center mt-12">
                <div class="col-lg-4 text-center">
                    <img
                        src="{{ asset('/media/ilustraciones/5.png') }}"
                        style="transform: scaleX(-1); width: 320px;"
                        class="mb-7"
                        alt="Inicio"
                    >
                </div>

                <div class="col-lg-6">
                    <h2 class="fw-bolder text-dark mb-4">
                        Empieza navegando desde el menú
                    </h2>

                    <p class="text-muted fs-4 mb-6">
                        No tienes permisos para usar la búsqueda rápida en el inicio.
                        Navega por los módulos disponibles desde el menú lateral.
                    </p>

                    <div class="d-flex align-items-center gap-3 text-muted fs-5">
                        <i class="ki-outline ki-information-5 fs-2 text-primary"></i>
                        <span>Si necesitas acceso, solicita autorización al administrador</span>
                    </div>
                </div>
            </div>

        @endif

    </div>


@script
<script>
    const inicializarSelect2Estudiante = () => {
    const $select = $('.lista_estudiante');

    if ($select.hasClass('select2-hidden-accessible')) {
        $select.select2('destroy');
    }

    $select.select2({
        placeholder: 'Buscar por código, DNI o nombre',
        minimumInputLength: 2,
        allowClear: true,
        width: '100%',
        language: {
            inputTooShort: () => 'Ingrese al menos 2 caracteres para buscar',
            noResults: () => 'No se encontraron estudiantes',
            searching: () => 'Buscando estudiantes…',
            loadingMore: () => 'Cargando más resultados…',
            errorLoading: () => 'Error al cargar los resultados'
        },
        ajax: {
            delay: 300,
            transport: function (params, success, failure) {

                @this.call('buscarEstudiantes', params.data.term ?? '')
                    .then(response => {
                        success(response);
                    })
                    .catch(() => {
                        success({ results: [] });
                    });

            },
            processResults: function (data) {
                return { results: data };
            }
        }

    }).on('change', function() {
                const value = $(this).val();

                // Validación visual estilo Metronic
                if (!value) {
                    @this.set('id_estudiante', null);
                    @this.call('evaluarInicio');
                    $(this).removeClass('is-valid').addClass('is-invalid');
                } else {
                    @this.set('id_estudiante', value);
                    $(this).removeClass('is-invalid').addClass('is-valid');
                }

                setTimeout(() => {
                    $(this).next('.select2-container')
                        .removeClass('select2-container--focus');
                }, 50);
            })
            .on('select2:open', function() {
                $('.select2-results__options')
                    .addClass('custom-select2-results');
            });
};


    const inicializarSelect2TipoDocumento = () => {
        const $select = $('.lista_tipo_documento');

        // Evita doble inicialización
        if ($select.hasClass('select2-hidden-accessible')) {
            $select.select2('destroy');
        }

        $select
            .select2({
                placeholder: 'Seleccione tipo de documento'
                , minimumResultsForSearch: 0,
                allowClear: true
                , width: '100%'
                , language: {
                    errorLoading: () => 'No se pudieron encontrar los resultados'
                    , loadingMore: () => 'Cargando más resultados…'
                    , noResults: () => 'No hay coincidencias'
                    , searching: () => 'Buscando…'
                }
            })
            .on('change', function() {
                const value = $(this).val();

                @this.set('id_tipo_documento', value);

                // Validación visual estilo Metronic
                if (!value) {
                    @this.call('evaluarInicio');
                    $(this).removeClass('is-valid').addClass('is-invalid');
                } else {
                    $(this).removeClass('is-invalid').addClass('is-valid');
                }

                setTimeout(() => {
                    $(this).next('.select2-container')
                        .removeClass('select2-container--focus');
                }, 50);
            })
            .on('select2:open', function() {
                $('.select2-results__options')
                    .addClass('custom-select2-results');
            });
    };

    document.addEventListener('livewire:navigated', () => {
        inicializarSelect2Estudiante();
        inicializarSelect2TipoDocumento();
    });

    document.addEventListener('DOMContentLoaded', () => {
        inicializarSelect2Estudiante();
        inicializarSelect2TipoDocumento();
    });

</script>
@endscript
