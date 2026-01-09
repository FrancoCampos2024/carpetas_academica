<?php

namespace App\Models;


use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;

class Usuario extends Authenticatable
{
    use SoftDeletes;

    const CREATED_AT = 'au_fechacr';
    const UPDATED_AT = 'au_fechamd';
    const DELETED_AT = 'au_fechael';

    protected $table='ta_usuario';
    protected $primaryKey = 'id_usuario';

    public $timestamps =true;

    protected $fillable = [
        'nombre_usuario',
        'clave_usuario',
        'estado_usuario',
        'id_rol',
        'id_persona',
        'au_fechacr',
        'au_fechamd',
        'au_fechael',
        'au_usuariocr',
        'au_usuariomd',
        'au_usuarioel'
    ];

    protected $hidden = [
        'clave_usuario',
        'au_fechacr',
        'au_fechamd',
        'au_fechael',
        'au_usuariocr',
        'au_usuariomd',
        'au_usuarioel'
    ];

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

    public function rol(){
        return $this->belongsTo(Rol::class,'id_rol');
    }

    public function persona(){
        return $this->belongsTo(Persona::class,'id_persona');
    }

    public function scopeBuscar($query, $buscar)
    {
        if (empty($buscar)) {
            return $query;
        }

        return $query->whereHas('persona', function ($subQuery) use ($buscar) {
            $subQuery->where(function ($subSubQuery) use ($buscar) {
                $subSubQuery
                    ->buscar($buscar);
            });
        })->orWhere('nombre_usuario', 'LIKE', "%{$buscar}%");
    }

    // Scope para filtrar por estado
    public function scopeEstado($query, $estado)
    {
        if ($estado == null) {
            return $query;
        }

        return $query->where('estado_usuario', $estado);
    }

    // Scope para limitar la búsqueda
    public function scopeLimite($query, $limite)
    {
        if ($limite == null) {
            return $query;
        }

        return $query->limit($limite);
    }
}
