<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->increments('id');//este es el campo id de la tabla ventas, que se sera la llave primaria que sera un campo incremental
            $table->integer('idcliente')->unsigned();// se implementa el campo id cliente que es entero, con el unsigne le agrego un indice, porque este campo sera la llave foranea que hace referencia al cliente involucrado
            $table->foreign('idcliente')->references('id')->on('clientes');//se indica la llave foranea, que es con la columna idCliente, columna de la tabla ventas, y hace referencia a la llave primaria que se llama id de nuestra tabla personas
            $table->integer('idusuario')->unsigned();//Agrego el campo idusuario que es un inteher se le agrega un idice 
            $table->foreign('idusuario')->references('id')->on('users');//Le indico que el campo idusuario va ser la llave foranea para poder relacionarla con la tabla users, 
            //indicandole que el campo idusuario de mi tabla ventas es igual al id de la tabla users, para crear la relacion entre la table ventas y tabla user y determinar cual el es usuario responsable de la venta
            $table->string('tipo_comprobante', 20);//le indicamos un tipo de comprobante ya sea tipo boleta,factura o ticket
            $table->string('serie_comprobante', 20)->unique();//el numero de serie, y es un campo no obligatorio de llenar
            $table->dateTime('fecha_hora');//un campo de frcha para saber el dia de la venta
            $table->decimal('impuesto', 4, 2);//precision de dos decimales y 4 digitos
            $table->decimal('total', 11, 2);//precision de dos decimales y 11 digitos
            $table->string('estado', 20);//campo para darle un estado al ingreso si es cancelada o anulada o registrada
            $table->timestamps();//propiedad de creaat at donde se almacena la fecha del registro y el update donde se almacena el campo de actualizacion
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ventas');
    }
}
