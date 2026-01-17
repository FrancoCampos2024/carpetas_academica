<?php

namespace App\Services;

use App\Models\Documento;
use App\Repositories\Documento\DocumentoRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DocumentoService
{
    /**
     * Create a new class instance.
     */

    protected $DocumentoRepository;

    public function __construct(DocumentoRepositoryInterface $DocumentoRepository)
    {
        $this->DocumentoRepository = $DocumentoRepository;
    }

    public function obtenerDocumentosPorAlumno($id_alumno)
    {
        return $this->DocumentoRepository->getDocumentosPorAlumno($id_alumno);
    }

    public function obtenerPorId($id_documento,Array $relaciones=[]){
        return $this->DocumentoRepository->obtenerPorId($id_documento,$relaciones);
    }




    public function guardar_archivo(mixed $archivo, string $ruta, ?string $disco = 'externo'): ?array
    {
        $storage = Storage::disk($disco);

        // 1) Generar nombre e info del archivo
        $info = $this->generar_info_archivo($archivo);
        $ruta_completa = $this->obtener_ruta_completa($ruta, $info['nombre_final']);

        // 2) Asegurar que la carpeta exista (opcional pero recomendable)
        $this->asegurar_directorio($ruta_completa['carpeta_completa'], $storage);

        // 3) Guardar el archivo en el disco externo
        if (!$storage->putFileAs($ruta_completa['carpeta_completa'], $archivo, $info['nombre_final'])) {
            throw new \Exception("No se pudo guardar el archivo '{$info['nombre_final']}' en el disco '{$disco}'.");
        }

        // 4) Retornar info para guardar en BD
        return [
            'nombre_ad'    => $info['nombre_original'],
            'ruta_ad'      => $ruta_completa['ruta_relativa'], // ej: Alumno/2520222070/202512031234abcd.pdf
            'extension_ad' => $info['extension'],
        ];
    }

    protected function asegurar_directorio(string $carpeta_completa, $storage)
    {
        if (
            !is_dir($storage->path($carpeta_completa)) &&
            !mkdir($storage->path($carpeta_completa), 0755, true) &&
            !is_dir($storage->path($carpeta_completa))
        ) {

            throw new \Exception("No se pudo crear la carpeta '{$carpeta_completa}'.");
        }
    }

    // Generar información del archivo
    protected function generar_info_archivo(mixed $archivo): array
    {
        $marca_tiempo    = now()->format('YmdHis');
        $uid             = uniqid();
        $nombre_original = pathinfo($archivo->getClientOriginalName(), PATHINFO_FILENAME);
        $extension       = $archivo->getClientOriginalExtension();

        $nombre_final = "{$marca_tiempo}{$uid}.{$extension}";

        return compact('nombre_original', 'extension', 'nombre_final');
    }

    protected function obtener_ruta_completa(string $ruta, string $nombre_final): array
    {
        // "documentos.alumno.2520222070" → "Documentos/Alumno/2520222070/"
        $ruta = collect(explode('.', $ruta))
            ->map(fn($segmento) => ucfirst($segmento))
            ->implode('/');

        $carpeta_completa = rtrim($ruta, '/') . '/';
        $ruta_relativa    = $carpeta_completa . $nombre_final;

        return compact('carpeta_completa', 'ruta_relativa');
    }



    public function guardarDocumentoPdf($archivo, int $id_alumno, int $id_tipo_documento, $nombreDocumento): Documento
    {
        $infoArchivo = $this->guardar_archivo(
            $archivo,
            "documentos.alumno.$id_alumno",
            'share'
        );

        $documento = Documento::create([
            'id_alumno'              => $id_alumno,
            'ruta_documento'         => $infoArchivo['ruta_ad'], // solo la ruta relativa
            'tipo_documento_catalogo'=> $id_tipo_documento,
            'nombre_documento'       => $nombreDocumento,
        ]);

        return $documento;
    }

    public function actualizarDocumentoPdf($archivo, Documento $documento, $nombreDocumento): Documento
    {
        // 1. Guardar archivo nuevo (NO borrar el anterior)
        $infoArchivo = $this->guardar_archivo(
            $archivo,
            "documentos.alumno.{$documento->id_alumno}",
            'share'
        );

        // 2. Actualizar solo la BD
        $documento->ruta_documento = $infoArchivo['ruta_ad'];
        $documento->nombre_documento = $nombreDocumento;
        $documento->save();

        return $documento;
    }


    public function obtenerDocumentosTipoIdalumno(int $id_tipo , int $id_alumno,int $paginado = 10){
        return $this->DocumentoRepository->obtenerDocumentosTipoIdalumno($id_tipo,$id_alumno,$paginado);
    }

    public function existeItemCatalogo(int $tipo_dcoumento_catalogo):bool{
        return $this->DocumentoRepository->existeItemCatalogo($tipo_dcoumento_catalogo);
    }

    public function paginarPorTipoYRangoCreacion(int $tipo, string $inicio, string $fin, int $perPage = 10, string $estado = 'creados')
    {
        $esModoEliminado = ($estado === 'eliminados');

        // Definimos qué columnas de auditoría usar según el modo
        $columnaFecha = $esModoEliminado ? 'd.au_fechael' : 'd.au_fechacr';
        $columnaUsuario = $esModoEliminado ? 'd.au_usuarioel' : 'd.au_usuariocr';

        return DB::table('ta_documento as d')
            ->select([
                'd.id_documento',
                'd.id_alumno',
                'd.ruta_documento',
                'd.nombre_documento',
                'd.tipo_documento_catalogo',
                'd.au_fechacr',
                'd.au_usuariocr',
                'd.au_fechael',   // Añadimos estas para que el Livewire pueda leerlas
                'd.au_usuarioel',
            ])
            // Si es modo "creados", fechael debe ser NULL.
            // Si es modo "eliminados", fechael NO debe ser NULL.
            ->when(!$esModoEliminado, fn($q) => $q->whereNull('d.au_fechael'))
            ->when($esModoEliminado, fn($q) => $q->whereNotNull('d.au_fechael'))

            // Filtramos por el rango de fechas usando la columna correspondiente (creación o eliminación)
            ->where($columnaFecha, '>=', $inicio)
            ->whereRaw("$columnaFecha < DATE_ADD(?, INTERVAL 1 DAY)", [$fin])

            // Filtro por tipo de documento
            ->when($tipo !== 0, fn ($q) => $q->where('d.tipo_documento_catalogo', $tipo))

            // Ordenamos por la fecha relevante
            ->orderByDesc($columnaFecha)
            ->paginate($perPage);
    }

    public function eliminar(Documento $documento)
    {
        return $this->DocumentoRepository->eliminar($documento);
    }



}
