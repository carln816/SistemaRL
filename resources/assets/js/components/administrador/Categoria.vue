<template>
            <main class="main">
            <!-- Breadcrumb <li class="breadcrumb-item"><a href="/">Escritorio</a></li>-->
            <ol class="breadcrumb">
                <li class="breadcrumb-item"> <i class="fa fa-tasks" aria-hidden="true"></i> <strong>Categorías</strong></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Categorías
                        <button type="button" class="btn btn-success" @click="abrirModal('categoria','registrar')" >
                            <i class="fa fa-plus-circle" aria-hidden="true"> Nuevo</i>
                        </button>
                        <button type="button" @click="cargarPdf()" class="btn btn-danger">
                            <i class="fa fa-file-pdf-o" aria-hidden="true"> PDF</i>
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                      <option value="nombre">Nombre</option>
                                      <option value="descripcion">Descripción</option>
                                    </select><!-- //cuando se enter vamos a llamar nuestro metodo -->
                                    <input type="text" v-model="buscar" @keyup.enter="listarCategoria(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarCategoria(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button> 
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="categoria in arrayCategoria" :key="categoria.id">
                                    <td>
                                        <button type="button" class="btn btn-warning btn-sm" @click="abrirModal('categoria', 'actualizar', categoria)" >
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <!--<button type="button" class="btn btn-danger btn-sm">
                                          <i class="icon-trash"></i>
                                        </button>-->
                                        <template v-if="categoria.condicion"> <!--si la condicion es 1 lo que hago es moestro un boton para desactivar el metodo -->
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarCategoria(categoria.id)" v-if="idrol==1">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                        <template v-else><!--si la categoria no esta activa, mostrate un boton para llamar al metodo activar-->
                                            <button type="button" class="btn btn-primary active btn-sm" @click="activarCategoria(categoria.id)" v-if="idrol==1">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template>
                                    </td>
                                    <td v-text="categoria.nombre"></td><!-- esto nos mostrara el nombre de la categoria -->
                                    <td v-text="categoria.descripcion"></td>
                                    <td>
                                        <div v-if="categoria.condicion"><!--  si la condicion es igual 1-->
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
                                <div class="form-group row"> 
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="nombre" class="form-control" placeholder="Nombre de categoría">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Descripción</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="descripcion" class="form-control" placeholder="Ingrese descripción">
                                        <!--<textarea class="form-control" v-model="descripcion" placeholder="Experience" style="margin-top: 0px; margin-bottom: 0px; height: 60px;"></textarea>-->
                                    </div>
                                </div>
                                <div v-show="errorCategoria" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjCategoria" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarCategoria()">Crear</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarCategoria()">Actualizar</button>
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
    import { required, minLength, maxLength, between  } from 'vuelidate/lib/validators';
    import Vuelidate from 'vuelidate';
    export default {
        data (){
            return {
                idrol: App.idrol,
                categoria_id:0,
                nombre : '',
                descripcion : '',
                arrayCategoria : [],
                modal : 0,
                tituloModal :  '',
                tipoAccion : 0,
                errorCategoria : 0,
                errorMostrarMsjCategoria : [],//arrays donde almaceno los posibles errores que tenga
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'nombre',// para indicar el campo de busqueda
                buscar : ''//y buscar para indicar cual es el texto de busqueda para realizar el filtro de toda la lista
            }
        },
        validations :{
            nombre: {
                required,
                minLength: minLength(2),
                maxLength: maxLength(10)
            }
        },
        components: {
            Vuelidate
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
            listarCategoria (page,buscar,criterio){
                let me=this;//la cadena de texto la concateno con la siguiente cadena, con el parametro buscar que mediante get a nuestro controlador es igual a la varibale buscar 
                //de igual manera concatenamos con el oarametro criterio para enviarlo a travez del metodo get y este parametro va ser igual a lo que tenemos en nuestro criterio
                var url= '/categoria?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                //axios.get('/categoria'.then(function (response) { recibimos los parametros desde la ruta categorias
                axios.get(url).then(function (response) { //mediante el metodo de axios obtengo todos los registros de la tabla categorias
                    var respuesta= response.data;
                    me.arrayCategoria = respuesta.categorias.data;// almacene todos el contenido del objeto response
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
             cargarPdf(){//hace referencia a la url que hace referencia nuestro pdf con el metodo open
                window.open('http://127.0.0.1:8000/categoria/listarPdf','_blank');     
                //window.open('http://localhost:8000/producto/listarPdf','_blank');
            },
            cambiarPagina(page,buscar,criterio){
               let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarCategoria(page,buscar,criterio);// le envia valores al metodo
            },
            /*registrarCategoria(){
                //realizamos la verificacio con una estructura condicioanl
                //si el metodo validar categoria devuelve un 1 sifnifica que tengo un error simplemente ejecutamos un return
                if (this.validarCategoria()){
                    return;
                }
                let me = this;//declaramos la varibale para hacer refencia a este mismo archivo
                //utilizaremos el verbo axios.post 
                // le vamos a enviar dos parametros primero la ruta
                // y como segundo vamos enviar los valores del controlador lo son nombre y descripcion
                //donde los recibe el metodo store
               // metodo axios que recibe dos parametros primero la ruta registrar en la tabla categorias de la base de datos
               // y se le envia en la siguiente data, nombre y descripcion
               axios.post('/categoria/registrar',{
                    'nombre': this.nombre,
                    'descripcion': this.descripcion
                }).then(function (response){//llamamos al metodo cerrar y al listar este en caso de que se ejecute,
                //de manera sastifactorio de lo contrario error de consola
                // si se ejecuta de manera correcta llamamos al metodo cerrarmodal y listarcategoria para actualizar el listado
                    me.cerrarModal();
                    me.listarCategoria(1,'','nombre');//le enviamos qe muestre nuestra primera pagina el fltro  
                    // si hubiera un errror lo veremos en la consola
                    }).catch(function (error) {
                    console.log(error);
                });
            },*/

            registrarCategoria(){//Primer metodo
                if (this.validarCategoria()){
                    return;
                }
                let me = this;
                axios.post('/categoria/registrar',{
                    'nombre':this.nombre,
                    'descripcion': this.descripcion,
                    }).then(response => {
                    //console.log(response.data.error)
                    me.cerrarModal();
                    me.listarCategoria(1,'','nombre');
                     swal(
                            'Registrada!!!',
                            'La Categoria ha sido registrada',
                            'success'
                        )
                    }).catch(error => {
                    //console.log(error.response.data.errors);
                    swal(
                        'Duplicada!!!',
                        'Ya existe una Categoria con ese nombre',
                        'error'
                    )
                });
            },
            actualizarCategoria(){
                //realizamos la verificacio con una estructura condicioanl
                //si el metodo validar categoria devuelve un 1 sifnifica que tengo un error simplemente ejecutamos un return
                if (this.validarCategoria()){
                    return;
                }

                let me = this;//declaramos la varibale para hacer refencia a este mismo archivo
                //utilizaremos el verbo axios.post 
                // le vamos a enviar dos parametros primero la ruta
                // y como segundo vamos enviar los valores del controlador lo son nombre y descripcion
                //donde los recibe el metodo store
               // metodo axios que recibe dos parametros primero la ruta registrar en la tabla categorias de la base de datos
               // y se le envia en la siguiente data, nombre y descripcion
               axios.put('/categoria/actualizar',{
                    'nombre': this.nombre,
                    'descripcion': this.descripcion,
                    'id' : this.categoria_id
                    }).then(response => {
               //}).then(function (response) { //llamamos al metodo cerrar y al listar este en caso de que se ejecute,
                //de manera sastifactorio de lo contrario error de consola
                // si se ejecuta de manera correcta llamamos al metodo cerrarmodal y listarcategoria para actualizar el listado
                        swal('Actualizada!!!',
                            'Se ha actualizada su Categoria',
                            'success'
                        )
                    me.cerrarModal();
                    me.listarCategoria(1,'','nombre');
                    // si hubiera un errror lo veremos en la consola
                    }).catch(error => {
                    console.log(error.response.data.errors);
                    swal(
                        'Duplicada!!!',
                        'Ya existe una categoria con ese nombre',
                        'error'
                    )
                });
            },

            /*actualizarCategoria(){
                //realizamos la verificacio con una estructura condicioanl
                //si el metodo validar categoria devuelve un 1 sifnifica que tengo un error simplemente ejecutamos un return
                if (this.validarCategoria()){
                    return;
                }

                let me = this;//declaramos la varibale para hacer refencia a este mismo archivo
                //utilizaremos el verbo axios.post 
                // le vamos a enviar dos parametros primero la ruta
                // y como segundo vamos enviar los valores del controlador lo son nombre y descripcion
                //donde los recibe el metodo store
               // metodo axios que recibe dos parametros primero la ruta registrar en la tabla categorias de la base de datos
               // y se le envia en la siguiente data, nombre y descripcion
               axios.put('/categoria/actualizar',{
                    'nombre': this.nombre,
                    'descripcion': this.descripcion,
                    'id' : this.categoria_id
                }).then(response => { //llamamos al metodo cerrar y al listar este en caso de que se ejecute,
                //de manera sastifactorio de lo contrario error de consola
                // si se ejecuta de manera correcta llamamos al metodo cerrarmodal y listarcategoria para actualizar el listado
                        swal('Actualizado!!!',
                            'Se ha registrado su categoria',
                            'success'
                        )
                    me.cerrarModal();
                    me.listarCategoria(1,'','nombre');
                    // si hubiera un errror lo veremos en la consola
                    }).catch(error => {
                    console.log(error.response.data.errors);
                    swal('Duplicado!!!',
                            'Esta duplicado',
                            'error'
                        )
                });
            },*/

            desactivarCategoria(id){
                swal({
                title: 'Esta seguro de desactivar esta categoría?',
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

                    axios.put('/categoria/desactivar',{
                        'id': id
                    }).then(function (response) {
                        me.listarCategoria(1,'','nombre');
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
            activarCategoria(id){
                swal({
                title: 'Esta seguro de activar esta categoría?',
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

                    axios.put('/categoria/activar',{
                        'id': id
                    }).then(function (response) {
                        me.listarCategoria(1,'','nombre');
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
                
            },
            validarCategoria(){
                this.errorCategoria = 0;
                this.errorMostrarMsjCategoria = [];
                //validamos si el nombre esta vacio, entonces de esa clase la variable error usando su metodo push le agregamos el sigiente mensaje 
                if (!this.nombre) this.errorMostrarMsjCategoria.push("El nombre de la categoria no puede estar vacio.");
                //si tengo un error evaluamos nuestro array y nuestra variable error categoria va a pasar 1 cuando tenga al menos 1 error.
                if (this.errorMostrarMsjCategoria.length) this.errorCategoria = 1;

                return this.errorCategoria;
            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.nombre= '';
                this.descripcion = '';
                this.errorMostrarMsjCategoria = [];
            },
            abrirModal(modelo, accion, data = []){ //estructura multiple donde evalua el parametro
                switch(modelo){// en caso el texto sea igual al modelo categoria se agrega otra seleccion selectiva
                    case "categoria":   // case que validad que nuestra variable sea igual a categoria 
                    {
                        switch(accion){ // nos sirve para evaluar nuestro segundo parametro y se evaluara dependiendo del caso
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Crear Categoría';
                                this.nombre= '';
                                this.descripcion = '';
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {//mostrar por consola si se esta obteniendo de manera adecuada un obejto
                                //console.log(data);
                                //le indicamos a nuestra ventana modal se visualice
                                this.modal=1;
                                this.tituloModal='Actualizar categoria';
                                this.tipoAccion=2;
                                this.categoria_id = data['id'];
                                this.nombre = data['nombre'];
                                this.descripcion = data['descripcion'];
                                break;
                            }
                        }
                    }
                }
            }
        },
        mounted() {
            this.listarCategoria(1,this.buscar,this.criterio);
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