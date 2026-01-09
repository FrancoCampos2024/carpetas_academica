<div class="card mb-6 mb-xl-9">
    <div class="card-header py-3 w-75">
        <div class="card-title">
            <h2 class="fs-4">Documentos No Únicos</h2>
        </div>
    </div>

    <div class="card-body pt-0 pb-3">
        <table class="table table-row-dashed align-middle gy-2">

            <thead>
                <tr class="fw-bold text-muted text-uppercase fs-7">
                    <th class="text-center">N°</th>
                    <th class="text-center">Tipo Documento</th>
                    <th class="text-center">Ver Documentos</th>
                </tr>
            </thead>

            <tbody class="fs-7 text-gray-700">

                @forelse ($tiposDocumentosNoUnicos as $tipo)
                <tr class="text-center">

                    <td>{{ $loop->iteration }}</td>

                    <td class="text-center">
                        <span class="badge fs-8 {{ $tipo->documento->count() > 0 ? 'badge-light-success' : 'badge-light-danger' }}"
                            style="text-transform: uppercase; font-weight: bold;">
                            {{ $tipo->descripcion_catalogo }}
                        </span>
                    </td>

                    <td class="text-center">

                        <button
                            type="button"
                            class="btn btn-sm btn-icon btn-active-light-primary"
                            data-bs-toggle="tooltip"
                            data-bs-dismiss="click"
                            title="Ver Documentos"
                            x-data="{ cargando: false }"
                            @cargando.window="cargando = false"
                            onclick="window.location.href='{{ route('documentos.no_unicos.index', [
                                'alumno' => encriptar($this->id_alumno),
                                'tipo'   => encriptar($tipo->id_catalogo)
                            ]) }}'"
                            :disabled="cargando"
                            :class="{ 'active': cargando }"
                            wire:ignore
                        >

                        <template x-if="!cargando">
                            <i class="ki-outline ki-eye fs-1 text-muted"></i>
                        </template>
                        <template x-if="cargando">
                            <span>
                                <x-spinner />
                            </span>
                        </template>
                        </button>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-8 text-muted">
                        <div
                            x-data="{ cargado: false }"
                            x-init="cargado = true"
                        >
                        <template x-if="cargado">
                            <x-blank-state-table mensaje="No se encontraron registros"/>
                        </template>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div
            class="position-absolute top-50 start-50 translate-middle"
            style="margin-top: 1.06rem;"
            wire:loading
            wire:target="buscar, gotoPage, previousPage, nextPage"
        >
            <span>
                <x-spinner class="text-primary" style="width: 35px; height: 35px;"/>
            </span>
        </div>
        <div>
            @if ($tiposDocumentosNoUnicos->hasPages())
                <div class="d-flex justify-content-between">
                <div class="d-flex align-items-center">Mostrando {{ $tiposDocumentosNoUnicos->firstItem() }} - {{ $tiposDocumentosNoUnicos->lastItem() }} de {{ $tiposDocumentosNoUnicos->total() }} registros</div>
                <div class="pagination pagination-lg">{{ $tiposDocumentosNoUnicos->links() }}</div>
            </div>
            @else
                <div class="d-flex justify-content-between py-2">
                <div class="d-flex align-items-center text-muted
                    ">Mostrando {{ $tiposDocumentosNoUnicos->firstItem() }} - {{ $tiposDocumentosNoUnicos->lastItem() }} de {{ $tiposDocumentosNoUnicos->total() }} registros</div>
                </div>
            @endif
        </div>
    </div>

</div>

