<?php

namespace App\Livewire\Carpeta;

use App\Enums\EstadoEnum;
use App\Services\AlumnoService;
use App\Services\CatalogoService;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\Attributes\On;

#[Layout('components.layouts.app')]
class Index extends Component
{
    //Reder de la vista
    public function render()
    {
        return view('livewire.carpeta.index', [
            'pageTitle' => 'Gestión de Carpetas'
        ]);
    }
}
