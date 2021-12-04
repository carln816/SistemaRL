<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//se crea una ruta de tipo group indicando nuestro primer middleware
//en este caso guest es invitado esto quiere decir aque rutas puede tener acceso sin haberse autenticado
Route::group(['middleware' => ['guest']], function(){
    Route::get('/','Auth\LoginController@showLoginForm');
    Route::post('/login','Auth\LoginController@login')->name('login');
    
    
});
//rutas para los usuarios autenticados
Route::group(['middleware' => ['auth']], function(){

    Route::post('/logout','Auth\LoginController@logout')->name('logout');
    Route::get('/dashboard', 'DashboardController');
    
   Route::get('/main', function () {
        return view('contenido/contenido');
    })->name('main');


    /*Route::get('/', function () {
        return view('conte/conte');
    })->name('main');*/

    /*Route::get('/', function () {
        return view('bienvenido');
    });*/

    Route::group(['middleware' => ['Empleado']], function(){
        Route::get('/categoria', 'CategoriaController@index');
        Route::post('/categoria/registrar', 'CategoriaController@store');
        Route::put('/categoria/actualizar', 'CategoriaController@update');
        Route::put('/categoria/desactivar', 'CategoriaController@desactivar');
        Route::put('/categoria/activar', 'CategoriaController@activar');
        Route::get('/categoria/selectCategoria', 'CategoriaController@selectCategoria');

        Route::get('/categoria/listarPdf', 'CategoriaController@listarPdf')->name('categorias.pdf');
        Route::get('/categoria/consuPdf/{id}', 'CategoriaController@consuPdf')->name('categorias.pdf');

        Route::get('/articulo', 'ArticuloController@index');
        Route::post('/articulo/registrar', 'ArticuloController@store');
        Route::put('/articulo/actualizar', 'ArticuloController@update');
        Route::get('/articulo/listarPdf', 'ArticuloController@listarPdf')->name('articulos.pdf');

        Route::get('/producto', 'ProductoController@index');
        Route::post('/producto/registrar', 'ProductoController@store');
        Route::put('/producto/actualizar', 'ProductoController@update');
        Route::put('/producto/desactivar', 'ProductoController@desactivar');
        Route::put('/producto/activar', 'ProductoController@activar');
        Route::get('/producto/listarPdf', 'ProductoController@listarPdf')->name('productos.pdf');
        Route::get('/producto/consuPdf/{id}', 'ProductoController@consuPdf')->name('productos.pdf');

        Route::get('/cliente', 'ClienteController@index');
        Route::get('/cliente/listarPdf', 'ClienteController@listarPdf')->name('clientes.pdf');
        Route::get('/cliente/consuPdf/{id}', 'ClienteController@consuPdf')->name('clientes.pdf');

        Route::get('/proveedor', 'ProveedorController@index');
        Route::get('/proveedor/listarPdf', 'ProveedorController@listarPdf')->name('proveedor.pdf');
        Route::get('/proveedor/consuPdf/{id}', 'ProveedorController@consuPdf')->name('proveedor.pdf');
    });


    Route::group(['middleware' => ['Administrador']], function(){
        Route::get('/categoria', 'CategoriaController@index');
          

        Route::post('/categoria/registrar', 'CategoriaController@store');

        Route::put('/categoria/actualizar', 'CategoriaController@update');
        Route::put('/categoria/desactivar', 'CategoriaController@desactivar');
        Route::put('/categoria/activar', 'CategoriaController@activar');
        Route::post('check/categoria','CategoriaController@check');//validar
        Route::get('/categoria/selectCategoria', 'CategoriaController@selectCategoria');

        Route::get('/categoria/listarPdf', 'CategoriaController@listarPdf')->name('categorias.pdf');
        Route::get('/categoria/consuPdf/{id}', 'CategoriaController@consuPdf')->name('categorias.pdf');

        /*Route::get('/observacion', 'ObservacionController@index');
        Route::post('/observacion/registrar', 'ObservacionController@store');*/
        

        Route::get('/articulo', 'ArticuloController@index');
          
        Route::post('/articulo/registrar', 'ArticuloController@store');
        Route::put('/articulo/actualizar', 'ArticuloController@update');
        Route::put('/articulo/desactivar', 'ArticuloController@desactivar');
        Route::put('/articulo/activar', 'ArticuloController@activar');
        Route::get('/articulo/listarPdf', 'ArticuloController@listarPdf')->name('articulos.pdf');
        Route::post('/articulo/eliminar', 'ArticuloController@destroy');
        //Route::delete('/eliminar/{id}', 'ArticuloController@eliminar')->name('articulos.eliminar');

        Route::get('/producto', 'ProductoController@index');
        Route::post('/producto/registrar', 'ProductoController@store');
        Route::put('/producto/actualizar', 'ProductoController@update');
        Route::put('/producto/desactivar', 'ProductoController@desactivar');
        Route::put('/producto/activar', 'ProductoController@activar');
        Route::get('/producto/buscarProducto', 'ProductoController@buscarProducto');
        Route::get('/producto/listarProducto', 'ProductoController@listarProducto');
        Route::get('/producto/buscarProductoVenta', 'ProductoController@buscarProductoVenta');
        Route::get('/producto/listarProductoVenta', 'ProductoController@listarProductoVenta');
        
        Route::get('/producto/listarPdf', 'ProductoController@listarPdf')->name('productos.pdf');
        
        Route::get('/producto/consuPdf/{id}', 'ProductoController@consuPdf')->name('producto_consuPdf');

        //Route::get('/producto/{id}', 'ProductoController@eliminarproducto')->name('eliminarproducto');


        Route::get('/materia', 'MateriaController@index');
        Route::post('/materia/registrar', 'MateriaController@store');
        Route::put('/materia/actualizar', 'MateriaController@update');
        Route::put('/materia/desactivar', 'MateriaController@desactivar');
        Route::put('/materia/activar', 'MateriaController@activar');
        Route::get('/materia/buscarMateria', 'MateriaController@buscarMateria');
        Route::get('/materia/listarMateria', 'MateriaController@listarMateria');

        Route::get('/materia/listarPdf', 'MateriaController@listarPdf')->name('materia.pdf');
        Route::get('/materia/consuPdf/{id}', 'MateriaController@consuPdf')->name('materias.pdf');

        Route::get('/cliente', 'ClienteController@index');
        Route::post('/cliente/registrar', 'ClienteController@store');
        Route::put('/cliente/actualizar', 'ClienteController@update');
        Route::put('/cliente/desactivar', 'ClienteController@desactivar');
        Route::put('/cliente/activar', 'ClienteController@activar');
        Route::get('/cliente/selectCliente', 'ClienteController@selectCliente');

        Route::get('/cliente/listarPdf', 'ClienteController@listarPdf')->name('clientes.pdf');
        Route::get('/cliente/consuPdf/{id}', 'ClienteController@consuPdf')->name('clientes.pdf');

        Route::get('/ingreso', 'IngresoController@index');
        Route::get('/ingreso/obtenerCabecera', 'IngresoController@obtenerCabecera'); 
        Route::get('/ingreso/obtenerDetalles', 'IngresoController@obtenerDetalles'); 
        Route::get('/ingreso', 'IngresoController@index');
        Route::post('/ingreso/registrar', 'IngresoController@store');
        Route::put('/ingreso/desactivar', 'IngresoController@desactivar');
        Route::get('/ingreso/pdf/{id}','IngresoController@pdf')->name('ingreso_pdf');
        Route::get('/ingreso/listarPdf','IngresoController@listarPdf')->name('ingresos_pdf');
        Route::get('/ingreso/pdfTicket/{id}','IngresoController@pdfTicket')->name('ingresoticket_pdf');
        

        Route::get('/venta','VentaController@index');
        Route::post('/venta/registrar', 'VentaController@store');
        Route::get('/venta/obtenerCabecera', 'VentaController@obtenerCabecera');
        Route::get('/venta/obtenerDetalles', 'VentaController@obtenerDetalles');
        Route::put('/venta/desactivar', 'VentaController@desactivar');
        Route::get('/venta/pdf/{id}','VentaController@pdf')->name('venta_pdf');
        Route::get('/venta/pdfTicket/{id}','VentaController@pdfTicket')->name('ventaticket_pdf');
        Route::get('/venta/listarPdf','VentaController@listarPdf')->name('ventas_pdf');


        Route::get('/proveedor', 'ProveedorController@index');
        Route::post('/proveedor/registrar', 'ProveedorController@store');
        Route::put('/proveedor/actualizar', 'ProveedorController@update');
        Route::put('/proveedor/desactivar', 'ProveedorController@desactivar');
        Route::put('/proveedor/activar', 'ProveedorController@activar');
        Route::get('/proveedor/selectProveedor', 'ProveedorController@selectProveedor');
        Route::get('/proveedor/listarPdf', 'ProveedorController@listarPdf')->name('proveedor.pdf');
        Route::get('/proveedor/consuPdf/{id}', 'ProveedorController@consuPdf')->name('proveedor_consuPdf');

        Route::get('/rol', 'RolController@index');
        Route::get('rol/selectRol', 'RolController@selectRol');
        

        Route::get('/persona', 'PersonaController@index');
        Route::post('/persona/registrar', 'PersonaController@store');
        Route::put('/persona/actualizar', 'PersonaController@update');
        Route::put('/persona/desactivar', 'PersonaController@desactivar');
        Route::put('/persona/activar', 'PersonaController@activar');
        Route::get('/persona/getPerson', 'PersonaController@getPerson');

        Route::get('/persona/listarPdf', 'PersonaController@listarPdf')->name('personas.pdf');
        Route::get('/persona/consuPdf/{id}', 'PersonaController@consuPdf')->name('personas.pdf');

        Route::get('/user', 'UserController@index');
        Route::post('/user/registrar', 'UserController@store');
        Route::put('/user/actualizar', 'UserController@update');
        Route::put('/user/desactivar', 'UserController@desactivar');
        Route::put('/user/activar', 'UserController@activar'); 
        Route::get('/get-user/{id}','UserController@getUserId');
        Route::post('/user/cambioPassword', 'UserController@cambioPassword');
    });
    
});


///////////cambiar abajo

/*Route::get('/', function () {
    return view('contenido/contenido');
});

Route::get('/categoria', 'CategoriaController@index');
Route::post('/categoria/registrar', 'CategoriaController@store');
Route::put('/categoria/actualizar', 'CategoriaController@update');
Route::put('/categoria/desactivar', 'CategoriaController@desactivar');
Route::put('/categoria/activar', 'CategoriaController@activar');
Route::get('/categoria/selectCategoria', 'CategoriaController@selectCategoria');

Route::get('/articulo', 'ArticuloController@index');
Route::post('/articulo/registrar', 'ArticuloController@store');
Route::put('/articulo/actualizar', 'ArticuloController@update');
Route::put('/articulo/desactivar', 'ArticuloController@desactivar');
Route::put('/articulo/activar', 'ArticuloController@activar');

Route::get('/cliente', 'ClienteController@index');
Route::post('/cliente/registrar', 'ClienteController@store');
Route::put('/cliente/actualizar', 'ClienteController@update');

Route::get('/proveedor', 'ProveedorController@index');
Route::post('/proveedor/registrar', 'ProveedorController@store');
Route::put('/proveedor/actualizar', 'ProveedorController@update');

Route::get('/rol', 'RolController@index');
Route::get('/rol/selectRol', 'RolController@selectRol');

Route::get('/user', 'UserController@index');
Route::post('/user/registrar', 'UserController@store');
Route::put('/user/actualizar', 'UserController@update');
Route::put('/user/desactivar', 'UserController@desactivar');
Route::put('/user/activar', 'UserController@activar');
//Route::get('/home', 'HomeController@index')->name('home');*/