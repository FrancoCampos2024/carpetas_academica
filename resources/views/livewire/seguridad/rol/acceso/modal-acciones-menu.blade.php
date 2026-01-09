<div wire:ignore.self class="modal fade" id="modal-acciones" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-700px"> <div class="modal-content">
            <div class="modal-header">
                <h3 class="fw-bold my-0">Acciones del menú: {{ $this->nombre_menu }}</h3>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-outline ki-cross fs-1"></i>
                </div>
            </div>

            <form autocomplete="off" novalidate wire:submit.prevent="guardarAccionesMenu">
                <div class="modal-body px-10 py-8"> @php
                        $coleccion = collect($this->acciones);
                        $accionLista = $coleccion->first(fn($a) => strtoupper($a->nombre_accion) === 'LISTAR');
                        $tieneAccionListar = !is_null($accionLista);

                        $listaActivada = $tieneAccionListar &&
                                        isset($accionesSeleccionadas[$accionLista->id_accion]) &&
                                        $accionesSeleccionadas[$accionLista->id_accion];
                    @endphp

                    {{-- Usamos row-cols para asegurar 2 columnas perfectas --}}
                    <div class="row row-cols-1 row-cols-md-2 g-5">
                        @forelse ($this->acciones as $accion)
                            @php
                                $esLista = strtoupper($accion->nombre_accion) === 'LISTAR';
                                $deshabilitado = $tieneAccionListar && !$esLista && !$listaActivada;
                            @endphp

                            <div class="col">
                                <div
                                    class="d-flex align-items-center p-2 rounded-2 transition-all duration-300"
                                    style="transition: all 0.3s ease; {{ $deshabilitado ? 'opacity: 0.35; pointer-events: none;' : '' }}"
                                >
                                    <label class="form-check form-check-custom form-check-solid cursor-pointer">
                                        <input
                                            class="form-check-input h-20px w-20px cursor-pointer"
                                            type="checkbox"
                                            wire:model.live="accionesSeleccionadas.{{ $accion->id_accion }}"
                                            @if($deshabilitado) disabled @endif
                                        >
                                        <span class="form-check-label cursor-pointer ms-3 fw-semibold text-gray-800">
                                            {{ ucfirst(strtolower($accion->nombre_accion)) }}
                                        </span>
                                    </label>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center text-muted py-10">
                                <i class="ki-outline ki-information-5 fs-2x mb-3 d-block"></i>
                                Este menú no tiene acciones registradas.
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="modal-footer d-flex justify-content-center border-0 pt-0 pb-10">
                    <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="guardarAccionesMenu">
                        <span wire:loading.remove wire:target="guardarAccionesMenu">Guardar Cambios</span>
                        <span wire:loading wire:target="guardarAccionesMenu">
                            Guardando... <x-spinner style="width: 18px; height: 18px;" class="ms-2" />
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
