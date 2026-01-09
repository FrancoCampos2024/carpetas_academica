<div>
    <div class="app-container container-fluid">
        <div class="card">
            <div class="d-flex flex-wrap flex-stack my-5 mx-8">
                <div class="d-flex align-items-center position-relative my-1 me-4 fs-7">
                    <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                    <input
                        type="text"
                        data-kt-user-table-filter="buscar"
                        class="form-control form-control-solid ps-13 w-xl-350px w-300"
                        placeholder="Buscar persona"
                        wire:model.live.debounce.500ms="buscar"
                    />
                </div>

                <div class="d-flex my-2">
                    @if($puedeCrear)
                        <a
                            class="btn btn-primary px-4 px-sm-6"
                            x-data="{ cargando: false }"
                            @click="cargando = true; $wire.dispatch('abrirModalGuardarPersona')"
                            @cargando.window="cargando = false"
                            :class="{ 'disabled': cargando }"
                        >
                            <template x-if="!cargando">
                                <i class="ki-outline ki-plus fs-2 px-0"></i>
                            </template>
                            <template x-if="cargando">
                                <span>
                                    <x-spinner style="width: 20px; height: 20px;"/>
                                </span>
                            </template>
                            <span class="d-none d-sm-inline">
                                Nuevo
                            </span>
                        </a>
                    @endif
                </div>
            </div>

            <div class="card-body py-4">
                <div lass="dataTables_wrapper dt-bootstrap4 no-footer">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer">
                            <thead>
                                <tr class="text-start text-muted fw-bold text-uppercase gs-0">
                                    <th class="w-10px pe-2">N°</th>
                                    <th class="min-w-250px">NOMBRE COMPLETO</th>
                                    <th class="min-w-125px">DOCUMENTO</th>
                                    <th class="min-w-50px">FECHA CREACIÓN</th>
                                    <th class="min-w-125px">ESTADO</th>
                                    <th class="text-center min-w-125px">ACCIÓN</th>
                                </tr>
                            </thead>
                            <tbody
                                class="text-gray-600"
                                wire:loading.class="opacity-25"
                                wire:target="buscar, gotoPage, previousPage, nextPage"
                            >
                                @php
                                    $contador = $this->persona->firstItem();
                                @endphp
                                @forelse ($this->persona as $item)
                                    <tr wire:key="{{ $item->id_persona }}">
                                        <td>{{ $contador++ }}</td>
                                        <td>{{ strtoupper($item->nombres_persona . ' ' . $item->apellido_pat_persona . ' ' . $item->apellido_mat_persona) }}</td>
                                        <td>{{ $item->dni_persona }}</td>
                                        <td>{{ formatoFechaText($item->au_fechacr) }}</td>
                                        <td>
                                            @if ($item->estado_persona === \App\Enums\EstadoEnum::HABILITADO->value)
                                            <span class="badge py-2 px-4 fs-6 fw-normal badge-light-success cursor-default">
                                                <span class="bottom-0 bg-success rounded-circle border border-4 border-body h-20px w-20px me-2"></span>
                                                {{ \App\Enums\EstadoEnum::HABILITADO->descripcion() }}
                                            </span>
                                            @elseif ($item->estado_persona === \App\Enums\EstadoEnum::DESHABILITADO->value)
                                                <span class="badge py-2 px-4 fs-6 fw-normal badge-light-danger cursor-default">
                                                    <span class="bottom-0 bg-danger rounded-circle border border-4 border-body h-20px w-20px me-2"></span>
                                                    {{ \App\Enums\EstadoEnum::DESHABILITADO->descripcion() }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if( $puedeModificar || $puedeEliminar || $puedeCambiarEstado)
                                                <a
                                                    class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm fs-6"
                                                    data-bs-toggle="dropdown"
                                                >
                                                    Acciones
                                                    <i class="ki-outline ki-down fs-5 ms-1"></i>
                                                </a>

                                                <div
                                                    class="dropdown-menu dropdown-menu-end menu menu-sub menu-sub-dropdown
                                                        menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary
                                                        fw-semibold fs-6 w-125px py-4"
                                                    data-kt-menu="true"
                                                    x-data="{ cargando_opciones: false }"
                                                    onclick="event.stopPropagation();"
                                                >

                                                    <!-- MODIFICAR -->
                                                    @if($puedeModificar)
                                                    <div
                                                        class="menu-item px-3"
                                                        x-data="{ cargando: false }"
                                                        @cargando.window="cargando = false; cargando_opciones = false"
                                                        :class="{ 'item-disabled': cargando_opciones }"
                                                    >
                                                        <a
                                                            class="menu-link px-3 d-flex align-items-center gap-2"
                                                            @click="
                                                                cargando = true;
                                                                cargando_opciones = true;
                                                                $dispatch('modalEditarPersona', { id_persona: {{ $item->id_persona }} });
                                                            "
                                                            :class="{ 'active': cargando }"
                                                            onclick="event.stopPropagation();"
                                                            wire:ignore
                                                        >
                                                            <template x-if="!cargando">
                                                                <span>Modificar</span>
                                                            </template>

                                                            <template x-if="cargando">
                                                                <span>
                                                                    <x-spinner class="ms-2" style="width: 20px; height: 20px;" />
                                                                </span>
                                                            </template>
                                                        </a>
                                                    </div>
                                                    @endif

                                                    @if($puedeCambiarEstado)
                                                    <div class="menu-item px-3">
                                                        <a
                                                            class="menu-link px-3"
                                                            x-data="{ cargando: false }"
                                                            @click="
                                                                cargando = true;
                                                                $dispatch('abrirModalEstadoPersona', { id_persona: {{ $item->id_persona }} });
                                                            "
                                                            @cargando.window="cargando = false"
                                                            :class="{ 'active': cargando }"
                                                            onclick="event.stopPropagation();"
                                                        >
                                                            Estado
                                                            <template x-if="cargando">
                                                                <span>
                                                                    <x-spinner class="ms-2" style="width: 20px; height: 20px;" />
                                                                </span>
                                                            </template>
                                                        </a>
                                                    </div>
                                                    @endif

                                                    @if($puedeEliminar)
                                                    <div class="menu-item px-3">
                                                        <a
                                                            class="menu-link px-3 text-danger"
                                                            x-data="{ cargando: false }"
                                                            @click="
                                                                cargando = true;
                                                                $dispatch('abrirModalEliminarPersona', { id_persona: {{ $item->id_persona }} });
                                                            "
                                                            @cargando.window="cargando = false"
                                                            :class="{ 'active': cargando }"
                                                            onclick="event.stopPropagation();"
                                                        >
                                                            Eliminar
                                                            <template x-if="cargando">
                                                                <span>
                                                                    <x-spinner class="ms-2" style="width: 20px; height: 20px;" />
                                                                </span>
                                                            </template>
                                                        </a>
                                                    </div>
                                                    @endif
                                                </div>
                                            @else
                                                <span class=" text-muted fw-bold">Sin acciones</span>
                                            @endif
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td
                                            colspan="6"
                                            class="text-center py-8 text-muted"
                                        >
                                            <!-- Mostrar mensaje si no hay registros -->
                                            <div
                                                x-data="{ cargado: false, modo: localStorage.getItem('data-bs-theme-mode') || 'light' }"
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
                            @if ($this->persona->hasPages())
                                <div class="d-flex justify-content-between">
                                    <div class="d-flex align-items-center">Mostrando {{ $this->persona->firstItem() }} - {{ $this->persona->lastItem() }} de {{ $this->persona->total() }} registros</div>
                                    <div class="pagination pagination-lg">{{ $this->persona->links() }}</div>
                                </div>
                            @else
                                <div class="d-flex justify-content-between py-2">
                                    <div class="d-flex align-items-center text-muted
                                    ">Mostrando {{ $this->persona->firstItem() }} - {{ $this->persona->lastItem() }} de {{ $this->persona->total() }} registros</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
