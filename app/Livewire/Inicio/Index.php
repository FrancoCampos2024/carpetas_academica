<?php

namespace App\Livewire\Inicio;

use App\Models\Permiso;
use App\Services\AlumnoService;
use App\Services\CatalogoService;
use App\Services\DocumentoService;
use App\Services\MenuService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
class Index extends Component
{
    use WithPagination;

    public ?int $id_tipo_documento = null;
    public ?int $id_estudiante = null;

    public array $documentos = [];
    public bool $busquedaRealizada = false;

    public bool $puedeVerInicio = false;

    protected CatalogoService $catalogoService;
    protected AlumnoService $alumnoService;
    protected DocumentoService $documentoService;
    protected MenuService $menuService;

    public function boot(
        CatalogoService $catalogoService,
        AlumnoService $alumnoService,
        DocumentoService $documentoService,
        MenuService $menuService
    ) {
        $this->catalogoService  = $catalogoService;
        $this->alumnoService    = $alumnoService;
        $this->documentoService = $documentoService;
        $this->menuService      = $menuService;
    }

    public function mount(): void
    {
        if (!Auth::check()) {
            $this->puedeVerInicio = false;
            $this->busquedaRealizada = false;
            $this->documentos = [];
            return;
        }

        $this->puedeVerInicio = Gate::allows('autorizacion', ['VER', 'INICIO']);

        if (!$this->puedeVerInicio) {
            $this->busquedaRealizada = false;
            $this->documentos = [];
            $this->resetValidation();
        }
    }

    #[Computed]
    public function tiposDocumentos()
    {
        return $this->catalogoService
            ->listarPorPadre(4)
            ->get();
    }

    public function buscarEstudiantes(string $query = '')
    {
        $response = $this->alumnoService->getAlumnos($query, 10);
        $alumnos = $response['data'] ?? $response;

        return collect($alumnos)->map(function ($a) {
            return [
                'id'   => $a['id'],
                'text' => trim(
                    $a['apellido_paterno'] . ' ' .
                    $a['apellido_materno'] . ', ' .
                    $a['nombre']
                ),
            ];
        })->values();
    }

    public function buscar(): void
    {
        if (!$this->puedeVerInicio) {
            abort(403);
        }

        $this->validate([
            'id_tipo_documento' => 'required|integer',
            'id_estudiante'     => 'required|integer',
        ]);

        $this->busquedaRealizada = true;

        $resultado = $this->documentoService
            ->obtenerDocumentosTipoIdalumno(
                $this->id_tipo_documento,
                $this->id_estudiante,
                10
            );

        $this->documentos = $resultado->items();

        if (empty($this->documentos)) {
            $this->dispatch('notificar', [
                'tipo' => 'warning',
                'mensaje' => 'No se encontraron documentos para este estudiante.',
            ]);
        }
    }

    public function evaluarInicio(): void
    {
        if (empty($this->id_estudiante) && empty($this->id_tipo_documento)) {
            $this->busquedaRealizada = false;
            $this->documentos = [];
            $this->resetValidation();
        }
    }

    public function render()
    {
        return view('livewire.inicio.index');
    }
}
