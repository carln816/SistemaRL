<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{   
    use Notifiable;

    protected $fillable = [
       'idpersona', 'usuario', 'password', 'idrol', 'condicion'
    ];

    public $timestamps = false;
    
    //public $timestamps = false;//lo agregamos porque nuestra tabla no tiene los campos de creat ni update
    //los campos no existen.
    //porque a la tabla que le hago referencia con este modelo no tiene los campos timesstant qque con crear y actualizar 

    protected $hidden = [
        'password', 'remember_token',
    ];
    //declaramos la funcion rol para hacer referencia al modelo rol
    //le indicamos que un usuario pertenece aun rol.
    public function rol(){
        return $this->belongsTo('App\Rol');
    }
    //para indicar que un usuario hace refrencia a una persona 
    public function persona(){
        return $this->belongsTo('App\Persona');
    }

    /*public function empleado(){
        return $this->belongsTo('App\Empleado');
    }*/

}
