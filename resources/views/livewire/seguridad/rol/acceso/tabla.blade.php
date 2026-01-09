<div class="card">
   <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title fw-bold mb-0">
            <i class="ki-outline ki-shield-tick fs-1 me-2"></i>
            Asignar acceso – {{ $this->rolActual?->nombre_rol ?? 'Rol no seleccionado' }}
        </h3>

        <div class="d-flex align-items-center gap-1">
            <a href="{{ route('seguridad.roles.index') }}" class="btn btn-light-primary btn-sm">
                <i class="ki-solid ki-black-left fs-1"></i>
                Regresar
            </a>
        </div>
    </div>



    <div class="card-body py-4">
        <div class="table-responsive d-flex justify-content-center">
            <table class="table align-middle table-row-dashed fs-6 gy-3 w-100">
                <thead>
                    <tr class="text-muted fw-bold text-uppercase">
                        <!-- columnas vacías izquierda -->
                        <th style="width: 15%;"></th>

                        <!-- columnas reales -->
                        <th class="px-3 text-start">
                            Nombre del menú
                        </th>
                        <th class="px-3 text-center" style="width: 90px;">
                            Acciones
                        </th>

                        <!-- columnas vacías derecha -->
                        <th style="width: 15%;"></th>
                    </tr>
                </thead>

                <tbody class="text-gray-600">
                    @forelse ($this->menus as $menu)
                        <tr wire:key="menu-{{ $menu->id_menu }}">
                            <!-- vacío izquierda -->
                            <td></td>

                            <!-- contenido -->
                            <td class="px-3 fw-semibold fs-5 text-start">
                                {{ $menu->nombre_menu }}
                            </td>

                            <td class="px-3 text-center">
                                <button
                                    type="button"
                                    class="btn btn-sm btn-icon btn-active-light-primary"
                                    x-data="{ cargando: false }"
                                    @click="
                                        cargando = true;
                                        $dispatch('abrirModalAcciones', { id_menu: {{ $menu->id_menu }} });
                                    "
                                    @cargando.window="cargando = false"
                                    :class="{ 'active': cargando }"
                                    onclick="event.stopPropagation();"
                                >
                                    <template x-if="!cargando">
                                        <i class="ki-outline ki-setting-3 fs-1"></i>
                                    </template>

                                    <template x-if="cargando">
                                        <span>
                                            <x-spinner />
                                        </span>
                                    </template>
                                </button>
                            </td>

                            <!-- vacío derecha -->
                            <td></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-6">
                                No hay menús registrados
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
