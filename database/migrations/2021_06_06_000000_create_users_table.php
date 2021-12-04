<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            ///cuando voy a iniciar los datos del sistema voy a registrar los datos de la tabla persona,los datos comunes tanto para clientes, usuarios y proveedores.
            $table->integer('idpersona')->unsigned();//tiene la propiedad unisigned para relacionar con nuestra tabla persona
            //con el metodo forein voy a crear la llave foranea,
            //este campo id de la tabla users voy hacer referencia al campo id de la tabla persona
            //para crear la relacion entre users y personas 
            //la eliminacion va ser en cascada. 
            $table->foreign('idpersona')->references('id')->on('personas')->onDelete('cascade');
            
            //se implementa el campo usuario para ingresar al sistema
            $table->string('usuario',20)->unique();
            $table->string('password');
            $table->boolean('condicion')->default(1);

            $table->integer('idrol')->unsigned();//implementamos el campo idrol, le agregamos el metodo unsigned para poder relacionar con nuestro metodo rol.
            $table->foreign('idrol')->references('id')->on('roles');//le indicamos queel metodo idrol es una llave foranea  que hace referencia al campo id de nuestra tabla roles.

            $table->rememberToken();
            //$table->timestamps();NO SE NECESITA EL CAMPO
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
