<?php

namespace App\Livewire\Catalogo;

use App\Enums\EstadoEnum;
use App\Models\Catalogo;
use App\Services\CatalogoService;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;

class ListaPadre extends Component
{
    public $buscar = '';
    public $tabla = null;

   
    public $modo_modal = 1;
    public $titulo_modal = 'Nueva Tabla';
    public $id_catalogo = null;
    public $nombre_tabla = '';

    protected CatalogoService $catalogoService;

    public function __construct(){
        $this->catalogoService = resolve(CatalogoService::class);
    }



    protected function rules()
    {
        return [
            'nombre_tabla' => 'required|string|max:100',
        ];
    }

    #[On('refrescar_tabla')]
    public function cargando_padre()
    {
        $this->dispatch('cargando_padre', cargando: false);
    }

    protected $messages = [
        'nombre_tabla.required' => 'El nombre de la tabla es obligatorio',
        'nombre_tabla.max' => 'El nombre no puede exceder 100 caracteres',
    ];

    #[Computed]
    public function catalogos()
    {
        return $this->catalogoService->listarPorPadre(0,$this->buscar)->get();
    }

    public function seleccionar_tabla($id_catalogo)
    {
        $this->tabla = $id_catalogo;
        $this->dispatch('tabla-seleccionada', id_tabla: $id_catalogo);
        $this->dispatch('obtener_id_tabla', id_tabla: $id_catalogo);
    }

    public function modalCatalogo($nombre, $accion)
    {
        $this->dispatch('modal', nombre: $nombre, accion: $accion);
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function render()
    {
        return view('livewire.catalogo.lista-padre');
    }
}
