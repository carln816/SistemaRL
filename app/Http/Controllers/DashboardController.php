<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB; 
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //funcion que solo usaremos aqui
    public function __invoke(Request $request)//recibe objetos de tipo request 
    {//La ventaja de usar invokes que ademas cuando se registre una ruta en un controlador en este caso dashboard, no se necesita 
        //especificar un metodo en la ruta debido aque por defecto se asume que se esta usando esta funcion invoke
        
        $anio=date('Y');//Declaro la varibale anio, y con la funcion date voy a obtener el año actual
        //Se declara una variable llamada ingresos, donde hago referencia a la clase DB, uso el metodo table en este caso  indico que voy a
        //obtener datos de la tabla ingresos y le asigno el alias i
        $ingresos=DB::table('ingresos as i')
        ->select(DB::raw('MONTH(i.fecha_hora) as mes'),//Con el metodo select, voy a obtener el mes de la fecha de nuestro ingreso y a este
        // valor que tengo lo renombro como el mes, En este caso estamos usando la clase DB con el metodo raw para usar la funcion que nos 
        //permite obtener el mesde una fecha establecida
        DB::raw('YEAR(i.fecha_hora) as anio'),//Tambien obtendremos el año, del campo fecha de la tabla ingreso y lo renombro anio
        DB::raw('SUM(i.total) as total'))//Y por ultimo mediante la funcion "SUM" voy a sumar el campo total y mostrando la suma de todos los campos totales, se mostrara en el campo total 
        ->where('Estado','=','Registrado')
        ->whereYear('i.fecha_hora',$anio)//Usando el metodo whereYear, voy a filtrar que solamente me muestre  los meses y los totales asignados a cada mes del año actual
        ->groupBy(DB::raw('MONTH(i.fecha_hora)'),DB::raw('YEAR(i.fecha_hora)'))//Para poder agrupar usnado el metodo raw se dice que se va hacer la agrupacion por el mes y por el año
        ->get();//con el metodo get obtengo todos los registros 

        $ventas=DB::table('ventas as v')
        ->select(DB::raw('MONTH(v.fecha_hora) as mes'),
        DB::raw('YEAR(v.fecha_hora) as anio'),
        DB::raw('SUM(v.total) as total'))
        ->where('Estado','=','Registrado')
        ->whereYear('v.fecha_hora',$anio)
        ->groupBy(DB::raw('MONTH(v.fecha_hora)'),DB::raw('YEAR(v.fecha_hora)'))
        ->get();
        
        return ['ingresos'=>$ingresos,'ventas'=>$ventas,'anio'=>$anio];
        //Voy a retornar todo lo que tengo en las variables ingresos y ventas es decir la lista de valores y tambien lo que tengo en el parametro anio lo voy a devolver lo que tengo en la variable anio    

    }
}