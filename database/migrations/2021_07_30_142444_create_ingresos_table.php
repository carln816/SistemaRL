<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIngresosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ingresos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idproveedor')->unsigned();//le indicamos que sera de tipo de entero y le asignamos la propiedad unsigne porque se convertira en un indice que nos permitira relacionar la tabla ingresos con proveedores
            $table->foreign('idproveedor')->references('id')->on('proveedores');//la tabla ingresos esta relacionada con las tablas proveedores atraves de la llave foreana idproveedor
            $table->integer('idusuario')->unsigned();
            $table->foreign('idusuario')->references('id')->on('users');
            $table->string('tipo_comprobante', 20);//le indicamos un tipo de comprobante ya sea tipo boleta,factura o ticket
            $table->string('serie_comprobante', 20)->unique();//el numero de serie
            $table->string('unidad_medida', 50);
            $table->dateTime('fecha_hora');
            $table->decimal('impuesto', 4, 2);//precision de dos decimales y 4 digitos
            $table->decimal('total', 11, 2);//precision de dos decimales y 11 digitos
            $table->string('estado', 20);//campo para darle un estado al ingreso si es cancelada o anulada o registrada
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ingresos');
    }
}
