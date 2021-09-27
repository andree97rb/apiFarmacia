<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = "usuario";
    protected $fillable = array('nombre', 'clave', 'idTrabajador');
    public $timestamps = false;
    
    public function trabajador() {
        return $this->belongsTo('App\Models\Trabajador', 'idTrabajador');
    }
}
