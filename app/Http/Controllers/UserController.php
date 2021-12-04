<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Persona;

class UserController extends Controller
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

            $users = User::join('personas','users.idpersona','=','personas.id')
            ->join('roles','users.idrol','=','roles.id')//tambien uno la tabla user con la tabla roles, le indico que el campo idrol de mi tabla user va ser igual al campo id de mi tabla roles
            ->select('users.id','users.idpersona','personas.nombre as nombre_persona',
            'users.usuario','users.password','users.condicion','users.idrol',
            'roles.nombre as rol')//para que el campo nombre no sea ambiguo lo renombro rol
            ->where('idpersona','<>',1)//para no mostrar
            ->orderBy('users.id', 'desc')->paginate(3);
        }

        else{//pero si la variable buscar es diferente de vacio,
            //el arreglo clientes va ser igual a las clientes pero usaremos la condicion where,
            //donde voy a decir el texto a buscar puede estar contenido ya sea al inicio o al final de donde en nuestro campo criterio
            // los porcentajes son comodines que nos indican que el texto puede estar al inicio o al final
            $users = User::join('personas','users.idpersona','=','personas.id')
            ->join('roles','users.idrol','=','roles.id')//tambien uno la tabla user con la tabla roles, le indico que el campo idrol de mi tabla user va ser igual al campo id de mi tabla roles
            ->select('users.id','users.idpersona','personas.nombre as nombre_persona',
            'users.usuario','users.password','users.condicion','users.idrol',
            'roles.nombre as rol')//para que el campo nombre no sea ambiguo lo renombro rol
            ->where('idpersona','<>',1)
            ->where('users.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('users.id', 'desc')->paginate(3);           
        }
        
        return [
            'pagination' => [
                'total'        => $users->total(),
                'current_page' => $users->currentPage(),
                'per_page'     => $users->perPage(),
                'last_page'    => $users->lastPage(),
                'from'         => $users->firstItem(),
                'to'           => $users->lastItem(),
            ],
            'users' => $users
        ];
    }

    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        
        try{
            DB::beginTransaction();
            $request->validate([
                'usuario' => 'required|unique:users',
            ]);

            $user = new User();//que le enviamos al modelo
            $user->idpersona = $request->idpersona;
            $user->usuario = $request->usuario;
            $user->password = bcrypt( $request->password);// este pass lo encryptamos usando el metodo
            $user->condicion = '1';
            $user->idrol = $request->idrol;          

            //$user->id = $persona->id;

            $user->save();

            DB::commit();

        } catch (Exception $e){
            DB::rollBack();
        }
       
    }
    public function cambioPassword(Request $request){
        if (!$request->ajax()) return redirect('/');
        //dd($request->all());
        $request->validate([
            'current_pass' => 'required',
            'password' => 'required|confirmed',
            'idpersona' => 'required'
        ]);
        
        $old_pass = User::where('idpersona',Auth::user()->idpersona)->first();
        
        $current_pass = $request->current_pass;
        if(Hash::check($current_pass,$old_pass->password)) {
            
            //dd($current_pass);
            $new_pass = Hash::make($request->password);

            User::where('idpersona',Auth::user()->idpersona)->update(['password' => $new_pass]);
            
            
        }else{
            return response()->json(['errors' => '0']);
        }
    }

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        
        try{
            DB::beginTransaction();
            $request->validate([
                'usuario' => 'required|unique:users,usuario,'.$request->id.',id'
            ]);

            //Buscar primero el usuario a modificar
            $user = User::findOrFail($request->id);

            $user->idpersona = $request->idpersona;
            $user->usuario = $request->usuario;
            $user->password = bcrypt( $request->password);
            $user->condicion = '1';
            $user->idrol = $request->idrol;            
            $user->save();

            DB::commit();

        } catch (Exception $e){
            DB::rollBack();
        }

    }
        
    public function getUserId(Request $request,$id){
        if (!$request->ajax()) return redirect('/');
        //lo que le digo seleccioneme los datos de la tabla usuario y personas, uname la tabla personas donde el usario od sea igual al persona id 
        //retorneme la variable user
       $user = DB::select("
            SELECT
            u.*, 
            p.*
            FROM users u
        
            INNER JOIN personas p ON u.id=p.id
            WHERE u.id = '$id'
            "      
       );
        return response()->json($user);
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $user = User::findOrFail($request->id);
        $user->condicion = '0';
        $user->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $user = User::findOrFail($request->id);
        $user->condicion = '1';
        $user->save();
    }
}