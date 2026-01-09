<?php

namespace App\Repositories\Rol;

use App\Models\Rol;

interface RolRepositoryInterface
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
    public function modificar(array $datos, Rol $rol);

    // Eliminar un registro
    public function eliminar(Rol $rol);

    //Verifica si un modelo tiene relaciones existentes.
    public function verificarRelaciones(Rol $rol, array $relaciones);

    public function existePorNombre(string $nombre): bool;

    public function existePorNombreParaOtro(string $nombre, int $idRol): bool;
}
