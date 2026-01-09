<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Persona extends Model
{
    use SoftDeletes;
    
    const CREATED_AT = 'au_fechacr';
    const UPDATED_AT = 'au_fechamd';
    const DELETED_AT = 'au_fechael';

    protected $table='ta_persona';
    protected $primaryKey = 'id_persona';

    public $timestamps =true;

    protected $fillable = [
        'dni_persona',
        'nombres_persona',
        'apellido_pat_persona',
        'apellido_mat_persona',
        'telefono_persona',
        'correo_persona',
        'estado_persona',
        'tipo_persona_catalogo',
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

    protected $appends = [
        'nombre_completo'
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

    public function usuario(){
        return $this->hasOne(Usuario::class,'id_persona');
    }

    public function tipoPersona()
    {
        return $this->belongsTo(Catalogo::class,'tipo_persona_catalogo','id_catalogo');
    }

    public function getNombreCompletoAttribute()
    {
        return "{$this->nombres_persona} {$this->apellido_pat_persona} {$this->apellido_mat_persona}";
    }


    public function scopeBuscar($query, $buscar)
    {
        if (empty($buscar)) {
            return $query;
        }

        return $query->where(function ($subQuery) use ($buscar) {
            $subQuery
                ->orWhere('dni_persona', 'LIKE', "%{$buscar}%")
                ->orWhere('nombres_persona', 'LIKE', "%{$buscar}%")
                ->orWhere('apellido_pat_persona', 'LIKE', "%{$buscar}%")
                ->orWhere('apellido_mat_persona', 'LIKE', "%{$buscar}%")
                ->orWhereRaw("CONCAT(COALESCE(nombres_persona, ''), ' ', COALESCE(apellido_pat_persona, ''), ' ', COALESCE(apellido_mat_persona, '')) LIKE ?", ["%{$buscar}%"])
                ->orWhere('correo_persona', 'LIKE', "%{$buscar}%")
                ->orWhere('telefono_persona', 'LIKE', "%{$buscar}%");
        });
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
