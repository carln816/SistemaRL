<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetalleVentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_ventas', function (Blueprint $table) {
            $table->increments('id');//cuenta con campo id de llave primaria que es incremental
            $table->integer('idventa')->unsigned();//se agrega el campo idventa en un valor entero,  con el unsigne le agrego un indice, porque este campo sera 
            //la llave foranea que hace referencia a  la venta
            $table->foreign('idventa')->references('id')->on('ventas')->onDelete('cascade');//Le indico que el campo idventa de la tabla detalle_venta va ser una 
            //llave una foreanay va ser referencia al campo id de la tabla ventas, para relacionar una venta con sus detalles
            $table->integer('idproducto')->unsigned();//se implementa el camppo idproducto que sera un entero, se le agrega la propiedad unsigne para indicar que es un indice
            $table->foreign('idproducto')->references('id')->on('productos');//indico que el campo idproducto es una llave foranea, que hace refrencia al campo id de la tabla productos 
            $table->integer('cantidad');//se agrega el campo cantidad que es entero
            $table->string('peso', 20);//le indicamos que se agregue el campo de tipo string para que use letras y numeros
            $table->decimal('precio', 11, 2);//se agrega el campo precio que es un decimal con 11 digitos y con una precision de dos decimales
            $table->decimal('descuento', 11, 2);//se agrega el campo descuento que es un decimal con 11 digitos y con una precision de dos decimales
            //No se haran uso de los campos creat at y update, asi que borramos la propiedad de timestamp
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalle_ventas');
    }
}
