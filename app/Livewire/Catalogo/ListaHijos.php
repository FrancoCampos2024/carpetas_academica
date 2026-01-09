<?php

namespace App\Livewire\Catalogo;

use App\Enums\EstadoEnum;
use App\Services\CatalogoService;
use App\Services\DocumentoService;
use App\Services\PersonaService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class ListaHijos extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public ?int $id_tabla = null;

    public ?int $id_item = null;
    public string $descripcion = '';
    public string $tituloModal = 'Nuevo item';

    public ?int $id_catalogo_estado = null;
    public int $modoCatalogo = 1;
    public int $modoCatalogoEstado = 1;
    public string $nombreCatalogoEstado = '';
    public string $tituloModalEstado = 'Cambiar estado';

    public bool $es_unico = false;

    public $puedeCrear;
    public $puedeModificar;
    public $puedeCambiarEstado;

    protected CatalogoService $catalogoService;
    protected DocumentoService $documentoService;
    protected PersonaService $personaService;

    public function __construct()
    {
        $this->catalogoService  = resolve(CatalogoService::class);
        $this->documentoService = resolve(DocumentoService::class);
        $this->personaService   = resolve(PersonaService::class);
    }

    #[On('tabla-seleccionada')]
    public function cargarHijos($id_tabla): void
    {
        $this->id_tabla = $id_tabla ? (int) $id_tabla : null;
        $this->resetPage();
    }

    public function getListaHijosProperty()
    {
        if (!$this->id_tabla) {
            return new LengthAwarePaginator([], 0, 10, 1, [
                'path'  => request()->url(),
                'query' => request()->query(),
            ]);
        }

        return $this->catalogoService
            ->listarPorPadre($this->id_tabla)
            ->paginate(8);
    }


    public function puedeEditarCatalogo(int $idCatalogo): bool
    {
        return (
            $this->documentoService->existeItemCatalogo($idCatalogo) ||
            $this->personaService->existeItemCatalogo($idCatalogo)
        );
    }


    #[On('abrirModalItem')]
    public function abrirModalItem()
    {
        $this->modoCatalogo = 1;
        $this->tituloModal = 'Nuevo item';

        $this->reset(['descripcion', 'id_item']);
        $this->resetValidation();

        $this->es_unico = false;

        $this->dispatch('cargando', cargando: 'false');
        $this->modalCatalogo('#modal-item', 'show');
    }

    #[On('abrirModalItemEditar')]
    public function abrirModalItemEditar($id)
    {
        if ($this->puedeEditarCatalogo($id)) {
            $this->dispatch(
                'toastr',
                boton_cerrar: true,
                progreso_avance: true,
                duracion: '3000',
                titulo: 'Advertencia',
                tipo: 'warning',
                mensaje: 'Este catálogo ya está siendo utilizado y no puede editarse.',
                posicion_y: 'top',
                posicion_x: 'right'
            );
            return;
        }

        $this->modoCatalogo = 2;
        $this->id_item = $id;
        $this->tituloModal = 'Editar item';

        $catalogo = $this->catalogoService->obtenerPorid($id, []);
        $this->descripcion = $catalogo->descripcion_catalogo;
        $this->es_unico = (bool) $catalogo->unico_catalogo;

        $this->resetValidation();
        $this->modalCatalogo('#modal-item', 'show');
    }

    #[On('abrirModalEstado')]
    public function abrirModalEstado(int $id_catalogo)
    {
        $this->tituloModalEstado = 'Cambiar estado';

        $item = $this->catalogoService->obtenerPorid($id_catalogo, []);
        if (!$item) {
            return;
        }

        $this->nombreCatalogoEstado = $item->descripcion_catalogo;
        $this->id_catalogo_estado = $item->id_catalogo;

        $this->modoCatalogoEstado = $item->estado_catalogo === EstadoEnum::HABILITADO->value
            ? 1
            : 0;

        $this->resetValidation();
        $this->dispatch('cargando', cargando: 'false');
        $this->modalCatalogo('#modal-estado-catalogo', 'show');
    }

    public function guardar_item()
    {
        $this->validate([
            'descripcion' => 'required|string|max:100',
        ]);

        try {
            if ($this->modoCatalogo === 1) {
                $this->catalogoService->crear([
                    'descripcion_catalogo' => strtoupper(trim($this->descripcion)),
                    'id_padre'            => $this->id_tabla,
                    'estado_catalogo'     => EstadoEnum::HABILITADO->value,
                    'unico_catalogo'      => $this->es_unico ? 1 : 0,
                ]);
            } else {
                $item = $this->catalogoService->obtenerPorid($this->id_item, []);

                $this->catalogoService->modificar([
                    'descripcion_catalogo' => strtoupper(trim($this->descripcion)),
                    'unico_catalogo'       => $this->es_unico ? 1 : 0,
                ], $item);
            }

            $this->modalCatalogo('#modal-item', 'hide');
            $this->reset(['descripcion', 'modoCatalogo', 'id_item']);
            $this->resetPage();
        } catch (\Exception $e) {
            $this->dispatch('toastr', tipo: 'error', mensaje: $e->getMessage());
        }
    }

    public function estado_catalogo()
    {
        $item = $this->catalogoService->obtenerPorId($this->id_catalogo_estado, []);
        if (!$item) return;

        $nuevoEstado = $this->modoCatalogoEstado === 1
            ? EstadoEnum::DESHABILITADO->value
            : EstadoEnum::HABILITADO->value;

        $this->catalogoService->modificar(
            ['estado_catalogo' => $nuevoEstado],
            $item
        );

        $this->dispatch(
            'cargando_padre',
            cargando: false,
            modo_catalogo: 1
        );

        $this->modalCatalogo('#modal-estado-catalogo', 'hide');
        $this->resetPage();
    }

    public function modalCatalogo($nombre, $accion)
    {
        $this->dispatch('modal', nombre: $nombre, accion: $accion);
    }

    public function mount(){
        $this->puedeCrear = Gate::allows('autorizacion',['CREAR','CATALOGO']);
        $this->puedeModificar = Gate::allows('autorizacion',['MODIFICAR','CATALOGO']);
        $this->puedeCambiarEstado = Gate::allows('autorizacion',['ESTADO','CATALOGO']);
    }

    public function render()
    {
        return view('livewire.catalogo.lista-hijos');
    }
}
