<?php

namespace App\Services;

use App\Models\Usuario;
use App\Repositories\Usuario\UsuarioRepositoryInterface;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use PDOException;

class RelacionesUsuarioExcepton extends \Exception {}
class ErrorConexionException extends Exception {}
class AutenticacionException extends Exception {}

class UsuarioService
{

    protected $usuarioRepository;
    public function __construct(UsuarioRepositoryInterface $usuarioRepository)
    {
        $this->usuarioRepository=$usuarioRepository;
    }

    public function listarPaginado(
        int $paginado = 10,
        ?string $buscar = null,
        string $columnaOrden = 'id_persona',
        string $orden = 'asc',
        array $relaciones = []
    ) {
        return $this->usuarioRepository->listarPaginado(
            $paginado,
            $buscar,
            $columnaOrden,
            $orden,
            $relaciones
        );
    }

    public function registrar(array $datos)
    {
        DB::beginTransaction();

        try {
            $existe_usuario = $this->usuarioRepository->existePorNombreUsuario($datos['nombre_usuario']);

            if ($existe_usuario) {
                throw new \Exception('El nombre de usuario ya está en uso.');
            }

            $usuario = $this->usuarioRepository->registrar($datos);

            DB::commit();

            return $usuario;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Error al registrar el usuario.'.$e->getMessage());
        }
    }

    public function obtenerPorId(int $id, array $relaciones = [])
    {
        return $this->usuarioRepository->obtenerPorId($id,$relaciones);
    }

    public function personaTieneUsuario(int $idpersona){
        return $this->usuarioRepository->personaTieneUsuario($idpersona);
    }
    public function rolTieneUsuarios(int $id_rol){
        return $this->usuarioRepository->rolTieneUsuarios($id_rol);
    }

    public function modificar(array $datos, Usuario $usuario)
    {
        DB::beginTransaction();

        try {

            $usuario = $this->usuarioRepository->modificar($datos, $usuario);

            DB::commit();
            return $usuario;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Ocurrió un error al modificar el usuario.');
        }
    }

    public function autenticar(string $usuario, string $password): ?Usuario
    {
        try {
            $usuarioModel = $this->usuarioRepository->autenticar(strtoupper($usuario), $password);

            if (!$usuarioModel) {
                throw new AutenticacionException("Estas credenciales son incorrectas.");
            }

            return $usuarioModel;

        } catch (PDOException | QueryException $e) {
            throw new ErrorConexionException("Error de conexión." . $e->getMessage());
        } catch (AutenticacionException $e) {
            throw $e;
        } catch (Exception $e) {
            throw new Exception("Error inesperado." . $e->getMessage());
        }
    }

    public function eliminar (Usuario $usuario){
        return $this->usuarioRepository->eliminar($usuario);
    }

    public function obtenerUsuariosPorIds(array $ids): array
    {
        if (empty($ids)) {
            return [];
        }

        return Usuario::with('persona')
            ->whereIn('id_usuario', $ids)
            ->get()
            ->mapWithKeys(function ($u) {
                $persona = $u->persona;

                $nombreCompleto = $persona
                    ? trim(
                        ($persona->nombres_persona ?? '') . ' ' .
                        ($persona->apellido_pat_persona ?? '') . ' ' .
                        ($persona->apellido_mat_persona ?? '')
                    )
                    : 'No disponible';

                return [
                    $u->id_usuario => [
                        'nombre' => $nombreCompleto,
                        'login'  => $u->nombre_usuario,
                    ]
                ];
            })
            ->toArray();
    }

}
