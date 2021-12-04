<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    //
    //le indicamos  que este modelo hace referncia a nuestra tabla roles
    protected $table = 'roles';
    //le indicamos los campos donde vamos a obtener y enviar valores.
    protected $fillable = ['nombre','descripcion','condicion'];
    //le indicamos el valor falsoporque recordemos que la tabla roles no tiene los campos de times. asi que vamos a desabilitarlos.
    public $timestamps = false;

    public function users(){//un rol puede tener varios usuarios
        return $this->hasMany('App\User');//l
    }

    
}
