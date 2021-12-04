<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Cliente;

class ClienteController extends Controller
{
    //
    public function index(Request $request)
    {
        //con esto se agrega seguridad a las peticiones que estamos realizando
        // determinar si la peticion que se ha hecho es diferente a una peticion ajax, que se redirige a ruta raiz, de esta manera solo podra acceder por el ajax
        if (!$request->ajax()) return redirect('/');
        //en la variable buscar voy almacenar lo que estoy recibiendo en el parametro buscar atravez de ajax
        $buscar = $request->buscar;
        //en la variable criterio voy almacenar lo que estoy recibiendo en el parametro criterio mediante el metodo get atravez de ajax
        $criterio = $request->criterio;
        
        if ($buscar==''){//muestra los registros de mi tabla clientes, ordenandolos de forma descendente por el id
            $clientes = Cliente::orderBy('id', 'desc')->paginate(3);
        }
        else{//pero si la variable buscar es diferente de vacio,
            //el arreglo clientes va ser igual a las clientes pero usaremos la condicion where,
            //donde voy a decir el texto a buscar puede estar contenido ya sea al inicio o al final de donde en nuestro campo criterio
            // los porcentajes son comodines que nos indican que el texto puede estar al inicio o al final
            $clientes = Cliente::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(3);
        }
               
        return [
            'pagination' => [
                'total'        => $clientes->total(),
                'current_page' => $clientes->currentPage(),
                'per_page'     => $clientes->perPage(),
                'last_page'    => $clientes->lastPage(),
                'from'         => $clientes->firstItem(),
                'to'           => $clientes->lastItem(),
            ],
            'clientes' => $clientes
        ];
    }
    //caso venta
    public function selectCliente(Request $request){//aun no
        if (!$request->ajax()) return redirect('/');

        $filtro = $request->filtro;//se declara la variable de nombre filtro y esta es igual a lo que recibo en la propiedad filtro del objeto request dicho sera enviado desde la vista 
        //$clientes = Cliente::join('clientes','clientes.id','=','clientes.id')//se declara clientes donde se almacena la lista de todos que coincidan con el texto almacenado en la varibale filtro
        //uno el modelo Cliente con la tabla clientes, y el campo de union va ser de la tabla clientes el campo id es igual al campo id de clientes 
        $clientes = Cliente::where('clientes.nombre', 'like', '%'. $filtro . '%')//se usara el filtro where, donde se realizara por el campo nombre de la tabla clientes, usando la clausala likeconun comodin al inicio y uno de porcentaje al final, 
        //indicando que el texto que buscando, en este caso almacenado en loa variable filtro puede estar al inicio, ala mitad al final, de nuestro campo nombre.
        ->orWhere('clientes.num_documento', 'like', '%'. $filtro . '%')// la propiedad orwhere para indicar otro filtro en este caso por el num_docuemnto usando la clausala like
        ->select('clientes.id','clientes.nombre','clientes.num_documento')//con la propiedad select voy a seleccionar la columna id de mi tabla clientes, el nombre y el num_documento
        ->orderBy('clientes.nombre', 'asc')->get();//ordenamos por medio del campo nombre de manera ascendete

        return ['clientes' => $clientes];//cuando hagamos referencia a este metodo lo que hare es devolver en un parametro llamado clientes la lista que tengo almacenada en la variable clientes que cumplan con el filtro 
    }

    public function listarPdf(Request $request)
    {//join('clientes','clientes.id','=','clientes.id')
        $clientes = Cliente::select('clientes.id','clientes.nombre','clientes.tipo_documento',
            'clientes.num_documento','clientes.direccion','clientes.telefono',
            'clientes.email','clientes.contacto','clientes.telefono_contacto')
           //se usa el metodo para order por el id de la persona
            ->orderBy('clientes.nombre', 'desc')->get();

        $cont=Cliente::count();//la variable cont tiene la cantidad de registros registrados
        //almaceno la variable pdf usando el alias pdf  y el metodo load para mostrar el reporte en una vista que se llama articulo 
        //y a esa vista le voy a enviar un parametro articulos que va ser igual alo que contiene nuestra variable articulos 
        //y otro parametro llamado con que va ser igual a lo que contiene nuestra variable cont
        $pdf = \PDF::loadView('pdf.clientespdf', ['clientes'=>$clientes, 'cont'=>$cont]);
        return $pdf->download('clientes.pdf');//retorno la varibale pdf donde hace referencia a su metodo download 
    }

    public function consuPdf(Request $request,$id)
    {//join('clientes','clientes.id','=','clientes.id')
        $clientes = Cliente::select('clientes.id','clientes.nombre','clientes.tipo_documento',
        'clientes.num_documento','clientes.direccion','clientes.telefono',
        'clientes.email','clientes.id','clientes.contacto','clientes.telefono_contacto')
        ->where('clientes.id','=',$id)//filtro el id cuyo id es igual al id que recibo como paramentro
        ->orderBy('clientes.id', 'desc')->take(1)->get();//obtengo una sola venta con el metodo get

        //se declara la variable numcodigo hago uso del modelo client.
        $numid=Cliente::select('id')->where('id',$id)->get();//Selecciono el campo num_documento, pero solamente filtro el id de la tabla client
        //sea igual al valor del parametro id que estoy recibiendo

        //Declaro la variable pdf se hace uso del alias PDF, SE USA el metodo loadview, y le indico que la vista que se llama client que esta dentro de la 
        //carpeta pdf genero el reporte, y le voy a enviar en el parametro client el valor que tenemps en la variable client, y en el parametro detalles el 
        //valor que tenemos en la variable detalles
        $pdf = \PDF::loadView('pdf.clientesconsupdf',['clientes'=>$clientes]);
        return $pdf->download('clientes-'.$numid[0]->id.'.pdf');//se retorna el objeto pdf y con el metodo download el documento tendra el nombre 
    }


    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        //implementaremos un capturador de excepciones
        // dentro del try usamos transacciones
        try{
            DB::beginTransaction();
            $cliente = new Cliente();
            $request->validate([
                'num_documento' => 'required|unique:clientes',
            ]);

            $cliente->nombre = $request->nombre;
            $cliente->tipo_documento = $request->tipo_documento;
            $cliente->num_documento = $request->num_documento;
            $cliente->direccion = $request->direccion;
            $cliente->telefono = $request->telefono;
            $cliente->email = $request->email; 
            $cliente->contacto = $request->contacto;
            $cliente->telefono_contacto = $request->telefono_contacto;
            $cliente->condicion = '1';
            $cliente->save();

            DB::commit();//hacemos referencia a nuestro metodo commit
        
        } catch (Exception $e){
            DB::rollBack();//si ocurriera algun error llamamos al metodo rolback para que desaga la transaccion
        }

    }

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        
        try{
            DB::beginTransaction();
            //vamos a buscar el cliente a modificar
            //creamos nuestro objeto cliente se hace rferencia al modelo cliente pero usando el meto find 
            //voy a obtener el campo cliente cuya propiedad es igual a la propiedad id que estoy resiviendo en el objeto request
            $cliente = Cliente::findOrFail($request->id);
            //creamos el objeto cliente hacemos referencia al objeto cliente utilizando el metodo find voy a obtener la cliente a cuya id 
            //es el mismo id que tengo en el objeto cliente
            $request->validate([
                'num_documento' => 'required|unique:clientes,num_documento,'.$request->id.',id'
            ]);
            $cliente->nombre = $request->nombre;
            $cliente->tipo_documento = $request->tipo_documento;
            $cliente->num_documento = $request->num_documento;
            $cliente->direccion = $request->direccion;
            $cliente->telefono = $request->telefono;
            $cliente->email = $request->email;
            $cliente->contacto = $request->contacto;
            $cliente->telefono_contacto = $request->telefono_contacto;
            $cliente->condicion = '1';

            $cliente->save();

            DB::commit();//hacemos referencia a nuestro metodo commit
        } catch (Exception $e){
            DB::rollBack();//si ocurriera algun error llamamos al metodo rolback para que desaga la transaccion
        }
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $cliente = Cliente::findOrFail($request->id);
        $cliente->condicion = '0';
        $cliente->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $cliente = Cliente::findOrFail($request->id);
        $cliente->condicion = '1';
        $cliente->save();
    }
}