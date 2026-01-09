<div class="card" x-data="{ cargando_opciones: false }">
    <div class="card-header">
        <h3 class="card-title fw-bold">
            <i class="ki-outline ki-cube-3 fs-1 text-primary me-2"></i>
            Tabla
        </h3>
        <div class="card-toolbar gap-2">
            @if($puedeCrear)
                <button
                    x-data
                    type="button"
                    class="btn btn-sm btn-icon btn-active-light-primary"
                    @cargando.window="cargando_opciones = false"
                    @click="cargando_opciones = true;"
                    :disabled="cargando_opciones"
                    wire:click="abrirModalItem({{ null }})"
                    wire:loading.attr="disabled"
                    wire:loading.class="active"
                    wire:target="abrirModalItem({{ null }})"
                >
                    <i
                        class="ki-outline ki-plus-square fs-1"
                        wire:loading.remove
                        wire:target="abrirModalItem({{ null }})"
                    ></i>
                    <span
                        wire:loading
                        wire:target="abrirModalItem({{ null }})"
                    >
                        <x-spinner class="text-primary" />
                    </span>
                </button>
            @endif
        </div>
    </div>
    <div class="card-body py-4">
        <div lass="dataTables_wrapper dt-bootstrap4 no-footer">
            <div class="table-responsive position-relative">
                <table class="table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer">
                    <thead>
                        <tr class="text-start text-muted fw-bold text-uppercase gs-0">
                            <th class="w-10px pe-2">N°</th>
                            <th class="min-w-125px">CAMPO</th>
                            <th class="min-w-125px">ESTADO</th>
                            <th class="text-center w-100px">ACCIÓN</th>
                        </tr>
                    </thead>
                    <tbody
                        class="text-gray-600 fw-semibold"
                        wire:loading.class="opacity-25"
                        wire:target="listaHijos, id_tabla, previousPage, nextPage, gotoPage"
                    >
                        @php
                            $contador = $this->listaHijos->firstItem();
                        @endphp
                        @forelse ($this->listaHijos as $item)
                            <tr wire:key="{{ $item->id_catalogo }}">
                                <td>{{ $contador++ }}</td>
                                <td>{{ $item->descripcion_catalogo }}</td>
                                <td>
                                    @if ($item->estado_catalogo === \App\Enums\EstadoEnum::HABILITADO->value)
                                        <span class="badge py-2 px-4 fs-6 fw-normal badge-light-success cursor-default">
                                            <span class="bottom-0 bg-success rounded-circle border border-4 border-body h-20px w-20px me-2"></span>
                                            {{ \App\Enums\EstadoEnum::HABILITADO->descripcion() }}
                                        </span>
                                    @elseif ($item->estado_catalogo === \App\Enums\EstadoEnum::DESHABILITADO->value)
                                        <span class="badge py-2 px-4 fs-6 fw-normal badge-light-danger cursor-default">
                                            <span class="bottom-0 bg-danger rounded-circle border border-4 border-body h-20px w-20px me-2"></span>
                                            {{ \App\Enums\EstadoEnum::DESHABILITADO->descripcion() }}
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">

                                    @if($puedeModificar || $puedeCambiarEstado)
                                        <div class="d-flex justify-content-center gap-2">

                                            @if($puedeModificar)
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-icon btn-active-light-primary"
                                                @if($this->puedeEditarCatalogo($item->id_catalogo)) disabled @endif
                                                x-on:click="$wire.abrirModalItemEditar({{ $item->id_catalogo }})"
                                                wire:loading.attr="disabled"
                                            >
                                                <i
                                                    class="ki-outline ki-notepad-edit fs-1"
                                                    wire:loading.remove
                                                    wire:target="abrirModalItemEditar({{ $item->id_catalogo }})"
                                                ></i>
                                                <span
                                                    wire:loading
                                                    wire:target="abrirModalItemEditar({{ $item->id_catalogo }})"
                                                >
                                                    <x-spinner class="text-primary" />
                                                </span>
                                            </button>
                                            @endif

                                            @if($puedeCambiarEstado)
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-icon btn-active-light-primary"
                                                x-on:click="$wire.abrirModalEstado({{ $item->id_catalogo }})"
                                                wire:loading.attr="disabled"
                                            >
                                                <i
                                                    class="ki-outline ki-lock fs-1"
                                                    wire:loading.remove
                                                    wire:target="abrirModalEstado({{ $item->id_catalogo }})"
                                                ></i>
                                                <span
                                                    wire:loading
                                                    wire:target="abrirModalEstado({{ $item->id_catalogo }})"
                                                >
                                                    <x-spinner class="text-primary" />
                                                </span>
                                            </button>
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
                                    colspan="4"
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
                    <div
                        class="position-absolute top-50 start-50 translate-middle"
                        style="margin-top: 1.06rem;"
                        wire:loading
                        wire:target="listaHijos, id_tabla, previousPage, nextPage, gotoPage"
                    >
                        <x-spinner class="text-primary" style="width: 35px; height: 35px;" />
                    </div>
                </table>
                <div>
                    @if ($this->listaHijos->hasPages())
                        <div class="d-flex justify-content-between">
                            <div class="d-flex align-items-center">Mostrando {{ $this->listaHijos->firstItem() }} - {{ $this->listaHijos->lastItem() }} de {{ $this->listaHijos->total() }} registros</div>
                            <div class="pagination pagination-lg">{{ $this->listaHijos->links(data: ['scrollTo' => false]) }}</div>
                        </div>
                    @else
                        <div class="d-flex justify-content-between py-2">
                            <div class="d-flex align-items-center text-muted
                            ">Mostrando {{ $this->listaHijos->firstItem() }} - {{ $this->listaHijos->lastItem() }} de {{ $this->listaHijos->total() }} registros</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para cambiar el estado de la tabla -->
    <div wire:ignore.self class="modal fade" id="modal-estado-catalogo" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="fw-bold my-0">
                        {{ $tituloModalEstado }}
                    </h3>
                    <div
                        class="btn btn-icon btn-sm btn-active-icon-primary icon-rotate-custom"
                        data-bs-dismiss="modal"
                        aria-label="Cerrar"
                    >
                        <i class="ki-outline ki-cross fs-1"></i>
                    </div>
                </div>

                <form autocomplete="off" class="form fv-plugins-bootstrap5 fv-plugins-framework" wire:submit="estado_catalogo">

                    <div class="modal-body px-5">
                        <div class="d-flex flex-column px-5">
                            <div class="modal-header text-center flex-column border-0">
                                <p>
                                    <i class="ki-duotone ki-lock text-{{ $modoCatalogoEstado === 1 ? 'danger' : 'success' }}" style="font-size: 7rem !important;">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>
                                </p>
                                <h4 class="modal-title w-100 mt-5">
                                    ¿Estás seguro de que deseas {{ $modoCatalogoEstado === 1 ? 'deshabilitar' : 'habilitar' }} esta tabla?
                                </h4>
                            </div>

                            <div class="px-4 text-center fs-5">
                                <p class="text-gray-700">
                                    @if ($modoCatalogoEstado === 1)
                                        @if ($modoCatalogo === 1)
                                            Al deshabilitar esta tabla, todos los elementos dejarán de estar disponibles en los formularios. Sin embargo, su información se mantendrá
                                            almacenada y podrás volver a habilitarla en cualquier momento.
                                        @else
                                            Al deshabilitar este elemento, todos los elementos dejarán de estar disponibles en los formularios. Sin embargo, su información se mantendrá
                                            almacenada y podrás volver a habilitarla en cualquier momento.
                                        @endif
                                    @elseif ($modoCatalogoEstado === 2)
                                        Al habilitar este registro, estará disponible en el sistema y podrás realizar operaciones con él.
                                    @endif
                                </p>

                                <div class="d-flex justify-content-center mt-7">
                                    <div class="fw-bold">Registro:</div>
                                    <div class="px-2 text-gray-700 text-start">{{ $nombreCatalogoEstado }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer d-flex justify-content-center">
                        <button
                            type="button"
                            class="btn d-flex align-items-center btn-light-secondary me-4"
                            data-bs-dismiss="modal"
                            aria-label="Cancelar"
                        >
                            Cancelar
                        </button>

                        <button
                            type="submit"
                            class="btn d-flex align-items-center btn-{{ $modoCatalogoEstado === 1 ? 'danger' : 'success' }}"
                            wire:loading.attr="disabled"
                            wire:target="estado_catalogo"
                            @click="$dispatch('cargando_padre', { cargando: true, modo_catalogo: {{ $modoCatalogoEstado }} })"
                        >
                            <span wire:loading.remove wire:target="estado_catalogo">
                                {{ $modoCatalogoEstado === 1 ? 'Deshabilitar' : 'Habilitar' }}
                            </span>
                            <span wire:loading wire:target="estado_catalogo">
                                Cargando... <x-spinner style="width: 20px; height: 20px;" />
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para agregar o editar un item -->
    <div wire:ignore.self class="modal fade" id="modal-item" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content">

                <div class="modal-header placeholder-glow">
                    <h3 class="fw-bold my-0">
                        {{ $tituloModal }}
                    </h3>
                    <div
                        class="btn btn-icon btn-sm btn-active-icon-primary icon-rotate-custom"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    >
                        <i class="ki-outline ki-cross fs-1"></i>
                    </div>
                </div>

                <form autocomplete="off" novalidate class="form fv-plugins-bootstrap5 fv-plugins-framework" wire:submit="guardar_item">

                    <div class="modal-body px-5">
                        <div class="d-flex flex-column px-5 px-lg-10">

                            <div class="form-floating mb-7">
                                <input
                                    type="text"
                                    class="form-control text-uppercase @if ($errors->has('descripcion')) is-invalid @elseif($descripcion) is-valid @endif"
                                    id="descripcion"
                                    autocomplete="off"
                                    placeholder="Descripción"
                                    wire:model.live="descripcion"
                                    maxlength="100"
                                />
                                <label for="descripcion">
                                    Descripción <span class="text-danger">*</span>
                                </label>
                                @error('descripcion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            @if ($id_tabla == 4)
                                <div class="form-check form-switch mb-7">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        id="es_unico"
                                        wire:model.live="es_unico"
                                    >
                                    <label class="form-check-label fw-semibold" for="es_unico">
                                        Documento único (solo se permite un registro)
                                    </label>
                                </div>
                            @endif

                        </div>
                    </div>

                    <div class="modal-footer d-flex justify-content-center">
                        <button
                            type="button"
                            class="btn d-flex align-items-center btn-light-secondary me-4"
                            data-bs-dismiss="modal"
                            aria-label="Cancelar"
                        >
                            Cancelar
                        </button>

                        <button
                            type="submit"
                            class="btn d-flex align-items-center btn-primary"
                            wire:loading.attr="disabled"
                            wire:target="guardar_item"
                        >
                            <span class="indicator-label" wire:loading.remove wire:target="guardar_item">
                                Guardar
                            </span>
                            <span class="indicator-progress" wire:loading wire:target="guardar_item">
                                Cargando... <x-spinner style="width: 20px; height: 20px;" />
                            </span>
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>

</div>
