<div>
    <div class="app-container container-fluid">
        <div class="card">
            <div class="d-flex flex-wrap flex-stack my-5 mx-8">

                <!-- BUSCADOR -->
                <div class="d-flex align-items-center position-relative my-1 me-4 fs-7">
                    <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                    <input
                        type="text"
                        class="form-control form-control-solid ps-13 w-xl-350px w-300"
                        placeholder="Buscar usuario"
                        wire:model.live.debounce.500ms="buscar"
                    />
                </div>

                <!-- BOTÓN NUEVO -->
                @if($puedeCrear)
                    <div class="d-flex my-2">
                        <a
                            class="btn btn-primary px-4 px-sm-6"
                            x-data="{ cargando: false }"
                            @click="cargando = true; $wire.dispatch('abrirModalUsuario')"
                            @cargando.window="cargando = false"
                            :class="{ 'disabled': cargando }"
                        >
                            <template x-if="!cargando">
                                <i class="ki-outline ki-plus fs-2 px-0"></i>
                            </template>
                            <template x-if="cargando">
                                <span>
                                    <x-spinner style="width: 20px; height: 20px;" />
                                </span>
                            </template>
                            <span class="d-none d-sm-inline">Nuevo</span>
                        </a>
                    </div>
                @endif

            </div>

            <div class="card-body py-4">
                <div class="table-responsive">

                    <table class="table align-middle table-row-dashed fs-6 gy-5">
                        <thead>
                            <tr class="text-start text-muted fw-bold text-uppercase gs-0">
                                <th class="w-10px pe-2">N°</th>
                                <th class="min-w-250px">USUARIO</th>
                                <th class="min-w-150px">ROL</th>
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
                                $contador = $this->usuarios->firstItem();
                            @endphp

                            @forelse ($this->usuarios as $item)
                                <tr wire:key="usuario-{{ $item->id_usuario }}">
                                    <td>{{ $contador++ }}</td>

                                    <!-- USUARIO -->
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="d-flex flex-column">
                                                <span class="fw-bold text-gray-800 fs-6">
                                                    {{ strtoupper(
                                                        $item->persona->nombres_persona.' '.
                                                        $item->persona->apellido_pat_persona.' '.
                                                        $item->persona->apellido_mat_persona
                                                    ) }}
                                                </span>
                                                <span class="text-gray-500 fs-7">
                                                    {{ $item->nombre_usuario }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- ROL -->
                                    <td>
                                        {{ $item->rol?->nombre_rol ?? 'Sin rol' }}
                                    </td>

                                    <!-- FECHA -->
                                    <td>{{ formatoFechaText($item->au_fechacr) }}</td>

                                    <!-- ESTADO -->
                                    <td>
                                        @if ($item->estado_usuario === \App\Enums\EstadoEnum::HABILITADO->value)
                                            <span class="badge py-2 px-4 fs-6 fw-normal badge-light-success cursor-default">
                                                <span class="bg-success rounded-circle h-10px w-10px me-2"></span>
                                                {{ \App\Enums\EstadoEnum::HABILITADO->descripcion() }}
                                            </span>
                                        @else
                                            <span class="badge py-2 px-4 fs-6 fw-normal badge-light-danger cursor-default">
                                                <span class="bg-danger rounded-circle h-10px w-10px me-2"></span>
                                                {{ \App\Enums\EstadoEnum::DESHABILITADO->descripcion() }}
                                            </span>
                                        @endif
                                    </td>

                                    <!-- ACCIONES -->
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
                                                        href="{{ route('seguridad.usuarios.detalle', encriptar($item->id_usuario)) }}"
                                                        class="menu-link px-3"
                                                        x-data="{ cargando: false }"
                                                        @click="cargando = true"
                                                    >
                                                        <span x-show="!cargando">Modificar</span>
                                                        <span x-show="cargando">
                                                            <x-spinner class="ms-2" style="width: 20px; height: 20px;" />
                                                        </span>
                                                    </a>
                                                </div>
                                                @endif

                                                <!-- ESTADO -->
                                                @if($puedeCambiarEstado)
                                                <div
                                                    class="menu-item px-3"
                                                    x-data="{ cargando: false }"
                                                    @cargando.window="cargando = false; cargando_opciones = false"
                                                    :class="{ 'item-disabled': cargando_opciones }"
                                                >
                                                    <a
                                                        class="menu-link px-3"
                                                        @click="
                                                            cargando = true;
                                                            cargando_opciones = true;
                                                            $dispatch('abrirModalEstadoUsuario', { id_usuario: {{ $item->id_usuario }} });
                                                        "
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
                                                <div
                                                    class="menu-item px-3"
                                                    x-data="{ cargando: false }"
                                                    @cargando.window="cargando = false; cargando_opciones = false"
                                                    :class="{ 'item-disabled': cargando_opciones }"
                                                >
                                                    <a
                                                        class="menu-link px-3 text-danger"
                                                        @click="
                                                            cargando = true;
                                                            cargando_opciones = true;
                                                            $dispatch('abrirModalEliminarUsuario', { id_usuario: {{ $item->id_usuario }} });
                                                        "
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
                                    <td colspan="6" class="text-center py-8 text-muted">
                                        No se encontraron registros
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
                            @if ($this->usuarios->hasPages())
                                <div class="d-flex justify-content-between">
                                    <div class="d-flex align-items-center">Mostrando {{ $this->usuarios->firstItem() }} - {{ $this->usuarios->lastItem() }} de {{ $this->usuarios->total() }} registros</div>
                                    <div class="pagination pagination-lg">{{ $this->usuarios->links() }}</div>
                                </div>
                            @else
                                <div class="d-flex justify-content-between py-2">
                                    <div class="d-flex align-items-center text-muted
                                    ">Mostrando {{ $this->usuarios->firstItem() }} - {{ $this->usuarios->lastItem() }} de {{ $this->usuarios->total() }} registros</div>
                                </div>
                            @endif
                        </div>

                </div>
            </div>
        </div>
    </div>
</div>
