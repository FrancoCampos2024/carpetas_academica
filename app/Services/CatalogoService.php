<?php

namespace App\Services;

use App\Models\Catalogo;
use App\Repositories\Catalogo\CatalogoRepository;

class CatalogoService
{
    /**
     * Create a new class instance.
     */

    protected $catalogoRepository;
    public function __construct(CatalogoRepository $catalogoRepository)
    {
        $this->catalogoRepository=$catalogoRepository;
    }

    public function getTiposDocumentos($id_alumno , ?int $unico = null , int $porPagina = 5)
    {
        return $this->catalogoRepository->getTiposDocumentos($id_alumno , $unico, $porPagina); 
    }

    public function obtenerPorid($id_catalogo ,array $relaciones){
        return $this->catalogoRepository->obtenerPorId($id_catalogo,$relaciones);
    }

    public function listarPorPadre(?int $padre=null , ?string $buscar = null )
    {
        return $this->catalogoRepository->listarPorPadre($padre , $buscar);
    }

    public function crear(array $datos){
        return $this->catalogoRepository->registrar($datos);
    }
    public function modificar(array $datos , Catalogo $model){
        return $this->catalogoRepository->modificar($datos,$model);
    }

    public function listarTabla(int $idPadre)
    {
        return $this->catalogoRepository->listarTabla($idPadre) ;
    }

}
