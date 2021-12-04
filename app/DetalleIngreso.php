<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleIngreso extends Model
{
    //
    protected $table = 'detalle_ingresos';//declaramos la propiedad table para asignar el valor de la tabla a la que estoy haciendo referencia.
    protected $fillable = [//no se le indica el campo id de detalle porque es un campo increment
        //indicamos los campos de la tabla detalle_ingresos, que van a recibir y enviando valores 
        'idingreso', 
        'idmateria',
        'cantidad',
        'precio'
    ];
    public $timestamps = false;//delcaro la propiedad timestamps,le indico que sea falso por que es nuestra tabla de migraciones no hemos indicado que tenga los campos timestamps

}
