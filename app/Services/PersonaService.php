<?php

namespace App\Services;

use App\Models\Persona;
use App\Repositories\Persona\PersonaRepositoryInterface;
use Illuminate\Support\Facades\DB;

class PersonaService
{
    protected PersonaRepositoryInterface $personaRepository;

    public function __construct()
    {
        $this->personaRepository = resolve(PersonaRepositoryInterface::class);
    }

    public function listar(){
        return $this->personaRepository->listar();
    }

    public function registrar(array $datos)
    {
        DB::beginTransaction();

        try {
            if ($this->personaRepository->existePorNombreCompleto(
                $datos['nombres_persona'],
                $datos['apellido_pat_persona'],
                $datos['apellido_mat_persona']
            )) {
                throw new \Exception('La persona ya está registrada.');
            }

            if ($this->personaRepository->existePorDniPersona($datos['dni_persona'])) {
                throw new \Exception('El DNI ya está registrado.');
            }

            if (!empty($datos['correo_persona']) &&
                $this->personaRepository->existePorCorreoPersona($datos['correo_persona'])) {
                throw new \Exception('El correo ya está registrado.');
            }


            $persona = $this->personaRepository->registrar($datos);

            DB::commit();

            return $persona;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Error al registrar la persona.'.$e->getMessage());
        }
    }


    public function modificar(array $datos, Persona $persona)
    {
        DB::beginTransaction();

        try {

            if ($this->personaRepository->existeNombreCompletoParaOtro(
                $datos['nombres_persona'],
                $datos['apellido_pat_persona'],
                $datos['apellido_mat_persona'],
                $persona->id_persona
            )) {
                throw new \Exception('Ya existe otra persona con el mismo nombre completo.');
            }

            if ($this->personaRepository->existeDniParaOtro(
                $datos['dni_persona'],
                $persona->id_persona
            )) {
                throw new \Exception('El DNI ya está registrado en otra persona.');
            }

            if (!empty($datos['correo_persona']) &&
                $this->personaRepository->existeCorreoParaOtro(
                    $datos['correo_persona'],
                    $persona->id_persona
                )) {
                throw new \Exception('El correo ya está registrado en otra persona.');
            }

            $persona = $this->personaRepository->modificar($datos, $persona);

            DB::commit();
            return $persona;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Ocurrió un error al modificar la persona.');
        }
    }

    public function cambiarEstado(Persona $persona, string $estado)
    {
        DB::beginTransaction();

        try {
            $persona = $this->personaRepository->modificar(
                ['estado_persona' => $estado],
                $persona
            );

            DB::commit();
            return $persona;

        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Error al cambiar el estado de la persona.');
        }
    }


    public function obtenerPorId(int $id, array $relaciones = []){
        return $this->personaRepository->obtenerPorId($id,$relaciones);
    }

    public function listarPaginado(
        int $paginado = 10,
        ?string $buscar = null,
        string $columnaOrden = 'id_persona',
        string $orden = 'asc',
        array $relaciones = []
    ) {
        return $this->personaRepository->listarPaginado(
            $paginado,
            $buscar,
            $columnaOrden,
            $orden,
            $relaciones
        );
    }

    public function existeItemCatalogo(int $tipo_documento_catalogo){
        return $this->personaRepository->existeItemCatalogo($tipo_documento_catalogo);
    }

    public function eliminar (Persona $persona){
        return $this->personaRepository->eliminar($persona);
    }

}
