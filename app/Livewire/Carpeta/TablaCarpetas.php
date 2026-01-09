<?php

namespace App\Livewire\Carpeta;

use App\Services\AlumnoService;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('livewire.carpeta.index')]
class TablaCarpetas extends Component
{
    //Para la paginacion de tablas
    use WithPagination;


    //Variables generales
    public $paginado = 10;
    public $buscar = '';
    public $puedeListar;
    public $puedeModificar;
    public bool $alumnosApiError = false;
    public ?string $alumnosApiErrorMsg = null;
    protected AlumnoService $alumnoService;

    //Inyectamos los services
    public function __construct()
    {
        $this->alumnoService = resolve(AlumnoService::class);
    }

    public function mount()
    {
        $this->puedeListar = Gate::allows('autorizacion', ['LISTAR', 'CARPETAS']);
        $this->puedeModificar = Gate::allows('autorizacion', ['MODIFICAR', 'CARPETAS']);
    }

    //Funciones generales
    #[Computed()]
    public function Alumnos()
    {
        if (!$this->puedeListar) {
            return [];
        }

        $res = $this->alumnoService->getAlumnos($this->buscar, $this->paginado);

        if (!$res['ok']) {

            if ($this->alumnosApiErrorMsg !== $res['message']) {
                $this->alumnosApiError = true;
                $this->alumnosApiErrorMsg = $res['message'];

                $this->dispatch(
                    'toastr',
                    ...mensajeToastr(false, true, '5000', 'Error', 'error',
                    'No se pudo cargar las carpetas', 'top', 'right')
                );
            }

            return [];
        }

        $this->alumnosApiError = false;
        $this->alumnosApiErrorMsg = null;

        return $res['data'];
    }

    public function updatedBuscar()
    {
        $this->resetPage();
    }


    //DISEÑO CARGANDO LA TABLA
    public function placeholder()
    {
        return <<<'HTML'
        <div class="row g-5 gx-xl-10 mb-5 mb-xl-10 animate__animated animate__fadeIn animate__faster">
            <div class="col-12">
                <div class="card">
                    <div class="d-flex flex-wrap flex-stack my-5 mx-8">
                        <div class="d-flex align-items-center position-relative my-1 me-4 fs-7">
                            <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                            <input
                                type="text"
                                data-kt-user-table-filter="buscar"
                                class="form-control form-control-solid ps-13 w-xl-350px w-300"
                                placeholder="Buscar"
                                disabled
                            />
                        </div>
                    </div>

                    <div class="card-body py-4">
                        <div lass="dataTables_wrapper dt-bootstrap4 no-footer">
                            <div class="table-responsive">
                                <table class="table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer">
                                    <thead>
                                        <tr class="text-start text-muted fw-bold text-uppercase gs-0">
                                            <th class="text-center">N°</th>
                                            <th class="text-center">CE</th>
                                            <th class="text-center">Alumno</th>
                                            <th class="text-center">Escuela</th>
                                            <th class="text-center">Condición</th>
                                            <th class="text-center">Situación</th>
                                            <th class="text-center">Documentos</th>
                                            <th class="text-center">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-gray-600 fw-bold placeholder-glow">
                                        <tr>
                                            <td>
                                                <span class="placeholder col-12" style="background-color: #c4c4c4; border-radius: 0.42rem; height: 1.5rem;"></span>
                                            </td>
                                            <td>
                                                <span class="placeholder col-12" style="background-color: #c4c4c4; border-radius: 0.42rem; height: 1.5rem;"></span>
                                            </td>
                                            <td>
                                                <span class="placeholder col-12" style="background-color: #c4c4c4; border-radius: 0.42rem; height: 1.5rem;"></span>
                                            </td>
                                            <td>
                                                <span class="placeholder col-12" style="background-color: #c4c4c4; border-radius: 0.42rem; height: 1.5rem;"></span>
                                            </td>
                                            <td>
                                                <span class="placeholder col-12" style="background-color: #c4c4c4; border-radius: 0.42rem; height: 1.5rem;"></span>
                                            </td>
                                            <td>
                                                <span class="placeholder col-12" style="background-color: #c4c4c4; border-radius: 0.42rem; height: 1.5rem;"></span>
                                            </td>
                                            <td>
                                                <span class="placeholder col-12" style="background-color: #c4c4c4; border-radius: 0.42rem; height: 1.5rem;"></span>
                                            </td>
                                            <td>
                                                <span class="placeholder col-12" style="background-color: #c4c4c4; border-radius: 0.42rem; height: 1.5rem;"></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="placeholder col-12" style="background-color: #c4c4c4; border-radius: 0.42rem; height: 1.5rem;"></span>
                                            </td>
                                            <td>
                                                <span class="placeholder col-12" style="background-color: #c4c4c4; border-radius: 0.42rem; height: 1.5rem;"></span>
                                            </td>
                                            <td>
                                                <span class="placeholder col-12" style="background-color: #c4c4c4; border-radius: 0.42rem; height: 1.5rem;"></span>
                                            </td>
                                            <td>
                                                <span class="placeholder col-12" style="background-color: #c4c4c4; border-radius: 0.42rem; height: 1.5rem;"></span>
                                            </td>
                                            <td>
                                                <span class="placeholder col-12" style="background-color: #c4c4c4; border-radius: 0.42rem; height: 1.5rem;"></span>
                                            </td>
                                            <td>
                                                <span class="placeholder col-12" style="background-color: #c4c4c4; border-radius: 0.42rem; height: 1.5rem;"></span>
                                            </td>
                                            <td>
                                                <span class="placeholder col-12" style="background-color: #c4c4c4; border-radius: 0.42rem; height: 1.5rem;"></span>
                                            </td>
                                            <td>
                                                <span class="placeholder col-12" style="background-color: #c4c4c4; border-radius: 0.42rem; height: 1.5rem;"></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="placeholder col-12" style="background-color: #c4c4c4; border-radius: 0.42rem; height: 1.5rem;"></span>
                                            </td>
                                            <td>
                                                <span class="placeholder col-12" style="background-color: #c4c4c4; border-radius: 0.42rem; height: 1.5rem;"></span>
                                            </td>
                                            <td>
                                                <span class="placeholder col-12" style="background-color: #c4c4c4; border-radius: 0.42rem; height: 1.5rem;"></span>
                                            </td>
                                            <td>
                                                <span class="placeholder col-12" style="background-color: #c4c4c4; border-radius: 0.42rem; height: 1.5rem;"></span>
                                            </td>
                                            <td>
                                                <span class="placeholder col-12" style="background-color: #c4c4c4; border-radius: 0.42rem; height: 1.5rem;"></span>
                                            </td>
                                            <td>
                                                <span class="placeholder col-12" style="background-color: #c4c4c4; border-radius: 0.42rem; height: 1.5rem;"></span>
                                            </td>
                                            <td>
                                                <span class="placeholder col-12" style="background-color: #c4c4c4; border-radius: 0.42rem; height: 1.5rem;"></span>
                                            </td>
                                            <td>
                                                <span class="placeholder col-12" style="background-color: #c4c4c4; border-radius: 0.42rem; height: 1.5rem;"></span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        HTML;
    }

    //Render de la vista
    public function render()
    {
        return view('livewire.carpeta.tabla-carpetas');
    }
}
