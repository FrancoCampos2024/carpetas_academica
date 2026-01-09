<?php

namespace App\Repositories\Documento;

use App\Models\Documento;

interface DocumentoRepositoryInterface
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
    public function modificar(array $datos, Documento $documento);

    // Eliminar un registro
    public function eliminar(Documento $documento);

    //Verifica si un modelo tiene relaciones existentes.
    public function verificarRelaciones(Documento $documento, array $relaciones);

    public function getDocumentosPorAlumno($id_alumno);

    public function obtenerDocumentosTipoIdalumno(int $id_tipo , int $id_alumno);

    public function existeItemCatalogo(int $tipo_documento_catalogo);

}
