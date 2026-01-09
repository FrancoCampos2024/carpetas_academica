<?php

namespace App\Livewire\Seguridad\Auth;

use App\Services\AutenticacionException;
use App\Services\ErrorConexionException;
use App\Services\UsuarioService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.auth')]
#[Title('Login | Sistema de Gestion de Documentos Academicos | SGDA')]
class Login extends Component
{
    public $titulo = '¡Bienvenido!';
    public $usuario = '';
    public $password = '';
    public $remember = false;
    public $mensaje_error;

    protected UsuarioService $usuarioService;

    public function __construct()
    {
        $this->usuarioService = resolve(UsuarioService::class);
    }

    // Validación dinámica
    public function rules()
    {
        return [
            'usuario' => 'required|min:3|max:30|regex:/^[A-Za-z0-9@._-]+$/',
            'password' => 'required|min:8|max:20'
        ];
    }

    public function messages()
    {
        return [
            'password.required' => 'El campo contraseña es obligatorio.',
            'password.min' => 'El campo contraseña debe contener al menos 8 caracteres.'
        ];
    }

    public function iniciarSesion()
    {
        $this->validate();

        try {
            $usuarioInput = limpiarCadena($this->usuario);

            $usuarioModel = $this->usuarioService->autenticar($usuarioInput, $this->password);

            Auth::login($usuarioModel, $this->remember);
            Session::regenerate();

            return redirect()->intended('/inicio');

        } catch (ErrorConexionException $e) {
            session()->flash('message', $e->getMessage());
        } catch (AutenticacionException $e) {
            session()->flash('message', $e->getMessage());
        } catch (Exception $e) {
            session()->flash('message', "Error inesperado: " . $e->getMessage());
        }
    }


    public function updated($valor)
    {
        $this->validateOnly($valor);
    }

    public function mount()
    {
        $this->usuario = '';
        $this->password = '';
    }

    public function render()
    {
        return view('livewire.seguridad.auth.login');
    }
}
