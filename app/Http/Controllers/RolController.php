<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rol;

class RolController extends Controller
{
    //
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
 
        $buscar = $request->buscar;
        $criterio = $request->criterio;
         
        if ($buscar==''){
            $roles = Rol::orderBy('id', 'desc')->paginate(3);
        }
        else{
            $roles = Rol::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(3);
        }
         
        return [
            'pagination' => [
                'total'        => $roles->total(),
                'current_page' => $roles->currentPage(),
                'per_page'     => $roles->perPage(),
                'last_page'    => $roles->lastPage(),
                'from'         => $roles->firstItem(),
                'to'           => $roles->lastItem(),
            ],
            'roles' => $roles
        ];
    }
    //funcion seleccionar rol
    public function selectRol(Request $request)
    {
        //implemento el objeto roles
        //hago referencia del modelo rol y filtro todo los roles con los capos que condicion sea igual a 1
        $roles = Rol::where('condicion', '=', '1')
        ->select('id','nombre')// de mi tabla roles selecciono el campo id y nombre 
        ->orderBy('nombre', 'asc')->get();//el metodo get me permite obtener todo el listado de roles 

        return ['roles' => $roles];//retorno en el arrays roles todo el valor de ese objeto roles  cuya condicion sea igual a 1.
    }     
}