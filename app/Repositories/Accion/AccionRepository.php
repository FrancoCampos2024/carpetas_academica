<?php

namespace App\Repositories\Accion;

use App\Models\Accion;
use App\Trait\FuncionesModelTrait;

class AccionRepository implements AccionRepositoryInterface
{
    use FuncionesModelTrait;
    protected $model;
    public function __construct(Accion $model)
    {
        $this->model = $model;
    }

    public function listarPorMenu($idMenu)
    {
        return $this->model
            ->where('id_menu', $idMenu)
            ->orderBy('id_accion')
            ->get();
    }

}
