<div wire:ignore.self class="modal fade" id="modal-eliminar-usuario" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header placeholder-glow">
                <h3 class="fw-bold my-0 text-danger">
                    Eliminar usuario
                </h3>
                <div class="btn btn-icon btn-sm btn-active-icon-primary icon-rotate-custom"
                     data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-outline ki-cross fs-1"></i>
                </div>
            </div>

            <form autocomplete="off"
                  class="form fv-plugins-bootstrap5 fv-plugins-framework"
                  wire:submit="eliminar_usuario">

                <div class="modal-body px-5">
                    <div class="d-flex flex-column px-5">

                        <div class="modal-header text-center flex-column border-0">
                            <p>
                                <i class="ki-duotone ki-trash text-danger"
                                   style="font-size: 7rem !important;">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                            </p>

                            <h4 class="modal-title w-100 mt-5">
                                ¿Estás seguro de que deseas eliminar este usuario?
                            </h4>
                        </div>

                        <div class="px-4 text-center fs-5">
                            <p class="text-gray-700">
                                {{ $mensaje_cuerpo_eliminar_usuario }}
                            </p>

                            <div class="d-flex justify-content-center mt-7">
                                <div class="fw-bold">Usuario:</div>
                                <div class="px-2 text-gray-700 text-start">
                                    {{ $nombre_usuario_eliminar }}
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer d-flex justify-content-center">
                    <button type="reset"
                            class="btn d-flex align-items-center btn-light-secondary me-4"
                            data-bs-dismiss="modal">
                        Cancelar
                    </button>

                    <button type="submit"
                            class="btn d-flex align-items-center btn-danger"
                            wire:loading.attr="disabled"
                            wire:target="eliminar_usuario">

                        <span class="indicator-label" wire:loading.remove>
                            Eliminar
                        </span>

                        <span class="indicator-progress" wire:loading>
                            Eliminando...
                            <span>
                                <x-spinner style="width: 20px; height: 20px;" />
                            </span>
                        </span>
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
