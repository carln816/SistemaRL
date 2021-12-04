<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Materia;
use Carbon\Carbon;

class MateriaController extends Controller
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
        
        if ($buscar==''){//trabaja por el metodo join donde uniremos con la tabla categorias
            //muestra los registros de mi tabla categoria, ordenandolos de forma descendente por el id
            //en este caso la llave foranea de mi tabla materias debe ser igual a la llave primaria id de mi tabla categorias
            $materias = Materia::join('categorias','materias.idcategoria','=','categorias.id')
            //empezara a seleccionar todas las columnas que quiero obtener
            //de mi tabla categorias la voy a renombrar por nombre_categoria
            ->select('materias.id','materias.idcategoria','materias.codigo','materias.nombre',
            'categorias.nombre as nombre_categoria','materias.fecha_hora','materias.precio_compra','materias.stock',
            'materias.unidad_medida','materias.descripcion','materias.condicion')
            ->orderBy('materias.id', 'desc')->paginate(3);
        }
        else{
            $materias = Materia::join('categorias','materias.idcategoria','=','categorias.id')
            //empezara a seleccionar todas las columnas que quiero obtener
            //de mi tabla categorias la voy a renombrar por nombre_categoria
            ->select('materias.id','materias.idcategoria','materias.codigo','materias.nombre',
            'categorias.nombre as nombre_categoria','materias.fecha_hora','materias.precio_compra','materias.stock',
            'materias.unidad_medida','materias.descripcion','materias.condicion')
            //lo que vamos a decir es si criterio es nombre el campo con los que vamos a bucar es criterio. nombre y si el criterio es codigo voy a buscar por el campo materias. codigo
            ->where('materias.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('materias.id', 'desc')->paginate(3);
        }
        return [
            'pagination' => [
                'total'        => $materias->total(),
                'current_page' => $materias->currentPage(),
                'per_page'     => $materias->perPage(),
                'last_page'    => $materias->lastPage(),
                'from'         => $materias->firstItem(),
                'to'           => $materias->lastItem(),
            ],
            'materias' => $materias
        ];
    }

    public function listarMateria(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        //recibe dos paramentros, buscar y criterio
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){//si el parametro buscar esta vacio voy a obtener el listado de las materias
            $materias = Materia::join('categorias','materias.idcategoria','=','categorias.id')
            ->select('materias.id','materias.idcategoria','materias.codigo','materias.nombre',
            'categorias.nombre as nombre_categoria','materias.precio_compra',
            'materias.stock','materias.unidad_medida','materias.descripcion','materias.condicion')
            ->orderBy('materias.id', 'desc')->paginate(10);
        }
        else{//sino realiza el filtro, usando el metodo where por ese criterio de busqueda
            $materias = Materia::join('categorias','materias.idcategoria','=','categorias.id')
            ->select('materias.id','materias.idcategoria','materias.codigo','materias.nombre',
            'categorias.nombre as nombre_categoria','materias.precio_compra','materias.stock',
            'materias.unidad_medida','materias.descripcion','materias.condicion')
            ->where('materias.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('materias.id', 'desc')->paginate(10);//muestro los 10 primeros resultados para no mostar todo
        }
    
        return ['materias' => $materias];//retorna el objeto materias y  le envio ese objeto materias lo que tengo en mi objeto materias, es decir la lista de todas la materias
    }

    public function buscarMateria(Request $request){
        if (!$request->ajax()) return redirect('/');//se implementa la estructura condicional para validar que solo se acepten peticiones ajax

        $filtro = $request->filtro;//declaro la variable filtro donde voy a recibir la propiedad filtro de nuestro objeto request que estoy recibiendo mediante ajax
        $materias = Materia::where('codigo','=', $filtro)//declaro la varibale materias donde es igual al modelo Materia, donde el codigo sea igual al filtro que estoy recibiendo 
        ->select('id','nombre')->orderBy('nombre', 'asc')->take(1)->get();// selecciono el campo nombre y el id del Materia. y lo ordeno por el campo nombre, en este caso solo tomare un registro 

        return ['materias' => $materias];// se va a retornar, enviando en el parametro llamado materias, el listado de todos que tengo en la variable materias
    }

    public function listarPdf(Request $request)//arreglar
    {
        $materias = Materia::join('categorias','materias.idcategoria','=','categorias.id')
            ->select('materias.id','materias.idcategoria','materias.codigo','materias.nombre',
            'categorias.nombre as nombre_categoria','materias.created_at','materias.stock',
            'materias.unidad_medida','materias.precio_compra','materias.descripcion','materias.condicion')
            ->orderBy('materias.nombre', 'desc')->get();

        $cont=Materia::count();//la variable cont tiene la cantidad de registros registrados
        //almaceno la variable pdf usando el alias pdf  y el metodo load para mostrar el reporte en una vista que se llama producto
        //y a esa vista le voy a enviar un parametro productos que va ser igual al que contiene nuestra variable productos 
        //y otro parametro llamado cont que va ser igual a lo que contiene nuestra variable cont
        $pdf = \PDF::loadView('pdf.materiaspdf', ['materias'=>$materias, 'cont'=>$cont]);
        return $pdf->download('materias.pdf');//retorno la varibale pdf donde hace referencia a su metodo download 
    }

    public function consuPdf(Request $request,$id)//arreglar
    {
        $materias = Materia::join('categorias','materias.idcategoria','=','categorias.id')
        ->select('materias.id','materias.idcategoria','materias.codigo','materias.nombre',
        'categorias.nombre as nombre_categoria','materias.created_at','materias.stock',
        'materias.unidad_medida','materias.precio_compra','materias.descripcion','materias.condicion')
        ->where('materias.id','=',$id)//filtro el id cuyo id es igual al id que recibo como paramentro
        ->orderBy('materias.id', 'desc')->take(1)->get();//obtengo una sola venta con el metodo get

        //se declara la variable numcodigo hago uso del modelo producto.
        $numcodigo=Materia::select('codigo')->where('id',$id)->get();//Selecciono el campo codigo, pero solamente filtro el id de la tabla productos
        //sea igual al valor del parametro id que estoy recibiendo

        //Declaro la variable pdf se hace uso del alias PDF, SE USA el metodo loadview, y le indico que la vista que se llama producto que esta dentro de la 
        //carpeta pdf genero el reporte, y le voy a enviar en el parametro producto el valor que tenemos en la variable producto
        $pdf = \PDF::loadView('pdf.materiasconsupdf',['materias'=>$materias]);
        return $pdf->download('materias-'.$numcodigo[0]->codigo.'.pdf');//se retorna el objeto pdf y con el metodo download el documento tendra el nombre 
    }

    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $request->validate([
            'codigo' => 'required|unique:materias',
        ]);
        
        //variable para identificar mi zona horaria, usando la clase carbon le indico la fecha depedinedo mi zona horaria
        //con esta variable se capta de manera automatica la fecha uso la funccion 
        $mytime= Carbon::now('America/Costa_Rica');
        //delacaramos un objeto categoria para instanciar
        $materia = new Materia();
        $materia->idcategoria = $request->idcategoria;
        $materia->codigo = $request->codigo;
        $materia->nombre = $request->nombre;
        $materia->precio_compra = $request->precio_compra;
        $materia->stock = $request->stock;
        $materia->unidad_medida = $request->unidad_medida;
        $materia->fecha_hora = $mytime->toDateTimeString();
        $materia->descripcion = $request->descripcion;
        $materia->condicion = '1';
        $materia->save();
    }
    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $request->validate([
            'codigo' => 'required|unique:materias,codigo,'.$request->id.',id'
        ]);
        //el objeto es igual a la clase categoria, pero vamos a usar el metodo
        //findOrFail y le enviamos como argumento la propiedad id que la recibimos
        //en el objeto request
        $mytime= Carbon::now('America/Costa_Rica');

        $materia = Materia::findOrFail($request->id);
        $materia->idcategoria = $request->idcategoria;
        $materia->codigo = $request->codigo;
        $materia->nombre = $request->nombre;
        $materia->precio_compra = $request->precio_compra;
        $materia->stock = $request->stock;
        $materia->unidad_medida = $request->unidad_medida;
        $materia->fecha_hora = $mytime->toDateTimeString();
        $materia->descripcion = $request->descripcion;
        $materia->condicion = '1';
        $materia->save();
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $materia = Materia::findOrFail($request->id);
        $materia->condicion = '0';
        $materia->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $materia = Materia::findOrFail($request->id);
        $materia->condicion = '1';
        $materia->save();
    }
}