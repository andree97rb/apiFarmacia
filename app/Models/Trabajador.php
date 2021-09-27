<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trabajador extends Model
{
    protected $table = "trabajador";
    protected $fillable = array('nombres', 'apellidoPaterno', 'apellidoMaterno', 'tipoDocumento', 'numeroDocumento', 'correo', 'celular');
    public $timestamps = false;

    public function usuario()
    {
        return $this->hasMany('App\Models\Usuario', 'idTrabajador');
    }
}