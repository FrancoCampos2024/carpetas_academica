<?php

namespace App\Livewire\Seguridad\Usuario;

use App\Enums\EstadoEnum;
use App\Services\CatalogoService;
use App\Services\PersonaService;
use App\Services\RolService;
use App\Services\UsuarioService;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Index extends Component
{

    public $modo_edicion = false;
    public $id_usuario;

    public $lista_persona;
    public $lista_rol;
    public $nombre_usuario;
    public $contrasenha;

    public $usuario_estado_id;
    public $modo_modal;
    public $mensaje_cuerpo_modal;

    public $usuarioEliminarId;
    public $nombre_usuario_eliminar;
    public $mensaje_cuerpo_eliminar_usuario;

    protected CatalogoService $catalogoService;
    protected RolService $rolService;
    protected PersonaService $personaService;
    protected UsuarioService $usuarioService;

    public function __construct()
    {
        $this->catalogoService = resolve(CatalogoService::class);
        $this->rolService     = resolve(RolService::class);
        $this->personaService = resolve(PersonaService::class);
        $this->usuarioService = resolve(UsuarioService::class);
    }

    #[Computed]
    public function lista_roles()
    {
        return $this->rolService->listar();
    }

    #[Computed]
    public function lista_personas()
    {
        return $this->personaService->listar();
    }

    #[On('abrirModalUsuario')]
    public function abrirModalUsuario()
    {
        $this->resetFormulario();
        $this->modo_edicion = false;

        $this->dispatch('cargando', cargando: 'false');
        $this->modalUsuario('#modal-usuario', 'show');
    }

    #[On('modalEditarUsuario')]
    public function modalEditarUsuario(int $id_usuario)
    {
        $usuario = $this->usuarioService->obtenerPorId($id_usuario);

        if (!$usuario) return;

        $this->modo_edicion   = true;
        $this->id_usuario     = $usuario->id_usuario;
        $this->lista_persona  = $usuario->id_persona;
        $this->lista_rol      = $usuario->id_rol;
        $this->nombre_usuario = $usuario->nombre_usuario;

        $this->dispatch('cargando', cargando: 'false');
        $this->modalUsuario('#modal-usuario', 'show');
    }

    #[On('abrirModalEstadoUsuario')]
    public function abrirModalEstadoUsuario(int $id_usuario)
    {
        $usuario = $this->usuarioService->obtenerPorId($id_usuario);

        if (!$usuario) {
            $this->dispatch('toast', mensaje: 'Usuario no encontrado', tipo: 'error');
            return;
        }

        $this->usuario_estado_id = $usuario->id_usuario;

        if ($usuario->estado_usuario === EstadoEnum::HABILITADO->value) {
            $this->modo_modal = 0;
            $this->mensaje_cuerpo_modal =
                'Este usuario quedará deshabilitado y no podrá acceder al sistema.';
        } else {
            $this->modo_modal = 1;
            $this->mensaje_cuerpo_modal =
                'Este usuario será habilitado nuevamente.';
        }

        $this->nombre_usuario = $usuario->nombre_usuario;
        $this->dispatch('cargando', cargando: 'false');
        $this->modalUsuario('#modal-estado-usuario', 'show');
    }

    #[On('abrirModalEliminarUsuario')]
    public function abrirModalEliminarUsuario(int $id_usuario)
    {
        $usuario = $this->usuarioService->obtenerPorId($id_usuario);

        if (!$usuario) {
            $this->dispatch('toast', mensaje: 'Usuario no encontrado', tipo: 'error');
            return;
        }

        $this->usuarioEliminarId = $usuario->id_usuario;
        $this->nombre_usuario_eliminar = $usuario->nombre_usuario;
        $this->mensaje_cuerpo_eliminar_usuario =
            'Esta acción eliminará el usuario de forma lógica y no podrá volver a iniciar sesión.';

        $this->dispatch('cargando', cargando: 'false');
        $this->modalUsuario('#modal-eliminar-usuario', 'show');
    }

    public function guardar_usuario()
    {
        $this->validate();

        if (
            !$this->modo_edicion &&
            $this->usuarioService->personaTieneUsuario($this->lista_persona)
        ) {
            $this->dispatch(
                'toastr',
                ...mensajeToastr(
                    false, true, '4000',
                    'Acción no permitida', 'warning',
                    'La persona seleccionada ya tiene un usuario registrado.',
                    'top', 'right'
                )
            );

            $this->modalUsuario('#modal-usuario', 'hide');
            return;
        }

        try {
            if (!$this->modo_edicion) {

                $this->usuarioService->registrar([
                    'id_persona'     => $this->lista_persona,
                    'id_rol'         => $this->lista_rol,
                    'nombre_usuario' => strtoupper($this->nombre_usuario),

                    'clave_usuario'  => Hash::make($this->contrasenha),

                    'estado_usuario' => EstadoEnum::HABILITADO,
                ]);

                $mensajeToastr = mensajeToastr(
                    false, true, '3000',
                    'Éxito', 'success',
                    'Usuario registrado correctamente',
                    'top', 'right'
                );
            }

        } catch (Exception $e) {
            $mensajeToastr = mensajeToastr(
                false, true, '5000',
                'Error', 'error',
                $e->getMessage(),
                'top', 'right'
            );
        }

        $this->modalUsuario('#modal-usuario', 'hide');
        $this->dispatch('refrescar_usuario');

        if (isset($mensajeToastr)) {
            $this->dispatch('toastr', ...$mensajeToastr);
        }

        $this->resetFormulario();
    }


    public function cambiar_estado_usuario()
    {
        $usuario = $this->usuarioService->obtenerPorId($this->usuario_estado_id);

        if (!$usuario) {
            $this->dispatch('toast', mensaje: 'Usuario no encontrado', tipo: 'error');
            return;
        }

        $nuevoEstado = $this->modo_modal === 1
            ? EstadoEnum::HABILITADO->value
            : EstadoEnum::DESHABILITADO->value;

        $this->usuarioService->modificar(
            ['estado_usuario' => $nuevoEstado],
            $usuario
        );

        $this->modalUsuario('#modal-estado-usuario', 'hide');

        $this->dispatch(
            'toastr',
            ...mensajeToastr(
                false, true, '3000',
                'Éxito', 'success',
                'Estado actualizado correctamente',
                'top', 'right'
            )
        );

        $this->dispatch('refrescar_usuario');
    }

    public function eliminar_usuario()
    {
        $usuario = $this->usuarioService->obtenerPorId($this->usuarioEliminarId);

        if (!$usuario) {
            $this->dispatch('toast', mensaje: 'Usuario no encontrado', tipo: 'error');
            return;
        }

        $this->usuarioService->eliminar($usuario);

        $this->modalUsuario('#modal-eliminar-usuario', 'hide');

        $this->dispatch(
            'toastr',
            ...mensajeToastr(
                false, true, '3000',
                'Éxito', 'success',
                'Usuario eliminado correctamente',
                'top', 'right'
            )
        );

        $this->dispatch('refrescar_usuario');

        $this->reset([
            'usuarioEliminarId',
            'nombre_usuario_eliminar',
            'mensaje_cuerpo_eliminar_usuario'
        ]);
    }

    protected function rules()
    {
        return [
            'lista_persona'  => 'required',
            'lista_rol'      => 'required',
            'nombre_usuario' => 'required|min:3|max:50',
            'contrasenha'    => $this->modo_edicion
                ? 'nullable|min:8'
                : 'required|min:8',
        ];
    }

    public  function resetFormulario()
    {
        $this->reset([
            'lista_persona',
            'lista_rol',
            'nombre_usuario',
            'contrasenha',
            'id_usuario',
            'modo_edicion'
        ]);

        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function modalUsuario($nombre, $accion)
    {
        $this->dispatch('modal', nombre: $nombre, accion: $accion);
    }

    public function render()
    {
        return view('livewire.seguridad.usuario.index');
    }
}
