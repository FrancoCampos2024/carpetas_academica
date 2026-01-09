<?php

namespace App\Livewire\Seguridad\Rol\Acceso;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Services\MenuService;
use App\Services\AccionService;
use App\Services\PermisoService;

class Index extends Component
{
    public $rol;
    public ?int $id_menu = null;
    public string $nombre_menu = '';
    public $acciones = [];
    public $accionesSeleccionadas = [];

    protected MenuService $menuService;
    protected AccionService $accionService;
    protected PermisoService $permisoService;

    public function __construct()
    {
        $this->menuService   = resolve(MenuService::class);
        $this->accionService = resolve(AccionService::class);
        $this->permisoService = resolve(PermisoService::class);
    }

    public function mount(String $rol)
    {
        $idReal = desencriptar($rol);
        if (!$idReal) {
            abort(404, 'Parámetros de acceso no válidos');
        }
        $this->rol = $idReal;
    }

    #[On('abrirModalAcciones')]
    public function abrirModalAcciones(int $id_menu)
    {
        $this->id_menu = $id_menu;
        $menu = $this->menuService->obtenerPorId($id_menu);

        if (!$menu) return;

        $this->nombre_menu = $menu->nombre_menu;
        $this->acciones = $this->accionService->listarPorMenu($id_menu);

        $this->accionesSeleccionadas = $this->permisoService
            ->obtenerPermisosPorAcciones($this->rol, $this->acciones);

        $this->dispatch('cargando', false);
        $this->dispatch('modal', nombre: '#modal-acciones', accion: 'show');
    }

    public function updatedAccionesSeleccionadas($value, $key)
    {
        $idAccion = explode('.', $key)[0];
        $accionTocada = collect($this->acciones)->firstWhere('id_accion', $idAccion);

        if ($accionTocada && strtoupper($accionTocada->nombre_accion) === 'LISTAR' && !$value) {
            $this->accionesSeleccionadas = [
                $idAccion => false
            ];
        }
    }

    public function guardarAccionesMenu()
    {
        if (!$this->rol || !$this->id_menu) {
            return;
        }

        try {
            $this->permisoService->guardarPermisos(
                $this->rol,
                $this->id_menu,
                $this->accionesSeleccionadas
            );

            $this->dispatch('modal', nombre: '#modal-acciones', accion: 'hide');

            $this->dispatch(
                'toastr',
                ...mensajeToastr(
                    false,
                    true,
                    '3000',
                    'Éxito',
                    'success',
                    'Permisos actualizados correctamente',
                    'top',
                    'right'
                )
            );

        } catch (\Exception $e) {
            $this->dispatch(
                'toastr',
                ...mensajeToastr(
                    false,
                    true,
                    '5000',
                    'Error',
                    'error',
                    $e->getMessage(),
                    'top',
                    'right'
                )
            );
        }
    }

    public function render()
    {
        return view('livewire.seguridad.rol.acceso.index');
    }
}
