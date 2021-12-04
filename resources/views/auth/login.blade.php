@extends('auth.contenido')

@section('login')
<div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card-group mb-0">
          <div class="card p-4">
          <form class="form-horizontal was-validated" method="POST" action="{{ route('login')}}">
          {{ csrf_field() }} <!--metodo de proteccion de solicitudes de falsificacion aytavez de sitios crosside -->
            <div class="card-body">
              <h1>Acceder</h1>
              <p class="text-muted">Control de acceso al sistema</p>
              <div class="form-group mb-3{{$errors->has('usuario' ? 'is-invalid' : '')}}"> <!-- se agrega una verificacion la cual dice que si se lanza una excepcion en el campo usuario se agrega la clase invalid a todo el did, esos si sucede un error, pero sino ocurre nada no  se le agrega nada -->
                <span class="input-group-addon"><i class="icon-user"></i></span>
                <input type="text" value="{{old('usuario')}}" name="usuario" id="usuario" class="form-control" placeholder="Usuario"><!-- mediante el metodo muestro el valor de usuario-->
                {!!$errors->first('usuario','<span class="invalid-feedback">:message</span>')!!} <!-- el primer error que aparezca lu muestro en una etiqueta spam con la clase ivalid felback  y mostramos el mensaje  -->
              </div>
              <div class="form-group mb-4{{$errors->has('password' ? 'is-invalid' : '')}}">
                <span class="input-group-addon"><i class="icon-lock"></i></span>
                <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                {!!$errors->first('password','<span class="invalid-feedback">:message</span>')!!}
              </div>
              <div class="row">
                <div class="col-6">
                  <button type="submit" class="btn btn-primary px-4">Acceder</button>
                </div>
              </div>
            </div>
          </form>
          </div>
          <div class="card text-white bg-primary py-5 d-md-down-none" style="width:44%">
            <div class="card-body text-center">
              <div>
                <h2>Cooperativa Coopelácteos</h2>
                <p>Sistema que permite automatizar el proceso del control del inventario de la cooperativa Coopelácteos.</p>
                <!--<a href="https://www.udemy.com/user/juan-carlos-arcila-diaz/" target="_blank" class="btn btn-primary active mt-3">Ver el curso!</a>-->
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
@endsection