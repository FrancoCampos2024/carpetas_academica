<?php

namespace App\Repositories\Catalogo;

use App\Models\Catalogo;
use App\Trait\FuncionesModelTrait;

class CatalogoRepository implements CatalogoRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    use FuncionesModelTrait;
    protected $model;
    public function __construct(Catalogo $model)
    {
        $this->model = $model;
    }

    public function getTiposDocumentos($id_alumno, ?int $unico = null, int $porPagina = 5)
    {
        $query = $this->model
            ->where('id_padre', 4)
            ->when(!is_null($unico), function ($q) use ($unico) {
                $q->where('unico_catalogo', $unico);
            })
            ->with(['documento' => function ($q) use ($id_alumno) {
                $q->where('id_alumno', $id_alumno);
            }]);

        $paginado = $query->paginate($porPagina);

        $paginado->getCollection()->transform(function ($tipo) {
            $tipo->documento_registrado = $tipo->documento->isNotEmpty();
            return $tipo;
        });

        return $paginado;
    }

    public function listarPorPadre(?int $padre = null, ?string $buscar = null)
    {
        return $this->model::query()
            ->where('id_padre', $padre)
            ->when($buscar, function ($q) use ($buscar) {
                $q->where('descripcion_catalogo', 'like', '%' . $buscar . '%');
            })
            ->orderBy('descripcion_catalogo');
    }

    public function listarTabla(int $idPadre)
    {
        return Catalogo::where('id_padre', $idPadre)->get(); 
    }



}
