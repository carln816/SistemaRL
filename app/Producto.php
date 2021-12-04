<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    //declaramos lo campos de la tabla que recibimos y almacenamos
    protected $fillable =[
        'idcategoria',
        'codigo',
        'nombre',
        'fecha_hora',
        'stock',
        'peso',
        'precio_venta',
        'descripcion',
        'condicion'
    ];
    //creamos la funcion para relacionar las tablas
    //indicando que un producto pertenece a una categoria
    public function categoria(){//retornamos usando el metodo belongsto haciendo refeencia al modelo app/categoria
        return $this->belongsTo('App\Categoria');
    }
}
