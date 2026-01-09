<?php

namespace App\Repositories\Menu;

use App\Models\Menu;

interface MenuRepositoryInterface
{
    // Listar todos los registros
    public function listar();

    // Encontrar un registro por id
    public function obtenerPorId(int $id, array $relaciones = []);

    // Listar roles paginados con relaciones precargadas
    public function listarPaginado(int $paginado = 10, ?string $buscar = null, string $columnaOrden, string $orden = 'asc', array $relaciones = []);

    // Buscar registros por coincidencia
    public function buscar(?string $buscar);

    // Registrar un nuevo registro
    public function registrar(array $datos);

    // Modificar un registro
    public function modificar(array $datos, Menu $menu);

    // Eliminar un registro
    public function eliminar(Menu $menu);

    //Verifica si un modelo tiene relaciones existentes.
    public function verificarRelaciones(Menu $menu , array $relaciones);

    public function listarAccionesPorNombreMenu($nombre_menu);
}
