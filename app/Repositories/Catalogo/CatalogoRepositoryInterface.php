<?php

namespace App\Repositories\Catalogo;

use App\Models\Catalogo;

interface CatalogoRepositoryInterface
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
    public function modificar(array $datos, Catalogo $catalogo);

    // Eliminar un registro
    public function eliminar(Catalogo $catalogo);

    //Verifica si un modelo tiene relaciones existentes.
    public function verificarRelaciones(Catalogo $catalogo, array $relaciones);

    public function getTiposDocumentos($id_alumno,?int $unico = null,int $porPagina =5);

    public function listarPorPadre(?int $id_padre = null , ?string $buscar = null);

    public function listarTabla(int $idPadre);

}
