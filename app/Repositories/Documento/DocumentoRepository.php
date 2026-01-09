<?php

namespace App\Repositories\Documento;

use App\Models\Documento;
use App\Trait\FuncionesModelTrait;

class DocumentoRepository implements DocumentoRepositoryInterface
{

    use FuncionesModelTrait;
    protected $model;
    public function __construct(Documento $model)
    {
        $this->model=$model;
    }

    public function getDocumentosPorAlumno($id_alumno)
    {
        // Aquí recuperas todos los documentos asociados al alumno
        return Documento::where('id_alumno', $id_alumno)->get(); // Devuelve todos los documentos del alumno
    }


    public function obtenerDocumentosTipoIdalumno(int $id_tipo, int $id_alumno, int $paginado = 10)
    {
        return Documento::where('id_alumno', $id_alumno)
            ->where('tipo_documento_catalogo', $id_tipo)
            ->orderBy('au_fechacr', 'desc')
            ->paginate($paginado);
    }

    public function existeItemCatalogo(int $tipo_documento_catalogo): bool
    {
        return Documento::where('tipo_documento_catalogo', $tipo_documento_catalogo)
                ->exists();
    }

}


