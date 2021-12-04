<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Categoria;

class CategoriaController extends Controller
{
    
    //con esto se agrega seguridad a las peticiones que estamos realizando
    public function index(Request $request)
    {   // determinar si la peticion que se ha hecho es diferente a una peticion ajax, que se redirige a ruta raiz, de esta manera solo podra acceder por el ajax
        if (!$request->ajax()) return redirect('/');
        //en la variable buscar voy almacenar lo que estoy recibiendo en el parametro buscar atravez de ajax
        $buscar = $request->buscar;
        //en la variable criterio voy almacenar lo que estoy recibiendo en el parametro criterio mediante el metodo get atravez de ajax
        $criterio = $request->criterio;
        
        if ($buscar==''){//muestra los registros de mi tabla categoria, ordenandolos de forma descendente por el id
            $categorias = Categoria::orderBy('id', 'desc')->paginate(3);
        }
        else{//pero si la variable buscar es diferente de vacio,
            //el arreglo categorias va ser igual a las categorias pero usaremos la condicion where,
            //donde voy a decir el texto a buscar puede estar contenido ya sea al inicio o al final de donde en nuestro campo criterio
            // los porcentajes son comodines que nos indican que el texto puede estar al inicio o al final
            $categorias = Categoria::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(3);
        }
        
        return [
            'pagination' => [
                'total'        => $categorias->total(),
                'current_page' => $categorias->currentPage(),
                'per_page'     => $categorias->perPage(),
                'last_page'    => $categorias->lastPage(),
                'from'         => $categorias->firstItem(),
                'to'           => $categorias->lastItem(),
            ],
            'categorias' => $categorias
        ];
    }

    public function selectCategoria(Request $request){
        if (!$request->ajax()) return redirect('/');
        $categorias = Categoria::where('condicion','=','1')
        ->select('id','nombre')->orderBy('nombre', 'asc')->get();
        return ['categorias' => $categorias];

    }
 
    public function listarPdf(Request $request)
    {
        $categorias = Categoria::select('categorias.id','categorias.nombre','categorias.descripcion',)
            ->orderBy('categorias.nombre', 'desc')->get();

        $cont=Categoria::count();//la variable cont tiene la cantidad de registros registrados
        //almaceno la variable pdf usando el alias pdf  y el metodo load para mostrar el reporte en una vista que se llama producto
        //y a esa vista le voy a enviar un parametro productos que va ser igual al que contiene nuestra variable productos 
        //y otro parametro llamado cont que va ser igual a lo que contiene nuestra variable cont
        $pdf = \PDF::loadView('pdf.categoriapdf', ['categorias'=>$categorias, 'cont'=>$cont]);
        return $pdf->download('categoria.pdf');//retorno la varibale pdf donde hace referencia a su metodo download 
    }

    public function consuPdf(Request $request,$id)
    {
        $categorias = Categoria::select('categorias.id','categorias.nombre','categorias.descripcion',)
        ->where('categorias.id','=',$id)//filtro el id cuyo id es igual al id que recibo como paramentro
        ->orderBy('categorias.id', 'desc')->take(1)->get();//obtengo una sola venta con el metodo get

        //se declara la variable numcodigo hago uso del modelo producto.
        $numcodigo=Categoria::select('nombre')->where('id',$id)->get();//Selecciono el campo codigo, pero solamente filtro el id de la tabla productos
        //sea igual al valor del parametro id que estoy recibiendo

        //Declaro la variable pdf se hace uso del alias PDF, SE USA el metodo loadview, y le indico que la vista que se llama producto que esta dentro de la 
        //carpeta pdf genero el reporte, y le voy a enviar en el parametro producto el valor que tenemos en la variable producto
        $pdf = \PDF::loadView('pdf.categoriaconsupdf',['categorias'=>$categorias]);
        return $pdf->download('categoria-'.$numcodigo[0]->nombre.'.pdf');//se retorna el objeto pdf y con el metodo download el documento tendra el nombre 
    }

    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        //dd($request->all());
        try{
            
            //$this->_validate($request);
            $request->validate([
                'nombre' => 'required|unique:categorias',
                //, 'string', 'max:10'
            ]);
            
            /*$categoria = new Categoria();
            $categoria->nombre = $request->nombre;
            $categoria->descripcion = $request->descripcion;
            $categoria->condicion = '1';
            $categoria->save();*/
            Categoria::create([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'condicion' => '1'
            ]);

            
        } catch (Exception $e){
            
        }
    }

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        //el objeto es igual a la clase categoria, pero vamos a usar el metodo
        //findOrFail y le enviamos como argumento la propiedad id que la recibimos
        //en el objeto request
        try{
            DB::beginTransaction();

            $request->validate([
                'nombre' => 'required|unique:categorias,nombre,'.$request->id.',id'
            ]);
            
            $categoria = Categoria::findOrFail($request->id);
            $categoria->nombre = $request->nombre;
            $categoria->descripcion = $request->descripcion;
            $categoria->condicion = '1';
            $categoria->save();

            DB::commit();//hacemos referencia a nuestro metodo commit
        } catch (Exception $e){
            DB::rollBack();//si ocurriera algun error llamamos al metodo rolback para que desaga la transaccion
        }
    }

    public function desactivar(Request $request)
    {
        //
        if (!$request->ajax()) return redirect('/');
        $categoria = Categoria::findOrFail($request->id);
        $categoria->condicion = '0';
        $categoria->save();
    }

    public function activar(Request $request)
    {
        //
        if (!$request->ajax()) return redirect('/');
        $categoria = Categoria::findOrFail($request->id);
        $categoria->condicion = '1';
        $categoria->save();
    }
}