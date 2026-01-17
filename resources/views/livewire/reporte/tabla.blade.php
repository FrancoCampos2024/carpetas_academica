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
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="position-relative">
                        <button type="button" class="btn btn-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                            <i class="ki-outline ki-filter fs-2 pe-0"></i>
                        </button>

                        <div id="menu_filtro_documentos" class="menu menu-sub menu-sub-dropdown w-300px w-md-350px" data-kt-menu="true" data-kt-menu-dismiss="false">
                            <div class="px-7 py-5">
                                <div class="fs-5 text-dark fw-bold">Opciones de filtro</div>
                                <div class="text-muted fs-7">Rango por fecha de {{ $ver_eliminados ? 'eliminación' : 'creación' }}</div>
                            </div>

                            <div class="separator border-gray-200"></div>

                            <div class="px-7 py-5">
                                <div class="mb-5">
                                    <div class="form-floating">
                                        <input type="date" class="form-control" id="fecha_desde" wire:model.defer="fecha_desde">
                                        <label for="fecha_desde">Fecha inicio</label>
                                    </div>
                                </div>

                                <div class="mb-5">
                                    <div class="form-floating">
                                        <input type="date" class="form-control" id="fecha_hasta" wire:model.defer="fecha_hasta">
                                        <label for="fecha_hasta">Fecha fin</label>
                                    </div>
                                </div>

                                <div class="mb-5">
                                    <label class="form-label fw-semibold">Ver documentos:</label>
                                    <div class="d-flex flex-column gap-2">
                                        <label class="form-check form-check-sm form-check-custom form-check-solid">
                                            <input class="form-check-input" type="radio" value="creados" wire:model.defer="filtro_estado" name="estado"/>
                                            <span class="form-check-label text-gray-600">Creados</span>
                                        </label>
                                        <label class="form-check form-check-sm form-check-custom form-check-solid">
                                            <input class="form-check-input" type="radio" value="eliminados" wire:model.defer="filtro_estado" name="estado"/>
                                            <span class="form-check-label text-gray-600">Eliminados</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2">
                                    <button type="button" class="btn btn-sm btn-light" wire:click="limpiarFiltros" wire:loading.attr="disabled">
                                        Limpiar
                                    </button>
                                    <button type="button" class="btn btn-sm btn-primary" wire:click="aplicarFiltros" wire:loading.attr="disabled">
                                        <span wire:loading.remove wire:target="aplicarFiltros">Aplicar</span>
                                        <span wire:loading wire:target="aplicarFiltros"><x-spinner style="width:20px; height:20px;" /></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($puedeExportar)
                    <div class="d-flex justify-content-end align-items-center">
                        <button type="button" class="btn btn-primary px-4" data-bs-toggle="dropdown">
                            <i class="ki-outline ki-exit-right-corner fs-2 pe-0"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end menu menu-sub menu-sub-dropdown w-175px py-4">
                            <div class="menu-item px-3">
                                <a class="menu-link px-3" href="#" wire:click.prevent="exportarPdf">
                                    Exportar PDF
                                    <span wire:loading wire:target="exportarPdf" class="ms-2"><x-spinner style="width: 15px; height: 15px;"/></span>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="card-body py-4">
            <div class="table-responsive position-relative">
                @php
                    $documentos = $this->Documentos;
                    $contador = ($documentos instanceof \Illuminate\Pagination\LengthAwarePaginator) ? $documentos->firstItem() : 1;
                @endphp

                <table class="table align-middle table-row-dashed fs-6 gy-5">
                    <thead>
                        <tr class="text-start text-muted fw-bold text-uppercase gs-0">
                            <th class="w-50px">N°</th>
                            <th class="min-w-250px">ALUMNO</th>
                            <th class="min-w-150px text-center">TIPO</th>
                            <th class="min-w-200px">ARCHIVO / RUTA</th>
                            <th class="min-w-150px text-center">
                                @if($ver_eliminados)
                                    <span class="text-danger">FECHA ELIMINACIÓN</span>
                                @else
                                    FECHA CREACIÓN
                                @endif
                            </th>
                            <th class="min-w-120px text-end pe-7">
                                {{ $ver_eliminados ? 'ELIMINADO POR' : 'USUARIO' }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 fw-semibold" wire:loading.class="opacity-25">
                        @forelse ($documentos as $documento)
                            @php
                                $color = ['success', 'primary', 'warning', 'info'][ord($documento['alumno_nombre'] ?? 'A') % 4];
                                $fechaAMostrar = $ver_eliminados ? ($documento['au_fechael'] ?? null) : ($documento['au_fechacr'] ?? null);
                            @endphp
                            <tr wire:key="doc-{{ $documento['id_documento'] }}">
                                <td><span class="text-muted fw-bold ps-3">{{ $contador++ }}</span></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-35px symbol-circle me-3">
                                            <span class="symbol-label bg-light-{{ $color }} text-{{ $color }} fw-bold">
                                                {{ substr($documento['alumno_nombre'] ?? 'N', 0, 1) }}
                                            </span>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <a href="#" class="text-gray-800 text-hover-primary mb-1 fw-bold">{{ $documento['alumno_nombre'] }}</a>
                                            <span class="text-muted fs-7">
                                                <span class="badge badge-secondary py-1">{{ $documento['alumno_codigo'] }}</span>
                                                <span class="mx-1">|</span> DNI: {{ $documento['alumno_dni'] }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-light-{{ $color }} fs-8 fw-bold px-4 py-3">
                                        {{ strtoupper($documento['tipo_nombre']) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex flex-column" style="max-width: 300px;">
                                        <div class="d-flex align-items-start mb-1">
                                            <i class="ki-outline ki-file text-primary fs-2 me-2 mt-1"></i>
                                            <span class="text-gray-800 fw-bold text-break">{{ $documento['nombre_documento'] }}</span>
                                        </div>
                                        <div class="text-muted fs-7 text-break ps-8">
                                            {{ \Illuminate\Support\Str::after($documento['ruta_documento'], 'Documentos/') }}
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="{{ $ver_eliminados ? 'text-danger' : 'text-gray-800' }} mb-1 fw-bold">
                                        {{ !empty($fechaAMostrar) ? \Carbon\Carbon::parse($fechaAMostrar)->format('d/m/Y') : '-' }}
                                    </div>
                                    <div class="text-muted fs-7">
                                        {{ !empty($fechaAMostrar) ? \Carbon\Carbon::parse($fechaAMostrar)->format('H:i A') : '' }}
                                    </div>
                                </td>
                                <td class="text-end pe-7">
                                    <span class="badge {{ $ver_eliminados ? 'badge-light-danger' : 'badge-light' }} fw-bold">
                                        {{ $documento['usuario_nombre'] ?? '-' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center py-20"><x-blank-state-table mensaje="No se encontraron registros"/></td></tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="position-absolute top-50 start-50 translate-middle" wire:loading wire:target="aplicarFiltros, limpiarFiltros, gotoPage">
                    <x-spinner class="text-primary" style="width: 35px; height: 35px;"/>
                </div>

                @if($documentos instanceof \Illuminate\Pagination\LengthAwarePaginator && $documentos->hasPages())
                    <div class="d-flex justify-content-between mt-3">
                        <div class="text-muted">Mostrando {{ $documentos->firstItem() }} - {{ $documentos->lastItem() }} de {{ $documentos->total() }}</div>
                        <div>{{ $documentos->links() }}</div>
                    </div>
                @endif
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


