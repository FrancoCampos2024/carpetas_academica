<div class="card">
    <div class="card-header">
        <h3 class="card-title fw-bold">
            <i class="ki-outline ki-financial-schedule fs-1 text-primary me-2"></i>
            Lista de tablas
        </h3>
    </div>
    <div class="card-body position-relative">
        <div class="row g-5">
            <div
                class="col-12"
                style="height: 360px; overflow-y: auto;"
                x-data="{ cargando: false, modo_catalogo: 1 }"
            >
                <div
                    class="row g-3"
                    wire:loading.class="opacity-25"
                    wire:target="buscar, seleccionar_tabla"
                    x-on:cargando_padre.window="cargando = $event.detail.cargando, modo_catalogo = $event.detail.modo_catalogo"
                >
                    @forelse ($this->catalogos as $item)
                        <div class="col-12" wire:key="{{ $item->id_catalogo }}-catalogo" id="{{ $item->id_catalogo }}">
                            <div class="d-flex justify-content-between align-items-center">
                                <span
                                    class="d-flex align-items-center cursor-pointer text-hover-primary {{ $tabla == $item->id_catalogo ? 'text-primary' : '' }}"
                                    wire:click="seleccionar_tabla({{ $item->id_catalogo }})"
                                    @click="$dispatch('seleccionar-tabla', { id_tabla: {{ $item->id_catalogo }} })"
                                >
                                    <span class="bullet me-3"></span> {{ $item->descripcion_catalogo }}
                                </span>
                                @php
                                    $iconClass =
                                        $item->estado_catalogo === \App\Enums\EstadoEnum::HABILITADO->value
                                            ? 'text-success'
                                            : 'text-danger';
                                @endphp
                                @if ($item->id_catalogo !== $tabla)
                                    <span>
                                        <i class="ki-outline ki-lock fs-1 {{ $iconClass }}"></i>
                                    </span>
                                @else
                                    <template x-if="cargando && modo_catalogo === 1">
                                        <span>
                                            <x-spinner class="text-muted fs-1" style="width: 26px; height: 26px;" />
                                        </span>
                                    </template>
                                    <template x-if="cargando && modo_catalogo !== 1">
                                        <span>
                                            <i class="ki-outline ki-lock fs-1 {{ $iconClass }}"></i>
                                        </span>
                                    </template>
                                    <template x-if="!cargando">
                                        <span>
                                            <i class="ki-outline ki-lock fs-1 {{ $iconClass }}"></i>
                                        </span>
                                    </template>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="d-flex justify-content-center align-items-center">
                                <span class="text-center text-muted py-8">
                                    <!-- Mostrar mensaje si no hay registros -->
                                    <div
                                        x-data="{ cargado: false, modo: localStorage.getItem('data-bs-theme-mode') || 'light' }"
                                        x-init="cargado = true"
                                    >
                                        <template x-if="cargado">
                                            <x-blank-state-table mensaje="No se encontraron registros"/>
                                        </template>
                                    </div>
                                </span>
                            </div>
                        </div>
                    @endforelse
                </div>
                <div
                    class="position-absolute top-50 start-50 translate-middle"
                    style="margin-top: 1.06rem;"
                    wire:loading
                    wire:target="buscar, seleccionar_tabla"
                >
                    <x-spinner class="text-primary" style="width: 35px; height: 35px;" />
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para agregar o editar una tabla -->
    <div wire:ignore.self class="modal fade" id="modal-tabla" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content">

                <div class="modal-header">
                    <h3 class="fw-bold my-0">
                        {{ $titulo_modal }}
                    </h3>
                    <div
                        class="btn btn-icon btn-sm btn-active-icon-primary icon-rotate-custom"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    >
                        <i class="ki-outline ki-cross fs-1"></i>
                    </div>
                </div>

                <form autocomplete="off" novalidate class="form fv-plugins-bootstrap5 fv-plugins-framework" wire:submit="guardar_tabla">

                    <div class="modal-body px-5">
                        <div class="d-flex flex-column px-5 px-lg-10">

                            @if ($modo_modal == 2)
                                <div class="alert bg-light-warning border border-2 border-warning border-dashed d-flex flex-column flex-sm-row p-5 mb-10">
                                    <i class="ki-duotone ki-information fs-2hx text-warning me-4 mb-5 mb-sm-0">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>
                                    <div class="d-flex flex-column pe-0 pe-sm-8">
                                        <h5 class="mb-1">
                                            Advertencia
                                        </h5>
                                        <span>
                                            Esta tabla esta relacionada con tablas dependientes. Modificar este nombre puede impactar en las opciones mostradas en el sistema.
                                        </span>
                                    </div>
                                </div>
                            @endif

                            <div class="form-floating mb-7">
                                <input
                                    type="text"
                                    class="form-control text-uppercase @if ($errors->has('nombre_tabla')) is-invalid @elseif($nombre_tabla) is-valid @endif"
                                    id="nombre_tabla"
                                    autocomplete="off"
                                    placeholder="Nombre tabla"
                                    wire:model.live="nombre_tabla"
                                    maxlength="100"
                                />
                                <label for="nombre_tabla">
                                    Nombre <span class="text-danger">*</span>
                                </label>
                                @error('nombre_tabla')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
                            class="btn d-flex align-items-center btn-primary"
                            wire:loading.attr="disabled"
                            wire:target="guardar_tabla"
                        >
                            <span wire:loading.remove wire:target="guardar_tabla">
                                Guardar
                            </span>
                            <span wire:loading wire:target="guardar_tabla">
                                Cargando... <x-spinner style="width: 20px; height: 20px;" />
                            </span>
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

@script
<script>
    $wire.on('obtener_id_tabla', (event) => {
        let tabla = document.getElementById(event.id_tabla);
        tabla.scrollIntoView({ behavior: 'smooth', block: 'center' });
    });
</script>
@endscript
