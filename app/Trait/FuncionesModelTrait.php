<?php

namespace App\Trait;

use Illuminate\Database\Eloquent\Model;

trait FuncionesModelTrait
{
    // Listar todos los registros de un modelo.
    public function listar()
    {
        return $this->model::all();
    }

    /**
     * Obtener un registro por su ID con relaciones opcionales.
     */
    public function obtenerPorId(int $id, array $relaciones = [])
    {
        return $this->model::with($relaciones)
            ->find($id);
    }

    /**
     * Listar registros con paginación, relaciones y búsqueda opcional.
     */
    public function listarPaginado(int $paginado = 10, ?string $buscar = null, string $columnaOrden, string $orden = 'asc', array $relaciones = [])
    {
        $query = $this->model::query()->with($relaciones);

        if (!empty($buscar)) {
            $query->buscar($buscar);
        }

        return $query->orderBy($columnaOrden, $orden)->paginate($paginado);
    }

    /**
     * Buscar registros por coincidencia utilizando un scope definido en el modelo.
     */
    public function buscar(?string $buscar)
    {
        return $this->model::buscar($buscar)->get();
    }

    /**
     * Registrar un nuevo registro.
     */
    public function registrar(array $datos)
    {
        return $this->model::create($datos);
    }

    
    /**
     * Modificar un registro existente.
     */
    public function modificar(array $datos, Model $modelo)
    {
        $modelo->update($datos);
        return $modelo->fresh();
    }

    /**
     * Verificar si un modelo tiene relaciones antes de eliminarlo.
     */
    public function eliminar(Model $modelo)
    {
        return $modelo->delete();
    }

    //Verifica si un modelo tiene relaciones existentes.
    public function verificarRelaciones(Model $modelo, array $relaciones)
    {
        foreach ($relaciones as $relacion) {
            if (str_contains($relacion, '.')) {
                // Separar la relación padre de la hija
                [$relacionPadre, $relacionHija] = explode('.', $relacion, 2);

                // Consulta optimizada con whereHas para evitar iteraciones innecesarias
                if ($modelo->$relacionPadre()->whereHas($relacionHija)->exists()) {
                    return true;
                }
            } else {
                if ($modelo->$relacion()->exists()) {
                    return true;
                }
            }
        }
        return false;
    }
}

