<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    //
    //protected $table = 'categorias';//aqui se deberia usar esta propiedad si no contaramos con una tabla que nos tome los valores de las misma
    //protected $primaryKey = 'id';//con esto indicamos cuando es la primaria de la tabla, pero como el eloquen asume por defecto que el id es la llave primaria.
    //Con esto se define los atributos del modelo que se asignaran en mas

    //con la propiedad fillable indicamos que los campos se le asignara valores en masa
    protected $fillable = ['nombre', 'descripcion', 'condicion'];

    //esto indica que una categoria puede tener varios prodcuctos, materias
    public function productos()
    {
        return $this->hasMany('App\Producto');
    }

    public function materiaprimas()
    {
        return $this->hasMany('App\Materia');
    }
}