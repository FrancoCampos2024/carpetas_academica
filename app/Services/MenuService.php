<?php

namespace App\Services;

use App\Repositories\Menu\MenuRepositoryInterface;

class MenuService
{

    protected MenuRepositoryInterface $menuRepository;

    public function __construct()
    {
        $this->menuRepository = resolve(MenuRepositoryInterface::class);
    }

    public function listar(){
        return $this->menuRepository->listar();
    }

    public function obtenerPorId(int $idmenu){
        return $this->menuRepository->obtenerPorId($idmenu);
    }

    public function listarAccionesPorNombreMenu($nombre_menu){
        return $this->menuRepository->listarAccionesPorNombreMenu($nombre_menu);
    }

}
