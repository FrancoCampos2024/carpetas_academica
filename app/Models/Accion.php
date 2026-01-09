<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Menu;

class Accion extends Model
{
    protected $table='ta_accion';
    protected $primaryKey = 'id_accion';

    public $timestamps =false;

    protected $fillable = [
        'nombre_accion',
        'id_menu'
    ];

    public function menu(){
        return $this->belongsTo(Menu::class,'id_menu');
    }
    public function permisos()
    {
        return $this->hasMany(Permiso::class, 'id_accion', 'id_accion');
    }


}
