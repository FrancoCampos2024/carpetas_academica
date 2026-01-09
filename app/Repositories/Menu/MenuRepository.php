<?php

namespace App\Repositories\Menu;

use App\Enums\EstadoEnum;
use App\Models\Menu;
use App\Trait\FuncionesModelTrait;

class MenuRepository implements MenuRepositoryInterface
{
    use FuncionesModelTrait;

    protected $model;

    public function __construct(Menu $menu)
    {
        $this->model = $menu;
    }

    // Listar acciones de un menu por nombre de menu
    public function listarAccionesPorNombreMenu($nombre_menu)
    {
        return $this->model::query()
            ->whereRaw('UPPER(TRIM(nombre_menu)) = ?', [strtoupper(trim($nombre_menu))])
            ->with(['accion', 'accion.tipoAccion'])
            ->first();
    }



}
