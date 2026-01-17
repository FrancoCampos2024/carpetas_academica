<?php

namespace App\Livewire\Reporte;

use App\Services\AlumnoService;
use App\Services\CatalogoService;
use App\Services\DocumentoService;
use App\Services\UsuarioService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class Tabla extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public int $id_tipo_documento = 0;
    public ?string $fecha_desde = null;
    public ?string $fecha_hasta = null;
    public $puedeExportar;
    public string $filtro_estado = 'creados';
    public bool $ver_eliminados = false;

    protected DocumentoService $documentoService;
    protected CatalogoService $catalogoService;
    protected AlumnoService $alumnoService;
    protected UsuarioService $usuarioService;

    public function boot(
        DocumentoService $documentoService,
        CatalogoService $catalogoService,
        AlumnoService $alumnoService,
        UsuarioService $usuarioService
    ) {
        $this->documentoService = $documentoService;
        $this->catalogoService  = $catalogoService;
        $this->alumnoService    = $alumnoService;
        $this->usuarioService = $usuarioService;
    }

    public function mount()
    {
        $this->fecha_desde = now()->startOfMonth()->format('Y-m-d');
        $this->fecha_hasta = now()->format('Y-m-d');

        $this->dispatch('sync-tipo-select', tipo: $this->id_tipo_documento);
        $this->puedeExportar = Gate::allows('autorizacion',['EXPORTAR','REPORTE']);

    }

    public function setTipoDocumento($value)
    {
        $this->id_tipo_documento = (int)($value ?: 0);
        $this->resetPage();

        $this->dispatch('sync-tipo-select', tipo: $this->id_tipo_documento);
    }

    public function aplicarFiltros()
    {
        $this->ver_eliminados = ($this->filtro_estado === 'eliminados');
        $this->resetPage();
        $this->dispatch('sync-tipo-select', tipo: $this->id_tipo_documento);
    }

    public function limpiarFiltros()
    {
        $this->id_tipo_documento = 0;
        $this->fecha_desde = now()->startOfMonth()->format('Y-m-d');
        $this->fecha_hasta = now()->format('Y-m-d');
        $this->resetPage();

        $this->dispatch('sync-tipo-select', tipo: 0);
    }

    #[Computed]
    public function tiposDocumentos()
    {
        return $this->catalogoService->listarPorPadre(4)->get();
    }

    #[Computed]
    public function tiposMap(): array
    {
        return $this->tiposDocumentos
            ->pluck('descripcion_catalogo', 'id_catalogo')
            ->toArray();
    }

    #[Computed]
    public function Documentos()
    {
        $tipo  = (int) ($this->id_tipo_documento ?? 0);
        $desde = $this->fecha_desde ?? now()->startOfMonth()->format('Y-m-d');
        $hasta = $this->fecha_hasta ?? now()->format('Y-m-d');

        // 1. Determinar la columna de usuario según el estado
        $esModoEliminado = ($this->filtro_estado === 'eliminados');
        $columnaUsuario = $esModoEliminado ? 'au_usuarioel' : 'au_usuariocr';

        $paginator = $this->documentoService
            ->paginarPorTipoYRangoCreacion($tipo, $desde, $hasta, 10, $this->filtro_estado);

        $items = $paginator->getCollection();
        $tiposMap = $this->tiposMap;

        // 2. Obtener IDs de usuarios dinámicamente (creador o eliminador)
        $idsUsuarios = $items
            ->pluck($columnaUsuario)
            ->filter()
            ->unique()
            ->values()
            ->all();

        $usuariosMap = $this->usuarioService->obtenerUsuariosPorIds($idsUsuarios);

        // 3. Obtener IDs de alumnos
        $idsAlumnos = $items->pluck('id_alumno')->filter()->unique()->values()->all();
        $alumnosMap = [];
        foreach ($idsAlumnos as $id) {
            $al = $this->alumnoService->getAlumnoCached((int)$id, 60);
            $alumnosMap[(int)$id] = is_array($al) ? $al : null;
        }

        // 4. Mapeo y enriquecimiento de datos
        $itemsEnriquecidos = $items->map(function ($d) use ($tiposMap, $alumnosMap, $usuariosMap, $columnaUsuario) {
            $doc = (array) $d;

            // Tipo de documento
            $tipoId = (int) ($doc['tipo_documento_catalogo'] ?? 0);
            $doc['tipo_nombre'] = $tiposMap[$tipoId] ?? ('Tipo #' . ($tipoId ?: '-'));

            // Datos del Alumno
            $idAlumno = (int) ($doc['id_alumno'] ?? 0);
            $al = ($idAlumno > 0) ? ($alumnosMap[$idAlumno] ?? null) : null;

            if (is_array($al) && isset($al[0]) && is_array($al[0])) {
                $al = $al[0];
            }
            if (!is_array($al)) $al = null;

            $doc['alumno_codigo']    = $al['codigo'] ?? '-';
            $doc['alumno_dni']       = $al['numero_documento'] ?? '-';
            $doc['alumno_nombre']    = trim(($al['nombre'] ?? '') . ' ' . ($al['apellido_paterno'] ?? '') . ' ' . ($al['apellido_materno'] ?? '')) ?: 'No disponible';
            $doc['alumno_escuela']   = $al['escuela'] ?? 'No disponible';

            // Usuario (Dinámico: quien creó o quien eliminó)
            $idUsuario = (int) ($doc[$columnaUsuario] ?? 0);
            $usuario = $usuariosMap[$idUsuario] ?? null;

            $doc['usuario_nombre'] = $usuario['nombre'] ?? 'No disponible';
            $doc['usuario_login']  = $usuario['login']  ?? '-';

            return $doc;
        });

        $paginator->setCollection($itemsEnriquecidos);
        return $paginator;
    }

    public function exportarPdf()
    {
        $this->resetPage();
        $tipo = (int) ($this->id_tipo_documento ?? 0);
        $desde = Carbon::parse($this->fecha_desde ?? now()->startOfMonth())->startOfDay()->format('Y-m-d');
        $hasta = Carbon::parse($this->fecha_hasta ?? now())->format('Y-m-d');

        $esModoEliminado = ($this->filtro_estado === 'eliminados');
        $columnaUsuario = $esModoEliminado ? 'au_usuarioel' : 'au_usuariocr';
        $vistaPdf = 'pdf.Documentos-reporte';

        $tiposMap = $this->tiposMap;
        $tipoLabel = $tipo === 0 ? 'TODOS' : ($tiposMap[$tipo] ?? ('TIPO #' . $tipo));

        $docs = $this->documentoService
            ->paginarPorTipoYRangoCreacion($tipo, $desde, $hasta, 100000, $this->filtro_estado)
            ->getCollection();

        $docs = collect($docs);

        $idsUsuarios = $docs
            ->pluck($columnaUsuario)
            ->filter()
            ->unique()
            ->values()
            ->all();

        $usuariosMap = $this->usuarioService->obtenerUsuariosPorIds($idsUsuarios);

        $idsAlumnos = $docs->pluck('id_alumno')->filter()->unique()->values()->all();
        $alumnosMap = [];
        foreach ($idsAlumnos as $id) {
            $al = $this->alumnoService->getAlumnoCached((int)$id, 60);
            $alumnosMap[(int)$id] = is_array($al) ? $al : null;
        }

        $items = $docs->map(function ($d) use ($tiposMap, $alumnosMap, $usuariosMap, $columnaUsuario) {
            $doc = (array) $d;

            $tipoId = (int) ($doc['tipo_documento_catalogo'] ?? 0);
            $doc['tipo_nombre'] = $tiposMap[$tipoId] ?? ('Tipo #' . ($tipoId ?: '-'));

            $idAlumno = (int) ($doc['id_alumno'] ?? 0);
            $al = ($idAlumno > 0) ? ($alumnosMap[$idAlumno] ?? null) : null;

            if (is_array($al) && isset($al[0]) && is_array($al[0])) $al = $al[0];
            if (!is_array($al)) $al = null;

            $doc['alumno_codigo']    = $al['codigo'] ?? '-';
            $doc['alumno_dni']       = $al['numero_documento'] ?? '-';
            $doc['alumno_nombre']    = trim(($al['nombre'] ?? '') . ' ' . ($al['apellido_paterno'] ?? '') . ' ' . ($al['apellido_materno'] ?? '')) ?: 'No disponible';
            $doc['alumno_escuela']   = $al['escuela'] ?? 'No disponible';
            $doc['alumno_condicion'] = $al['condicion'] ?? 'No disponible';
            $doc['alumno_situacion'] = $al['situacion'] ?? 'No disponible';

            $idUsuario = (int) ($doc[$columnaUsuario] ?? 0);
            $usuario = $usuariosMap[$idUsuario] ?? null;

            $doc['usuario_nombre'] = $usuario['nombre'] ?? 'No disponible';
            $doc['usuario_login']  = $usuario['login'] ?? '-';

            return $doc;
        });

        $data = [
            'items' => $items,
            'generado' => now(),
            'es_eliminados' => $esModoEliminado,
            'filtros_limpios' => [
                'tipo'   => $tipoLabel,
                'desde'  => $desde,
                'hasta'  => $hasta,
                'estado' => strtoupper($this->filtro_estado)
            ],
            'nombre_institucion' => 'UNIVERSIDAD NACIONAL INTERCULTURAL DE LA AMAZONIA',
            'direccion_institucion' => 'CAR. SAN JOSE KM. 0.63 CAS. SAN JOSE (COSTADO INSTITUTO BILINGUE)',
            'ruc_institucion' => '20393146657',
        ];

        $pdf = Pdf::loadView($vistaPdf, $data)->setPaper('a4', 'landscape');

        $prefix = $esModoEliminado ? 'reporte_eliminados_' : 'reporte_creados_';
        $fileName = $prefix . now()->format('Ymd_His') . '.pdf';

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $fileName);
    }

    public function render()
    {
        return view('livewire.reporte.tabla');
    }
}
