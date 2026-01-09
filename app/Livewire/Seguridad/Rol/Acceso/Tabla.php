<?php

namespace App\Livewire\Seguridad\Rol\Acceso;

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Services\MenuService;
use App\Services\RolService;
use App\Models\Rol;

class Tabla extends Component
{
    public ?int $rol = null;

    protected MenuService $menuService;
    protected RolService $rolService;

    public function __construct()
    {
        $this->menuService = resolve(MenuService::class);
        $this->rolService  = resolve(RolService::class);
    }

    #[Computed]
    public function menus()
    {
        return $this->menuService->listar();
    }

    #[Computed]
    public function rolActual(): ?Rol
    {
        if (!$this->rol) {
            return null;
        }

        return $this->rolService->obtenerPorId($this->rol);
    }


    public function render()
    {
        return view('livewire.seguridad.rol.acceso.tabla');
    }
}
