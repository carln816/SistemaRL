<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    //implementamos la propiedad fillable en este caso sera un vector 
    //dentro de la propiedad vamos a agregar los campos que van a rercibir y enviar valores de la base de datos hasta la aplicacion
    protected $fillable =[//como vemos no tenemos el campo de nuestra llave primaria id, no esta recibiendo ni enviando ningun valor por eso no se incluye, ya que el campo id es incremental
        'idcliente', 
        'idusuario',
        'tipo_comprobante',
        'serie_comprobante',
        //'unidad_medida',
        'fecha_hora',
        'impuesto',
        'total',
        'estado'
    ];
}
