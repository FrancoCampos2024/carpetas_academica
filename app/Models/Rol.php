<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Rol extends Model
{
    use SoftDeletes;
    
    const CREATED_AT = 'au_fechacr';
    const UPDATED_AT = 'au_fechamd';
    const DELETED_AT = 'au_fechael';

    protected $table='ta_rol';
    protected $primaryKey = 'id_rol';

    public $timestamps =true;

    protected $fillable = [
        'nombre_rol',
        'estado_rol',
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

    public function scopeBuscar($query, $buscar)
    {
        return $query->where('nombre_rol', 'LIKE', "%{$buscar}%");
    }

    public function permisos(){
        return $this->hasMany(Permiso::class,'id_rol');
    }

    public function usuarios(){
        return $this->hasMany(Usuario::class,'id_rol');
    }

}
