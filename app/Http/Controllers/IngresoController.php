<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Ingreso;
use App\DetalleIngreso;

class IngresoController extends Controller
{
    //
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        //en la variable buscar voy almacenar lo que estoy recibiendo en el parametro buscar atravez de ajax
        $buscar = $request->buscar;
        //en la variable criterio voy almacenar lo que estoy recibiendo en el parametro criterio mediante el metod get atravez de ajax
        $criterio = $request->criterio;
         
        if ($buscar==''){
             
            //Unimos la tabla ingreso con la tabla proveedores, y le indico que de mi tabla ingresos el id proveedor sea igual al id de la tabla proveedores
            //trabaja por el metodo join donde uniremos con la tabla proveedores
            // se usa el metodo join para unir con la tabla proveedores, en este caso unimos a proveedor con la tabla proveedores. 
            //en este caso proveedor mediante que campo de la tabla proveedores el campo id debe ser en este caso igual al campo id de la tabla persona
            $ingresos = Ingreso::join('proveedores','ingresos.idproveedor','=','proveedores.id')
            ->join('users','ingresos.idusuario','=','users.id')//tambien uno la tabla user con la tabla users, le indico que de mi tabla ingresos el id usuario va ser el igual al campo id de la tabla users
            ->select('ingresos.id','ingresos.tipo_comprobante',//muestro todos los campos que deseo mostrar
            'ingresos.serie_comprobante','ingresos.unidad_medida','ingresos.fecha_hora',
            'ingresos.impuesto','ingresos.total','ingresos.estado','proveedores.nombre','users.usuario')
            ->orderBy('ingresos.id', 'desc')->paginate(3);// se ordenan de forma descendente por el id y se pagina de 3 en 3
        }
        else{//pero si la variable buscar es diferente de vacio,
            $ingresos = Ingreso::join('proveedores','ingresos.idproveedor','=','proveedores.id')
            ->join('users','ingresos.idusuario','=','users.id')
            ->select('ingresos.id','ingresos.tipo_comprobante',
            'ingresos.serie_comprobante','ingresos.unidad_medida','ingresos.fecha_hora',
            'ingresos.impuesto','ingresos.total','ingresos.estado','proveedores.nombre','users.usuario')
            ->where('ingresos.'.$criterio, 'like', '%'. $buscar . '%')->orderBy('ingresos.id', 'desc')->paginate(3);
            //se filtra con el where por medio del campo criterio
            //de mi tabla ingresos concateno con el parametro criterio, realizamos la busqueda usando like, 
            //con los comodines de los porcentajes al inicio y al final y voy a buscar el texto que tengo por parametro en buscar
            //y ordenamos todos los registros mediante el campo id de la tabla ingresos
        }
 
         
        return [
            'pagination' => [
                'total'        => $ingresos->total(),
                'current_page' => $ingresos->currentPage(),
                'per_page'     => $ingresos->perPage(),
                'last_page'    => $ingresos->lastPage(),
                'from'         => $ingresos->firstItem(),
                'to'           => $ingresos->lastItem(),
            ],
            'ingresos' => $ingresos
        ];
    }
 

    public function listarPdf(){
        $ingresos = Ingreso::join('proveedores','ingresos.idproveedor','=','proveedores.id')
            ->join('users','ingresos.idusuario','=','users.id')
            ->select('ingresos.id','ingresos.tipo_comprobante','ingresos.serie_comprobante',
            'ingresos.unidad_medida','ingresos.fecha_hora','ingresos.impuesto','ingresos.total',
            'ingresos.estado','proveedores.nombre','users.usuario')
            ->orderBy('ingresos.id', 'asc')->get();
        $cont=Ingreso::count();

        $pdf = \PDF::loadView('pdf.ingresospdf',['ingresos'=>$ingresos,'cont'=>$cont])->setPaper('a4', 'landscape');
        return $pdf->download('ingresos.pdf');
    }

    public function pdf(Request $request,$id){
        $ingreso = Ingreso::join('proveedores','ingresos.idproveedor','=','proveedores.id')
        ->join('users','ingresos.idusuario','=','users.id')
        ->select('ingresos.id','ingresos.tipo_comprobante','ingresos.serie_comprobante',
        'ingresos.unidad_medida','ingresos.created_at','ingresos.impuesto','ingresos.total',
        'ingresos.estado','proveedores.nombre','proveedores.tipo_documento','proveedores.num_documento',
        'proveedores.direccion','proveedores.email',
        'proveedores.telefono','users.usuario')
        ->where('ingresos.id','=',$id)
        ->orderBy('ingresos.id', 'desc')->take(1)->get();

        $detalles = DetalleIngreso::join('materias','detalle_ingresos.idmateria','=','materias.id')
        ->select('detalle_ingresos.cantidad','detalle_ingresos.precio',
        'materias.nombre as materia')
        ->where('detalle_ingresos.idingreso','=',$id)
        ->orderBy('detalle_ingresos.id', 'desc')->get();

        $numingreso=Ingreso::select('serie_comprobante')->where('id',$id)->get();

        $pdf = \PDF::loadView('pdf.ingreso',['ingreso'=>$ingreso,'detalles'=>$detalles]);
        return $pdf->download('ingreso-'.$numingreso[0]->serie_comprobante.'.pdf');
    }

    public function pdfTicket(Request $request,$id){
        $ingreso = Ingreso::join('proveedores','ingresos.idproveedor','=','proveedores.id')
        ->join('users','ingresos.idusuario','=','users.id')
        ->select('ingresos.id','ingresos.tipo_comprobante','ingresos.serie_comprobante',
        'ingresos.created_at','ingresos.impuesto','ingresos.total',
        'ingresos.estado','proveedores.nombre','proveedores.tipo_documento','proveedores.num_documento',
        'proveedores.direccion','proveedores.email',
        'proveedores.telefono','users.usuario')
        ->where('ingresos.id','=',$id)
        ->orderBy('ingresos.id', 'desc')->take(1)->get();

        $detalles = DetalleIngreso::join('materias','detalle_ingresos.idmateria','=','materias.id')
        ->select('detalle_ingresos.cantidad','detalle_ingresos.precio',
        'materias.nombre as materia')
        ->where('detalle_ingresos.idingreso','=',$id)
        ->orderBy('detalle_ingresos.id', 'desc')->get();

        $numingreso=Ingreso::select('serie_comprobante')->where('id',$id)->get();

        $pdf = \PDF::loadView('pdf.ingresoticket',['ingreso'=>$ingreso,'detalles'=>$detalles])->setPaper('a5');
        return $pdf->download('ingresoTicket-'.$numingreso[0]->serie_comprobante.'.pdf');
    }

    public function obtenerCabecera(Request $request){
        if (!$request->ajax()) return redirect('/');
 
        $id = $request->id;
        //como es un ingreso unico la variable se llama ingreso
        //declaro una variable cuyo nombre es id que va ser igual a la propiedad id del objeto request que estoy recibiendo mediante ajax
        $ingreso = Ingreso::join('proveedores','ingresos.idproveedor','=','proveedores.id')//la variable ingresos que va ser igual a ingreso, uniendo ingreso con proveedores, para obtener el proveedor 
        ->join('users','ingresos.idusuario','=','users.id')//de igual manera se une con la tabla users para obtener el usuario responsable del ingreso
        //debajo voy a seleccionar de la tabla ingresos los siguientes valores y de la tabla proveedores el nombre y de users el usuario
        ->select('ingresos.id','ingresos.tipo_comprobante','ingresos.serie_comprobante',
        'ingresos.unidad_medida','ingresos.fecha_hora','ingresos.impuesto','ingresos.total',
        'ingresos.estado','proveedores.nombre','users.usuario')
        ->where('ingresos.id','=',$id)//mediante el metodo where de mi tabla ingresos el campo id, va ser igual a la variable id, que seria el valor del campo id que estoy obteniendo mediante ajax
        ->orderBy('ingresos.id', 'desc')->take(1)->get();//ordeno por el campo id de manera descendente, utilizando el metodo take obtengo un registro , usando el metodo get obtendremos el ingreso
         
        return ['ingreso' => $ingreso];
    }
 
    public function obtenerDetalles(Request $request){
        if (!$request->ajax()) return redirect('/');
 
        $id = $request->id;
        $detalles = DetalleIngreso::join('materias','detalle_ingresos.idmateria','=','materias.id')//vamos aunir detalleingreso con la tabla materias, mediante el campo detalle_ingresos el campo idmateria va ser igual campo id  de la tabla materias
        ->select('detalle_ingresos.cantidad','detalle_ingresos.precio','materias.nombre as materia')//se van a selecionar los siguintes valores de la tabla ingresos y de la tabla materias el nombre
        ->where('detalle_ingresos.idingreso','=',$id)//mediante el metodo where de mi tabla detalle_ingresos el campo idingreso va ser igual al valor de la variable id 
        ->orderBy('detalle_ingresos.id', 'desc')->get();//ordeno el campo id de manera descendente, mediante el metodo get obtendremos todos los detalles
         
        return ['detalles' => $detalles];//se retorna el parametro detalles  y el valor que le vamos a enviar a ese parametro detalles, sera lo que tenemos en la variable detalles 
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
             //delacaramos un objeto ingreso para instanciar el modelo ingreso
            $ingreso = new Ingreso();
             //le envio valores de propiedad
            $ingreso->idproveedor = $request->idproveedor;//valores a la propiedad idproveedor y esto es lo que obtengo desde mi formulario en el objeto id proveedor
            $ingreso->idusuario = \Auth::user()->id;//a la propiedad idusuario del objeto ingreso le envio el usuario que se encuentra autenticado, usando la clase auth, el objeto user  y le envio el id 
            //$ingreso->descripcion = $request->descripcion;
            $ingreso->tipo_comprobante = $request->tipo_comprobante;//se le va enviar el tipo de comrobante que esta recibiendo mediante ajax
            $ingreso->serie_comprobante = $request->serie_comprobante;//=
            $ingreso->unidad_medida = $request->unidad_medida;//=
            $ingreso->fecha_hora = $mytime->toDateTimeString();//a la propiedad fecha le envio lo que tengo almacenado en mytime que captura la hora actual pero mediante el metodo todatestring lo convierto a una fecha y hora aceptable
            //toDateTimeString();//a la propiedad fecha le envio lo que tengo almacenado en mytime que captura la hora actual pero mediante el metodo todatestring lo convierto a una fecha y hora aceptable
            $ingreso->impuesto = $request->impuesto;//le envio lo que recibo en el parametro impuesto mediante ajax
            $ingreso->total = $request->total;//=
            $ingreso->estado = 'Registrado';//a la propiedad estado le envio que se encuentra registrado
            $ingreso->save();//el objeto se debe guardo en la tabla ingresos
 
            $detalles = $request->data;//recibo lo que tengo en la propiedad data del objeto request, que estoy recibiendo mediante ajax, en la propiedad data van a ir todos los arrays de los detalles
            //Array de detalles
            //Recorro todos los elementos
 
            foreach($detalles as $ep=>$det)//recorre todo la variable detalles
            {//lo va hacer en la variable det
                //se crea el objeto detalleIngreso
                $detalle = new DetalleIngreso();
                $detalle->idingreso = $ingreso->id;//y a la propiedad idingreso de mi objeto detalle le voy a enviar el id del objeto ingreso, objeto que se guardo recientemente en la tabla ingresos de la base de datos
                //es decir le envia el id al que pertenece el detalle de ingreso
                $detalle->idmateria = $det['idmateria'];// le envio el indice idmateria que tengo en el array det que recorre todos los detalles, es decir todo el array de detalles que estoy recibiendo mediante ajax
                $detalle->cantidad = $det['cantidad'];//de igual manera cantidad lo que recibo en el indice cantidad
                $detalle->precio = $det['precio'];//a la propiedad precio que recibe en el indice precio en este caso en el array que se recorre mediante el bucle foreach
                $detalle->save();//guardamos por el metodo save
            }
             
            DB::commit();//hacemos referencia a nuestro metodo commit
        } catch (Exception $e){
            DB::rollBack();//si ocurriera algun error llamamos al metodo rolback para que desaga la transaccion 
        }
    }
 
    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $ingreso = Ingreso::findOrFail($request->id);
        $ingreso->estado = 'Anulado';
        $ingreso->save();
    }
}
