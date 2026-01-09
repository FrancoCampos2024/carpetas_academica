<div
    wire:ignore.self
    class="modal fade"
    id="modal-eliminar-rol"
    data-bs-backdrop="static"
    data-bs-keyboard="false"
>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- HEADER -->
            <div class="modal-header placeholder-glow">
                <h3 class="fw-bold my-0 text-danger">
                    Eliminar rol
                </h3>

                <div
                    class="btn btn-icon btn-sm btn-active-icon-danger icon-rotate-custom"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                >
                    <i class="ki-outline ki-cross fs-1"></i>
                </div>
            </div>

            <!-- FORM -->
            <form
                autocomplete="off"
                novalidate
                class="form fv-plugins-bootstrap5 fv-plugins-framework"
                wire:submit.prevent="eliminarRol"
            >

                <!-- BODY -->
                <div class="modal-body px-5">
                    <div class="d-flex flex-column px-5">

                        <!-- ICON -->
                        <div class="text-center flex-column border-0">
                            <p class="mb-4">
                                <i
                                    class="ki-duotone ki-trash text-danger"
                                    style="font-size: 7rem;"
                                >
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                            </p>

                            <h4 class="modal-title w-100 mt-5">
                                ¿Estás seguro de que deseas eliminar este rol?
                            </h4>
                        </div>

                        <!-- DESCRIPTION -->
                        <div class="px-4 text-center fs-5 mt-5">
                            <p class="text-gray-700">
                                Esta acción eliminará el rol de forma lógica.
                                <br>
                                <strong>No se borrará físicamente</strong> y no podrá ser utilizado en el sistema.
                            </p>

                            <!-- DATA -->
                            <div class="d-flex justify-content-center mt-7">
                                <div class="fw-bold">Rol:</div>
                                <div class="px-2 text-gray-700 text-start">
                                    {{ $nombreRolEliminar }}
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- FOOTER -->
                <div class="modal-footer d-flex justify-content-center">

                    <button
                        type="button"
                        class="btn d-flex align-items-center btn-light-secondary me-4"
                        data-bs-dismiss="modal"
                    >
                        Cancelar
                    </button>

                    <button
                        type="submit"
                        class="btn d-flex align-items-center btn-danger"
                        wire:loading.attr="disabled"
                        wire:target="eliminarRol"
                    >
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
