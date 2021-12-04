<template>
            <main class="main">
            <!-- Breadcrumb <li class="breadcrumb-item"><a href="/">Escritorio</a></li>-->
            <ol class="breadcrumb">
                <li class="breadcrumb-item"> <i class="fa fa-shopping-basket" aria-hidden="true"></i> <strong>Materia Prima</strong></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Materia Prima
                        <button type="button" @click="abrirModal('materia','registrar')" class="btn btn-success">
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
                                      <option value="codigo">codigo</option>
                                    </select><!-- //cuando se enter vamos a llamar nuestro metodo -->
                                    <input type="text" v-model="buscar" @keyup.enter="listarMateria(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarMateria(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button> 
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Codigo</th>
                                    <th>Nombre</th>
                                    <th>Categoria</th>
                                    <!--<th>Precio Compra</th>-->
                                    <th>Stock</th>
                                    <th>Unid.Medida</th>
                                    <th>Fecha Hora</th>
                                    <th>Descripción</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="materia in arrayMateria" :key="materia.id">
                                    <td>
                                        <button type="button" @click="abrirModal('materia', 'actualizar', materia)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <template v-if="materia.condicion"> <!--si la condicion es 1 lo que hago es moestro un boton para desactivar el metodo -->
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarMateria(materia.id)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                        <template v-else><!--si la categoria no esta activa, mostrate un boton para llamar al metodo activar-->
                                            <button type="
                                            button" class="btn btn-primary active btn-sm" @click="activarMateria(materia.id)">
                                                <i class="fa fa-check-circle" aria-hidden="true"></i>
                                            </button>
                                        </template>
                                    </td>
                                    <td v-text="materia.codigo"></td>
                                    <td v-text="materia.nombre"></td>
                                    <td v-text="materia.nombre_categoria"></td>
                                    <!--<td v-text="materia.precio_compra"></td>-->
                                    <td v-text="materia.stock"></td>
                                    <td v-text="materia.unidad_medida"></td>
                                    <td v-text="materia.fecha_hora"></td>
                                    <td v-text="materia.descripcion"></td>
                                    <td>
                                        <div v-if="materia.condicion"><!--  si la condicion es igual 1-->
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
                                    <label class="col-md-3 form-control-label" for="text-input">Categoria</label>
                                    <div class="col-md-9">
                                        <select class="form-control" v-model="idcategoria">
                                            <option value="0" disabled>Seleccione</option>
                                            <option v-for="categoria in arrayCategoria" :key="categoria.id" :value="categoria.id" v-text="categoria.nombre"></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Código</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="codigo" class="form-control" placeholder="CPLM">                                        
                                        <barcode :value="codigo" :options="{format: 'EAN-13'}">
                                            Se inicializa con CPLM Y se agrega la inicial de la materia a comprar "L", mas numero identificador.!
                                        </barcode>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="nombre" class="form-control" placeholder="Nombre de la materia">                                        
                                    </div>
                                </div>
                                <!--<div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="number-input">Precio Compra</label>
                                    <div class="col-md-9">
                                        <input type="number" v-model="precio_compra" class="form-control" placeholder="">                                        
                                    </div>
                                </div>-->
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Stock</label>
                                    <div class="col-md-9">
                                        <input type="number" v-model="stock" class="form-control" placeholder="">                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Unidad Medida</label>
                                    <div class="col-md-9">
                                        <select v-model="unidad_medida" class="form-control">
                                            <option value="Kilogramos">Kilogramos</option>
                                            <option value="Litros">Litros</option>
                                            <option value="Unida">Unidad</option>
                                        </select>                                    
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Descripción</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="descripcion" class="form-control" placeholder="Ingrese descripción">
                                    </div>
                                </div>
                                <div v-show="errorMateria" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjMateria" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarMateria()">Crear</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarMateria()">Actualizar</button>
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
    import VueBarcode from 'vue-barcode';
    export default {
        data (){
            return {
                materia_id: 0,
                idcategoria : 0,
                nombre_categoria : '',
                codigo : '',
                nombre : '',
                precio_compra : 0,
                stock : 0,
                unidad_medida :'',
                descripcion : '',
                arrayMateria : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorMateria : 0,
                errorMostrarMsjMateria : [],//arrays donde almaceno los posibles errores que tenga
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
                buscar : '',//y buscar para indicar cual es el texto de busqueda para realizar el filtro de toda la lista
                arrayCategoria :[]
            }
        },
        components: {
            'barcode': VueBarcode
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
            listarMateria (page,buscar,criterio){
                let me=this;//la cadena de texto la concateno con la siguiente cadena, con el parametro buscar que mediante get a nuestro controlador es igual a la varibale buscar 
                //de igual manera concatenamos con el oarametro criterio para enviarlo a travez del metodo get y este parametro va ser igual a lo que tenemos en nuestro criterio
                var url= '/materia?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                //axios.get('/categoria'.then(function (response) { recibimos los parametros desde la ruta categorias
                axios.get(url).then(function (response) { //mediante el metodo de axios obtengo todos los registros de la tabla categorias
                    var respuesta= response.data;
                    me.arrayMateria = respuesta.materias.data;// almacene todos el contenido del objeto response
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            cargarPdf(){//hace referencia a la url que hace referencia nuestro pdf con el metodo open
                window.open('http://127.0.0.1:8000/materia/listarPdf','_blank');     
                //window.open('http://localhost:8000/materia/listarPdf','_blank');
            },
            selectCategoria(){
                let me=this;
                var url= '/categoria/selectCategoria';
                axios.get(url).then(function (response) {
                    //console.log(response);
                    var respuesta= response.data;
                    me.arrayCategoria = respuesta.categorias;
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
                me.listarMateria(page,buscar,criterio);// le envia valores al metodo
            },
            /*eliminarMateria(materia){
                let me = this;
                me.arrayDetalle.splice(materia, 1);
            },*/
            registrarMateria(){
                //realizamos la verificacio con una estructura condicioanl
                //si el metodo validar categoria devuelve un 1 sifnifica que tengo un error simplemente ejecutamos un return
                if (this.validarMateria()){
                    return;
                }

                let me = this;//declaramos la varibale para hacer refencia a este mismo archivo
                //utilizaremos el verbo axios.post 
                // le vamos a enviar dos parametros primero la ruta
                // y como segundo vamos enviar los valores del controlador lo son nombre y descripcion
                //donde los recibe el metodo store
               // metodo axios que recibe dos parametros primero la ruta registrar en la tabla categorias de la base de datos
               // y se le envia en la siguiente data, nombre y descripcion
               axios.post('/materia/registrar',{
                    'idcategoria': this.idcategoria,
                    'codigo': this.codigo,
                    'nombre': this.nombre,
                    'stock': this.stock,
                    'unidad_medida': this.unidad_medida, 
                    'precio_compra': this.precio_compra,
                    'descripcion': this.descripcion
                }).then(response => {//llamamos al metodo cerrar y al listar este en caso de que se ejecute,
                //de manera sastifactorio de lo contrario error de consola
                // si se ejecuta de manera correcta llamamos al metodo cerrarmodal y listarcategoria para actualizar el listado
                    me.cerrarModal();
                    me.listarMateria(1,'','nombre');//le enviamos qe muestre nuestra primera pagina el fltro  
                    swal(
                            'Registrada!!!',
                            'La Materia ha sido registrada',
                            'success'
                        ) 
                    // si hubiera un errror lo veremos en la consola
                    }).catch(error => {
                    console.log(error.response.data.errors);
                    swal(
                        'Duplicada!!!',
                        'Ya existe una Materia con ese codigo',
                        'error'
                    )
                });
            },
            actualizarMateria(){
                //realizamos la verificacio con una estructura condicioanl
                //si el metodo validar categoria devuelve un 1 sifnifica que tengo un error simplemente ejecutamos un return
                if (this.validarMateria()){
                    return;
                }

                let me = this;//declaramos la varibale para hacer refencia a este mismo archivo
                //utilizaremos el verbo axios.post 
                // le vamos a enviar dos parametros primero la ruta
                // y como segundo vamos enviar los valores del controlador lo son nombre y descripcion
                //donde los recibe el metodo store
               // metodo axios que recibe dos parametros primero la ruta registrar en la tabla categorias de la base de datos
               // y se le envia en la siguiente data, nombre y descripcion
               axios.put('/materia/actualizar',{
                    'idcategoria': this.idcategoria,
                    'codigo': this.codigo,
                    'nombre': this.nombre,
                    'stock': this.stock,
                    'unidad_medida': this.unidad_medida,
                    'precio_compra': this.precio_compra,
                    'descripcion': this.descripcion,
                    'id': this.materia_id
                }).then(response => { //llamamos al metodo cerrar y al listar este en caso de que se ejecute,
                //de manera sastifactorio de lo contrario error de consola
                // si se ejecuta de manera correcta llamamos al metodo cerrarmodal y listarcategoria para actualizar el listado
                    me.cerrarModal();
                    me.listarMateria(1,'','nombre');
                    swal(
                        'Actualizada!!!',
                        'Se ha actualizada la materia',
                        'success'
                        )
                    // si hubiera un errror lo veremos en la consola
                    }).catch(error => {
                    console.log(error.response.data.errors);
                    swal(
                        'Duplicada!!!',
                        'Ya existe una Materia con ese codigo',
                        'error'
                    )
                });
            },
            /*registrarMateria(){
                //realizamos la verificacio con una estructura condicioanl
                //si el metodo validar categoria devuelve un 1 sifnifica que tengo un error simplemente ejecutamos un return
                if (this.validarMateria()){
                    return;
                }

                let me = this;//declaramos la varibale para hacer refencia a este mismo archivo
                //utilizaremos el verbo axios.post 
                // le vamos a enviar dos parametros primero la ruta
                // y como segundo vamos enviar los valores del controlador lo son nombre y descripcion
                //donde los recibe el metodo store
               // metodo axios que recibe dos parametros primero la ruta registrar en la tabla categorias de la base de datos
               // y se le envia en la siguiente data, nombre y descripcion
               axios.post('/materia/registrar',{
                    'idcategoria': this.idcategoria,
                    'codigo': this.codigo,
                    'nombre': this.nombre,
                    'stock': this.stock,
                    'precio_compra': this.precio_compra,
                    'descripcion': this.descripcion
                }).then(function (response){//llamamos al metodo cerrar y al listar este en caso de que se ejecute,
                //de manera sastifactorio de lo contrario error de consola
                // si se ejecuta de manera correcta llamamos al metodo cerrarmodal y listarcategoria para actualizar el listado
                    me.cerrarModal();
                    me.listarMateria(1,'','nombre');//le enviamos qe muestre nuestra primera pagina el fltro  
                    // si hubiera un errror lo veremos en la consola
                    }).catch(function (error) {
                    console.log(error);
                });
            },*/
            /*actualizarMateria(){
                //realizamos la verificacio con una estructura condicioanl
                //si el metodo validar categoria devuelve un 1 sifnifica que tengo un error simplemente ejecutamos un return
                if (this.validarMateria()){
                    return;
                }

                let me = this;//declaramos la varibale para hacer refencia a este mismo archivo
                //utilizaremos el verbo axios.post 
                // le vamos a enviar dos parametros primero la ruta
                // y como segundo vamos enviar los valores del controlador lo son nombre y descripcion
                //donde los recibe el metodo store
               // metodo axios que recibe dos parametros primero la ruta registrar en la tabla categorias de la base de datos
               // y se le envia en la siguiente data, nombre y descripcion
               axios.put('/materia/actualizar',{
                    'idcategoria': this.idcategoria,
                    'codigo': this.codigo,
                    'nombre': this.nombre,
                    'stock': this.stock,
                    'precio_compra': this.precio_compra,
                    'descripcion': this.descripcion,
                    'id': this.materia_id
                }).then(function (response) { //llamamos al metodo cerrar y al listar este en caso de que se ejecute,
                //de manera sastifactorio de lo contrario error de consola
                // si se ejecuta de manera correcta llamamos al metodo cerrarmodal y listarcategoria para actualizar el listado
                    me.cerrarModal();
                    me.listarMateria(1,'','nombre');
                    // si hubiera un errror lo veremos en la consola
                    }).catch(function (error) {
                    console.log(error);
                });
            },*/
            desactivarMateria(id){
                swal({
                title: 'Esta seguro de desactivar este Materia?',
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

                    axios.put('/materia/desactivar',{
                        'id': id
                    }).then(function (response) {
                        me.listarMateria(1,'','nombre');
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
            activarMateria(id){
                swal({
                title: 'Esta seguro de activar este Materia?',
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

                    axios.put('/materia/activar',{
                        'id': id
                    }).then(function (response) {
                        me.listarMateria(1,'','nombre');
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
            validarMateria(){
                this.errorMateria = 0;
                this.errorMostrarMsjMateria = [];
                //si el idcategoria es igual a 0 significa que no he selecionadp ninguna categoria en el objeto select
                 if (this.idcategoria==0) this.errorMostrarMsjMateria.push("Seleccione una categoría.");
                //validamos si el nombre esta vacio, entonces de esa clase la variable error usando su metodo push le agregamos el sigiente mensaje 
                if (!this.nombre) this.errorMostrarMsjMateria.push("El nombre de la Materia no puede estar vacio.");
                //si el stock es vacio yo voy a mostrar el error
                if (!this.stock) this.errorMostrarMsjMateria.push("El stock de la materia debe ser un número y no puede estar vacío.");
                //si el precio de venta es vacio yo voy a mostrar el error
                //if (!this.precio_compra) this.errorMostrarMsjMateria.push("El precio compra de la materia debe ser un número y no puede estar vacío.");
                //si tengo un error evaluamos nuestro array y nuestra variable error Materia va a pasar 1 cuando tenga al menos 1 error.
                if (this.errorMostrarMsjMateria.length) this.errorMateria = 1;

                return this.errorMateria;
            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.idcategoria= 0;
                this.nombre_categoria = '';
                this.codigo = '';
                this.nombre = '';
                this.precio_compra = 0;
                this.stock = 0;
                this.unidad_medida = '';
                this.descripcion = '';
		        this.errorMateria=0;
            },
            abrirModal(modelo, accion, data = []){ //estructura multiple donde evalua el parametro
                switch(modelo){// en caso el texto sea igual al modelo categoria se agrega otra seleccion selectiva
                    case "materia":   // case que validad que nuestra variable sea igual a categoria 
                    {
                        switch(accion){ // nos sirve para evaluar nuestro segundo parametro y se evaluara dependiendo del caso
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Crear Materia';
                                this.idcategoria=0;
                                this.nombre_categoria='';
                                this.codigo='';
                                this.nombre= '';
                                this.precio_compra=0;
                                this.stock=0;
                                this.unidad_medida = '';
                                this.descripcion = '';
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {//mostrar por consola si se esta obteniendo de manera adecuada un obejto
                                //console.log(data);
                                //le indicamos a nuestra ventana modal se visualice
                                this.modal=1;
                                this.tituloModal='Actualizar Materia';
                                this.tipoAccion=2;
                                this.materia_id=data['id'];
                                this.idcategoria=data['idcategoria'];
                                this.codigo=data['codigo'];
                                this.nombre = data['nombre'];
                                this.stock=data['stock'];
                                this.unidad_medida=data['unidad_medida'];
                                this.precio_compra=data['precio_compra'];
                                this.descripcion= data['descripcion'];
                                break;
                            }
                        }
                    }
                }
                this.selectCategoria();
            }
        },
        mounted() {
            this.listarMateria(1,this.buscar,this.criterio);
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