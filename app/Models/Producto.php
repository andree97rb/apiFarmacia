<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = "producto";
    protected $fillable = array('nombre', 'vigencia', 'idMarca');
    public $timestamps = false;
    
    public function marca() {
        return $this->belongsTo('App\Models\Marca', 'idMarca');
    }
}