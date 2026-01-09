<div wire:ignore.self class="modal fade" id="modal-estado-rol" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header placeholder-glow">
                <h3 class="fw-bold my-0">
                    Estado del rol
                </h3>
                <div
                    class="btn btn-icon btn-sm btn-active-icon-primary icon-rotate-custom"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                >
                    <i class="ki-outline ki-cross fs-1"></i>
                </div>
            </div>

            <form autocomplete="off" novalidate
                  class="form fv-plugins-bootstrap5 fv-plugins-framework"
                  wire:submit="cambiarEstadoRol">

                <div class="modal-body px-5">
                    <div class="d-flex flex-column px-5 ">

                        <div class="modal-header text-center flex-column border-0">
                            <p>
                                <i class="ki-duotone ki-lock text-{{ $modoModalEstado === 1 ? 'success' : 'danger' }}"
                                   style="font-size: 7rem !important;">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                            </p>

                            <h4 class="modal-title w-100 mt-5">
                                ¿Estás seguro de que deseas
                                {{ $modoModalEstado === 1 ? 'habilitar' : 'deshabilitar' }}
                                este registro?
                            </h4>
                        </div>

                        <div class="px-4 text-center fs-5">
                            <p class="text-gray-700">
                                @if ($modoModalEstado === 1)
                                    Habilitar este registro permitirá que vuelva a estar disponible y participe en las operaciones del sistema.
                                @elseif ($modoModalEstado === 2)
                                    Al deshabilitar este registro, el rol no podrá ser utilizado en operaciones actuales del sistema.
                                    Sin embargo, su información se mantendrá almacenada y podrás volver a habilitarla en cualquier momento.
                                @endif
                            </p>

                            <div class="d-flex justify-content-center mt-7">
                                <div class="fw-bold">Registro:</div>
                                <div class="px-2 text-gray-700 text-start">
                                    {{ $nombreRolEstado }}
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer d-flex justify-content-center">
                    <button
                        type="reset"
                        class="btn d-flex align-items-center btn-light-secondary me-4"
                        data-bs-dismiss="modal"
                        aria-label="cancel"
                    >
                        Cancelar
                    </button>

                    <button
                        type="submit"
                        class="btn d-flex align-items-center btn-{{ $modoModalEstado === 1 ? 'success' : 'danger' }}"
                        wire:loading.attr="disabled"
                        wire:target="cambiarEstadoRol"
                    >
                        <span class="indicator-label" wire:loading.remove wire:target="cambiarEstadoRol">
                            {{ $modoModalEstado === 1 ? 'Habilitar' : 'Deshabilitar' }}
                        </span>

                        <span class="indicator-progress" wire:loading wire:target="cambiarEstadoRol">
                            Cargando...
                            <span>
                                <x-spinner style="width: 20px; height: 20px;"/>
                            </span>
                        </span>
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
