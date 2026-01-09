<?php

namespace App\Services;

use App\Models\Usuario;
use App\Repositories\Permiso\PermisoRepositoryInterface;

class PermisoService
{

    public function __construct(
        protected PermisoRepositoryInterface $permisoRepository
    ) {}

    public function puedeAcceder(Usuario $usuario, string $accion, string $menu): bool
    {
        if (empty($usuario->id_rol)) {
            return false;
        }

        $accion_normalizada = str_replace('_', ' ', strtoupper(trim($accion)));
        $menu_normalizado   = strtoupper(trim($menu));

        return $this->permisoRepository->verificarPermiso(
            (int) $usuario->id_rol,
            $accion_normalizada,
            $menu_normalizado
        );
    }

    public function obtenerPermisosPorRol(int $id_rol)
    {
        return $this->permisoRepository->obtenerPermisosPorRol($id_rol);
    }

    public function guardarPermisos(int $id_rol, int $id_menu, array $acciones_seleccionadas)
    {
        return $this->permisoRepository->guardarPermisos($id_rol, $id_menu, $acciones_seleccionadas);
    }

    public function eliminarPermisosPorMenu(int $id_rol, int $id_menu)
    {
        return $this->permisoRepository->eliminarPermisosPorMenu($id_rol, $id_menu);
    }

    public function obtenerPermisosPorAcciones(int $id_rol, $acciones): array
    {
        return $this->permisoRepository
            ->obtenerPermisosPorAcciones($id_rol, $acciones);
    }





}
