<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ingreso extends Model
{
    //
    protected $fillable = [//como vemos no tenemos el campo de nuestra llave primaria id, no esta recibiendo ni enviando ningun valor por eso no se incluye, ya que el campo id es incremental
        'idproveedor', 
        'idusuario',
        //'descripcion',
        'tipo_comprobante',
        'serie_comprobante',
        'unidad_medida',
        'fecha_hora',
        'impuesto',
        'total',
        'estado'
    ];
    public function usuario()//esta funcion no permite obtener el usuario que registro el ingreso
    {
        return $this->belongsTo('App\User');// y retorno el modelo user
    }
    public function proveedor()//nos permitira identficar cual es el proveedor que nos abastece los productos
    {
        return $this->belongsTo('App\Proveedor');//y retorno al modelo proveedor
    }
}
