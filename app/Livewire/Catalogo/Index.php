<?php

namespace App\Livewire\Catalogo;

use Livewire\Attributes\On;
use Livewire\Component;

class Index extends Component
{
    public $id_tabla;

    public function render()
    {
        return view('livewire.catalogo.index');
    }

    public function mount()
    {
        $this->id_tabla = request()->query('tabla');
    }

    #[On('obtener_id_tabla')]
    public function obtener_tabla($id_tabla)
    {
        $this->id_tabla = $id_tabla;
    }
}
