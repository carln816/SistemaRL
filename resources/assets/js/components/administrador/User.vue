<template>
            <main class="main">
            <!-- Breadcrumb <li class="breadcrumb-item"><a href="/">Escritorio</a></li>-->
            <ol class="breadcrumb">
                <li class="breadcrumb-item"> <i class="fa fa-users" aria-hidden="true"></i> <strong>Usuarios</strong></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Usuarios
                        <button type="button" @click="abrirModal('user','registrar')" class="btn btn-success">
                            <i class="fa fa-plus-circle" aria-hidden="true">&nbsp;Nuevo</i>
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                      <option value="usuario">Usuario</option>
                                    </select><!-- //cuando se enter vamos a llamar nuestro metodo -->
                                    <input type="text" v-model="buscar" @keyup.enter="listarUser(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarUser(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button> 
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <!--<th>Nombre</th>
                                    <th>Tipo Documento</th>
                                    <th>Número</th>
                                    <th>Dirección</th>
                                    <th>Teléfono</th>
                                    <th>Email</th>-->
                                    <th>Persona</th>
                                    <th>Usuario</th>
                                    <th>Rol</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="user in arrayUser" :key="user.id">
                                    <td>
                                        <button type="button" @click="abrirModal('user','actualizar',user)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button>&nbsp;
                                        <template v-if="user.condicion">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarUsuario(user.id)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-primary active btn-sm" @click="activarUsuario(user.id)">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template>
                                    </td>
                                    <!--<td v-text="persona.nombre"></td> esto nos mostrara el nombre de la categoria
                                    <td v-text="persona.tipo_documento"></td>
                                    <td v-text="persona.num_documento"></td>
                                    <td v-text="persona.direccion"></td>
                                    <td v-text="persona.telefono"></td>
                                    <td v-text="persona.email"></td>-->
                                    <td v-text="user.nombre_persona"></td>
                                    <td v-text="user.usuario"></td>
                                    <td v-text="user.rol"></td>
                                    <td>
                                        <div v-if="user.condicion"><!--  si la condicion es igual 1-->
                                            <span class="badge badge-success">Activo</span>
                                        </div>
                                        <div v-else><!-- si no -->
                                            <span class="badge badge-danger">Desactivado</span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <nav>
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1"> <!--solo muestro este boton cuando este en la pgina dos en adelante -->
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']"> <!--vamos a iterar la propiedad computada page number y agregamos la directiva bin para agregar la clase active siempre y cuando la pgina iterado sea igual a la propiedad computada isActive esto me retorna a la pagina actual-->
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page"> <!--mientras la pagina actual sea menos a laultima pagina se podra visualizar el siguiente-->
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal agregar/actualizar-->
            <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close"  @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <!--<div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre (*)</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="nombre" class="form-control" placeholder="Nombre de la persona">                                        
                                    </div>
                                </div>
                               <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Tipo Documento</label>
                                    <div class="col-md-9">
                                        <select v-model="tipo_documento" class="form-control">
                                            <option value="DNI">DNI</option>
                                            <option value="RUC">RUC</option>
                                            <option value="PASS">PASS</option>
                                        </select>                                    
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Número</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="num_documento" class="form-control" placeholder="Número de documento">                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Dirección</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="direccion" class="form-control" placeholder="Dirección">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Teléfono</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="telefono" class="form-control" placeholder="Teléfono">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Email</label>
                                    <div class="col-md-9">
                                        <input type="email" v-model="email" class="form-control" placeholder="Email">
                                    </div>
                                </div>-->
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Persona</label>
                                    <div class="col-md-9">
                                        <select class="form-control" v-model="idpersona">
                                            <option value="0" disabled>Seleccione</option>
                                            <option v-for="persona in arrayPersona" :key="persona.id" :value="persona.id" v-text="persona.nombre"></option>
                                        </select>
                                    </div>
                                </div>
                                <!--Se usa la directiva v-for que voy almacenar en un objeto rol todos los valores que tengo en el arrayrol.
                                el id es el key del bucle y el valor de cada accion va ser el id del objeto rol
                                y el texto que voy a mostrar en cada opcion sera el nombre del objeto rol-->
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Rol (*)</label>
                                    <div class="col-md-9">
                                        <select class="form-control" v-model="idrol">
                                            <option value="0">Seleccione un rol</option>
                                            <option v-for="rol in arrayRol" :key="rol.id" :value="rol.id" v-text="rol.nombre">
                                            
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Usuario (*)</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="usuario" class="form-control" placeholder="Nombre de usuario">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Password (*)</label>
                                    <div class="col-md-9">
                                        <input type="password" v-model="password" class="form-control" placeholder="Password de acceso">
                                    </div>
                                </div>
                                <div v-show="errorUser" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjUser" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarUser()">Crear</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarUser()">Actualizar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->
        </main>
</template>

<script>
    export default {
        data (){
            return {
                user_id: 0,
                idpersona : 0,
                nombre_persona : '',
                //nombre : '',
                //tipo_documento : 'DNI',
                //num_documento : '',
                //direccion : '',
                //telefono : '',
               // email : '',
                usuario : '',
                password : '',
                idrol : 0,
                arrayUser : [],
                arrayRol : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorUser : 0,
                errorMostrarMsjUser : [],
                arrayPersona : [],
                //arrays donde almaceno los posibles errores que tenga
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'usuario',// para indicar el campo de busqueda
                buscar : ''//y buscar para indicar cual es el texto de busqueda para realizar el filtro de toda la lista
            }
        },
        computed:{//funcion donde retornamos el objeto
            isActived: function(){
                return this.pagination.current_page;
            },
            //Calcula los elementos de la paginación
            pagesNumber: function() {//para determinar los elementos de la pagina
                if(!this.pagination.to) {// si la pagina es diferente de tu retorno un array vacio
                    return [];
                }
                //almacenamos una variable que le vamos a poner de nombre from
                var from = this.pagination.current_page - this.offset; 
                if(from < 1) {
                    from = 1;
                }
                //declaro la varible y en este caso el valor de la varibale va ser la suma entre from y el doble de lo que tenemos almacenado
                var to = from + (this.offset * 2); 
                //si to es mayor o igual que la ultima pagina
                if(to >= this.pagination.last_page){
                    //se establece el valor a esa ultima pagina a nuestra variable llamada to
                    to = this.pagination.last_page;
                }
                // declaro la variable y se inicializa en vacio este arreglo  
                var pagesArray = [];
                // la pagina actual sea menor que to se vaya agregando al from que es la pagina actual
                //mediante el metodo push al arreglo y asi sera hasta from sea mayor que to y se rompa el bucle
                while(from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;
            }
        },
        methods : {
            listarUser (page,buscar,criterio){
                let me=this;//la cadena de texto la concateno con la siguiente cadena, con el parametro buscar que mediante get a nuestro controlador es igual a la varibale buscar 
                //de igual manera concatenamos con el oarametro criterio para enviarlo a travez del metodo get y este parametro va ser igual a lo que tenemos en nuestro criterio
                var url= '/user?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                //axios.get('/categoria'.then(function (response) { recibimos los parametros desde la ruta categorias
                axios.get(url).then(function (response) { //mediante el metodo de axios obtengo todos los registros de la tabla categorias
                    var respuesta= response.data;
                    me.arrayUser = respuesta.users.data;// almacene todos el contenido del objeto response
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            /*selectRol(){
                let me=this;//la cadena de texto la concateno con la siguiente cadena, con el parametro buscar que mediante get a nuestro controlador es igual a la varibale buscar 
                //de igual manera concatenamos con el oarametro criterio para enviarlo a travez del metodo get y este parametro va ser igual a lo que tenemos en nuestro criterio
                var url= 'rol/selectRol';
                //axios.get('/categoria'.then(function (response) { recibimos los parametros desde la ruta categorias
                axios.get(url).then(function (response) { //mediante el metodo de axios obtengo todos los registros de la tabla categorias
                    var respuesta= response.data;
                    me.arrayRol = respuesta.roles.data;// almacene todos el contenido del objeto response
                })
                .catch(function (error) {
                    console.log(error);
                });
            },*/
            getPerson(){
                let me=this;
                var url= '/persona/getPerson';
                axios.get(url).then(response => {
                    //console.log(response);
                    //var respuesta= response.data;
                    me.arrayPersona = response.data;
                    console.log(me.arrayPersona)
                })
                .catch(error => {
                    console.log(error);
                });
            },
            selectRol(){
                let me=this;
                var url= '/rol/selectRol';
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayRol = respuesta.roles;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            
            cambiarPagina(page,buscar,criterio){
               let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarUser(page,buscar,criterio);// le envia valores al metodo
            },
            /*registrarPersona(){
                //realizamos la verificacio con una estructura condicioanl
                //si el metodo validar categoria devuelve un 1 sifnifica que tengo un error simplemente ejecutamos un return
                if (this.validarPersona()){
                    return;
                }
                let me = this;//declaramos la varibale para hacer refencia a este mismo archivo
                //utilizaremos el verbo axios.post 
                // le vamos a enviar dos parametros primero la ruta
                // y como segundo vamos enviar los valores del controlador lo son nombre y descripcion
                //donde los recibe el metodo store
               // metodo axios que recibe dos parametros primero la ruta registrar en la tabla categorias de la base de datos
               // y se le envia en la siguiente data, nombre, tipodocumento, numero, direccion, telefono, email.
               axios.post('/user/registrar',{
                    'nombre': this.nombre,
                    'tipo_documento': this.tipo_documento,
                    'num_documento' : this.num_documento,
                    'direccion' : this.direccion,
                    'telefono' : this.telefono,
                    'email' : this.email,
                    'usuario' : this.usuario,
                    'password' : this.password,
                    'idrol' : this.idrol                   
                }).then(function (response){//llamamos al metodo cerrar y al listar este en caso de que se ejecute,
                //de manera sastifactorio de lo contrario error de consola
                // si se ejecuta de manera correcta llamamos al metodo cerrarmodal y listarcategoria para actualizar el listado
                    me.cerrarModal();
                    me.listarPersona(1,'','nombre');//le enviamos qe muestre nuestra primera pagina el fltro  
                    // si hubiera un errror lo veremos en la consola
                    }).catch(function (error) {
                    console.log(error);
                });
            },*/
            registrarUser(){
                
                if (this.validarUser()){
                    return;
                }
                let me = this;
               axios.post('/user/registrar',{
                    //'nombre': this.nombre,
                    ////'tipo_documento': this.tipo_documento,
                    //'num_documento' : this.num_documento,
                    //'direccion' : this.direccion,
                    //'telefono' : this.telefono,
                   // 'email' : this.email,
                    'idpersona': this.idpersona,
                    'usuario' : this.usuario,
                    'password' : this.password,
                    'idrol' : this.idrol                   
                }).then(response => {
                    me.cerrarModal();
                    me.listarUser(1,'','nombre');
                    swal(
                        'Registrado!!!',
                        'El Usuario ha sido registrado',
                        'success'
                        )
                    }).catch(error => {
                    console.log(error.response.data.errors);
                    swal(
                        'Duplicado!!!',
                        'Ya ha sido usado ese nombre de Usuario',
                        'error'
                    )
                });
            },
            actualizarUser(){
                if (this.validarUser()){
                    return;
                }
                let me = this;
               axios.put('/user/actualizar',{
                    'idpersona': this.idpersona,
                    'usuario' : this.usuario,
                    'password' : this.password,
                    'idrol' : this.idrol,
                    'id': this.user_id
                //}).then(function (response) { 
                 }).then(response => {
                    swal(
                        'Actualizado!!!',
                        'Usuario Actualizado',
                        'success'
                    )
                    me.cerrarModal();
                    me.listarUser(1,'','nombre');
                    // si hubiera un errror lo veremos en la consola
                   }).catch(error => {
                    console.log(error.response.data.errors);
                    swal(
                        'Duplicado!!!',
                        'Esta duplicando el nombre de Usuario',
                        'error'
                    )
                });
            },
            /*actualizarPersona(){
                //realizamos la verificacio con una estructura condicioanl
                //si el metodo validar categoria devuelve un 1 sifnifica que tengo un error simplemente ejecutamos un return
                if (this.validarPersona()){
                    return;
                }

                let me = this;//declaramos la varibale para hacer refencia a este mismo archivo
                //utilizaremos el verbo axios.post 
                // le vamos a enviar dos parametros primero la ruta
                // y como segundo vamos enviar los valores del controlador lo son nombre y descripcion
                //donde los recibe el metodo store
               // metodo axios que recibe dos parametros primero la ruta registrar en la tabla categorias de la base de datos
               // y se le envia en la siguiente data, nombre, tipodocumento, numero, direccion, telefono, email.
               axios.put('/user/actualizar',{
                    'nombre': this.nombre,
                    'tipo_documento': this.tipo_documento,
                    'num_documento' : this.num_documento,
                    'direccion' : this.direccion,
                    'telefono' : this.telefono,
                    'email' : this.email,
                    'usuario' : this.usuario,
                    'password' : this.password,
                    'idrol' : this.idrol,
                    'id': this.persona_id
                }).then(function (response) { //llamamos al metodo cerrar y al listar este en caso de que se ejecute,
                //de manera sastifactorio de lo contrario error de consola
                // si se ejecuta de manera correcta llamamos al metodo cerrarmodal y listarcategoria para actualizar el listado
                    me.cerrarModal();
                    me.listarPersona(1,'','nombre');
                    // si hubiera un errror lo veremos en la consola
                    }).catch(function (error) {
                    console.log(error);
                });
            },*/
            validarUser(){
                this.errorUser = 0;
                this.errorMostrarMsjUser = [];
                //validamos si el nombre esta vacio, entonces de esa clase la variable error usando su metodo push le agregamos el sigiente mensaje 
                //if (!this.nombre) this.errorMostrarMsjUser.push("El nombre de la User no puede estar vacio.");
                if (!this.usuario) this.errorMostrarMsjUser.push("El nombre de usuario no puede estar vacio.");
                //if (!this.password) this.errorMostrarMsjUser.push("El password no puede estar vacio.");
                if (this.idrol==0) this.errorMostrarMsjUser.push("Debe seleccionar un rol para el usuario.");
                //si tengo un error evaluamos nuestro array y nuestra variable error User va a pasar 1 cuando tenga al menos 1 error.
                
                if (this.errorMostrarMsjUser.length) this.errorUser = 1;

                return this.errorUser;
            },
            
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                /*this.nombre='';
                this.tipo_documento='DNI';
                this.num_documento='';
                //this.direccion='';
                //this.telefono='';
               // this.email='';*/
                this.idpersona= 0;
                this.nombre_persona = '';
                this.usuario='';
                this.password='';
                this.idrol=0;
                this.errorUser=0;
            },
            abrirModal(modelo, accion, data = []){ //estructura multiple donde evalua el parametro
                
                this.selectRol();//muestro todo el listado de roles en nuestro objeto de formulario select
                switch(modelo){// en caso el texto sea igual al modelo categoria se agrega otra seleccion selectiva
                    case "user":   // case que validad que nuestra variable sea igual a categoria 
                    {
                        switch(accion){ // nos sirve para evaluar nuestro segundo parametro y se evaluara dependiendo del caso
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Crear Usuario';
                                /*this.nombre= '';
                                this.tipo_documento='DNI';
                                this.num_documento='';*/
                                //this.direccion='';
                                //this.telefono='';
                               // this.email='';
                                this.idpersona= 0;
                                this.nombre_persona = '';
                                this.usuario='';
                                this.password='';
                                this.idrol=0;
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {//mostrar por consola si se esta obteniendo de manera adecuada un obejto
                                //console.log(data);
                                //le indicamos a nuestra ventana modal se visualice
                                this.modal=1;
                                this.tituloModal='Actualizar Usuario';
                                this.tipoAccion=2;
                                this.user_id=data['id'];
                                this.idpersona=data['idpersona'];
                                //this.nombre = data['nombre'];
                                //this.tipo_documento = data['tipo_documento'];
                               // this.num_documento = data['num_documento'];
                                //this.direccion = data['direccion'];
                                //this.telefono = data['telefono'];
                                //this.email = data['email'];
                                this.usuario = data['usuario'];
                                this.password = data['password'];
                                this.idrol = data['idrol'];
                                break;
                            }
                        }
                    }
                }
            },
            desactivarUsuario(id){
                swal({
                title: 'Esta seguro de desactivar este usuario?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put('/user/desactivar',{
                        'id': id
                    }).then(function (response) {
                        me.listarUser(1,'','nombre');
                        swal(
                        'Desactivado!',
                        'El registro ha sido desactivado con éxito.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });
                    
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                    
                }
                })
                
            },
            activarUsuario(id){
                swal({
                title: 'Esta seguro de activar este usuario?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put('/user/activar',{
                        'id': id
                    }).then(function (response) {
                        me.listarUser(1,'','nombre');
                        swal(
                        'Activado!',
                        'El registro ha sido activado con éxito.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });
                    
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                    
                }
                }) 
                
            }
        },
        mounted() {
            this.listarUser(1,this.buscar,this.criterio);
            this.getPerson();
        }
    }
</script>

<style>
    .modal-content{
        width: 100% !important;
        position: absolute !important;
    }
    .mostrar{
        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important;
    }
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
</style>