<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Sistema Ventas Laravel Vue Js- IncanatoIT">
    <meta name="author" content="Incanatoit.com">
    <meta name="keyword" content="Sistema ventas Laravel Vue Js, Sistema compras Laravel Vue Js">
    <link rel="shortcut icon" href="img/favicon.png">
    
    <title>Cooperativa Coopelácteos RL</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js">
    <!-- Icons -"https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"3.4.1-->
    <link href="css/plantilla.css" rel="stylesheet">
     <script>//a la variable global le indico que envie esas varibiables al sistema
        window.App = {
            idpersona: {!! json_encode(Auth::user()->idpersona) !!},
            iduser: {!! json_encode(Auth::user()->id) !!},    //se crea una variable global para vue
            idrol: {!! json_encode(Auth::user()->idrol) !!}
        }

    </script>
</head>

<body class="app header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden">
    <div id="app">
    <header class="app-header navbar ">
        <button class="navbar-toggler mobile-sidebar-toggler d-lg-none mr-auto" type="button">
          <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand " ></a>
        <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button">
          <span class="navbar-toggler-icon"></span>
        </button>
        <!--<ul class="nav navbar-nav d-md-down-none">
            <li class="nav-item px-3">
                <a class="nav-link" href="#">Escritorio</a>
            </li>
            <li class="nav-item px-3">
                <a class="nav-link" href="#">Configuraciones</a>
            </li>
        </ul>-->
        <ul class="nav navbar-nav ml-auto" style="margin-right:50px;">
            <!--<li class="nav-item d-md-down-none" <header class="app-header navbar navbar-light" style="background-color: #989C9E;">>
                <a class="nav-link" href="#" data-toggle="dropdown">
                    <i class="icon-bell"></i>
                    <span class="badge badge-pill badge-danger">5</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <div class="dropdown-header text-center">
                        <strong>Notificaciones</strong>
                    </div>
                    <a class="dropdown-item" href="#">
                        <i class="fa fa-envelope-o"></i> Ingresos
                        <span class="badge badge-success">3</span>
                    </a>
                    <a class="dropdown-item" href="#">
                        <i class="fa fa-tasks"></i> Ventas
                        <span class="badge badge-danger">2</span>
                    </a>
                </div>
            </li>-->
            <li class="nav-item dropdown" style="padding:10px;">
                <a class="nav-link dropdown-toggle nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                    <img src="img/avatars/user2.png" class="img-avatar" alt="#">
                    <span class="d-md-down-none">{{Auth::user()->usuario}} </span><!-- se llama a la clase auth del metodo user y llamamos la propiedad usuario donde tenemos almacenado los datos  -->
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <div class="dropdown-header text-center">
                        <strong>Cuenta</strong>
                    </div><!--agregamos la sigunete url con el metodo route llamamos haciendo referencia a la ruta logout para que nos haga referencia al metodo logout de nuestro controladorlogin-->
                    <a class="dropdown-item" href="{{ route('logout')}}" 
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="nav-icon fa fa-power-off "></i> Cerrar sesión</a>
                    <!-- dentro del evento onclick incluyo llamo al formulario que tiene de indetificador logout-form
                    y asu metodo submit que se ejecutara cuando damos click en el boton   -->
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form><!-- el usuario tiene como id el logout form que hace referencia cuando haga click en el vinculo de cerrar sesion -->
                    <!-- en este caso el atributo action del formulario vamos hacer referencia a la ruta logout mediante el metodo post -->
                    <!-- Y le agregamos el csrf para prevenir ataques -->
                </div>
            </li>
    </header>

    <div class="app-body">
        <!-- si el usuario actual esta autenticado entonces-->
        @if (Auth::check())<!-- si el usuario autenticado tiene el rol 1 significa que es admistrador-->
            @if (Auth::user()->idrol == 1)<!-- como es administrador se le realiza un include a la plantilla sidebaradministrador-->
                @include('plantilla.sidebaradministrador')<!-- muestre el sidebarAdministrador-->
            @elseif (Auth::user()->idrol == 2)<!-- si el usuario autenticado tiene el rol  significa que es empleado-->
                @include('plantilla.sidebarempleado')<!-- muestre el sidebarEmpleado-->
                @else

                @endif
        @endif

        <!-- Contenido Principal -->
        @yield('contenido')
        <!-- /Fin del contenido principal <a href="http://www.incanatoit.com/">-->
    </div>   
    </div>
    <footer class="app-footer">
        <span >Desarrollado por <a>Carlos Nuñez</a> &copy; 2021 </span>
        <!--<span><a href="#">Cooperativa Coopelácteos RL</a> &copy; 2021</span>-->
    </footer>

    
    <!--<footer class="fixed-bottom bg-light text-muted">

        <div class="container text-center"> <p>Desarrollado por Cooperativa Coopelácteos RL</p> </div>
        
    </footer>-->
    <style>

    .c-footer {
        display: -ms-flexbox;
        display: flex;
        -ms-flex: 0 0 50px;
        flex: 0 0 50px;
        -ms-flex-wrap: wrap;
        flex-wrap: wrap;
        -ms-flex-align: center;
        align-items: center;
        height: 50px;
        padding: 0 1rem;
        color: #3c4b64;
        background: #ebedef;
        border-top: 1px solid #d8dbe0;
    }
    </style>
    <script src="js/app.js"></script>
    <script src="js/plantilla.js"></script>
</body>

</html>