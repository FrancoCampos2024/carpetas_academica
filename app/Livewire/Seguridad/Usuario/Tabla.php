<?php

namespace App\Livewire\Seguridad\Usuario;

use App\Services\UsuarioService;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class Tabla extends Component
{
    use WithPagination;
    public $buscar = '';
    public $mostrar_paginate = 10;
    protected $paginationTheme = 'bootstrap';
    public $puedeCrear;
    public $puedeModificar;
    public $puedeCambiarEstado;
    public $puedeEliminar;


    protected UsuarioService $usuarioService;

    public function __construct()
    {
        $this->usuarioService = resolve(UsuarioService::class);
    }

    // Función para reiniciar el páginado al buscar
    public function updatedBuscar()
    {
        $this->resetPage();
    }

    #[Computed()]
    #[On('refrescar_usuario')]
    public function usuarios()
    {
        return $this->usuarioService->listarPaginado($this->mostrar_paginate, $this->buscar, 'id_usuario', 'desc',[]);
    }

    public function placeholder()
    {
        return <<<'HTML'
        <div class="app-container container-fluid animate__animated animate__fadeIn animate__faster">
            <div class="card">
                <div class="d-flex flex-wrap flex-stack my-5 mx-8">
                    <div class="d-flex align-items-center position-relative my-1 me-4 fs-7">
                        <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                        <input
                            type="text"
                            data-kt-user-table-filter="buscar"
                            class="form-control form-control-solid ps-13 w-xl-350px w-300"
                            placeholder="Buscar persona"
                            disabled
                        />
                    </div>

                    <div class="d-flex my-2">
                        @can('autorizacion', ['REGISTRAR', 'PERSONA'])
                            <button
                                type="button"
                                class="btn btn-primary px-4 px-sm-6"
                                disabled
                            >
                                <i class="ki-outline ki-plus fs-2 px-0"></i>
                                <span class="d-none d-sm-inline">
                                    Nuevo
                                </span>
                            </button>
                        @endcan
                    </div>
                </div>

                <div class="card-body py-4">
                    <div lass="dataTables_wrapper dt-bootstrap4 no-footer">
                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer">
                                <thead>
                                    <tr class="text-start text-muted fw-bold text-uppercase gs-0">
                                        <th class="w-10px pe-2">N°</th>
                                        <th class="min-w-250px sorting">NOMBRE COMPLETO</th>
                                        <th class="min-w-125px sorting">DOCUMENTO</th>
                                        <th class="min-w-50px sorting">FECHA CREACIÓN</th>
                                        <th class="min-w-125px">ESTADO</th>
                                        <th class="min-w-125px text-center">ACCIÓN</th>
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
                                    </tr>
                                </tbody>
                            </table>

                            <div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        HTML;
    }

    public function mount (){
        $this->puedeCrear = Gate::allows('autorizacion', ['CREAR', 'USUARIOS']);
        $this->puedeModificar = Gate::allows('autorizacion', ['MODIFICAR', 'USUARIOS']);
        $this->puedeEliminar = Gate::allows('autorizacion', ['ELIMINAR', 'USUARIOS']);
        $this->puedeCambiarEstado = Gate::allows('autorizacion', ['ESTADO', 'USUARIOS']);
    }

    public function render()
    {
        return view('livewire.seguridad.usuario.tabla');
    }
}
