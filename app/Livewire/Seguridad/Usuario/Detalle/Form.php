<?php

namespace App\Livewire\Seguridad\Usuario\Detalle;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Hash;
use App\Models\Rol;
use App\Services\CatalogoService;
use App\Services\PersonaService;
use App\Services\RolService;
use App\Services\UsuarioService;
use Livewire\Attributes\Computed;

class Form extends Component
{

    public $id_usuario;

    public string $nombre_user;
    public string $persona_user;
    public string $usuario_nombre_rol;
    public string $documento;

    public string $nombre_usuario = '';
    public ?string $contrasenha = null;
    public ?int $lista_rol = null;

    public int $modo_modal = 0;

    protected CatalogoService $catalogoService;
    protected RolService $rolService;
    protected PersonaService $personaService;
    protected UsuarioService $usuarioService;

    public function __construct()
    {
        $this->catalogoService = resolve(CatalogoService::class);
        $this->rolService = resolve(RolService::class);
        $this->personaService = resolve(PersonaService::class);
        $this->usuarioService = resolve(UsuarioService::class);
    }

    public function mount($usuario)
    {
        $idReal = desencriptar($usuario);

        if (!$idReal) {
            abort(404);
        }

        $this->id_usuario = $idReal;
        $this->cargarUsuario();
    }

    private function cargarUsuario()
    {
        $usuario = $this->usuarioService->obtenerPorId($this->id_usuario, ['persona', 'rol']);

        $this->nombre_user = $usuario->nombre_usuario;
        $this->nombre_usuario = $usuario->nombre_usuario;
        $this->persona_user = $usuario->persona->nombres_persona . ' ' .
                            $usuario->persona->apellido_pat_persona . ' ' .
                            $usuario->persona->apellido_mat_persona;
        $this->documento = $usuario->persona->dni_persona ?? '';
        $this->usuario_nombre_rol = $usuario->rol?->nombre_rol ?? 'SIN ROL';
        $this->lista_rol = $usuario->id_rol;
    }

    #[On('cargar_modal_modificar_usuario')]
    public function abrirModalUsuario()
    {
        $this->modo_modal = 2;
        $this->dispatch('cargando', cargando: 'false');
        $this->dispatch('modal', nombre: '#modal-modificar-usuario', accion: 'show');
    }

    #[On('cargar_modal_modificar_contrasenha')]
    public function abrirModalContrasenha()
    {
        $this->modo_modal = 2;
        $this->contrasenha = null;
        $this->dispatch('cargando', cargando: 'false');
        $this->dispatch('modal', nombre: '#modal-modificar-contrasenha', accion: 'show');
    }

    #[On('cargar_modal_modificar_rol')]
    public function abrirModalRol()
    {
        $this->modo_modal = 2;
        $this->dispatch('autocompletado');
        $this->dispatch('cargando', cargando: 'false');
        $this->dispatch('modal', nombre: '#modal-modificar-rol', accion: 'show');
    }

    public function modificar_usuario()
    {
        $this->validate(['nombre_usuario' => 'required|min:3|max:50']);

        $usuario = $this->usuarioService->obtenerPorId($this->id_usuario);
        $data = ['nombre_usuario' => strtoupper($this->nombre_usuario)];
        $this->usuarioService->modificar($data, $usuario);

        $mensajeToastr = mensajeToastr(false, true, '3000', 'Éxito', 'success', 'Usuario actualizado correctamente', 'top', 'right');
        $this->cerrarModal('#modal-modificar-usuario');

        if ($mensajeToastr !== null) {
            $this->dispatch('toastr',
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
    }

    public function modificar_contrasenha()
    {
        $this->validate(['contrasenha' => 'required|min:8|max:20']);

        $usuario = $this->usuarioService->obtenerPorId($this->id_usuario);
        $data = ['clave_usuario' => Hash::make($this->contrasenha)];
        $this->usuarioService->modificar($data, $usuario);

        $mensajeToastr = mensajeToastr(false, true, '3000', 'Éxito', 'success', 'Contraseña actualizado correctamente', 'top', 'right');
        $this->contrasenha = null;
        $this->cerrarModal('#modal-modificar-contrasenha');

        if ($mensajeToastr !== null) {
            $this->dispatch('toastr',
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
    }

    public function modificar_rol()
    {
        $this->validate(['lista_rol' => 'required|exists:ta_rol,id_rol']);

        $usuario = $this->usuarioService->obtenerPorId($this->id_usuario);
        $data = ['id_rol' => $this->lista_rol];
        $this->usuarioService->modificar($data, $usuario);

        $mensajeToastr = mensajeToastr(false, true, '3000', 'Éxito', 'success', 'Rol actualizado correctamente', 'top', 'right');
        $this->cerrarModal('#modal-modificar-rol');

        if ($mensajeToastr !== null) {
            $this->dispatch('toastr',
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
    }

    private function cerrarModal(string $modal)
    {
        $this->cargarUsuario();
        $this->dispatch('modal', nombre: $modal, accion: 'hide');
        $this->dispatch('cargando');
    }

    public function lista_roles()
    {
        return Rol::where('estado_rol', 'HAB')->get();
    }

    public function render()
    {
        return view('livewire.seguridad.usuario.detalle.form');
    }
}
