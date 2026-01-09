<?php

namespace App\Repositories\Usuario;

use App\Enums\EstadoEnum;
use App\Models\Usuario;
use App\Trait\FuncionesModelTrait;
use Illuminate\Support\Facades\DB;
use App\Services\AutenticacionException;
use Illuminate\Support\Facades\Hash;

class UsuarioRepository implements UsuarioRepositoryInterface
{
    use FuncionesModelTrait;

    protected $model;
    public function __construct(Usuario $model)
    {
        $this->model=$model;
    }


    public function existePorNombreUsuario(string $nombreUsuario): bool
    {
        return $this->model::where('nombre_usuario', $nombreUsuario)->exists();
    }

    public function buscarHabilitados($buscar, $limite = null)
    {
        return Usuario::join('ta_persona', 'ta_persona.id_persona', '=', 'ta_usuario.id_persona')
            ->buscar($buscar)
            ->estado(EstadoEnum::HABILITADO)
            ->limite($limite)
            ->select(
                'ta_usuario.id_usuario',
                'ta_usuario.nombre_usuario',
                'ta_persona.nombres_persona',
                'ta_persona.apellido_paterno_persona',
                'ta_persona.apellido_materno_persona'
            )
            ->get();
    }


    public function obtenerUsuariosPorPersona(int $id_persona)
    {
        return $this->model::where('id_persona', $id_persona)
            ->where('estado_usuario', EstadoEnum::HABILITADO)
            ->with('roles:id_rol,nombre_rol')
            ->get()
            ->map(function ($usuario) {
                return (object) [
                    'id_usuario' => $usuario->id_usuario,
                    'nombre_usuario' => $usuario->nombre_usuario,
                    'nombre_rol' => $usuario->roles->first()->nombre_rol ?? 'Sin rol'
                ];
            });
    }

    public function personaTieneUsuario(int $id_persona): bool
    {
        return $this->model::where('id_persona', $id_persona)
            ->whereIn('estado_usuario', [
                EstadoEnum::HABILITADO->value,
                EstadoEnum::DESHABILITADO->value,
            ])
            ->exists();
    }

    public function rolTieneUsuarios(int $id_rol): bool
    {
        return $this->model::where('id_rol', $id_rol)
            ->whereIn('estado_usuario', [
                EstadoEnum::HABILITADO->value,
                EstadoEnum::DESHABILITADO->value,
            ])
            ->exists();
    }



    public function buscarPorNombreUsuario(string $nombre_usuario): ?Usuario
    {
        return Usuario::where('nombre_usuario', strtoupper($nombre_usuario))
            ->where('estado_usuario', EstadoEnum::HABILITADO)
            ->first();
    }


    public function autenticar(string $usuario, string $password): Usuario
    {
        $usuarioModel = $this->buscarPorNombreUsuario($usuario);
        //dd($usuarioModel);
        if (!$usuarioModel || !Hash::check($password, $usuarioModel->clave_usuario)) {
            throw new AutenticacionException("Usuario o contraseña incorrectos.");
        }

        return $usuarioModel;
    }



    public function verificarPermiso(int $id_usuario, string $accion, string $menu, ?string $modulo = null)
    {
        DB::connection('seguridad_mysql')->statement("SET @permiso = 0");

        DB::connection('seguridad_mysql')->statement(
            "CALL sp_seguridad_verificar_permiso(:id_usuario, :accion, :menu, :modulo, @permiso)", [
                'id_usuario' => $id_usuario,
                'accion' => $accion,
                'menu' => $menu,
                'modulo' => $modulo
            ]
        );

        $resultado = DB::connection('seguridad_mysql')->select("SELECT @permiso AS tiene_permiso");

        return (bool) $resultado[0]->tiene_permiso;
    }
}
