<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    //crear la accion que va retornar la vista login
    public function showLoginForm(){
        return view('auth.login');//retorno la vista que esta dentro de la carpeta auth y la vista se llama login 
    }
    //funcion para verificar ususarios de tipo request
    public function login(Request $request){
        $this->validateLogin($request);//se hace referencia a la funcion validate login le enviamos el objeto request
       
        //se utilizara una condicion verificando que el usuario y la contraseña son correctos
        //ademas verifaremos que la condicion del usuario este activo para poder acceder
        //implementamos la estructura condicional
        //y dentro voy a usar clase auth y su metodo attemp, le enviamos como parametro un arreglo,
        //en este arreglo le vamos a mandar la condiccion de acceso
        //en este caso le indico que la propiedad usuario sea igual a la propiedad usuario de mi objeto reques, que resivo mediante ajax, de igual manera para el paswrod
        //Y para finalizar le digo que la condicion debe ser igual a 1
        if (Auth::attempt(['usuario' => $request->usuario,'password' => $request->password,'condicion'=>1])){
            //si el usuario existe redireccion a la ruta
            return redirect()->route('main');
        }
        //si ocurre un error en la verificacion voy a decir que regrese a donde estaba
        return back()//indicando el error utilizamos el metodo witherrors, es decir al metodo back se le agrega ese metodo errors, que esta esperando un parametro
        //este parametro necesita el identificar de la plantilla blade de donde vamos a mostrar el error y cual es el error que vamos a mostar 
        //se usa el metodo trans para traducir el error
        //el error que mostramos se encuentra en la llave failed de nuestro archivo auth  de la carpeta lange y el idioma que tenemos instalado
        ->withErrors(['usuario' => trans('auth.failed')])
        ->withInput(request(['usuario']));//le regresamos lo que el usuario escribio en el inout usuario retornandolo
        
    }

    protected function validateLogin(Request $request){
         // valida la peticion del inicio de sesion de usuario  
        //a este metodo le paso dos parametros, el primero el objeto request y el segundo los parametrosque vamos a validar mediante un arreglo
        //entonces valido la propiedad usuario y password y le indico que el campo sea obligatorio de tipo de string  
        $this->validate($request,[
            'usuario' => 'required|string',
            'password' => 'required|string'
        ]);
    }
    //funcion publica que espera un objeto como parametro llamado request
    //se hace referencia a la clase auth con su metodo logout
    public function logout(Request $request){
        auth::logout();
        //se hace refrencia al metodo sesion del metodo request y tambien al metodo invalidate
        $request->session()->invalidate(); 
        return redirect('/');//para cuando se llame al metodo logout de cerrar sesion se redirecciona 

    }
}