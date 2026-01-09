<?php

namespace App\Repositories\Rol;

use App\Models\Rol;
use App\Trait\FuncionesModelTrait;

class RolRepository implements RolRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    use FuncionesModelTrait;
    protected $model;

    public function __construct(Rol $rol)
    {
        $this->model = $rol;
    }

    public function existePorNombre(string $nombre): bool
    {
        return $this->model
            ->where('nombre_rol', $nombre)
            ->exists();
    }

    public function existePorNombreParaOtro(string $nombre, int $idRol): bool
    {
        return $this->model
            ->where('nombre_rol', $nombre)
            ->where('id_rol', '!=', $idRol)
            ->exists();
    }

}
