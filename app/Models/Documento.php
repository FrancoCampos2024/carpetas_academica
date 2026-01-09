<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Documento extends Model
{
    use SoftDeletes;

    const CREATED_AT = 'au_fechacr';
    const UPDATED_AT = 'au_fechamd';
    const DELETED_AT = 'au_fechael';

    protected $table = 'ta_documento';
    protected $primaryKey = 'id_documento';
    public $timestamps = true;

    protected $fillable = [
        'id_alumno',
        'ruta_documento',
        'tipo_documento_catalogo',

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

    public function tipoDocumento()
    {
        return $this->belongsTo(Catalogo::class,'tipo_documento_catalogo','id_catalogo');
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
