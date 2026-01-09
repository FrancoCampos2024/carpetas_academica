<?php

namespace App\Models;

use App\Models\Accion;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table='ta_menu';
    protected $primaryKey = 'id_menu';

    public $timestamps =false;

    protected $fillable = [
        'nombre_menu',
        'ruta_menu'
    ];

    public function accion(){
        return $this->hasMany(Accion::class,'id_menu');
    }

}
