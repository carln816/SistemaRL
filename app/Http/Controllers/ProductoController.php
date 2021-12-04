<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Producto;
use App\Ingreso;
use Carbon\Carbon;

class ProductoController extends Controller
{
    //
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
         //en la variable buscar voy almacenar lo que estoy recibiendo en el parametro buscar atravez de ajax
        $buscar = $request->buscar;
        //en la variable criterio voy almacenar lo que estoy recibiendo en el parametro criterio mediante el metodo get atravez de ajax
        $criterio = $request->criterio;
        
        if ($buscar==''){//trabaja por el metodo join donde uniremos con la tabla categorias
            //muestra los registros de mi tabla categoria, ordenandolos de forma descendente por el id
            //en este caso la llave foranea de mi tabla productos debe ser igual a la llave primaria id de mi tabla categorias
            $productos = Producto::join('categorias','productos.idcategoria','=','categorias.id')
            //empezara a seleccionar todas las columnas que quiero obtener
            //de mi tabla categorias la voy a renombrar por nombre_categoria
            ->select('productos.id','productos.idcategoria','productos.codigo','productos.nombre',
            'categorias.nombre as nombre_categoria','productos.fecha_hora','productos.stock',
            'productos.peso','productos.precio_venta','productos.descripcion','productos.condicion')
            ->orderBy('productos.id', 'asc')->paginate(2);
        }

        else{
            $productos = Producto::join('categorias','productos.idcategoria','=','categorias.id')
            //empezara a seleccionar todas las columnas que quiero obtener
            //de mi tabla categorias la voy a renombrar por nombre_categoria
            ->select('productos.id','productos.idcategoria','productos.codigo','productos.nombre',
            'categorias.nombre as nombre_categoria','productos.fecha_hora','productos.stock',
            'productos.peso','productos.precio_venta','productos.descripcion','productos.condicion')
            //lo que vamos a decir es si criterio es nombre el campo con los que vamos a bucar es criterio. nombre y si el criterio es codigo voy a buscar por el campo productos. codigo
            ->where('productos.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('productos.id', 'asc')->paginate(2);
        }

        return [
            'pagination' => [
                'total'        => $productos->total(),
                'current_page' => $productos->currentPage(),
                'per_page'     => $productos->perPage(),
                'last_page'    => $productos->lastPage(),
                'from'         => $productos->firstItem(),
                'to'           => $productos->lastItem(),
            ],
            'productos' => $productos
        ];
    }

    public function listarProducto(Request $request)//revisar
    {
        if (!$request->ajax()) return redirect('/');
        //recibe dos paramentros, buscar y criterio
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){//si el parametro buscar esta vacio voy a obtener el listado de las productos
            $productos = Producto::join('categorias','productos.idcategoria','=','categorias.id')
            ->select('productos.id','productos.idcategoria','productos.codigo','productos.nombre',
            'categorias.nombre as nombre_categoria','productos.peso',
            'productos.stock','productos.descripcion','productos.condicion')
            ->orderBy('productos.id', 'desc')->paginate(10);
        }
        else{//sino realiza el filtro, usando el metodo where por ese criterio de busqueda
            $productos = Producto::join('categorias','productos.idcategoria','=','categorias.id')
            ->select('productos.id','productos.idcategoria','productos.codigo','productos.nombre',
            'categorias.nombre as nombre_categoria','productos.peso','productos.stock',
            'productos.descripcion','productos.condicion')
            ->where('productos.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('productos.id', 'desc')->paginate(10);//muestro los 10 primeros resultados para no mostar todo
        }
    
        return ['productos' => $productos];//retorna el objeto productos y  le envio ese objeto productos lo que tengo en mi objeto materias, es decir la lista de todas la materias
    }
    public function listarProductoVenta(Request $request)//revisar
    {
        if (!$request->ajax()) return redirect('/');
        //recibe dos paramentros, buscar y criterio
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){//si el parametro buscar esta vacio voy a obtener el listado de las productos
            $productos = Producto::join('categorias','productos.idcategoria','=','categorias.id')
            ->select('productos.id','productos.idcategoria','productos.codigo','productos.nombre',
            'categorias.nombre as nombre_categoria','productos.peso','productos.precio_venta',
            'productos.stock','productos.descripcion','productos.condicion')
            ->where('productos.stock','>','0')
            ->orderBy('productos.id', 'desc')->paginate(10);
        }
        else{//sino realiza el filtro, usando el metodo where por ese criterio de busqueda
            $productos = Producto::join('categorias','productos.idcategoria','=','categorias.id')
            ->select('productos.id','productos.idcategoria','productos.codigo','productos.nombre',
            'categorias.nombre as nombre_categoria','productos.peso','productos.precio_venta',
            'productos.stock','productos.descripcion','productos.condicion')
            ->where('productos.'.$criterio, 'like', '%'. $buscar . '%')
            ->where('productos.stock','>','0')
            ->orderBy('productos.id', 'desc')->paginate(10);//muestro los 10 primeros resultados para no mostar todo
        }
    
        return ['productos' => $productos];//retorna el objeto productos y  le envio ese objeto productos lo que tengo en mi objeto materias, es decir la lista de todas la materias
    }

    public function buscarProducto(Request $request){//revisar
        if (!$request->ajax()) return redirect('/');//se implementa la structura condicional para validar que solo se aceoten peticiones ajax

        $filtro = $request->filtro;//declaro la variable filtro donde voy a recibir la propiedad filtro de nuestro objeto request que estoy recibiendo mediante ajax
        $productos = Producto::where('codigo','=', $filtro)//declaro la varibale prod$productos donde es igual al modelo Materia, donde el codigo sea igual al filtro que estoy recibiendo 
        ->select('id','nombre')->orderBy('nombre', 'asc')->take(1)->get();// selecciono el campo nombre y el id del Materia. y lo ordeno por el campo nombre, en esTe caso solo tomare un registro 

        return ['productos' => $productos];// se va a retornar, enviando en el parametro llamado prod$productos, el listado de todos que tengo en la variable materias
    }

    public function buscarProductoVenta(Request $request){//revisar
        if (!$request->ajax()) return redirect('/');//se implementa la estructura condicional para validar que solo se acepten peticiones ajax

        $filtro = $request->filtro;//declaro la variable filtro donde voy a recibir la propiedad filtro de nuestro objeto request que estoy recibiendo mediante ajax
        $productos = Producto::where('codigo','=', $filtro)//declaro la varibale $productos donde es igual al modelo producto, donde el codigo sea igual al filtro que estoy recibiendo 
        ->select('id','nombre','stock','precio_venta','peso')//seleciono los campos nombre, stock y precio
        ->where('stock','>','0')//si el stock es mayor a 0, le indico que solo me muestre los articulos que estan en almacen
        ->orderBy('nombre', 'asc')//lo ordeno por el campo nombre,
        ->take(1)->get();//En este caso solo tomare un registro

        return ['productos' => $productos];
    }

    public function listarPdf(Request $request)
    {
        $productos = Producto::join('categorias','productos.idcategoria','=','categorias.id')
            ->select('productos.id','productos.idcategoria','productos.codigo','productos.nombre',
            'categorias.nombre as nombre_categoria','productos.created_at','productos.stock',
            'productos.peso','productos.precio_venta','productos.descripcion','productos.condicion')
            ->orderBy('productos.nombre', 'desc')->get();

        $cont=Producto::count();//la variable cont tiene la cantidad de registros registrados
        //almaceno la variable pdf usando el alias pdf  y el metodo load para mostrar el reporte en una vista que se llama producto
        //y a esa vista le voy a enviar un parametro productos que va ser igual al que contiene nuestra variable productos 
        //y otro parametro llamado cont que va ser igual a lo que contiene nuestra variable cont
        $pdf = \PDF::loadView('pdf.productospdf', ['productos'=>$productos, 'cont'=>$cont]);
        return $pdf->download('productos.pdf');//retorno la varibale pdf donde hace referencia a su metodo download 
    }

    public function consuPdf(Request $request,$id)
    {
        $productos = Producto::join('categorias','productos.idcategoria','=','categorias.id')
        ->select('productos.id','productos.idcategoria','productos.codigo','productos.nombre',
        'categorias.nombre as nombre_categoria','productos.created_at','productos.stock',
        'productos.peso','productos.precio_venta','productos.descripcion','productos.condicion')
        ->where('productos.id','=',$id)//filtro el id cuyo id es igual al id que recibo como paramentro
        ->orderBy('productos.id', 'desc')->take(1)->get();//obtengo una sola venta con el metodo get

        //se declara la variable numcodigo hago uso del modelo producto.
        $numcodigo=Producto::select('codigo')->where('id',$id)->get();//Selecciono el campo codigo, pero solamente filtro el id de la tabla productos
        //sea igual al valor del parametro id que estoy recibiendo

        //Declaro la variable pdf se hace uso del alias PDF, SE USA el metodo loadview, y le indico que la vista que se llama producto que esta dentro de la 
        //carpeta pdf genero el reporte, y le voy a enviar en el parametro producto el valor que tenemos en la variable producto
        $pdf = \PDF::loadView('pdf.productosconsupdf',['productos'=>$productos]);
        return $pdf->download('productos-'.$numcodigo[0]->codigo.'.pdf');//se retorna el objeto pdf y con el metodo download el documento tendra el nombre 
    }
 
    public function store(Request $request)
    {

        if (!$request->ajax()) return redirect('/');

        try{
            DB::beginTransaction();
            $request->validate([
                'codigo' => 'required|unique:productos',
            ]);
            ///variable para identificar mi zona horaria, usando la clase carbon le indico la fecha depedinedo mi zona horaria
            //con esta variable se capta de manera automatica la fecha uso la funccion 
            $mytime= Carbon::now('America/Costa_Rica');

            $producto = new Producto();
            $producto->idcategoria = $request->idcategoria;
            $producto->codigo = $request->codigo;
            $producto->nombre = $request->nombre;
            $producto->fecha_hora = $mytime->toDateTimeString();//a la propiedad fecha le envio lo que tengo almacenado en mytime que captura la hora actual pero mediante el metodo todatestring lo convierto a una fecha y hora aceptable          
            $producto->stock = $request->stock;//toDateString
            $producto->peso = $request->peso;
            $producto->precio_venta = $request->precio_venta;
            $producto->descripcion = $request->descripcion;
            $producto->condicion = '1';
            $producto->save();

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
            $request->validate([//Con esta variable request definida hacia el validate le inidico que el 
                'codigo' => 'required|unique:productos,codigo,'.$request->id.',id'
            ]);
            ///variable para identificar mi zona horaria, usando la clase carbon le indico la fecha dependiendo mi zona horaria
            //con esta variable se capta de manera automatica la fecha uso la funccion 
            $mytime= Carbon::now('America/Costa_Rica');

            //el objeto es igual a la clase categoria, pero vamos a usar el metodo
            //findOrFail y le enviamos como argumento la propiedad id que la recibimos
            //en el objeto request
            $producto = Producto::findOrFail($request->id);
            $producto->idcategoria = $request->idcategoria;
            $producto->codigo = $request->codigo;
            $producto->nombre = $request->nombre;
            $producto->fecha_hora = $mytime->toDateTimeString();//a la propiedad fecha le envio lo que tengo almacenado en mytime que captura la hora actual pero mediante el metodo todatestring lo convierto a una fecha y hora aceptable
            $producto->stock = $request->stock;//toDateTimeString
            $producto->peso = $request->peso;
            $producto->precio_venta = $request->precio_venta;
            $producto->descripcion = $request->descripcion;
            $producto->condicion = '1';
            $producto->save();

            DB::commit();//hacemos referencia a nuestro metodo commit

        } catch (Exception $e){
            DB::rollBack();//si ocurriera algun error llamamos al metodo rolback para que desaga la transaccion
        }
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $producto = Producto::findOrFail($request->id);
        $producto->condicion = '0';
        $producto->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $producto = Producto::findOrFail($request->id);
        $producto->condicion = '1';
        $producto->save();
    }

}
