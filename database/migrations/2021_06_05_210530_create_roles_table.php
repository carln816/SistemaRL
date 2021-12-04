<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       //creo la tabla roles, implemento los campos que debe tener y debajo le inserto valores a la tabla
       Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');//va a tener el campo id que va ser autoincrement y es la llave primaria
            $table->string('nombre', 30)->unique();
            $table->string('descripcion', 100)->nullable();
            $table->boolean('condicion')->default(1);  
        });
        // en la clase db  le indico con el metodo table cual es la tabla con la que voy a insertar valores.
        //en este caso es la tabla roles y utilizando el metodo insert envio el array toda una fila que voy a insertar en la tabla roles.
        //
        DB::table('roles')->insert(array('id'=>'1','nombre'=>'Administrador', 'descripcion'=>'Administradores de área'));
        DB::table('roles')->insert(array('id'=>'2','nombre'=>'Empleado', 'descripcion'=>'Empleado de área'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
