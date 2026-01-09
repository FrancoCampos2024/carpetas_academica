<div class="row g-5 gx-xl-10 mb-5 mb-xl-10">
    <div class="col-12">

        @if ($puedeListar)
            <div class="card shadow-sm">
                <div class="d-flex flex-wrap flex-stack py-4 px-6">
                    <div class="d-flex align-items-center position-relative fs-7">
                        <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                        <input type="text" class="form-control form-control-solid ps-13 w-xl-300px w-250px"
                                placeholder="Buscar alumno" wire:model.live.debounce.500ms="buscar">
                    </div>
                </div>

                <div class="card-body py-3">
                    <div class="table-responsive">
                        <table class="table table-sm align-middle gy-3 fs-7 table-row-bordered">
                            <thead class="text-muted fw-bold text-uppercase fs-8">
                                <tr>
                                    <th class="text-center">N°</th>
                                    <th class="text-center">CE</th>
                                    <th class="text-center">Alumno</th>
                                    <th class="text-center">Escuela</th>
                                    <th class="text-center">Condición</th>
                                    <th class="text-center">Situación</th>
                                    @if ($puedeModificar)
                                        <th class="text-center">Acción</th>
                                    @endif
                                </tr>
                            </thead>

                            <tbody class="text-gray-700">
                                @php $contador = 1; @endphp

                                @forelse ($this->Alumnos as $alumno)
                                    <tr wire:key="{{ $alumno['id'] }}">
                                        <td class="text-center">{{ $contador++ }}</td>
                                        <td class="text-center">{{ $alumno['codigo'] }}</td>
                                        <td class="text-center">
                                            <div>{{ $alumno['nombre'] }} {{ $alumno['apellido_paterno'] }} {{ $alumno['apellido_materno'] }}</div>
                                            <div class="fs-6 text-muted">{{ $alumno['numero_documento'] }}</div>
                                        </td>
                                        <td class="text-center">{{ $alumno['escuela'] ?? 'No disponible' }}</td>
                                        <td class="text-center">{{ $alumno['condicion'] ?? 'No disponible' }}</td>
                                        <td class="text-center">{{ $alumno['situacion'] ?? 'No disponible' }}</td>
                                        @if ($puedeModificar)
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-icon btn-active-light-primary"
                                                    data-bs-toggle="tooltip" title="Modificar Documento"
                                                    x-data="{ cargando: false}" @cargando.window="cargando = false"
                                                    onclick="window.location.href='{{ route('documentos.index', encriptar($alumno['id'])) }}'"
                                                    :disabled="cargando" :class="{ 'active': cargando }" wire:ignore>
                                                    <template x-if="!cargando">
                                                        <i class="ki-outline ki-pencil fs-1 text-muted"></i>
                                                    </template>
                                                    <template x-if="cargando">
                                                        <span><x-spinner /></span>
                                                    </template>
                                                </button>
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-10 text-muted">
                                            <x-blank-state-table mensaje="No se encontraron registros" />
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        @else
            {{-- ESTADO SIN PERMISO --}}
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center py-10">
                        <div class="col-lg-4 text-center">
                            <img src="{{ asset('/media/ilustraciones/5.png') }}" style="transform: scaleX(-1); width: 280px;" class="mb-7" alt="Sin acceso">
                        </div>
                        <div class="col-lg-8">
                            <h2 class="fw-bolder text-dark mb-4">Acceso Restringido</h2>
                            <p class="text-muted fs-5 mb-6">
                                No tienes permisos para listar las carpetas académicas. Navega por otros módulos disponibles o solicita acceso al administrador.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </div>
</div>
