<div wire:ignore.self class="modal fade" id="modal-modificar-usuario" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">

            <div class="modal-header placeholder-glow">
                <h3 class="fw-bold my-0">
                    Modificar nombre de usuario
                </h3>
                <div class="btn btn-icon btn-sm btn-active-icon-primary icon-rotate-custom"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                >
                    <i class="ki-outline ki-cross fs-1"></i>
                </div>
            </div>
qq
            <form
                novalidate
                autocomplete="off"
                class="form fv-plugins-bootstrap5 fv-plugins-framework"
                wire:submit="modificar_usuario"
            >

            <div class="modal-body px-5">
                    <div class="d-flex flex-column px-5 px-lg-10">
                        <!-- Info -->
                        <div class="alert alert-dismissible bg-light-primary border border-primary border-2 border-dashed d-flex flex-column flex-sm-row p-5 mb-7">
                            <i class="ki-duotone ki-information-5 fs-2hx text-primary me-4 mb-sm-0">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>

                            <div class="d-flex flex-column pe-0 pe-sm-10">
                                <span class="fs-6 fw-normal text-gray-700">
                                    Recuerda que se requiere una dirección de correo electrónico válida para completar la verificación delcorreo.
                                </span>
                            </div>

                            <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto icon-rotate-custom" data-bs-dismiss="alert">
                                <i class="ki-duotone ki-cross fs-1 text-primary"><span class="path1"></span><span class="path2"></span></i>
                            </button>
                        </div>

                        <!-- Usuario -->
                        <div class="form-floating mb-7">
                            <input type="text"
                                class="form-control text-uppercase
                                @if ($errors->has('nombre_usuario')) is-invalid
                                @elseif($nombre_usuario) is-valid
                                @endif"
                                id="nombre_usuario"
                                wire:model.live="nombre_usuario"
                                placeholder="Nombre de usuario"
                                autocomplete="off"
                                minlength="3"
                                maxlength="50"
                            />
                            <label for="nombre_usuario" class="required">
                                Nombre de usuario
                            </label>
                            @error('nombre_usuario')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                        class="btn d-flex align-items-center btn-primary"
                        wire:loading.attr="disabled"
                        wire:target="modificar_usuario"
                    >
                        <span class="indicator-label" wire:loading.remove wire:target="modificar_usuario">
                            Guardar
                        </span>
                        <span class="indicator-progress" wire:loading wire:target="modificar_usuario">
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
