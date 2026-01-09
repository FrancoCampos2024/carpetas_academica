<?php

namespace App\Livewire\Seguridad\Persona;

use App\Enums\EstadoEnum;
use App\Services\CatalogoService;
use App\Services\PersonaService;
use App\Services\UsuarioService;
use Livewire\Attributes\On;
use Livewire\Component;

class Index extends Component
{
    public $dni_persona;
    public $nombres_persona;
    public $apellido_pat_persona;
    public $apellido_mat_persona;
    public $telefono_persona;
    public $correo_persona;
    public $tipo_persona_catalogo;
    public $estado_persona = true;
    public $modo_edicion = false;
    public $id_persona;
    public $lista_tipos_persona = [];
    public $persona_estado_id;
    public $modo_modal;
    public $mensaje_cuerpo_modal;
    public $nombre_completo_persona;
    public $personaEliminarId;
    public $nombre_persona_eliminar;
    public $mensaje_cuerpo_eliminar;



    protected CatalogoService $catalogoService;
    protected PersonaService $personaService;
    protected UsuarioService $usuarioService;

    public function __construct()
    {
        $this->catalogoService = resolve(CatalogoService::class);
        $this->personaService  = resolve(PersonaService::class);
        $this->usuarioService  = resolve(UsuarioService::class);
    }

    public function limpiarModal()
    {
        $this->reset([
            'dni_persona',
            'nombres_persona',
            'apellido_pat_persona',
            'apellido_mat_persona',
            'telefono_persona',
            'correo_persona',
            'tipo_persona_catalogo',
            'estado_persona',
            'id_persona',
            'modo_edicion',
        ]);

        $this->estado_persona = true;
        $this->resetErrorBag();
        $this->resetValidation();
    }

    #[On('abrirModalGuardarPersona')]
    public function abrirModalGuardarPersona()
    {
        $this->limpiarModal();
        $this->modo_edicion = false;
        $this->lista_tipos_persona = $this->catalogoService
            ->listarPorPadre(1)
            ->get()
            ->toArray();

        $this->dispatch('cargando', cargando: 'false');
        $this->modalPersona('#modal-persona', 'show');
    }

    #[On('modalEditarPersona')]
    public function modalEditarPersona($id_persona)
    {
        $this->limpiarModal();

        $persona = $this->personaService->obtenerPorId($id_persona);

        $this->modo_edicion = true;

        $this->id_persona            = $persona->id_persona;
        $this->dni_persona           = $persona->dni_persona;
        $this->nombres_persona       = $persona->nombres_persona;
        $this->apellido_pat_persona  = $persona->apellido_pat_persona;
        $this->apellido_mat_persona  = $persona->apellido_mat_persona;
        $this->telefono_persona      = $persona->telefono_persona;
        $this->correo_persona        = $persona->correo_persona;
        $this->tipo_persona_catalogo = $persona->tipo_persona_catalogo;

        $this->lista_tipos_persona = $this->catalogoService->listarTabla(1)->toArray();

        $this->dispatch('cargando', cargando: 'false');
        $this->modalPersona('#modal-persona', 'show');
    }

    #[On('abrirModalEstadoPersona')]
    public function abrirModalEstadoPersona(int $id_persona)
    {
        $persona = $this->personaService->obtenerPorId($id_persona);

        if (!$persona) {
            $this->dispatch('toast', mensaje: 'Persona no encontrada', tipo: 'error');
            return;
        }

        $this->persona_estado_id = $persona->id_persona;
        $this->nombre_completo_persona =
            $persona->nombres_persona . ' ' .
            $persona->apellido_pat_persona . ' ' .
            $persona->apellido_mat_persona;

        if ($persona->estado_persona === EstadoEnum::HABILITADO->value) {
            $this->modo_modal = 0;
            $this->mensaje_cuerpo_modal = 'Esta persona quedará deshabilitada y no podrá ser utilizada en el sistema.';
        } else {
            $this->modo_modal = 1;
            $this->mensaje_cuerpo_modal = 'Esta persona será habilitada nuevamente en el sistema.';
        }
        $this->dispatch('cargando', cargando: 'false');
        $this->modalPersona('#modal-estado-persona', 'show');
    }

    #[On('abrirModalEliminarPersona')]
    public function abrirModalEliminarPersona(int $id_persona)
    {
        $persona = $this->personaService->obtenerPorId($id_persona);

        if (!$persona) {
            $this->dispatch('toastr', ...mensajeToastr(
                false, true, '3000',
                'Error', 'error',
                'Persona no encontrada',
                'top', 'right'
            ));
            return;
        }

        $this->personaEliminarId = $persona->id_persona;

        $this->nombre_persona_eliminar =
            $persona->nombres_persona . ' ' .
            $persona->apellido_pat_persona . ' ' .
            $persona->apellido_mat_persona;

        $this->mensaje_cuerpo_eliminar =
            'Esta acción eliminará la persona del sistema de forma lógica.';

        $this->dispatch('cargando', cargando: 'false');
        $this->modalPersona('#modal-eliminar-persona', 'show');
    }


    public function guardarPersona()
    {
        $this->validate();

        $data = [
            'dni_persona'           => $this->dni_persona,
            'nombres_persona'       => strtoupper($this->nombres_persona),
            'apellido_pat_persona'  => strtoupper($this->apellido_pat_persona),
            'apellido_mat_persona'  => strtoupper($this->apellido_mat_persona),
            'telefono_persona'      => $this->telefono_persona,
            'correo_persona'        => $this->correo_persona,
            'tipo_persona_catalogo' => $this->tipo_persona_catalogo,
        ];

        try {
            if (!$this->modo_edicion) {

                $data['estado_persona'] = EstadoEnum::HABILITADO->value;
                $this->personaService->registrar($data);

                $mensajeToastr = mensajeToastr(
                    false, true, '3000',
                    'Éxito', 'success',
                    'Persona registrada correctamente',
                    'top', 'right'
                );

            } else {

                $persona = $this->personaService->obtenerPorId($this->id_persona);
                $this->personaService->modificar($data, $persona);

                $mensajeToastr = mensajeToastr(
                    false, true, '3000',
                    'Éxito', 'success',
                    'Persona actualizada correctamente',
                    'top', 'right'
                );
            }

        } catch (\Exception $e) {

            $mensajeToastr = mensajeToastr(
                false, true, '5000',
                'Error', 'error',
                $e->getMessage(),
                'top', 'right'
            );
        }

        $this->limpiarModal();
        $this->modalPersona('#modal-persona', 'hide');
        $this->dispatch('refrescar_persona');

        if ($mensajeToastr !== null) {
            $this->dispatch('toastr', ...$mensajeToastr);
        }
    }


    public function cambiar_estado_persona()
    {
        $persona = $this->personaService->obtenerPorId($this->persona_estado_id);

        if (!$persona) {
            $this->dispatch('toast', mensaje: 'Persona no encontrada', tipo: 'error');
            return;
        }

        $nuevoEstado = $this->modo_modal === 1
            ? EstadoEnum::HABILITADO->value
            : EstadoEnum::DESHABILITADO->value;

        $this->personaService->cambiarEstado($persona, $nuevoEstado);

        $mensajeToastr = mensajeToastr(false, true, '3000', 'Éxito', 'success', 'Estado actualizado correctamente', 'top', 'right');

        $this->modalPersona('#modal-estado-persona', 'hide');

        if ($mensajeToastr !== null) {
            $this->dispatch(
                'toastr',
                boton_cerrar: $mensajeToastr['boton_cerrar'],
                progreso_avance: $mensajeToastr['progreso_avance'],
                duracion: $mensajeToastr['duracion'],
                titulo: $mensajeToastr['titulo'],
                tipo: $mensajeToastr['tipo'],
                mensaje: $mensajeToastr['mensaje'],
                posicion_y: $mensajeToastr['posicion_y'],
                posicion_x: $mensajeToastr['posicion_x']
            );
        }

        $this->dispatch('refrescar_persona');
    }

    public function eliminar_persona()
    {
        $persona = $this->personaService->obtenerPorId($this->personaEliminarId);

        if (!$persona) {
            $this->dispatch(
                'toast',
                mensaje: 'Persona no encontrada',
                tipo: 'error'
            );
            return;
        }

        if ($this->usuarioService->personaTieneUsuario($persona->id_persona)) {

            $this->modalPersona('#modal-eliminar-persona', 'hide');

            $mensajeToastr = mensajeToastr(
                false,
                true,
                '4000',
                'Acción no permitida',
                'warning',
                'No se puede eliminar la persona porque tiene una cuenta de usuario asociada.',
                'top',
                'right'
            );

            if ($mensajeToastr !== null) {
                $this->dispatch(
                    'toastr',
                    boton_cerrar: $mensajeToastr['boton_cerrar'],
                    progreso_avance: $mensajeToastr['progreso_avance'],
                    duracion: $mensajeToastr['duracion'],
                    titulo: $mensajeToastr['titulo'],
                    tipo: $mensajeToastr['tipo'],
                    mensaje: $mensajeToastr['mensaje'],
                    posicion_y: $mensajeToastr['posicion_y'],
                    posicion_x: $mensajeToastr['posicion_x']
                );
            }

            $this->reset([
                'personaEliminarId',
                'nombre_persona_eliminar',
                'mensaje_cuerpo_eliminar'
            ]);

            return;
        }

        $this->personaService->eliminar($persona);

        $mensajeToastr = mensajeToastr(
            false,
            true,
            '3000',
            'Éxito',
            'success',
            'Persona eliminada correctamente',
            'top',
            'right'
        );

        $this->modalPersona('#modal-eliminar-persona', 'hide');

        if ($mensajeToastr !== null) {
            $this->dispatch(
                'toastr',
                boton_cerrar: $mensajeToastr['boton_cerrar'],
                progreso_avance: $mensajeToastr['progreso_avance'],
                duracion: $mensajeToastr['duracion'],
                titulo: $mensajeToastr['titulo'],
                tipo: $mensajeToastr['tipo'],
                mensaje: $mensajeToastr['mensaje'],
                posicion_y: $mensajeToastr['posicion_y'],
                posicion_x: $mensajeToastr['posicion_x']
            );
        }

        $this->dispatch('refrescar_persona');

        $this->reset([
            'personaEliminarId',
            'nombre_persona_eliminar',
            'mensaje_cuerpo_eliminar'
        ]);
    }





    public function modalPersona($nombre, $accion)
    {
        $this->dispatch('modal', nombre: $nombre, accion: $accion);
    }

    public function rules()
    {
        return [
            'dni_persona'          => 'required|digits:8',
            'nombres_persona'      => 'required|string|max:100',
            'apellido_pat_persona' => 'required|string|max:100',
            'apellido_mat_persona' => 'required|string|max:100',
            'telefono_persona'     => 'nullable|digits:9',
            'correo_persona'       => 'required|email',
            'tipo_persona_catalogo' => 'required',
        ];
    }

    public function render()
    {
        return view('livewire.seguridad.persona.index');
    }
}
