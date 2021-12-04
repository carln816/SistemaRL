<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Materia extends Model
{
    //
    //declaramos lo campos de la tabla que recibimos y almacenamos
    protected $fillable =[
        'idcategoria','codigo','nombre','precio_compra','stock','unidad_medida','fecha_hora','descripcion','condicion'
    ];
    //creamos la funcion para relacionar las tablas
    //indicando que un articulo pertenece a una categoria
    public function categoria(){//retornamos usando el metodo belongsto haciendo refeencia al modelo app/categoria
        return $this->belongsTo('App\Categoria');
    }
}
