<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetalleIngresosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_ingresos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idingreso')->unsigned();//le indicamos que sera de tipo de entero y le asignamos la propiedad unsigne porque se convertira en un indice que nos permitira relacionar la tabla detalle_ingresos con ingresos
            $table->foreign('idingreso')->references('id')->on('ingresos')->onDelete('cascade');//le indico que la llave foranea va ser idingreso, haciendo referencia al campo id de nuestra tabla ingresos, utilizamos el on delete cascada esto es por si eliminos un ingreso de manera automatica se van a eliminar todos los detalles de ingreso que estan referenciando al ingreso
            $table->integer('idmateria')->unsigned()->nullable();////le indicamos que sera de tipo de entero y le asignamos la propiedad unsigne porque se convertira en un indice 
            $table->foreign('idmateria')->references('id')->on('materias')->nullable();//le indico que la llave foranea va ser idmateria, haciendo referencia al campo id de nuestra tabla materias
            $table->integer('cantidad');//se agrega el campo cantidad que es entero
            $table->decimal('precio', 11, 2);//precision de dos decimales y 11 digitos
            //No usaremos el timestamp
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalle_ingresos');
    }
}
