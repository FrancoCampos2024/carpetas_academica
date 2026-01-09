<div wire:ignore.self class="modal fade" id="modal-modificar-rol" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">

            <div class="modal-header placeholder-glow">
                <h3 class="fw-bold my-0">
                    Modificar rol de usuario
                </h3>
                <div class="btn btn-icon btn-sm btn-active-icon-primary icon-rotate-custom"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                >
                    <i class="ki-outline ki-cross fs-1"></i>
                </div>
            </div>

            <form novalidate autocomplete="off" class="form fv-plugins-bootstrap5 fv-plugins-framework" wire:submit="modificar_rol">

                <div class="modal-body px-5">
                    <div class="d-flex flex-column px-5 px-lg-10">
                        <!-- Info -->
                        <div class="alert alert-dismissible bg-light-warning border border-warning border-2 border-dashed d-flex flex-column flex-sm-row p-5 mb-7">
                            <i class="ki-duotone ki-information fs-2hx text-warning me-4 mb-sm-0">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>

                            <div class="d-flex flex-column pe-0 pe-sm-10">
                                <h4 class="mb-1">Advertencia</h4>
                                <span class="fs-6 fw-normal text-gray-700">
                                    Cambiar el rol de un usuario puede alterar sus permisos y nivel de acceso al sistema. Asegúrate de verificar cuidadosamente esta acción antes de confirmar.
                                </span>
                            </div>

                            <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto icon-rotate-custom" data-bs-dismiss="alert">
                                <i class="ki-duotone ki-cross fs-1 text-warning"><span class="path1"></span><span class="path2"></span></i>
                            </button>
                        </div>

                        <!-- Lista rol -->
                        <div class="mb-7">
                            <div class="form-floating" wire:ignore>
                                <select class="form-select lista_rol @if ($errors->has('lista_rol')) is-invalid @elseif($lista_rol) is-valid @endif"
                                    id="lista_rol"
                                    aria-label="Lista rol"
                                    wire:model="lista_rol"
                                >
                                    @foreach ($this->lista_roles() as $item)
                                        <option value="{{ $item->id_rol }}">
                                            {{ $item->nombre_rol }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="lista_rol" class="required">
                                    Asignar rol <span class="text-danger">*</span>
                                </label>
                            </div>

                            @error('lista_rol')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
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
                        wire:target="modificar_rol"
                    >
                        <span class="indicator-label" wire:loading.remove wire:target="modificar_rol">
                            Guardar
                        </span>
                        <span class="indicator-progress" wire:loading wire:target="modificar_rol">
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
