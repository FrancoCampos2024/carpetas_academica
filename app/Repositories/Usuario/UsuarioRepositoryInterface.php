<?php

namespace App\Repositories\Usuario;

use App\Models\Usuario;

interface UsuarioRepositoryInterface
{

   // Listar todos los registros
    public function listar();

    // Encontrar un registro por id
    public function obtenerPorId(int $id, array $relaciones = []);

    // Listar roles paginados con relaciones precargadas
    public function listarPaginado(int $paginado = 10, ?string $buscar = null, string $columnaOrden, string $orden = 'asc', array $relaciones = []);

    // Buscar registros por coincidencia
    public function buscar(?string $buscar);

    // Registrar un nuevo registro
    public function registrar(array $datos);

    // Modificar un registro
    public function modificar(array $datos, Usuario $usuario);

    // Eliminar un registro
    public function eliminar(Usuario $Usuario);

    //Verifica si un modelo tiene relaciones existentes.
    public function verificarRelaciones(Usuario $Usuario, array $relaciones);

    public function buscarPorNombreUsuario(string $nombre_usuario): ?Usuario;

    // Autenticar un usuario y retornar el modelo autenticado
    public function autenticar(string $nombre_usuario, string $password): ?Usuario;

    // Verificar si un usuario existe por nombre de usuario
    public function existePorNombreUsuario(string $nombreUsuario): bool;

    // Verificar si un usuario tiene un permiso
    public function verificarPermiso(int $id_usuario, string $accion, string $menu, ?string $modulo = null);

    public function obtenerUsuariosPorPersona(int $id_persona);

    public function personaTieneUsuario(int $id_persona): bool;

    public function rolTieneUsuarios(int $id_rol): bool;
}
