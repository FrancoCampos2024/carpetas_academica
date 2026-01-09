<?php

namespace App\Services;

use App\Repositories\Accion\AccionRepositoryInterface;

class AccionService
{
    
    protected AccionRepositoryInterface $accionRepository;
    public function __construct()
    {
        $this->accionRepository = resolve(AccionRepositoryInterface::class);
    }

    public function listarPorMenu(int $idMenu){
        return $this->accionRepository->listarPorMenu($idMenu);
    }

}
