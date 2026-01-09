<div wire:ignore.self class="modal fade" id="modal-modificar-contrasenha" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">

            <div class="modal-header placeholder-glow">
                <h3 class="fw-bold my-0">
                    Modificar contraseña de usuario
                </h3>
                <div class="btn btn-icon btn-sm btn-active-icon-primary icon-rotate-custom"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                >
                    <i class="ki-outline ki-cross fs-1"></i>
                </div>
            </div>

            <form novalidate autocomplete="off" class="form fv-plugins-bootstrap5 fv-plugins-framework" wire:submit="modificar_contrasenha">

                <div class="modal-body px-5">
                    <div class="d-flex flex-column px-5 px-lg-10">

                        <!-- Contraseña -->
                        <div class="form-floating mb-7">
                            <input
                                type="password"
                                class="form-control
                                @if ($errors->has('contrasenha')) is-invalid
                                @elseif($contrasenha) is-valid
                                @endif"
                                id="contrasenha"
                                wire:model.live="contrasenha"
                                placeholder="Contraseña"
                                autocomplete="off"
                                minlength="8"
                                maxlength="20"
                            >
                            <label for="contrasenha" class="required">
                                Contraseña
                            </label>
                            @error('contrasenha')
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
                        wire:target="modificar_contrasenha"
                    >
                        <span class="indicator-label" wire:loading.remove wire:target="modificar_contrasenha">
                            Guardar
                        </span>
                        <span class="indicator-progress" wire:loading wire:target="modificar_contrasenha">
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
