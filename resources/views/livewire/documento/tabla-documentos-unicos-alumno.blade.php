<div class="card mb-6 mb-xl-9">

        <!-- Header -->
        <div class="card-header py-3 w-75">
            <div class="card-title">
                <h2 class="fs-4">Documentos Unicos</h2>
            </div>
        </div>

        <!-- Body -->
        <div class="card-body pt-0 pb-3">
            <table class="table table-row-dashed align-middle gy-2" id="kt_table_customers_payment">

                <thead>
                    <tr class="fw-bold text-muted text-uppercase fs-7">
                        <th class="text-center">N°</th>
                        <th class="text-center">Tipo Documento</th>
                        <th class="text-center">Fecha Registro</th>
                        <th class="text-center">Acción</th>
                    </tr>
                </thead>

                <tbody class="fs-7 text-gray-700">
                    <!-- Iterar sobre los tipos de documentos -->
                    @forelse($tiposDocumentosUnicos as $tipo)
                    <tr class="text-center">
                        <td>{{ $loop->iteration }}</td>
                        <td class="text-center">
                            <!-- Verificar si el documento está registrado o no -->
                            <span class="badge fs-8 {{ $tipo->documento && $tipo->documento->count() > 0
                                                        ? 'badge-light-success'
                                                        : 'badge-light-danger'
                                                    }}" style="text-transform: uppercase; font-weight: bold;">
                                {{ $tipo->descripcion_catalogo }}
                            </span>

                        </td>

                        <td class="text-center">
                            @if($tipo->documento && $tipo->documento->count() > 0)
                            {{formatoFechaText($tipo->documento->first()->au_fechacr) }}
                            @else
                            No registrado
                            @endif
                        </td>
                        <td class="text-center">
                        @php
                            $existeDoc = $tipo->documento && $tipo->documento->count() > 0;
                            $tieneAlgunaAccion = ($existeDoc && ($puedeVer || $puedeModificar || $puedeEliminar)) || (!$existeDoc && $puedeCrear);
                        @endphp

                        @if($tieneAlgunaAccion)
                            <div class="d-flex justify-content-center align-items-center gap-1">
                                @if($existeDoc)
                                    {{-- BOTÓN VER --}}
                                    @if($puedeVer)
                                        <a href="{{ route('archivos.ver', ['disco' => encriptar(4), 'id_documento_hash' => encriptar($tipo->documento->first()->id_documento)]) }}"
                                            target="_blank"
                                            class="btn btn-sm btn-icon btn-active-light-primary"
                                            data-bs-toggle="tooltip"
                                            data-bs-dismiss="click"
                                            title="Ver documento"
                                            x-data
                                            >
                                            <i class="ki-outline ki-eye fs-1 text-muted"></i>
                                        </a>
                                    @endif

                                    {{-- BOTÓN EDITAR CON SPINNER --}}
                                    @if($puedeModificar)
                                        <div x-data="{ cargando: false }">
                                            <button type="button" class="btn btn-sm btn-icon btn-active-light-primary"
                                                    :disabled="cargando"
                                                    :class="{ 'active': cargando }"
                                                    @cargando.window="cargando = false"
                                                    @click="cargando = true; $dispatch('abrirModalEditarDocumento', { id_documento: {{ $tipo->documento->first()->id_documento }} })"
                                                    data-bs-toggle="tooltip" data-bs-dismiss="click" title="Cambiar Documento" wire:ignore>
                                                <template x-if="!cargando">
                                                    <i class="ki-outline ki-pencil fs-1 text-muted"></i>
                                                </template>
                                                <template x-if="cargando">
                                                    <span>
                                                        <x-spinner />
                                                    </span>
                                                </template>
                                            </button>
                                        </div>
                                    @endif

                                    {{-- BOTÓN ELIMINAR CON SPINNER --}}
                                    @if($puedeEliminar)
                                        <div x-data="{ cargando: false }">
                                            <button type="button" class="btn btn-sm btn-icon btn-active-light-danger"
                                                    :disabled="cargando"
                                                    :class="{ 'active': cargando }"
                                                    @cargando.window="cargando = false"
                                                    @click="cargando = true; $dispatch('abrirModalEliminarDocumento', { id_documento: {{ $tipo->documento->first()->id_documento }} })"
                                                    data-bs-toggle="tooltip" data-bs-dismiss="click" title="Eliminar Documento" wire:ignore>
                                                <template x-if="!cargando">
                                                    <i class="ki-outline ki-file-deleted fs-1 text-muted"></i>
                                                </template>
                                                <template x-if="cargando">
                                                    <span>
                                                        <x-spinner />
                                                    </span>
                                                </template>
                                            </button>
                                        </div>
                                    @endif

                                @elseif($puedeCrear)
                                    {{-- BOTÓN CREAR CON SPINNER --}}
                                    <div x-data="{ cargando: false }">
                                        <button type="button" class="btn btn-sm btn-icon btn-active-light-success"
                                                :disabled="cargando"
                                                :class="{ 'active': cargando }"
                                                @cargando.window="cargando = false"
                                                @click="cargando = true; $dispatch('abrirModalguardarDocumento', {id_tipo: {{ $tipo->id_catalogo }}})"
                                                data-bs-toggle="tooltip" data-bs-dismiss="click" title="Añadir Documento" wire:ignore>
                                            <template x-if="!cargando">
                                                <i class="ki-outline ki-add-files fs-1 text-muted"></i>
                                            </template>
                                            <template x-if="cargando">
                                                <span>
                                                    <x-spinner />
                                                </span>
                                            </template>
                                        </button>
                                    </div>
                                @endif
                            </div>

                        @else
                            @if(!$existeDoc && $puedeModificar)
                                <span class="text-muted fw-bold" data-bs-toggle="tooltip" title="Requiere registro previo">No disponible</span>
                            @else
                                <span class="text-muted fw-bold">Sin acciones</span>
                            @endif
                        @endif
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
            @if ($tiposDocumentosUnicos->hasPages())
                <div class="d-flex justify-content-between">
                <div class="d-flex align-items-center">Mostrando {{ $tiposDocumentosUnicos->firstItem() }} - {{ $tiposDocumentosUnicos->lastItem() }} de {{ $tiposDocumentosUnicos->total() }} registros</div>
                <div class="pagination pagination-lg">{{ $tiposDocumentosUnicos->links() }}</div>
            </div>
            @else
                <div class="d-flex justify-content-between py-2">
                <div class="d-flex align-items-center text-muted
                    ">Mostrando {{ $tiposDocumentosUnicos->firstItem() }} - {{ $tiposDocumentosUnicos->lastItem() }} de {{ $tiposDocumentosUnicos->total() }} registros</div>
                </div>
            @endif
        </div>
        </div>
</div>

