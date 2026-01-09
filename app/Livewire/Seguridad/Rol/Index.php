<?php

namespace App\Livewire\Seguridad\Rol;

use App\Enums\EstadoEnum;
use App\Services\RolService;
use App\Services\UsuarioService;
use Livewire\Component;
use Livewire\Attributes\On;

class Index extends Component
{

    public string $tituloModal = 'Nuevo rol';
    public string $nombreRol = '';
    public bool $modoEdicion = false;
    public ?int $idRol = null;

    public ?int $rolEstadoId = null;
    public int $modoModalEstado = 1;
    public string $nombreRolEstado = '';


    public ?int $rolEliminarId = null;
    public string $nombreRolEliminar = '';
    public string $mensajeCuerpoEliminar = '';

    protected RolService $rolService;
    protected UsuarioService $usuarioService;

    public function __construct()
    {
        $this->rolService = resolve(RolService::class);
        $this->usuarioService = resolve(UsuarioService::class);
    }


    protected function rules()
    {
        return [
            'nombreRol' => 'required|min:3|max:60'
        ];
    }

    protected function messages()
    {
        return [
            'nombreRol.required' => 'El nombre del rol es obligatorio.',
            'nombreRol.min'      => 'El nombre del rol debe tener al menos 3 caracteres.'
        ];
    }


    #[On('abrirModalRol')]
    public function abrirModalRol(?int $idRol = null)
    {
        $this->resetFormulario();

        if ($idRol) {
            $rol = $this->rolService->obtenerPorId($idRol);
            if (!$rol) return;

            $this->idRol       = $rol->id_rol;
            $this->nombreRol   = $rol->nombre_rol;
            $this->modoEdicion = true;
            $this->tituloModal = 'Editar rol';
        } else {
            $this->tituloModal = 'Nuevo rol';
        }

        $this->dispatch('cargando', cargando: 'false');
        $this->dispatch('modal', nombre: '#modal-rol', accion: 'show');
    }


    #[On('abrirModalEstadoRol')]
    public function abrirModalEstadoRol(int $idRol)
    {
        $rol = $this->rolService->obtenerPorId($idRol);
        if (!$rol) return;

        $this->rolEstadoId      = $rol->id_rol;
        $this->nombreRolEstado = $rol->nombre_rol;

        $this->modoModalEstado = $rol->estado_rol === EstadoEnum::HABILITADO->value
            ? 2
            : 1;

        $this->dispatch('cargando', cargando: 'false');
        $this->dispatch('modal', nombre: '#modal-estado-rol', accion: 'show');
    }


    #[On('abrirModalEliminarRol')]
    public function abrirModalEliminarRol(int $idRol)
    {
        $rol = $this->rolService->obtenerPorId($idRol);

        if (!$rol) {
            $this->dispatch(
                'toastr',
                ...mensajeToastr(false, true, '4000', 'Error', 'error',
                'Rol no encontrado', 'top', 'right')
            );
            return;
        }

        $this->rolEliminarId     = $rol->id_rol;
        $this->nombreRolEliminar = $rol->nombre_rol;
        $this->mensajeCuerpoEliminar =
            'Esta acción eliminará el rol de forma lógica.';

        $this->dispatch('cargando', cargando: 'false');
        $this->dispatch('modal', nombre: '#modal-eliminar-rol', accion: 'show');
    }


    public function guardarRol()
    {
        $this->validate();

        try {
            if ($this->modoEdicion) {
                $this->rolService->modificar($this->idRol, [
                    'nombre_rol' => strtoupper($this->nombreRol),
                ]);

                $mensajeToastr = mensajeToastr(false, true, '3000', 'Éxito',
                    'success', 'Rol actualizado correctamente', 'top', 'right');
            } else {
                $this->rolService->registrar([
                    'nombre_rol' => strtoupper($this->nombreRol),
                ]);

                $mensajeToastr = mensajeToastr(false, true, '3000', 'Éxito',
                    'success', 'Rol registrado correctamente', 'top', 'right');
            }
        } catch (\Exception $e) {
            $mensajeToastr = mensajeToastr(false, true, '5000', 'Error',
                'error', $e->getMessage(), 'top', 'right');
        }

        $this->dispatch('modal', nombre: '#modal-rol', accion: 'hide');
        $this->dispatch('refrescarRoles');
        $this->resetFormulario();

        if (isset($mensajeToastr)) {
            $this->dispatch('toastr', ...$mensajeToastr);
        }
    }


    public function cambiarEstadoRol()
    {
        try {
            $rol = $this->rolService->obtenerPorId($this->rolEstadoId);

            $nuevoEstado = $rol->estado_rol === EstadoEnum::HABILITADO->value
                ? EstadoEnum::DESHABILITADO->value
                : EstadoEnum::HABILITADO->value;

            $this->rolService->cambiarEstado($this->rolEstadoId, $nuevoEstado);

            $this->dispatch('modal', nombre: '#modal-estado-rol', accion: 'hide');
            $this->dispatch('refrescarRoles');

            $this->dispatch(
                'toastr',
                ...mensajeToastr(false, true, '3000', 'Éxito', 'success',
                'Estado del rol actualizado correctamente', 'top', 'right')
            );
        } catch (\Exception $e) {
            $this->dispatch(
                'toastr',
                ...mensajeToastr(false, true, '5000', 'Error', 'error',
                $e->getMessage(), 'top', 'right')
            );
        }
    }

    public function eliminarRol()
    {
        try {
            $rol = $this->rolService->obtenerPorId($this->rolEliminarId);
            if (!$rol) throw new \Exception('Rol no encontrado.');

            // 🚫 Validar uso
            if ($this->usuarioService->rolTieneUsuarios($rol->id_rol)) {
                $this->dispatch(
                    'toastr',
                    ...mensajeToastr(false, true, '4000', 'Acción no permitida',
                    'warning', 'El rol está asignado a usuarios activos.',
                    'top', 'right')
                );

                $this->dispatch('modal', nombre: '#modal-eliminar-rol', accion: 'hide');
                return;
            }

            $this->rolService->eliminar($rol);

            $this->dispatch(
                'toastr',
                ...mensajeToastr(false, true, '3000', 'Éxito', 'success',
                'Rol eliminado correctamente', 'top', 'right')
            );

            $this->dispatch('modal', nombre: '#modal-eliminar-rol', accion: 'hide');
            $this->dispatch('refrescarRoles');

        } catch (\Exception $e) {
            $this->dispatch(
                'toastr',
                ...mensajeToastr(false, true, '5000', 'Error', 'error',
                $e->getMessage(), 'top', 'right')
            );
        }

        $this->reset([
            'rolEliminarId',
            'nombreRolEliminar',
            'mensajeCuerpoEliminar'
        ]);
    }

    private function resetFormulario()
    {
        $this->reset([
            'nombreRol',
            'idRol',
            'modoEdicion'
        ]);
    }

    public function render()
    {
        return view('livewire.seguridad.rol.index');
    }
}
