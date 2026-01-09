<?php

namespace App\Services;

use App\Enums\EstadoEnum;
use App\Models\Rol;
use App\Repositories\Rol\RolRepositoryInterface;
use Illuminate\Support\Facades\DB;

class RolService
{
    
    protected RolRepositoryInterface $rolRepository;
    public function __construct()
    {
        $this->rolRepository = resolve(RolRepositoryInterface::class);
    }

    public function listar(){
        return $this->rolRepository->listar();
    }

    public function listarpaginado(?string $buscar = null)
    {
        return $this->rolRepository->listarPaginado(
            paginado: 10,
            buscar: $buscar,
            columnaOrden: 'au_fechacr',
            orden: 'desc'
        );
    }


    public function registrar(array $datos)
    {
        DB::beginTransaction();

        try {
            $nombreRol = strtoupper(trim($datos['nombre_rol']));

            if ($this->rolRepository->existePorNombre($nombreRol)) {
                throw new \Exception(
                    'El nombre del rol ya se encuentra registrado. Por favor, ingrese uno diferente.'
                );
            }

            $rol = $this->rolRepository->registrar([
                'nombre_rol' => $nombreRol,
                'estado_rol' => EstadoEnum::HABILITADO->value
            ]);

            DB::commit();
            return $rol;

        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception(
                'No se pudo registrar el rol. ' . $e->getMessage()
            );
        }
    }

    public function modificar(int $idRol, array $datos)
    {
        DB::beginTransaction();

        try {
            $rol = Rol::findOrFail($idRol);
            $nombreRol = strtoupper(trim($datos['nombre_rol']));

            if ($this->rolRepository->existePorNombreParaOtro($nombreRol, $rol->id_rol)) {
                throw new \Exception(
                    'Ya existe otro rol registrado con el mismo nombre.'
                );
            }

            $rol = $this->rolRepository->modificar([
                'nombre_rol' => $nombreRol
            ], $rol);

            DB::commit();
            return $rol;

        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception(
                'No se pudo modificar el rol. ' . $e->getMessage()
            );
        }
    }

    public function cambiarEstado(int $idRol, string $estado)
    {
        DB::beginTransaction();

        try {
            $rol = Rol::findOrFail($idRol);

            $rol->estado_rol = $estado;
            $rol->save();

            DB::commit();
            return $rol;

        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception(
                'No se pudo actualizar el estado del rol. ' . $e->getMessage()
            );
        }
    }


    public function obtenerPorId(int $idRol): Rol
    {
        return $this->rolRepository->obtenerPorId($idRol);
    }

    public function eliminar(Rol $rol){
        return  $this->rolRepository->eliminar($rol);
    }


}
