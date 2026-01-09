<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            'App\Repositories\Carpeta\CarpetaRepositoryInterface',
            'App\Repositories\Carpeta\CarpetaRepository'
        );

        $this->app->bind(
            'App\Repositories\Usuario\UsuarioRepositoryInterface',
            'App\Repositories\Usuario\UsuarioRepository'
        );

        $this->app->bind(
            'App\Repositories\Documento\DocumentoRepositoryInterface',
            'App\Repositories\Documento\DocumentoRepository'
        );

        $this->app->bind(
            'App\Repositories\DatoExtra\DatoExtraRepositoryInterface',
            'App\Repositories\DatoExtra\DatoExtraRepository'
        );

        $this->app->bind(
            'App\Repositories\CampoExtra\CampoExtraRepositoryInterface',
            'App\Repositories\CampoExtra\CampoExtraRepository'
        );

        $this->app->bind(
            'App\Repositories\Catalogo\CatalogoRepositoryInterface',
            'App\Repositories\Catalogo\CatalogoRepository'
        );

        $this->app->bind(
            'App\Repositories\Persona\PersonaRepositoryInterface',
            'App\Repositories\Persona\PersonaRepository'
        );

        $this->app->bind(
            'App\Repositories\Rol\RolRepositoryInterface',
            'App\Repositories\Rol\RolRepository'
        );

        $this->app->bind(
            'App\Repositories\Menu\MenuRepositoryInterface',
            'App\Repositories\Menu\MenuRepository'
        );

        $this->app->bind(
            'App\Repositories\Accion\AccionRepositoryInterface',
            'App\Repositories\Accion\AccionRepository'
        );

        $this->app->bind(
            'App\Repositories\Permiso\PermisoRepositoryInterface',
            'App\Repositories\Permiso\PermisoRepository'
        );
    }

    /**
     * Bootstrap services.
     */

    public function boot(): void
    {
        
    }
}
