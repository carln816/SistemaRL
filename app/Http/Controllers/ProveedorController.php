<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Proveedor;

class ProveedorController extends Controller
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
        
        if ($buscar==''){//muestra los registros de mi tabla proveedor, ordenandolos de forma descendente por el id
            $proveedores = Proveedor::orderBy('id', 'desc')->paginate(3);
        }
        else{
            //pero si la variable buscar es diferente de vacio,
            //el arreglo proveedor va ser igual a las proveedor pero usaremos la condicion where,
            //donde voy a decir el texto a buscar puede estar contenido ya sea al inicio o al final de donde en nuestro campo criterio
            // los porcentajes son comodines que nos indican que el texto puede estar al inicio o al final
            $proveedores = Proveedor::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(3);
        }

        
        return [
            'pagination' => [
                'total'        => $proveedores->total(),
                'current_page' => $proveedores->currentPage(),
                'per_page'     => $proveedores->perPage(),
                'last_page'    => $proveedores->lastPage(),
                'from'         => $proveedores->firstItem(),
                'to'           => $proveedores->lastItem(),
            ],
            'proveedores' => $proveedores
        ];
    }

    public function selectProveedor(Request $request){
        if (!$request->ajax()) return redirect('/');

        $filtro = $request->filtro;//se declara la variable de nombre filtro y esta es igual a lo que recibo en la propiedad filtro del objeto request dicho sera enviado desde la vista 
        $proveedores = Proveedor::where('proveedores.nombre', 'like', '%'. $filtro . '%')//se usara el filtro where, donde se realizara por el campo nombre de la tabla proveedores, usando la clausala like con un comodin al inicio y uno de porcentaje al final, 
        //indicando que el texto que buscando, en este caso almacenado en loa variable filtro puede estar al inicio, ala mitad al final, de nuestro campo nombre.
        ->orWhere('proveedores.num_documento', 'like', '%'. $filtro . '%')// la propiedad orwhere para indicar otro filtro en este caso por el num_docuemnto usando la clausala like
        ->select('proveedores.id','proveedores.nombre','proveedores.num_documento')//con la propiedad select voy a seleccionar la columna id de mi tabla proveedores, el nombre y el num_documento
        ->orderBy('proveedores.nombre', 'asc')->get();//ordenamos por medio del campo nombre de manera ascendete

        return ['proveedores' => $proveedores];//cuando hagamos referencia a este metodo lo que hare es devolver en un parametro llamado proveedores la lista que tengo almacenada en la variable proveedores que cumplan con el filtro 
    }

    public function listarPdf(Request $request)
    {//join('proveedores','proveedores.id','=','proveedores.id')
        $proveedores = Proveedor::select('proveedores.id','proveedores.nombre','proveedores.tipo_documento',
            'proveedores.num_documento','proveedores.direccion','proveedores.telefono',
            'proveedores.email','proveedores.contacto','proveedores.telefono_contacto')
           //se usa el metodo para order por el id de la persona
            ->orderBy('proveedores.nombre', 'desc')->get();

        $cont=Proveedor::count();//la variable cont tiene la cantidad de registros registrados
        //almaceno la variable pdf usando el alias pdf  y el metodo load para mostrar el reporte en una vista que se llama articulo 
        //y a esa vista le voy a enviar un parametro articulos que va ser igual alo que contiene nuestra variable articulos 
        //y otro parametro llamado con que va ser igual a lo que contiene nuestra variable cont
        $pdf = \PDF::loadView('pdf.proveedorpdf', ['proveedores'=>$proveedores, 'cont'=>$cont]);
        return $pdf->download('proveedor.pdf');//retorno la varibale pdf donde hace referencia a su metodo download 
    }

    public function consuPdf(Request $request,$id)
    {//join('proveedores','proveedores.id','=','proveedores.id')
        $proveedores = Proveedor::select('proveedores.id','proveedores.nombre','proveedores.tipo_documento',
        'proveedores.num_documento','proveedores.direccion','proveedores.telefono',
        'proveedores.email','proveedores.id','proveedores.contacto','proveedores.telefono_contacto')
        ->where('proveedores.id','=',$id)//filtro el id cuyo id es igual al id que recibo como paramentro
        ->orderBy('proveedores.id', 'desc')->take(1)->get();//obtengo una sola venta con el metodo get

        //se declara la variable numcodigo hago uso del modelo Proveedor.
        $numid=Proveedor::select('id')->where('id',$id)->get();//Selecciono el campo num_documento, pero solamente filtro el id de la tabla Proveedor
        //sea igual al valor del parametro id que estoy recibiendo

        //Declaro la variable pdf se hace uso del alias PDF, SE USA el metodo loadview, y le indico que la vista que se llama Proveedor que esta dentro de la 
        //carpeta pdf genero el reporte, y le voy a enviar en el parametro Proveedor el valor que tenemps en la variable Proveedor, y en el parametro detalles el 
        //valor que tenemos en la variable detalles
        $pdf = \PDF::loadView('pdf.proveedorconsupdf',['proveedores'=>$proveedores]);
        return $pdf->download('proveedor-'.$numid[0]->id.'.pdf');//se retorna el objeto pdf y con el metodo download el documento tendra el nombre 
    }

    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        //implementaremos un capturador de excepciones
        // dentro del try usamos transacciones
        try{
            DB::beginTransaction();
            $request->validate([
                'num_documento' => 'required|unique:proveedores',
            ]);
            $proveedor = new Proveedor();

            $proveedor->nombre = $request->nombre;
            $proveedor->tipo_documento = $request->tipo_documento;
            $proveedor->num_documento = $request->num_documento;
            $proveedor->direccion = $request->direccion;
            $proveedor->telefono = $request->telefono;
            $proveedor->email = $request->email;
            $proveedor->contacto = $request->contacto;
            $proveedor->telefono_contacto = $request->telefono_contacto;
            $proveedor->condicion = '1';
            $proveedor->save();

            DB::commit();//hacemos referencia a nuestro metodo commit
        
        } catch (Exception $e){
            DB::rollBack();//si ocurriera algun error llamamos al metodo rolback para que desaga la transaccion
        }

    }

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        //implementaremos un capturador de excepciones
        // dentro del try usamos transacciones
        try{
            DB::beginTransaction();
            $request->validate([
                'num_documento' => 'required|unique:proveedores,num_documento,'.$request->id.',id'
            ]);
            //vamos a buscar el proveedor a modificar
            //creamos nuestro objeto proveedor se hace rferencia al modelo proveedor pero usando el meto find 
            //voy a obtener el campo proveedor cuya propiedad es igual a la propiedad id que estoy resiviendo en el objeto request
            $proveedor = Proveedor::findOrFail($request->id);
        
            $proveedor->nombre = $request->nombre;
            $proveedor->tipo_documento = $request->tipo_documento;
            $proveedor->num_documento = $request->num_documento;
            $proveedor->direccion = $request->direccion;
            $proveedor->telefono = $request->telefono;
            $proveedor->email = $request->email;
            $proveedor->contacto = $request->contacto;
            $proveedor->telefono_contacto = $request->telefono_contacto;

            $proveedor->save();

            DB::commit();//hacemos referencia a nuestro metodo commit
        } catch (Exception $e){
            DB::rollBack();//si ocurriera algun error llamamos al metodo rolback para que desaga la transaccion
        }
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $proveedor = Proveedor::findOrFail($request->id);
        $proveedor->condicion = '0';
        $proveedor->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $proveedor = Proveedor::findOrFail($request->id);
        $proveedor->condicion = '1';
        $proveedor->save();
    }
}
