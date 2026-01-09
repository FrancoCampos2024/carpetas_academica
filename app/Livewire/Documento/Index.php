<?php

namespace App\Livewire\Documento;

use App\Services\AlumnoService;
use App\Services\CatalogoService;
use App\Services\DocumentoService;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.app')]
class Index extends Component
{
    use WithFileUploads;

    protected $paginationTheme = 'bootstrap';

    public $id_alumno;

    public $id_tipo;
    public $nombre_archivo_modal;
    public $archivo_modal;
    public $documento;
    public $archivoActual = null;
    public $modoEdicion = false;

    public $documentoEliminarId;
    public $nombre_registro_eliminar;
    public $mensaje_cuerpo_eliminar;
    public $puedeListar;
    public $puedeVer;
    public $puedeModificar;
    public $puedeCrear;
    public $puedeEliminar;

    protected DocumentoService $documentoService;
    protected CatalogoService $catalogoService;
    protected AlumnoService $alumnoService;

    public function __construct()
    {
        $this->documentoService = resolve(DocumentoService::class);
        $this->catalogoService  = resolve(CatalogoService::class);
        $this->alumnoService    = resolve(AlumnoService::class);
    }

    public function alumno()
    {
        $alumno = $this->alumnoService->getAlumno($this->id_alumno);
        return $alumno[0] ?? null;
    }

    public function cargar_documento()
    {
        $this->validate();
        $mensajeToastr = null;

        try {
            if (!$this->documento) {
                $this->documentoService->guardarDocumentoPdf(
                    $this->archivo_modal,
                    $this->id_alumno,
                    $this->id_tipo
                );

                $mensajeToastr = mensajeToastr(
                    false, true, '3000', 'Éxito', 'success',
                    'Documento registrado correctamente', 'top', 'right'
                );
            } else {
                if ($this->archivo_modal) {
                    $this->documentoService->actualizarDocumentoPdf(
                        $this->archivo_modal,
                        $this->documento
                    );
                }

                $mensajeToastr = mensajeToastr(
                    false, true, '3000', 'Éxito', 'success',
                    'Documento actualizado correctamente', 'top', 'right'
                );
            }
        } catch (\Exception $e) {
            $mensajeToastr = mensajeToastr(
                false, true, '5000', 'Error', 'error',
                $e->getMessage(), 'top', 'right'
            );
        }

        $this->dispatch('modal', nombre: '#modalGuardarDocumento', accion: 'hide');
        $this->reset(['archivo_modal', 'nombre_archivo_modal', 'documento']);

        if ($mensajeToastr) {
            $this->dispatch('toastr', ...$mensajeToastr);
        }
    }

    public function eliminar_registro()
    {
        try {
            $documento = $this->documentoService->obtenerPorId($this->documentoEliminarId, []);

            if (!$documento) {
                throw new \Exception('El documento no existe.');
            }

            $this->documentoService->eliminar($documento);

            $this->dispatch('modal', nombre: '#modal-eliminar-registro', accion: 'hide');

            $this->reset([
                'documentoEliminarId',
                'nombre_registro_eliminar',
                'mensaje_cuerpo_eliminar',
            ]);

            $this->dispatch(
                'toastr',
                ...mensajeToastr(false, true, '3000', 'Éxito', 'success',
                'Documento eliminado correctamente.', 'top', 'right')
            );
        } catch (\Exception $e) {
            $this->dispatch(
                'toastr',
                ...mensajeToastr(false, true, '5000', 'Error', 'error',
                $e->getMessage(), 'top', 'right')
            );
        }
    }

    #[On('abrirModalguardarDocumento')]
    public function abrirModalguardarDocumento($id_tipo)
    {
        $this->limpiarModal();
        $this->modoEdicion = false;
        $this->archivoActual = null;
        $this->id_tipo = $id_tipo;

        $this->dispatch('cargando', cargando: 'false');
        $this->modalDocumento('#modalGuardarDocumento', 'show');
    }

    #[On('abrirModalEditarDocumento')]
    public function abrirModalEditarDocumento($id_documento)
    {
        $this->limpiarModal();

        $documento = $this->documentoService->obtenerPorId($id_documento, []);
        if (!$documento) return;

        $this->documento = $documento;
        $this->modoEdicion = true;
        $this->archivoActual = $documento->ruta_documento;
        $this->nombre_archivo_modal = basename($documento->ruta_documento);
        $this->id_tipo = $documento->tipo_documento_catalogo;
        $this->dispatch('cargando', cargando: 'false');
        $this->modalDocumento('#modalGuardarDocumento', 'show');
    }

    #[On('abrirModalEliminarDocumento')]
    public function abrirModalEliminarDocumento($id_documento)
    {
        $documento = $this->documentoService->obtenerPorId($id_documento, []);
        $this->dispatch('cargando', cargando: 'false');
        if (!$documento) return;

        $this->documentoEliminarId = $documento->id_documento;
        $this->nombre_registro_eliminar = basename($documento->ruta_documento);
        $this->mensaje_cuerpo_eliminar =
            'Esta acción enviará el documento a eliminados y no se borrará físicamente.';

        $this->modalDocumento('#modal-eliminar-registro', 'show');
    }

    public function modalDocumento($nombre, $accion)
    {
        $this->dispatch('modal', nombre: $nombre, accion: $accion);
    }

    protected function rules()
    {
        return !$this->documento
            ? ['archivo_modal' => 'required|mimes:pdf|max:2048']
            : ['archivo_modal' => 'nullable|mimes:pdf|max:2048'];
    }

    protected function messages()
    {
        return [
            'archivo_modal.uploaded' => 'El archivo no se pudo subir.',
            'archivo_modal.mimes'    => 'Solo se permiten archivos PDF.',
            'archivo_modal.max'      => 'El archivo no debe superar los 2 MB.',
            'archivo_modal.required' => 'Debe seleccionar un archivo PDF.',
        ];
    }

    public function limpiarModal()
    {
        $this->reset([
            'archivo_modal',
            'nombre_archivo_modal',
            'documento', 
            'archivoActual',
            'modoEdicion'
        ]);
    }

    public function mount(String $alumno)
    {

        $idReal = desencriptar($alumno);

        if (!$idReal) {
            abort(404, 'Parámetros de acceso no válidos');
        }

        $this->id_alumno = $idReal;
        $this->puedeListar = Gate::allows('autorizacion', ['LISTAR', 'GESTION CARPETA']);
        $this->puedeCrear = Gate::allows('autorizacion', ['CREAR', 'GESTION CARPETA']);
        $this->puedeModificar = Gate::allows('autorizacion', ['MODIFICAR', 'GESTION CARPETA']);
        $this->puedeEliminar = Gate::allows('autorizacion', ['ELIMINAR', 'GESTION CARPETA']);
        $this->puedeVer = Gate::allows('autorizacion', ['VER', 'GESTION CARPETA']);

    }

    public function updatedArchivoModal()
    {
        if ($this->archivo_modal) {
            $this->nombre_archivo_modal = $this->archivo_modal->getClientOriginalName();
        }
    }

    public function eliminar_archivo_modal()
    {
        $this->reset([
            'archivo_modal',
            'nombre_archivo_modal',
        ]);
    }




    public function render()
    {
        return view('livewire.documento.index', [
            'alumno' => $this->alumno(),

            'tiposDocumentosUnicos' =>
                $this->catalogoService->getTiposDocumentos($this->id_alumno, 1, 5),

            'tiposDocumentosNoUnicos' =>
                $this->catalogoService->getTiposDocumentos($this->id_alumno, 0, 5),
        ]);
    }
}
