<div>
    <style>
        .select2-container .select2-selection--single {
            box-sizing: border-box;
            cursor: pointer;
            display: block;
            user-select: none;
            -webkit-user-select: none;
            height: auto !important;
        }
        .item-disabled { pointer-events: none; opacity: .6; }
    </style>

    <div class="card">

        <div class="card-header border-0 py-4">

            <div class="d-flex my-1 flex-wrap gap-3 align-items-center justify-content-between w-100">

                <div class="d-flex gap-3 align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="w-300px" wire:ignore>
                            <select class="form-select lista_tipo_documento" id="lista_tipo_documento">
                                    <option></option>
                                    @foreach ($this->tiposDocumentos as $tipo)
                                        <option value="{{ $tipo->id_catalogo }}">
                                            {{ $tipo->descripcion_catalogo }}
                                            {{ $tipo->unico_catalogo ? '(Único)' : '(Múltiple)' }}
                                        </option>
                                    @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="position-relative">
                        <button
                            type="button"
                            class="btn btn-light-primary"
                            data-kt-menu-trigger="click"
                            data-kt-menu-placement="bottom-end"
                            data-kt-menu-flip="bottom"
                            data-kt-menu-target="#menu_filtro_documentos">
                            <i class="ki-outline ki-filter fs-2 pe-0"></i>
                        </button>

                        <div id="menu_filtro_documentos"
                            class="menu menu-sub menu-sub-dropdown w-300px w-md-350px"
                            data-kt-menu="true"
                            style="display: none;"
                            data-kt-menu-dismiss="false">

                            <div class="px-7 py-5">
                                <div class="fs-5 text-dark fw-bold">Opciones de filtro</div>
                                <div class="text-muted fs-7">Rango de fecha (creación)</div>
                            </div>

                            <div class="separator border-gray-200"></div>

                            <div class="px-7 py-5">

                                <div class="mb-5">
                                    <div class="form-floating">
                                        <input
                                            type="date"
                                            class="form-control"
                                            id="fecha_desde"
                                            wire:model.defer="fecha_desde"
                                        >
                                        <label for="fecha_desde">Fecha inicio</label>
                                    </div>
                                </div>

                                <div class="mb-5">
                                    <div class="form-floating">
                                        <input
                                            type="date"
                                            class="form-control"
                                            id="fecha_hasta"
                                            wire:model.defer="fecha_hasta"
                                        >
                                        <label for="fecha_hasta">Fecha fin</label>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2">

                                    <button
                                        type="button"
                                        class="btn btn-sm btn-light"
                                        wire:click="limpiarFiltros"
                                        wire:loading.attr="disabled"
                                        wire:target="limpiarFiltros"
                                    >
                                        Limpiar
                                    </button>

                                    <button
                                        type="button"
                                        class="btn btn-sm btn-primary position-relative"
                                        style="min-width: 90px; height: 34px;"
                                        wire:click="aplicarFiltros"
                                        wire:loading.attr="disabled"
                                        wire:target="aplicarFiltros"
                                    >
                                        <span wire:loading.remove wire:target="aplicarFiltros">Aplicar</span>
                                        <span class="position-absolute top-50 start-50 translate-middle"
                                            wire:loading wire:target="aplicarFiltros">
                                            <x-spinner style="width:20px; height:20px;" />
                                        </span>
                                    </button>

                                </div>

                            </div>
                        </div>
                    </div>

                    @if($puedeExportar)
                    <div class="d-flex justify-content-end align-items-center">
                        <button type="button"
                                class="btn btn-primary d-flex align-items-center gap-2 px-4 px-sm-6"
                                data-bs-toggle="dropdown">
                            <i class="ki-outline ki-exit-right-corner fs-2 pe-0"></i>
                        </button>

                        <div class="dropdown-menu dropdown-menu-end menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-6 w-175px py-4"
                            data-kt-menu="true"
                            x-data="{ cargando_opciones: false }">

                            <div class="menu-item px-3"
                                x-data="{ cargando: false }"
                                @cargando.window="cargando = false; cargando_opciones = false"
                                :class="{ 'item-disabled': cargando_opciones }"
                                onclick="event.stopPropagation();"
                            >
                            <a class="menu-link px-3"
                                href="#"
                                wire:click.prevent="exportarPdf">
                                    Exportar PDF

                                    <span wire:loading wire:target="exportarPdf" class="ms-2">
                                        <x-spinner style="width: 20px; height: 20px;"/>
                                    </span>
                                </a>

                            </div>
                        </div>
                    </div>
                    @endif

                </div>
            </div>

        </div>

        <div class="card-body py-4">

            <div class="dataTables_wrapper dt-bootstrap4 no-footer">
                <div class="table-responsive position-relative">

                    @php
                        $documentos = $this->Documentos;
                        $contador = ($documentos instanceof \Illuminate\Pagination\LengthAwarePaginator)
                            ? $documentos->firstItem()
                            : 1;
                    @endphp

                    <table class="table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer">
                        <thead>
                            <tr class="text-start text-muted fw-bold text-uppercase gs-0">
                                <th class="w-50px">N°</th>
                                <th class="min-w-250px">ALUMNO</th>
                                <th class="min-w-150px text-center">TIPO</th>
                                <th class="min-w-200px">ARCHIVO / RUTA</th>
                                <th class="min-w-150px text-center">FECHA CREACIÓN</th>
                                <th class="min-w-120px text-end pe-7">USUARIO</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 fw-semibold"
                            wire:loading.class="opacity-25"
                            wire:target="gotoPage, previousPage, nextPage, aplicarFiltros, limpiarFiltros">

                            @forelse ($documentos as $documento)
                                @php
                                    $ruta = $documento['ruta_documento'] ?? '';
                                    $archivo = $ruta ? \Illuminate\Support\Str::afterLast($ruta, '/') : 'Sin archivo';
                                    $colores = ['success', 'primary', 'warning', 'info'];
                                    $color = $colores[ord($documento['alumno_nombre'] ?? 'A') % 4];
                                @endphp

                                <tr wire:key="doc-{{ $documento['id_documento'] ?? $loop->index }}">
                                    <td>
                                        <span class="text-muted fw-bold ps-3">{{ $contador++ }}</span>
                                    </td>

                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="symbol symbol-35px symbol-circle me-3">
                                                <span class="symbol-label bg-light-{{ $color }} text-{{ $color }} fw-bold">
                                                    {{ substr($documento['alumno_nombre'] ?? 'N', 0, 1) }}
                                                </span>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <a href="#" class="text-gray-800 text-hover-primary mb-1 fw-bold">
                                                    {{ $documento['alumno_nombre'] ?? 'No disponible' }}
                                                </a>
                                                <span class="text-muted fs-7">
                                                    <span class="badge badge-secondary py-1">{{ $documento['alumno_codigo'] ?? '-' }}</span>
                                                    <span class="mx-1">|</span> DNI: {{ $documento['alumno_dni'] ?? '-' }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="text-center">
                                        <span class="badge badge-light-{{ $color }} fs-8 fw-bold px-4 py-3">
                                            <i class="ki-outline ki- abstract-26 fs-9 text-{{ $color }} me-1"></i>
                                            {{ strtoupper($documento['tipo_nombre'] ?? 'General') }}
                                        </span>
                                    </td>

                                    <td>
                                        <div class="d-flex flex-column">
                                            <div class="d-flex align-items-center mb-1">
                                                <i class="ki-outline ki-file text-primary fs-2 me-2"></i>
                                                <span class="text-gray-800 fw-bold text-truncate w-200px">{{ $archivo }}</span>
                                            </div>
                                            <div class="text-muted fs-7 text-truncate w-250px" title="{{ $ruta }}">
                                                {{ $ruta ?: 'Ruta no disponible' }}
                                            </div>
                                        </div>
                                    </td>

                                    <td class="text-center">
                                        <div class="text-gray-800 mb-1">
                                            {{ !empty($documento['au_fechacr']) ? \Carbon\Carbon::parse($documento['au_fechacr'])->format('d/m/Y') : '-' }}
                                        </div>
                                        <div class="text-muted fs-7">
                                            {{ !empty($documento['au_fechacr']) ? \Carbon\Carbon::parse($documento['au_fechacr'])->format('H:i A') : '' }}
                                        </div>
                                    </td>

                                    <td class="text-end pe-7">
                                        <span class="badge badge-light fw-bold">{{ $documento['usuario_nombre'] ?? '-' }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-20">
                                        <x-blank-state-table mensaje="No se encontraron registros de documentos"/>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div
                        class="position-absolute top-50 start-50 translate-middle"
                        style="margin-top: 1.06rem;"
                        wire:loading
                        wire:target="gotoPage, previousPage, nextPage, aplicarFiltros, limpiarFiltros"
                    >
                        <x-spinner class="text-primary" style="width: 35px; height: 35px;"/>
                    </div>

                    @if($documentos instanceof \Illuminate\Pagination\LengthAwarePaginator && $documentos->hasPages())
                        <div class="d-flex justify-content-between mt-3">
                            <div class="d-flex align-items-center">
                                Mostrando {{ $documentos->firstItem() }} - {{ $documentos->lastItem() }}
                                de {{ $documentos->total() }} registros
                            </div>
                            <div class="pagination pagination-lg">
                                {{ $documentos->links() }}
                            </div>
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </div>
</div>

@script
<script>
    const initSelect2TipoDocumento = () => {
        const $select = $('#lista_tipo_documento');
        if (!$select.length) return;

        if ($select.hasClass('select2-hidden-accessible')) {
            $select.select2('destroy');
        }

        let syncing = false;

        $select.select2({
            placeholder: 'Seleccione tipo de documento',
            minimumResultsForSearch: 0,
            allowClear: true,
            width: '100%',
            language: {
                errorLoading: () => 'No se pudieron encontrar los resultados',
                loadingMore: () => 'Cargando más resultados…',
                noResults: () => 'No hay coincidencias',
                searching: () => 'Buscando…'
            }
        });

        $select.off('change.tipoDoc').on('change.tipoDoc', function () {
            if (syncing) return;

            const raw = $(this).val();
            const tipo = raw ? parseInt(raw, 10) : 0;

            @this.call('setTipoDocumento', tipo);
        });

        const syncVisual = (tipo) => {
            syncing = true;

            const t = parseInt(tipo ?? 0, 10) || 0;
            if (t === 0) {
                $select.val(null).trigger('change.select2');
            } else {
                $select.val(String(t)).trigger('change.select2');
            }

            syncing = false;
        };

        syncVisual(@js($id_tipo_documento));

        if (!window.__tipoDocSyncListener) {
            window.__tipoDocSyncListener = true;

            window.addEventListener('sync-tipo-select', (e) => {
                syncVisual(e.detail?.tipo ?? 0);
            });
        }
    };

    document.addEventListener('DOMContentLoaded', initSelect2TipoDocumento);
    document.addEventListener('livewire:navigated', initSelect2TipoDocumento);
</script>
@endscript


