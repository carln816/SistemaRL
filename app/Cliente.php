<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    //

    //donde se implementa el nombre de las columnas que van a recibir valores en la base de datos
    //protected $table = 'proveedores';//declaramos la propiedad table para asignar el valor de la tabla a la que estoy haciendo referencia.
    protected $fillable = [
        'nombre',
        'tipo_documento','num_documento', 
        'direccion','telefono','email', 
        'contacto', 'telefono_contacto'
    ];
    /* //le indicamos que hacemos referencia a la table proveedores desde este modelo
    // protected $table = 'clientes';//funciona de milagro
    //la propiedad fillable el nombre de los campos proveedores de los cuales vamos a obtener y enviar valores.
    protected $fillable = [
        'id', 'contacto', 'telefono_contacto', 'condicion'];

    public $timestamps = false;
    //añadiremos una funcion persona, para que recordemos que proveedor pertenece a una persona
    public function persona()
    {//retornamos el modelo persona
        return $this->belongsTo('App\Persona');
    }*/
}
