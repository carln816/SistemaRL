<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Venta;
use App\DetalleVenta;

class VentaController extends Controller
{
    //
    
    public function index(Request $request)
    {
        //con esto se agrega seguridad a las peticiones que estamos realizando
        // determinar si la peticion que se ha hecho es diferente a una peticion ajax, que se redirige a ruta raiz, de esta manera solo podra acceder por el ajax
        if (!$request->ajax()) return redirect('/');
        //en la variable buscar voy almacenar lo que estoy recibiendo en el parametro buscar atravez de ajax
        $buscar = $request->buscar;
        //en la variable criterio voy almacenar lo que estoy recibiendo en el parametro criterio mediante el metod get atravez de ajax
        $criterio = $request->criterio;
        
        if ($buscar==''){
            //Unimos la tabla venta con la tabla clientes, y le indico que de mi tabla ingresos el id cliente sea igual al id de la tabla clientes
            //trabaja por el metodo join donde uniremos con la tabla clientes
            //se usa el metodo join para unir la tabla clientes, en este caso unimos a cliente con la tabla clientes. 
            //en este caso el cliente mediante que campo de la tabla clientes el campo id debe ser en este caso igual al campo id de la tabla cliente
            $ventas = Venta::join('clientes','ventas.idcliente','=','clientes.id')
            ->join('users','ventas.idusuario','=','users.id')//tambien uno la tabla user con la tabla users, le indico que de mi tabla ventas el id usuario va ser el igual al campo id de la tabla users
            ->select('ventas.id','ventas.tipo_comprobante',//muestro todos los campos que deseo mostrar
            'ventas.serie_comprobante','ventas.fecha_hora',
            'ventas.impuesto','ventas.total','ventas.estado','clientes.nombre','users.usuario')
            ->orderBy('ventas.id', 'desc')->paginate(3);// se ordenan de forma descendente por el id y se pagina de 3 en 3
        }
        else{//pero si la variable buscar es diferente de vacio,
            $ventas = Venta::join('clientes','ventas.idcliente','=','clientes.id')
            ->join('users','ventas.idusuario','=','users.id')
            ->select('ventas.id','ventas.tipo_comprobante',
            'ventas.serie_comprobante','ventas.fecha_hora',
            'ventas.impuesto','ventas.total','ventas.estado','clientes.nombre','users.usuario')
            ->where('ventas.'.$criterio, 'like', '%'. $buscar . '%')->orderBy('ventas.id', 'desc')->paginate(3);
            //se filtra con el where por medio del campo criterio
            //de mi tabla ventas concateno con el parametro criterio, realizamos la busqueda usando like, 
            //con los comodines de los porcentajes al inicio y al final y voy a buscar el texto que tengo por parametro en buscar
            //y ordenamos todos los registros mediante el campo id de la tabla ventas
        }

        return [
            'pagination' => [
                'total'        => $ventas->total(),
                'current_page' => $ventas->currentPage(),
                'per_page'     => $ventas->perPage(),
                'last_page'    => $ventas->lastPage(),
                'from'         => $ventas->firstItem(),
                'to'           => $ventas->lastItem(),
            ],
            'ventas' => $ventas
        ];
    }

    public function obtenerCabecera(Request $request){
        if (!$request->ajax()) return redirect('/');

        $id = $request->id;
        //como es una venta unica la variable se llama venta
        //declaro una variable cuyo nombre es id que va ser igual a la propiedad id del objeto request que estoy recibiendo mediante ajax
        $venta = Venta::join('clientes','ventas.idcliente','=','clientes.id')//la variable ventas que va ser igual a venta, uniendo ventas con clientes, para obtener el cliente 
        ->join('users','ventas.idusuario','=','users.id')//de igual manera se une con la tabla users para obtener el usuario responsable de la venta
        //debajo voy a seleccionar de la tabla ventas los siguientes valores y de la tabla clientes el nombre y de users el usuario
        ->select('ventas.id','ventas.tipo_comprobante','ventas.serie_comprobante',
        'ventas.fecha_hora','ventas.impuesto','ventas.total',
        'ventas.estado','clientes.nombre','users.usuario')
        ->where('ventas.id','=',$id)//mediante el metodo where de mi tabla ventas el campo id, va ser igual a la variable id, que seria el valor del campo id que estoy obteniendo mediante ajax
        ->orderBy('ventas.id', 'desc')->take(1)->get();//ordeno por el campo id de manera descendente, utilizando el metodo take obtengo un registro , usando el metodo get obtendremos la venta
        
        return ['venta' => $venta];
    }

    public function obtenerDetalles(Request $request){
        if (!$request->ajax()) return redirect('/');

        $id = $request->id;//declaro una variable cuyo nombre es id que va ser igual a la propiedad id del objeto request que estoy recibiendo mediante ajax
        $detalles = DetalleVenta::join('productos','detalle_ventas.idproducto','=','productos.id')//vamos a unir detalleventa con la tabla productos, mediante el campo detalle_ventas el campo idproducto va ser igual campo id  de la tabla productos
        ->select('detalle_ventas.cantidad','detalle_ventas.precio',
        'detalle_ventas.peso','detalle_ventas.descuento',
        'productos.nombre as producto')//se van a selecionar los siguientes valores de la tabla ventas y de la tabla productos el nombre
        ->where('detalle_ventas.idventa','=',$id)//mediante el metodo where de mi tabla detalle_ventas el campo idventa va ser igual al valor de la variable id 
        ->orderBy('detalle_ventas.id', 'desc')->get();//ordeno el campo id de manera descendente, mediante el metodo get obtendremos todos los detalles
        
        return ['detalles' => $detalles];//se retorna el parametro detalles  y el valor que le vamos a enviar a ese parametro detalles, sera lo que tenemos en la variable detalles 
    }

    public function listarPdf(){
        $ventas = Venta::join('clientes','ventas.idcliente','=','clientes.id')
            ->join('users','ventas.idusuario','=','users.id')
            ->select('ventas.id','ventas.tipo_comprobante','ventas.serie_comprobante',
            'ventas.fecha_hora','ventas.impuesto','ventas.total',
            'ventas.estado','clientes.nombre','users.usuario')
            ->orderBy('ventas.id', 'desc')->get();
        $cont=Venta::count();

        $pdf = \PDF::loadView('pdf.ventaspdf',['ventas'=>$ventas,'cont'=>$cont])->setPaper('a4', 'landscape');
        return $pdf->download('ventas.pdf');
    }

    public function pdf(Request $request,$id)//aun NO
    {
        $venta = Venta::join('clientes','ventas.idcliente','=','clientes.id')//la variable venta hace referencia al modelo venta y voy a unir ese modelo con la tabla clientes, en este caso que de mi tabla ventas el idcliente es igual al id de la tabla clientes 
        ->join('users','ventas.idusuario','=','users.id')//tambien uno la tabla user con la tabla users, le indico que de mi tabla ventas el id usuario va ser el igual al campo id de la tabla users
        ->select('ventas.id','ventas.tipo_comprobante','ventas.serie_comprobante',//muestro todos los campos que deseo mostrar
        'ventas.created_at','ventas.impuesto','ventas.total',//el campo creat at es la fecha del registro de la venta
        'ventas.estado','clientes.nombre','clientes.tipo_documento','clientes.num_documento',
        'clientes.direccion','clientes.email',
        'clientes.telefono','users.usuario')
        ->where('ventas.id','=',$id)//filtro el id cuyo id es igual al id que recibo como paramentro
        ->orderBy('ventas.id', 'desc')->take(1)->get();//obtengo una sola venta con el metodo get

        //De esta manera se obtienen los detalles de la venta
        $detalles = DetalleVenta::join('productos','detalle_ventas.idproducto','=','productos.id')//en la variable detalles hago referncia 
        //al modelo DetalleVenta, uno el modelo con la tabla productos y le indico que de la tabla detalle de ventas el idproducto debe ser 
        //igual al id de la tabla productos
        ->select('detalle_ventas.cantidad','detalle_ventas.precio','detalle_ventas.descuento',//selecciono los campos 
        'detalle_ventas.peso','productos.nombre as producto')
        ->where('detalle_ventas.idventa','=',$id)//realizo solamente un filtro porque solo filtrare los detalles, cuyo campo idventa es igual 
        //al id que recibo como parametro en la funcion pdf 
        ->orderBy('detalle_ventas.id', 'desc')->get();//y ordeno por el id para mostrar ordenados los detalles por el orden de registro
        
        //se declara la variable numventa hago uso del modelo venta.
        $numventa=Venta::select('serie_comprobante')->where('id',$id)->get();//Selecciono el campo num, pero solamente filtro el id de la tabla ventas 
        //sea igual al valor del parametro id que estoy recibiendo

        //Declaro la variable pdf se hace uso del alias PDF, SE USA el metodo loadview, y le indico que la vista que se llama venta que esta dentro de la 
        //carpeta pdf genero el reporte, y le voy a enviar en el parametro venta el valor que tenemos en la variable venta, y en el parametro detalles el 
        //valor que tenemos en la variable detalles
        $pdf = \PDF::loadView('pdf.venta',['venta'=>$venta,'detalles'=>$detalles]);
        return $pdf->download('venta-'.$numventa[0]->serie_comprobante.'.pdf');//se retorna el objeto pdf y con el metodo download el documento tendra el nombre 
        //venta -mas la serie de la venta es decir la serie de comprobante, le digo mediante el array el indice cero lo que tengo en la propiedad 
        //serie_comprobante y concateno con la extension punto pdf        
    }

    public function pdfTicket(Request $request,$id){
        $venta = Venta::join('clientes','ventas.idcliente','=','clientes.id')
        ->join('users','ventas.idusuario','=','users.id')
        ->select('ventas.id','ventas.tipo_comprobante','ventas.serie_comprobante',
        'ventas.created_at','ventas.impuesto','ventas.total',
        'ventas.estado','clientes.nombre','clientes.tipo_documento','clientes.num_documento',
        'clientes.direccion','clientes.email',
        'clientes.telefono','users.usuario')
        ->where('ventas.id','=',$id)
        ->orderBy('ventas.id', 'desc')->take(1)->get();

        $detalles = DetalleVenta::join('productos','detalle_ventas.idproducto','=','productos.id')
        ->select('detalle_ventas.cantidad','detalle_ventas.precio','detalle_ventas.descuento',
        'productos.nombre as producto')
        ->where('detalle_ventas.idventa','=',$id)
        ->orderBy('detalle_ventas.id', 'desc')->get();

        $numventa=Venta::select('serie_comprobante')->where('id',$id)->get();

        $pdf = \PDF::loadView('pdf.ventaticket',['venta'=>$venta,'detalles'=>$detalles])->setPaper('a5');
        return $pdf->download('ventaTicket-'.$numventa[0]->serie_comprobante.'.pdf');
    }

    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        //implementaremos un capturador de excepciones
        // dentro del try usamos transacciones
        try{
            DB::beginTransaction();
            $request->validate([
                'serie_comprobante' => 'required|unique:ingresos',
            ]);
            ///variable para identificar mi zona horaria, usando la clase carbon le indico la fecha depedinedo mi zona horaria
            //con esta variable se capta de manera automatica la fecha uso la funccion 
            $mytime= Carbon::now('America/Costa_Rica');
            //delacaramos un objeto venta para instanciar el modelo venta
            $venta = new Venta();
            //le envio valores de propiedad
            $venta->idcliente = $request->idcliente;//valores a la propiedad idcliente y esto es lo que obtengo desde mi formulario en el objeto idcliente
            $venta->idusuario = \Auth::user()->id;//a la propiedad idusuario del objeto venta le envio el usuario que se encuentra autenticado, usando la clase auth, el objeto user  y le envio el id 
            //$venta->descripcion = $request->descripcion;
            $venta->tipo_comprobante = $request->tipo_comprobante;//se le va enviar el tipo de comrobante que esta recibiendo mediante ajax
            $venta->serie_comprobante = $request->serie_comprobante;//=
            //$venta->unidad_medida = $request->unidad_medida;//=
            $venta->fecha_hora = $mytime->toDateTimeString();//a la propiedad fecha le envio lo que tengo almacenado en mytime que captura la hora actual pero mediante el metodo todatestring lo convierto a una fecha y hora aceptable
            //toDateTimeString();//a la propiedad fecha le envio lo que tengo almacenado en mytime que captura la hora actual pero mediante el metodo todatestring lo convierto a una fecha y hora aceptable
            $venta->impuesto = $request->impuesto;//le envio lo que recibo en el parametro impuesto mediante ajax
            $venta->total = $request->total;//=
            $venta->estado = 'Registrado';//a la propiedad estado le envio que se encuentra registrado
            $venta->save();//el objeto se debe guardo en la tabla ventas

            $detalles = $request->data;//recibo lo que tengo en la propiedad data del objeto request, que estoy recibiendo mediante ajax, en la propiedad data van a ir todos los arrays de los detalles
            //Array de detalles
            //Recorro todos los elementos

            foreach($detalles as $ep=>$det)//recorre todo la variable detalles
            {//lo va hacer en la variable det
                //se crea el objeto DetalleVenta
                $detalle = new DetalleVenta();
                $detalle->idventa = $venta->id;//y a la propiedad idventa de mi objeto detalle le voy a enviar el id del objeto venta, objeto que se guardo recientemente en la tabla ventas de la base de datos
                //es decir le envia el id al que pertenece el detalle de venta
                $detalle->idproducto = $det['idproducto'];// le envio el indice idproducto que tengo en el array det que recorre todos los detalles, es decir todo el array de detalles que estoy recibiendo mediante ajax
                $detalle->cantidad = $det['cantidad'];//de igual manera cantidad lo que recibo en el indice cantidad
                $detalle->precio = $det['precio'];//a la propiedad precio que recibe en el indice precio en este caso en el array que se recorre mediante el bucle foreach
                $detalle->peso = $det['peso'];
                $detalle->descuento = $det['descuento'];///el objeto detalle propiedad descuento es igual al indice descuento del arrays detalles
                $detalle->save();//guardamos por el metodo save
            }
            
            DB::commit();//hacemos referencia a nuestro metodo commit
            return [//retornamos el parametro id,  el id de la venta que se va a generar
                'id' => $venta->id
            ];
        } catch (Exception $e){
            DB::rollBack();//si ocurriera algun error llamamos al metodo rolback para que desaga la transaccion 
        }
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $venta = Venta::findOrFail($request->id);
        $venta->estado = 'Anulado';
        $venta->save();
    }
}