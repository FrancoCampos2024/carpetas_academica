<div wire:ignore.self class="modal fade" id="modal-persona" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">

            <!-- HEADER -->
            <div class="modal-header">
                <h3 class="fw-bold my-0">
                    {{ $modo_edicion ? 'Modificar persona' : 'Registrar nueva persona' }}
                </h3>

                <div class="btn btn-icon btn-sm btn-active-icon-primary icon-rotate-custom"
                    data-bs-dismiss="modal">
                    <i class="ki-outline ki-cross fs-1"></i>
                </div>
            </div>

            <!-- FORMULARIO -->
            <form autocomplete="off" novalidate wire:submit="guardarPersona">

                <div class="modal-body px-5">
                    <div class="d-flex flex-column px-5 px-lg-10">

                        <!-- DNI -->
                        <div class="form-floating mb-7">
                            <input type="text" class="form-control
                                @error('dni_persona') is-invalid
                                @elseif($dni_persona) is-valid
                                @endif"
                                id="dni_persona" wire:model.live="dni_persona" maxlength="8"
                                placeholder="DNI"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                            >
                            <label for="dni_persona" class="required">DNI</label>
                            @error('dni_persona')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <!-- Nombres -->
                        <div class="form-floating mb-7">
                            <input type="text"
                                class="form-control text-uppercase
                                @error('nombres_persona') is-invalid
                                @elseif($nombres_persona) is-valid
                                @endif"
                                id="nombres_persona" wire:model.live="nombres_persona"
                                placeholder="Nombres"
                                oninput="this.value = this.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ\s]/g, '')"
                            />
                            <label for="nombres_persona" class="required">Nombres</label>
                            @error('nombres_persona')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <!-- Apellido paterno -->
                        <div class="form-floating mb-7">
                            <input type="text"
                                class="form-control text-uppercase
                                @error('apellido_pat_persona') is-invalid
                                @elseif($apellido_pat_persona) is-valid
                                @endif"
                                id="apellido_pat_persona" wire:model.live="apellido_pat_persona"
                                placeholder="Apellido paterno"
                                oninput="this.value = this.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ\s]/g, '')"
                            />
                            <label for="apellido_pat_persona" class="required">Apellido paterno</label>
                            @error('apellido_pat_persona')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <!-- Apellido materno -->
                        <div class="form-floating mb-7">
                            <input type="text"
                                class="form-control text-uppercase
                                @error('apellido_mat_persona') is-invalid
                                @elseif($apellido_mat_persona) is-valid
                                @endif"
                                id="apellido_mat_persona" wire:model.live="apellido_mat_persona"
                                placeholder="Apellido materno"
                                oninput="this.value = this.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ\s]/g, '')"
                            />
                            <label for="apellido_mat_persona" class="required">Apellido materno</label>
                            @error('apellido_mat_persona')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <!-- Teléfono -->
                        <div class="form-floating mb-7">
                            <input type="text" class="form-control
                                @error('telefono_persona') is-invalid
                                @elseif($telefono_persona) is-valid
                                @endif"
                                id="telefono_persona" wire:model.live="telefono_persona"
                                maxlength="9" placeholder="Teléfono"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                            />
                            <label for="telefono_persona">Teléfono</label>
                            @error('telefono_persona')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <!-- Correo -->
                        <div class="form-floating mb-7">
                            <input type="email" class="form-control
                                @error('correo_persona') is-invalid
                                @elseif($correo_persona) is-valid
                                @endif"
                                id="correo_persona" wire:model.live="correo_persona"
                                placeholder="Correo" />
                            <label for="correo_persona" class="required">Correo electrónico</label>
                            @error('correo_persona')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <!-- Tipo persona -->
                        <div class="mb-7">
                            <div class="form-floating">
                                <select
                                    class="form-select
                                        @error('tipo_persona_catalogo') is-invalid
                                        @elseif($tipo_persona_catalogo) is-valid
                                        @endif"
                                    id="tipo_persona_catalogo"
                                    wire:model.live="tipo_persona_catalogo"
                                >
                                    <option value="">Seleccione</option>
                                    @foreach($lista_tipos_persona as $tipo)
                                        <option value="{{ $tipo['id_catalogo'] }}">
                                            {{ $tipo['descripcion_catalogo'] }}
                                        </option>
                                    @endforeach
                                </select>

                                <label for="tipo_persona_catalogo" class="required">Tipo de persona</label>
                            </div>
                            @error('tipo_persona_catalogo')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>

                <!-- FOOTER -->
                <div class="modal-footer d-flex justify-content-center">
                    <button type="reset" class="btn btn-light-secondary me-4"
                            data-bs-dismiss="modal">
                        Cancelar
                    </button>

                    <button type="submit" class="btn btn-primary">
                        Guardar
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

