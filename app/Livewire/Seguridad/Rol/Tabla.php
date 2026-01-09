<?php

namespace App\Livewire\Seguridad\Rol;

use App\Services\RolService;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\WithPagination;

class Tabla extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public string $buscar = '';

    public $puedeCrear;
    public $puedeModificar;
    public $puedeCambiarEstado;
    public $puedeEliminar;
    public $puedeAsignarAcceso;

    protected RolService $rolService;

    public function __construct()
    {
        $this->rolService = resolve(RolService::class);
    }

    #[Computed]
    #[On('refrescarRoles')]
    public function roles()
    {
        return $this->rolService->listarpaginado($this->buscar);
    }

    public function updatedBuscar()
    {
        $this->resetPage();
    }

    public function mount (){
        $this->puedeCrear = Gate::allows('autorizacion', ['CREAR', 'ROLES']);
        $this->puedeModificar = Gate::allows('autorizacion', ['MODIFICAR', 'ROLES']);
        $this->puedeEliminar = Gate::allows('autorizacion', ['ELIMINAR', 'ROLES']);
        $this->puedeCambiarEstado = Gate::allows('autorizacion', ['ESTADO', 'ROLES']);
        $this->puedeAsignarAcceso = Gate::allows('autorizacion',['ASIGNAR','ROLES']);
    }

    public function render()
    {
        return view('livewire.seguridad.rol.tabla');
    }
}
