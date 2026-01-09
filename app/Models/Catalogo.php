<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Catalogo extends Model
{
    use SoftDeletes;

    const CREATED_AT = 'au_fechacr';
    const UPDATED_AT = 'au_fechamd';
    const DELETED_AT = 'au_fechael';

    protected $table = 'ta_catalogo';
    protected $primaryKey = 'id_catalogo';
    public $timestamps = true;

    protected $fillable = [
        'id_padre',
        'unico_catalogo',
        'descripcion_catalogo',
        'estado_catalogo',
        'au_fechacr',
        'au_fechamd',
        'au_fechael',
        'au_usuariocr',
        'au_usuariomd',
        'au_usuarioel'
    ];

    protected $hidden = [
        'au_fechacr',
        'au_fechamd',
        'au_fechael',
        'au_usuariocr',
        'au_usuariomd',
        'au_usuarioel'
    ];

    // Catálogo padre
    public function padre()
    {
        return $this->belongsTo(Catalogo::class, 'id_padre', 'id_catalogo');
    }

    // Catálogos hijos
    public function hijos()
    {
        return $this->hasMany(Catalogo::class, 'id_padre', 'id_catalogo');
    }

    public function documento(){
        return $this->hasMany(Documento::class, 'tipo_documento_catalogo');
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($usuario) {
            $usuario->au_usuariocr = Auth::id();
        });
        static::updating(function ($usuario) {
            $usuario->au_usuariomd = Auth::id();
        });
        static::deleting(function ($usuario) {
            $usuario->au_usuarioel = Auth::id();
            $usuario->save();
        });
    }
}
