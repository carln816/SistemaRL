<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model//por estandar de laravel el modelo debe ser singular de la tabla en la base de datos, 
//entonces segun el estandar la tabla deberia llamarse detalleventas
{
    //
    protected $table = 'detalle_ventas';//declaramos la propiedad table para asignar el valor de la tabla a la que estoy haciendo referencia.
    protected $fillable = [//no se le indica el campo id de detalle porque es un campo increment
        //indicamos los campos de la tabla detalle_ventas, que van a recibir y enviando valores 
        'idventa', 
        'idproducto',
        'cantidad',
        'peso',
        'precio',
        'descuento'
    ];
    public $timestamps = false;
    //delcaro la propiedad timestamps,le indico que sea falso por que es nuestra tabla de migraciones no hemos indicado que tenga los campos timestamps
}
