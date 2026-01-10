<?php

namespace App\Livewire\Documento\DetalleNoUnico;

use App\Services\AlumnoService;
use App\Services\CatalogoService;
use App\Services\DocumentoService;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
class Index extends Component
{
    use WithFileUploads;
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $id_alumno;
    public $id_tipo;

    public $archivoActual = null;
    public $modoEdicion = false;

    public $nombre_archivo_modal;
    public $archivo_modal;

    public $documento;
    public $documentoEliminarId;
    public $nombre_registro_eliminar;
    public $mensaje_cuerpo_eliminar;
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

    #[Computed]
    public function documentos()
    {
        return $this->documentoService->obtenerDocumentosTipoIdalumno(
            $this->id_tipo,
            $this->id_alumno,
            10
        );
    }

    #[Computed]
    public function nombreTipoDocumento()
    {
        $tipo = $this->catalogoService->obtenerPorid($this->id_tipo, []);
        return $tipo->descripcion_catalogo ?? '';
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
                    $this->id_tipo,
                    $this->nombre_archivo_modal
                );

                $mensajeToastr = mensajeToastr(
                    false, true, '3000', 'Éxito', 'success',
                    'Documento registrado correctamente', 'top', 'right'
                );
            } else {

                if ($this->archivo_modal) {
                    $this->documentoService->actualizarDocumentoPdf(
                        $this->archivo_modal,
                        $this->documento,
                        $this->nombre_archivo_modal
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
        $this->dispatch('documentoGuardado');

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
    public function abrirModalguardarDocumento()
    {
        $this->limpiarModal();
        $this->modoEdicion = false;
        $this->archivoActual = null;

        $this->dispatch('cargando', cargando: 'false');
        $this->modalDocumento('#modalGuardarDocumento', 'show');
    }

    #[On('abrirModalEditarDocumento')]
    public function abrirModalEditarDocumento($id_documento)
    {
        $this->limpiarModal();

        $documento = $this->documentoService->obtenerPorId($id_documento, []);

        if (!$documento) {
            return;
        }

        $this->documento = $documento;
        $this->modoEdicion = true;
        $this->archivoActual = $documento->ruta_documento;
        $this->nombre_archivo_modal = $documento->nombre_documento;
        $this->id_tipo = $documento->tipo_documento_catalogo;

        $this->dispatch('cargando', cargando: 'false');
        $this->dispatch('modal', nombre: '#modalGuardarDocumento', accion: 'show');
    }

    #[On('abrirModalEliminarDocumento')]
    public function abrirModalEliminarDocumento($id_documento)
    {
        $documento = $this->documentoService->obtenerPorId($id_documento, []);
        $this->dispatch('cargando', cargando: 'false');
        if (!$documento) return;

        $this->documentoEliminarId = $documento->id_documento;
        $this->nombre_registro_eliminar = $documento->nombre_documento;
        $this->mensaje_cuerpo_eliminar =
            'Esta acción enviará el documento a eliminados y no se borrará físicamente.';

        $this->modalDocumento('#modal-eliminar-registro', 'show');
    }

    public function modalDocumento($nombre, $accion)
    {
        $this->dispatch('modal', nombre: $nombre, accion: $accion);
    }

    public function updatedArchivoModal()
    {
        $this->resetErrorBag('archivo_modal');

        if ($this->archivo_modal) {
            $this->archivoActual = null;
        }

        if (!($this->archivo_modal instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile)) {
            $this->addError('archivo_modal', 'El archivo no se pudo procesar.');
            $this->archivo_modal = null;
            return;
        }

        $this->validateOnly('archivo_modal');
        $this->nombre_archivo_modal = $this->archivo_modal->getClientOriginalName();
    }

    public function eliminar_archivo_modal()
    {
        $this->archivo_modal = null;
        $this->nombre_archivo_modal = null;
        $this->resetErrorBag('archivo_modal');
    }

    #[On('errorArchivoGrande')]
    public function mostrarErrorArchivoGrande()
    {
        $this->addError('archivo_modal', 'El archivo debe pesar menos de 2 MB.');
        $this->archivo_modal = null;
        $this->nombre_archivo_modal = null;
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
            'archivo_modal.required' => 'Debe seleccionar un archivo PDF.',
            'archivo_modal.mimes'    => 'Solo se permiten archivos PDF.',
            'archivo_modal.max'      => 'El archivo no debe superar los 2 MB.',
        ];
    }

    public function limpiarModal()
    {
        $this->reset(['archivo_modal', 'nombre_archivo_modal']);
    }

    public function mount(string $alumno, string $tipo)
    {
        $idAlumnoReal = desencriptar($alumno);
        $idTipoReal   = desencriptar($tipo);

        if (!$idAlumnoReal || !$idTipoReal) {
            abort(404, 'Parámetros de acceso no válidos');
        }

        $this->id_alumno = $idAlumnoReal;
        $this->id_tipo   = $idTipoReal;

        $this->puedeCrear = Gate::allows('autorizacion', ['CREAR', 'GESTION CARPETA']);
        $this->puedeModificar = Gate::allows('autorizacion', ['MODIFICAR', 'GESTION CARPETA']);
        $this->puedeEliminar = Gate::allows('autorizacion', ['ELIMINAR', 'GESTION CARPETA']);
        $this->puedeVer = Gate::allows('autorizacion', ['VER', 'GESTION CARPETA']);
    }

    public function render()
    {
        return view('livewire.documento.detalle-no-unico.index');
    }

}
