<div wire:ignore.self class="modal fade" id="modal-estado-persona" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header placeholder-glow">
                <h3 class="fw-bold my-0">
                    Estado de la persona
                </h3>
                <div class="btn btn-icon btn-sm btn-active-icon-primary icon-rotate-custom" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-outline ki-cross fs-1"></i>
                </div>
            </div>

            <form autocomplete="off" class="form fv-plugins-bootstrap5 fv-plugins-framework" wire:submit="cambiar_estado_persona">

                <div class="modal-body px-5">
                    <div class="d-flex flex-column px-5">
                        <div class="modal-header text-center flex-column border-0">
                            <p>
                                <i class="ki-duotone ki-lock text-{{ $modo_modal === 1 ? 'success' : 'danger' }}" style="font-size: 7rem !important;">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                            </p>
                            <h4 class="modal-title w-100 mt-5">
                                ¿Estás seguro de que deseas
                                {{ $modo_modal === 1 ? 'habilitar' : 'deshabilitar' }}
                                este registro?
                            </h4>
                        </div>

                        <div class="px-4 text-center fs-5">
                            <p class="text-gray-700">
                                {{ $mensaje_cuerpo_modal }}
                            </p>

                            <div class="d-flex justify-content-center mt-7">
                                <div class="fw-bold">Persona:</div>
                                <div class="px-2 text-gray-700 text-start">
                                    {{ $nombre_completo_persona }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer d-flex justify-content-center">
                    <button type="reset" class="btn d-flex align-items-center btn-light-secondary me-4" data-bs-dismiss="modal" aria-label="cancel">
                        Cancelar
                    </button>

                    <button type="submit" class="btn d-flex align-items-center btn-{{ $modo_modal === 1 ? 'success' : 'danger' }}" wire:loading.attr="disabled" wire:target="cambiar_estado_persona">
                        <span class="indicator-label" wire:loading.remove>
                            {{ $modo_modal === 1 ? 'Habilitar' : 'Deshabilitar' }}
                        </span>

                        <span class="indicator-progress" wire:loading>
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

