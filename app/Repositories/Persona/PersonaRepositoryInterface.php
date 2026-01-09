<?php

namespace App\Repositories\Persona;

use App\Models\Persona;

interface PersonaRepositoryInterface
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
    public function modificar(array $datos, Persona $persona);

    // Eliminar un registro
    public function eliminar(Persona $persona);

    //Verifica si un modelo tiene relaciones existentes.
    public function verificarRelaciones(Persona $persona, array $relaciones);

    public function existeItemCatalogo(int $tipo_documento_catalogo);

    public function existePorNombreCompleto(string $nombresPersona, string $apellidoPaterno, string $apellidoMaterno): bool;

    public function existePorDniPersona(string $nombresPersona): bool;

    public function existePorCorreoPersona(string $nombresPersona): bool;

    public function existeDniParaOtro(string $dni, int $idPersona): bool;

    public function existeCorreoParaOtro(string $correo, int $idPersona): bool;

    public function existeNombreCompletoParaOtro(
        string $nombres,
        string $apellidoPaterno,
        string $apellidoMaterno,
        int $idPersona
    ): bool;
}
