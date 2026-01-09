<?php

use App\Livewire\Inicio\Index as InicioIndex;
use App\Livewire\Carpeta\Index as CarpetaIndex;
use App\Livewire\Documento\Index as DocumentoIndex;
use App\Livewire\Documento\DetalleNoUnico\Index as DetalleNoUnicoIndex;
use App\Livewire\Catalogo\Index as CatalogoIndex;
use App\Livewire\Reporte\Index as ReporteIndex;
use App\Livewire\Seguridad\Auth\Login;
use App\Livewire\Seguridad\Persona\Index as PersonaIndex;
use App\Livewire\Seguridad\Rol\Acceso\Index as AccesoIndex;
use App\Livewire\Seguridad\Rol\Index as RolIndex;
use App\Livewire\Seguridad\Usuario\Index as UsuarioIndex;
use App\Livewire\Seguridad\Usuario\Detalle\Form as UsuarioDetalleForm;
use App\Models\Documento;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

/*
|--------------------------------------------------------------------------
| AUTH (PÚBLICO)
|--------------------------------------------------------------------------
*/
Route::middleware(['throttle:100,1', 'guest'])->group(function () {
    Route::get('/login', Login::class)->name('login');
});

/*
|--------------------------------------------------------------------------
| APP (PROTEGIDO)
|--------------------------------------------------------------------------
*/
Route::middleware(['throttle:100,1', 'auth'])->group(function () {

    /* =======================
     * INICIO
     * ======================= */
    //Route::get('/', InicioIndex::class)->name('inicio.index');
    Route::get('/inicio', InicioIndex::class)->name('inicio.index');

    /* =======================
     * CARPETAS (LISTADO)
     * ======================= */
    Route::get('/carpetas', CarpetaIndex::class)
        ->name('carpetas.index');

    /* =======================
     * DOCUMENTOS POR ALUMNO
     * ======================= */
    Route::get('/carpetas/{alumno}/documentos', DocumentoIndex::class)
        ->name('documentos.index');

    /* =======================
     * DOCUMENTOS NO ÚNICOS
     * ======================= */
    Route::get(
        '/carpetas/{alumno}/documentos/no-unicos/{tipo}',
        DetalleNoUnicoIndex::class
    )->name('documentos.no_unicos.index');

    /* =======================
     * VISOR DE ARCHIVOS
     * ======================= */
    Route::get('/archivos/{disco}/{id_documento_hash}', function ($disco, $id_documento_hash) {

        // 1. Desencriptar el disco y el ID del documento
        $disco_real = desencriptar($disco);
        $id_real = desencriptar($id_documento_hash);

        // Si el ID no es válido, lanzamos 404
        if (!$id_real) {
            abort(404, 'Enlace de archivo no válido');
        }

        // 2. Buscar la ruta real en la BD usando el ID desencriptado
        $documento = Documento::find($id_real);

        if (!$documento) {
            abort(404, 'El registro del documento no existe');
        }

        $ruta_fisica = $documento->ruta_documento;

        // 3. Selección de disco (tu lógica original)
        $tipo_disco = match ($disco_real) {
            4 => 'share',
            default => abort(403, 'Disco no permitido'),
        };

        $disk = Storage::disk($tipo_disco);

        if (!$disk->exists($ruta_fisica)) {
            abort(404, 'Archivo físico no encontrado en el servidor');
        }

        // 4. Retornar el archivo (StreamedResponse)
        return new \Symfony\Component\HttpFoundation\StreamedResponse(
            function () use ($disk, $ruta_fisica) {
                echo $disk->get($ruta_fisica);
            },
            200,
            [
                'Content-Type'        => mime_content_type($disk->path($ruta_fisica)),
                'Content-Disposition' => 'inline; filename="' . basename($ruta_fisica) . '"',
            ]
        );
    })->name('archivos.ver'); // Quitamos el ->where('ruta', '.*') porque ya no hay slashes

    /* =======================
     * SEGURIDAD - PERSONAS
     * ======================= */
    Route::get('/seguridad/personas', PersonaIndex::class)
        ->name('seguridad.personas.index');

    /* =======================
     * SEGURIDAD - USUARIOS
     * ======================= */
    Route::get('/seguridad/usuarios', UsuarioIndex::class)
        ->name('seguridad.usuarios.index');

    Route::get('/seguridad/usuarios/{usuario}', UsuarioDetalleForm::class)
        ->name('seguridad.usuarios.detalle');

    /* =======================
     * SEGURIDAD - ROLES
     * ======================= */
    Route::get('/seguridad/roles', RolIndex::class)
        ->name('seguridad.roles.index');

    Route::get('/seguridad/roles/{rol}/accesos', AccesoIndex::class)
        ->name('seguridad.roles.acceso.index');

    /* =======================
     * CATÁLOGOS
     * ======================= */
    Route::get('/catalogos', CatalogoIndex::class)
        ->name('catalogos.index');

    /* =======================
     * REPORTES
     * ======================= */
    Route::get('/reportes', ReporteIndex::class)
        ->name('reportes.index');

    /* =======================
     * LOGOUT
     * ======================= */
    Route::post('/logout', function () {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect()->route('login');
    })->name('logout');
});
