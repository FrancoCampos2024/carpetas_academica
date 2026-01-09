<div class="col-lg-4 col-md-12">
    <div class="card mb-4">
        <div class="card-header py-1">
            <div class="d-flex align-items-center gap-2">
                <i class="ki-solid ki-user-square fs-1 text-primary"></i>
                <h2 class="fs-5 mb-0">Alumno</h2>
            </div>
            <div class="d-flex align-items-center gap-1">
                <a href="{{ route('carpetas.index') }}" class="btn btn-light-primary btn-sm">
                    <i class="ki-solid ki-black-left fs-1"></i>
                    Regresar
                </a>
            </div>

        </div>
        <div class="card-body pt-5 pb-5">
            <!-- Resumen -->
            <div class="d-flex flex-center flex-column mb-4">

                <!-- Foto del alumno -->
                <div wire:ignore class="symbol symbol-70px symbol-circle mb-4">
                    <img src="{{ asset($alumno['foto'] ?? 'assets/media/avatars/blank.png') }}"
                        onerror="this.src='{{ asset('assets/media/avatars/blank.png') }}'">

                </div>



                <!-- Condición del alumno -->
                <div class="badge badge-light-info d-inline fs-8">{{ $alumno['condicion'] ?? 'No disponible' }}</div>

                <!-- Nombre y Apellidos -->
                <div class="fs-5 text-gray-800 fw-bold mt-2 mb-0">
                    {{ $alumno['nombre'] }} {{ $alumno['apellido_paterno'] }} {{ $alumno['apellido_materno'] }}
                </div>

                <!-- Código del alumno -->
                <div class="fs-7 fw-semibold text-muted">{{ $alumno['codigo'] ?? 'Código no disponible' }}</div>
            </div>

            <!-- Toggle para mostrar más información -->
            <div class="d-flex flex-stack fs-6 py-2">
                <div class="fw-bold rotate collapsible" data-bs-toggle="collapse" href="#kt_customer_view_details">
                    Información
                    <span class="ms-1 rotate-180">
                        <i class="ki-duotone ki-down fs-4"></i>
                    </span>
                </div>
            </div>

            <div class="separator separator-dashed my-2"></div>

            <!-- Detalles del alumno -->
            <div id="kt_customer_view_details" class="collapse show">
                <div class="py-3 fs-7">

                    <!-- Situación -->
                    <div class="fw-bold mt-3">Situación</div>
                    <div class="text-gray-600">{{ $alumno['situacion'] ?? 'Situación no disponible' }}</div>

                    <!-- Escuela -->
                    <div class="fw-bold mt-3">Escuela</div>
                    <div class="text-gray-600">{{ $alumno['escuela'] ?? 'Escuela no disponible' }}</div>

                    <!-- DNI -->
                    <div class="fw-bold mt-3">DNI</div>
                    <div class="text-gray-600">{{ $alumno['numero_documento'] ?? 'DNI no disponible' }}</div>

                    <!-- Lenguaje -->
                    <div class="fw-bold mt-3">Lenguaje</div>
                    <div class="text-gray-600">{{ $alumno['facultad'] ?? 'Facultad no disponible' }}</div>

                    <!-- Teléfono Celular -->
                    <div class="fw-bold mt-3">Teléfono Celular</div>
                    <div class="text-gray-600">{{ $alumno['celular'] ?? 'Teléfono no disponible' }}</div>

                    <!-- Correo Institucional -->
                    <div class="fw-bold mt-3">Correo Institucional</div>
                    <div class="text-gray-600">{{ $this->alumno['correo_institucional'] ?? 'Correo no disponible' }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

