<?php

namespace App\Repositories\Permiso;

use App\Models\Accion;
use App\Models\Permiso;
use Illuminate\Support\Facades\DB;

class PermisoRepository implements PermisoRepositoryInterface
{
    protected $model;

    public function __construct(Permiso $permiso)
    {
        $this->model = $permiso;
    }

    public function verificarPermiso(int $id_rol, string $accion, string $menu): bool
    {
        return Permiso::query()
            ->where('id_rol', $id_rol)
            ->whereHas('accion', function ($q) use ($accion, $menu) {
                $q->whereRaw('UPPER(TRIM(nombre_accion)) = ?', [strtoupper(trim($accion))])
                    ->whereHas('menu', function ($m) use ($menu) {
                    $m->whereRaw('UPPER(TRIM(nombre_menu)) = ?', [strtoupper(trim($menu))]);
                });
            })
            ->exists();
    }


    public function obtenerPermisosPorRol(int $id_rol)
    {
        return $this->model::where('id_rol', $id_rol)
            ->with(['accion.menu', 'accion.tipoAccion'])
            ->get()
            ->groupBy('accion.id_menu');
    }

    public function obtenerPermisosPorAcciones(int $id_rol, $acciones): array
    {
        $idsAcciones = collect($acciones)
            ->pluck('id_accion')
            ->toArray();

        if (empty($idsAcciones)) {
            return [];
        }

        $permisos = Permiso::where('id_rol', $id_rol)
            ->whereIn('id_accion', $idsAcciones)
            ->pluck('id_accion')
            ->toArray();

        $resultado = [];

        foreach ($permisos as $id_accion) {
            $resultado[$id_accion] = true;
        }

        return $resultado;
    }


    public function guardarPermisos(int $id_rol, int $id_menu, array $acciones_seleccionadas)
    {
        DB::beginTransaction();

        try {

            $this->eliminarPermisosPorMenu($id_rol, $id_menu);

            $accionesValidas = Accion::where('id_menu', $id_menu)
                ->pluck('id_accion')
                ->toArray();

            $permisos = [];

            foreach ($acciones_seleccionadas as $id_accion => $seleccionado) {

                if (!$seleccionado) continue;

                $id_accion = (int) $id_accion;

                if (!in_array($id_accion, $accionesValidas)) continue;

                $permisos[] = [
                    'id_rol'    => $id_rol,
                    'id_accion' => $id_accion,
                ];
            }

            if ($permisos) {
                $this->model::insert($permisos);
            }

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }


    public function eliminarPermisosPorMenu(int $id_rol, int $id_menu)
    {
        return $this->model::whereHas('accion', function ($query) use ($id_menu) {
            $query->where('id_menu', $id_menu);
        })
            ->where('id_rol', $id_rol)
            ->delete();
    }

    public function listar()
    {
        return $this->model::with(['accion.menu', 'accion.tipoAccion', 'rol'])->get();
    }

    public function registrar(array $datos)
    {
        return $this->model::create($datos);
    }


    public function eliminar($permiso)
    {
        return $permiso->delete();
    }
}
