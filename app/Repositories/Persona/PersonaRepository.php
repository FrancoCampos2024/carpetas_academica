<?php

namespace App\Repositories\Persona;

use App\Models\Persona;
use App\Trait\FuncionesModelTrait;

class PersonaRepository implements PersonaRepositoryInterface
{

    use FuncionesModelTrait;
    protected $model;

    public function __construct(Persona $model)
    {
        $this->model = $model;
    }

    public function existeItemCatalogo(int $tipo_documento_catalogo): bool
    {
        return Persona::where('tipo_persona_catalogo', $tipo_documento_catalogo)
                ->exists();
    }

    public function existePorNombreCompleto(
    string $nombres,
    string $apellidoPaterno,
    string $apellidoMaterno
    ): bool {
        return $this->model::where('nombres_persona', $nombres)
            ->where('apellido_pat_persona', $apellidoPaterno)
            ->where('apellido_mat_persona', $apellidoMaterno)
            ->exists();
    }


    public function existePorDniPersona(string $dni): bool
    {
        return $this->model::where('dni_persona', $dni)->exists();
    }

    public function existePorCorreoPersona(string $correo): bool
    {
        return $this->model::where('correo_persona', $correo)->exists();
    }

    public function existeDniParaOtro(string $dni, int $idPersona): bool
    {
        return Persona::where('dni_persona', $dni)
            ->where('id_persona', '!=', $idPersona)
            ->exists();
    }

    public function existeCorreoParaOtro(string $correo, int $idPersona): bool
    {
        return Persona::where('correo_persona', $correo)
            ->where('id_persona', '!=', $idPersona)
            ->exists();
    }

    public function existeNombreCompletoParaOtro(
        string $nombres,
        string $apellidoPaterno,
        string $apellidoMaterno,
        int $idPersona
    ): bool {
        return $this->model::where('nombres_persona', $nombres)
            ->where('apellido_pat_persona', $apellidoPaterno)
            ->where('apellido_mat_persona', $apellidoMaterno)
            ->where('id_persona', '!=', $idPersona)
            ->exists();
    }




}
