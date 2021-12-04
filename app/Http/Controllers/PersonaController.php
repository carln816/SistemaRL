<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Persona;

class PersonaController extends Controller
{
    //
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
        
        
        if ($buscar==''){//muestra los registros de mi tabla clientes, ordenandolos de forma descendente por el id where('idpersona','<>',1)-
            $personas = Persona::orderBy('id', 'desc')->paginate(3);
        }
        else{//pero si la variable buscar es diferente de vacio,
            //el arreglo clientes va ser igual a las clientes pero usaremos la condicion where,
            //donde voy a decir el texto a buscar puede estar contenido ya sea al inicio o al final de donde en nuestro campo criterio
            // los porcentajes son comodines que nos indican que el texto puede estar al inicio o al final
            $personas = Persona::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(3);
        }

        return [
            'pagination' => [
                'total'        => $personas->total(),
                'current_page' => $personas->currentPage(),
                'per_page'     => $personas->perPage(),
                'last_page'    => $personas->lastPage(),
                'from'         => $personas->firstItem(),
                'to'           => $personas->lastItem(),
            ],
            'personas' => $personas
        ];
    }

    public function listarPdf(Request $request)
    {
        $personas = Persona::select('personas.id','personas.nombre','personas.tipo_documento',
            'personas.num_documento','personas.direccion','personas.telefono',
            'personas.email')
           //se usa el metodo para order por el id de la persona
            ->orderBy('personas.nombre', 'desc')->get();

        $cont=Persona::count();//la variable cont tiene la cantidad de registros registrados
        //almaceno la variable pdf usando el alias pdf  y el metodo load para mostrar el reporte en una vista que se llama articulo 
        //y a esa vista le voy a enviar un parametro articulos que va ser igual alo que contiene nuestra variable articulos 
        //y otro parametro llamado con que va ser igual a lo que contiene nuestra variable cont
        $pdf = \PDF::loadView('pdf.personaspdf', ['personas'=>$personas, 'cont'=>$cont]);
        return $pdf->download('personas.pdf');//retorno la varibale pdf donde hace referencia a su metodo download 
    }

    public function consuPdf(Request $request,$id)
    {
        $personas = Persona::select('personas.id','personas.nombre','personas.tipo_documento',
        'personas.num_documento','personas.direccion','personas.telefono',
        'personas.email')
        ->where('personas.id','=',$id)//filtro el id cuyo id es igual al id que recibo como paramentro
        ->orderBy('personas.id', 'desc')->take(1)->get();//obtengo una sola venta con el metodo get

        //se declara la variable numcodigo hago uso del modelo Proveedor.
        $numid=Persona::select('id')->where('id',$id)->get();//Selecciono el campo num_documento, pero solamente filtro el id de la tabla Proveedor
        //sea igual al valor del parametro id que estoy recibiendo

        //Declaro la variable pdf se hace uso del alias PDF, SE USA el metodo loadview, y le indico que la vista que se llama Proveedor que esta dentro de la 
        //carpeta pdf genero el reporte, y le voy a enviar en el parametro Proveedor el valor que tenemps en la variable Proveedor, y en el parametro detalles el 
        //valor que tenemos en la variable detalles
        $pdf = \PDF::loadView('pdf.personasconsupdf',['personas'=>$personas]);
        return $pdf->download('persona-'.$numid[0]->id.'.pdf');//se retorna el objeto pdf y con el metodo download el documento tendra el nombre 
    }



    public function getPerson(Request $request){
        if (!$request->ajax()) return redirect('/');
        $person = DB::select(
            "
            SELECT
                p.id,
                p.nombre
                    
            FROM users u

            RIGHT JOIN  personas p ON u.idpersona = p.id

            WHERE u.idpersona IS NULL
            "
        );
        return response()->json($person);
    }
    
    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        
        try{
            DB::beginTransaction();
            $request->validate([
                'num_documento' => 'required|unique:personas',
            ]);
            $persona = new Persona();
            $persona->nombre = $request->nombre;
            $persona->tipo_documento = $request->tipo_documento;
            $persona->num_documento = $request->num_documento;
            $persona->direccion = $request->direccion;
            $persona->telefono = $request->telefono;
            $persona->email = $request->email;
            
            $persona->save();

            DB::commit();

        } catch (Exception $e){
            DB::rollBack();
        }
       
    }

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        
        try{
            DB::beginTransaction();
            $request->validate([
                'num_documento' => 'required|unique:personas,num_documento,'.$request->id.',id'
            ]);
            //Buscar primero el usuario a modificar
            $persona = Persona::findOrFail($request->id);

            $persona->nombre = $request->nombre;
            $persona->tipo_documento = $request->tipo_documento;
            $persona->num_documento = $request->num_documento;
            $persona->direccion = $request->direccion;
            $persona->telefono = $request->telefono;
            $persona->email = $request->email;
            
            $persona->save();

            DB::commit();

        } catch (Exception $e){
            DB::rollBack();
        }

    }
    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $persona = Persona::findOrFail($request->id);
        $persona->condicion = '0';
        $persona->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $persona = Persona::findOrFail($request->id);
        $persona->condicion = '1';
        $persona->save();
    }
}
