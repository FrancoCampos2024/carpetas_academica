<div wire:ignore.self class="modal fade" id="modal-usuario" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">

            <div class="modal-header placeholder-glow">
                <h3 class="fw-bold my-0">
                    Registrar nuevo usuario
                </h3>
                <div class="btn btn-icon btn-sm btn-active-icon-primary icon-rotate-custom" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-outline ki-cross fs-1"></i>
                </div>
            </div>

            <form autocomplete="off" novalidate class="form fv-plugins-bootstrap5 fv-plugins-framework" wire:submit="guardar_usuario">

                <div class="modal-body px-5">
                    <div class="d-flex flex-column px-5 px-lg-10">

                        <div class="mb-7">
                            <div class="form-floating" wire:ignore>
                                <select
                                    class="form-select lista_persona"
                                    id="lista_persona"
                                >
                                    <option value=""></option>
                                    @foreach ($this->lista_personas() as $persona)
                                        <option value="{{ $persona->id_persona }}">
                                            {{ $persona->nombres_persona }}
                                            {{ $persona->apellido_pat_persona }}
                                            {{ $persona->apellido_mat_persona }}
                                        </option>
                                    @endforeach
                                </select>

                                <label for="lista_persona" class="required">
                                    Buscar persona
                                </label>
                            </div>

                            @error('lista_persona')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>


                        <!-- Datos de usuario -->
                        <div class="d-flex align-items-center mb-7">
                            <i class="ki-duotone ki-user fs-1 me-2 text-primary">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <h4 class="mb-0">Datos de usuario</h4>
                        </div>

                        <!-- Lista rol -->
                        <div class="mb-7">
                            <div class="form-floating" wire:ignore>
                                <select class="form-select lista_rol"
                                    id="lista_rol"
                                    >
                                    <option></option>
                                    @foreach ($this->lista_roles() as $item)
                                        <option value="{{ $item->id_rol }}">
                                            {{ $item->nombre_rol }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="lista_rol" class="required">
                                    Asignar rol
                                </label>
                            </div>
                            @error('lista_rol')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Usuario -->
                        <div class="form-floating mb-7">
                            <input type="text" class="form-control text-uppercase
                                    @if ($errors->has('nombre_usuario')) is-invalid
                                    @elseif($nombre_usuario) is-valid
                                    @endif" id="nombre_usuario" wire:model.live="nombre_usuario" placeholder="Nombre de usuario" autocomplete="off" minlength="3" maxlength="50" />
                            <label for="nombre_usuario" class="required">
                                Nombre de usuario
                            </label>
                            @error('nombre_usuario')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Contraseña -->
                        <div class="fv-row mb-3 fv-plugins-icon-container" data-kt-password-meter="true">
                            <div class="input-group">
                                <input id="contrasenha" class="form-control
                                        @if ($errors->has('contrasenha')) is-invalid
                                        @elseif($contrasenha) is-valid
                                        @endif" aria-describedby="basic-addon2" wire:model.live="contrasenha" type="password" placeholder="Contraseña" autocomplete="current-password" minlength="8" maxlength="20" />
                                <span style="cursor: pointer;" class="input-group-text" id="basic-addon2" data-kt-password-meter-control="visibility">
                                    <i class="ki-outline ki-eye-slash fs-2"></i>
                                    <i class="ki-outline ki-eye fs-2 d-none"></i>
                                </span>

                                @error('contrasenha')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer d-flex justify-content-center">
                    <button type="reset" class="btn d-flex align-items-center btn-light-secondary me-4" data-bs-dismiss="modal" aria-label="cancel" wire:click="resetFormulario">
                        Cancelar
                    </button>

                    <button type="submit" class="btn d-flex align-items-center btn-primary" wire:loading.attr="disabled" wire:target="guardar_usuario">
                        <span class="indicator-label" wire:loading.remove wire:target="guardar_usuario">
                            Guardar
                        </span>
                        <span class="indicator-progress" wire:loading wire:target="guardar_usuario">
                            Cargando...
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

