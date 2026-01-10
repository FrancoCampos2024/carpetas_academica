<div class="card mb-6 mb-xl-9">

    <!-- Header -->
    <div class="card-header py-3 w-100">
    <div class="d-flex justify-content-between align-items-center w-100">

    <!-- TÍTULO -->
    <h2 class="fs-4 mb-0">
        {{ $this->nombreTipoDocumento() }}
    </h2>

    <!-- GRUPO DE BOTONES -->
    <div class="d-flex gap-2">

        <a href="{{ route('documentos.index', ['alumno' => encriptar($this->id_alumno)]) }}"
            class="btn btn-light-primary btn-sm">
            <i class="ki-solid ki-black-left fs-1"></i>
            Regresar
        </a>
        @if($puedeCrear)
        <button
            type="button"
            class="btn btn-primary btn-sm"
            x-data="{ cargando: false }"
            @click="cargando = true; $dispatch('abrirModalguardarDocumento', { id_tipo:null });"
            @cargando.window="cargando = false"
            :disabled="cargando"
        >
            <template x-if="!cargando">
                <i class="ki-outline ki-plus fs-2 px-0"></i>
            </template>

            <template x-if="cargando">
                <x-spinner style="width: 20px; height: 20px;" />
            </template>

            <span class="d-none d-sm-inline">Nuevo</span>
        </button>
        @endif
    </div>

</div>

</div>


    <!-- Body -->
    <div class="card-body pt-0 pb-3">
        <table class="table table-row-dashed align-middle gy-2">

            <thead>
                <tr class="fw-bold text-muted text-uppercase fs-7">
                    <th class="text-center">N°</th>
                    <th class="text-center">Fecha Registro</th>
                    <th class="text-center">Acción</th>
                </tr>
            </thead>

            <tbody class="fs-7 text-gray-700">

                @forelse($this->Documentos() as $doc)
                <tr class="text-center">

                    <!-- N° -->
                    <td>{{ $loop->iteration }}</td>

                    <!-- Fecha de registro -->
                    <td>
                        {{ formatoFechaText($doc->au_fechacr) }}
                    </td>

                    <!-- Acción -->
                    <td>
                        @if($puedeVer || $puedeModificar || $puedeEliminar)
                            @if($puedeVer)
                                <a  href="{{ route('archivos.ver', ['disco' => encriptar(4), 'id_documento_hash' => encriptar($doc-> id_documento)]) }}"
                                    target="_blank"
                                    class="btn btn-sm btn-icon btn-active-light-primary"
                                    data-bs-toggle="tooltip"
                                    data-bs-dismiss="click"
                                    title="Ver documento">
                                        <i class="ki-outline ki-eye fs-1 text-muted"></i>
                                </a>
                            @endif

                            @if($puedeModificar)
                                <button
                                    type="button"
                                    class="btn btn-sm btn-icon btn-active-light-primary"
                                    data-bs-toggle="tooltip"
                                    data-bs-dismiss="click"
                                    title="Cambiar Documento"
                                    x-data="{ cargando: false}"
                                    @cargando.window="cargando = false"
                                    @click="cargando = true; $dispatch('abrirModalEditarDocumento', { id_documento: {{ $doc-> id_documento }} })"
                                    :disabled="cargando"
                                    :class="{ 'active': cargando }"
                                    wire:ignore
                                >
                                    <template x-if="!cargando">
                                        <i class="ki-outline ki-pencil fs-1 text-muted"></i>
                                    </template>
                                    <template x-if="cargando">
                                        <span>
                                            <x-spinner />
                                        </span>
                                    </template>
                                </button>
                            @endif

                            @if($puedeEliminar)
                            <button
                                    type="button"
                                    class="btn btn-sm btn-icon btn-active-light-danger"
                                    data-bs-toggle="tooltip"
                                    data-bs-dismiss="click"
                                    title="Eliminar Documento"
                                    x-data="{ cargando: false }"
                                    @cargando.window="cargando = false"
                                    @click="
                                        cargando = true;
                                        $dispatch('abrirModalEliminarDocumento', {
                                            id_documento: {{ $doc->id_documento }}
                                        });
                                    "
                                    :disabled="cargando"
                                    :class="{ 'active': cargando }"
                                    wire:ignore
                                >
                                    <template x-if="!cargando">
                                        <i class="ki-outline ki-file-deleted fs-1 text-muted"></i>
                                    </template>

                                    <template x-if="cargando">
                                        <span>
                                            <x-spinner />
                                        </span>
                                    </template>
                                </button>
                                @endif
                            @else
                                <span class=" text-muted fw-bold">Sin acciones</span>
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
            @if ($this->Documentos()->hasPages())
                <div class="d-flex justify-content-between">
                <div class="d-flex align-items-center">Mostrando {{ $this->Documentos()->firstItem() }} - {{ $this->Documentos()->lastItem() }} de {{ $this->Documentos()->total() }} registros</div>
                <div class="pagination pagination-lg">{{ $this->Documentos()->links() }}</div>
            </div>
            @else
                <div class="d-flex justify-content-between py-2">
                <div class="d-flex align-items-center text-muted
                    ">Mostrando {{ $this->Documentos()->firstItem() }} - {{ $this->Documentos()->lastItem() }} de {{ $this->Documentos()->total() }} registros</div>
                </div>
            @endif
        </div>

    </div>

</div>
