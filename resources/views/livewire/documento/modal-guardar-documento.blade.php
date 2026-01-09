<div wire:ignore.self class="modal fade" id="modalGuardarDocumento" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">

            <div class="modal-header">
                <h3 class="fw-bold my-0">
                    @if ($modoEdicion)
                        Editar documento
                    @else
                        Registrar documento
                    @endif
                </h3>
                <div class="btn btn-icon btn-sm btn-active-icon-primary icon-rotate-custom" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-outline ki-cross fs-1"></i>
                </div>
            </div>

            <form autocomplete="off" novalidate class="form fv-plugins-bootstrap5 fv-plugins-framework" wire:submit="cargar_documento">

                <div class="modal-body px-5">
                    <div class="d-flex flex-column px-5 px-lg-10">

                        <div class="d-flex align-items-center gap-2 mb-7 mt-4">
                            <i class="ki-outline ki-file-added fs-1 text-primary"></i>
                            <h3 class="mb-0">Seleccionar PDF</h3>
                        </div>

                        <div class="form-group">
                            <!-- Botón para seleccionar PDF -->
                            <div class="d-flex align-items-center">
                                <label for="archivo_modal" class="btn btn-sm btn-primary me-2" wire:loading.attr="disabled" wire:target="archivo_modal">
                                    Añadir archivo
                                </label>

                                <input
                                    type="file"
                                    id="archivo_modal"
                                    wire:model="archivo_modal"
                                    onchange="validarPesoPDF(event)"
                                    accept="application/pdf"
                                    class="d-none"
                                />
                            </div>

                            <!-- Spinner durante subida -->
                            <div class="d-flex flex-column align-items-center justify-content-center mt-2 mb-2 cursor-default">
                                <div wire:loading wire:target="archivo_modal" class="position-relative mt-3 mb-3" style="min-height: 60px;">
                                    <div class="d-flex flex-column align-items-center justify-content-center position-absolute top-0 start-0 end-0 bottom-0">
                                        <div class="spinner-border text-primary" style="width: 1.5rem; height: 1.5rem;" role="status">
                                            <span class="visually-hidden">Subiendo...</span>
                                        </div>
                                        <span class="mt-2 text-primary fw-semibold">Subiendo...</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Archivo seleccionado -->
                            <div wire:loading.remove wire:target="archivo_modal">

                                {{-- EDITAR: archivo existente --}}
                                @if ($modoEdicion && $archivoActual && !$archivo_modal)
                                    <div class="input-group mt-3 mb-3">
                                        <input type="text" class="form-control form-control-solid" value="{{ $nombre_archivo_modal }}" readonly />

                                        <a href="{{ route('archivos.ver', ['disco' => encriptar(4), 'id_documento_hash' => encriptar($documento->id_documento)]) }}"
                                           target="_blank"
                                           class="btn btn-sm btn-light">
                                            <i class="ki-outline ki-eye fs-2 text-muted"></i>
                                        </a>

                                        <button type="button" class="btn btn-sm btn-light" wire:click="$set('archivoActual', null)">
                                            <i class="ki-outline ki-cross fs-2 text-muted"></i>
                                        </button>
                                    </div>
                                    <span class="form-text fs-6 text-muted">Archivo actual registrado.</span>
                                @endif


                                {{-- Archivo nuevo --}}
                                @if ($archivo_modal)
                                    <div class="input-group mt-3 mb-3">
                                        <input type="text" class="form-control form-control-solid" value="{{ $nombre_archivo_modal }}" readonly />

                                        <button type="button"
                                                class="btn btn-sm btn-light"
                                                wire:click="eliminar_archivo_modal"
                                                wire:loading.attr="disabled"
                                                wire:target="eliminar_archivo_modal">
                                            <i class="ki-outline ki-cross fs-2 text-muted"></i>
                                        </button>
                                    </div>

                                    <span class="form-text fs-6 text-muted">Tamaño máximo por archivo 2MB.</span>
                                @endif
                            </div>

                            <!-- Error -->
                            @error('archivo_modal')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror

                        </div>

                    </div>
                </div>

                <div class="modal-footer d-flex justify-content-center">
                    <button
                        type="reset"
                        class="btn d-flex align-items-center btn-light-secondary me-4"
                        data-bs-dismiss="modal"
                        aria-label="cancel">
                        Cancelar
                    </button>

                    <button type="submit"
                            class="btn d-flex align-items-center btn-primary"
                            wire:loading.attr="disabled"
                            wire:target="cargar_documento, archivo_modal, eliminar_archivo_modal">
                        <span wire:loading.remove wire:target="cargar_documento">
                            Guardar
                        </span>
                        <span wire:loading wire:target="cargar_documento">
                            Cargando...
                            <x-spinner style="width: 20px; height: 20px;" />
                        </span>
                    </button>
                </div>

            </form>

        </div>
    </div>

    <script>
        function validarPesoPDF(event) {
            const file = event.target.files[0];
            if (!file) return;

            const maxBytes = 2 * 1024 * 1024;

            if (file.size > maxBytes) {
                event.target.value = "";

                const componentId = event.target.closest('[wire\\:id]').getAttribute('wire:id');
                Livewire.find(componentId).dispatch('errorArchivoGrande');
            }
        }
    </script>
</div>
