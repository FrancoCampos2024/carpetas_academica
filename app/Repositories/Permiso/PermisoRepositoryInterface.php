<?php

namespace App\Repositories\Permiso;

use App\Models\Permiso;

interface PermisoRepositoryInterface
{
    /**
     * Verificar si un rol tiene permiso para una acción específica en un menú
     */
    public function verificarPermiso(int $id_rol, string $accion, string $menu): bool;

    /**
     * Obtener todos los permisos de un rol agrupados por menú
     */
    public function obtenerPermisosPorRol(int $id_rol);

    /**
     * Guardar permisos de un rol para un menú específico
     */
    public function guardarPermisos(int $id_rol, int $id_menu, array $acciones_seleccionadas);

    /**
     * Eliminar todos los permisos de un rol para un menú específico
     */
    public function eliminarPermisosPorMenu(int $id_rol, int $id_menu);

    /**
     * Listar todos los permisos
     */
    public function listar();

    /**
     * Registrar un nuevo permiso
     */
    public function registrar(array $datos);

    /**
     * Eliminar un permiso
     */
    public function eliminar($permiso);

    public function obtenerPermisosPorAcciones(int $id_rol, array $acciones): array;
}
