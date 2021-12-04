<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    //donde se implementa el nombre de las columnas que van a recibir valores en la base de datos
    protected $fillable = ['nombre','tipo_documento','num_documento', 'direccion','telefono','email'];

    /*public function proveedor()
    {// le voy a indicar que una persona esta relacionado de manera directa con un proveedor
        return $this->hasOne('App\Proveedor');
    }// con el metodo hasOne hago referencia al modelo proveedor*/

    public function user(){///le indicamos que una persona esta relacionada de manera directa aun usuario
        return $this->hasOne('App\User');//la relacion es usando el metodo has haciendo referencia al modelo user.
    }
    
    /*public function cliente()
    {// le voy a indicar que una persona esta relacionado de manera directa con un proveedor
        return $this->hasOne('App\Cliente');
    }// con el metodo hasOne hago referencia al modelo proveedor*/

}