<template>
            <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item"> <i class="icon-chart" aria-hidden="true"></i> <strong>Reportes Ingresos</strong></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Ingresos
                        <button type="button" @click="cargarPdf()" class="btn btn-danger">
                            <i class="fa fa-file-pdf-o" aria-hidden="true"> PDF</i>
                        </button>
                    </div>
                    <!-- Listado -->
                    <!-- Si la variable listado es igual a 1, se visualiza el listado de ingresos y ocultamos el formulario que agrega detalles al ingreso -->
                    <template v-if="listado==1"><!-- le condiciono si el listado es igual a uno visualizo el div que me muestra el listado de los ingresos y se va ocultar el div que muestra  el formulario para agrear detalles al ingreso-->
                    <div class="card-body"><!-- se visualiza cuando el listado es igual 1 -->
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group"><!-- Formas de realizar la busqueda -->
                                    <select class="form-control col-md-3" v-model="criterio">
                                      <option value="tipo_comprobante">Tipo Comprobante</option>
                                      <option value="serie_comprobante">Serie Comprobante</option>
                                    </select><!-- Hacemos referencia al escribir al metodo listar -->
                                    <input type="text" v-model="buscar" @keyup.enter="listarIngreso(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarIngreso(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr><!-- Describimos las celdas ue ocupamos -->
                                        <th>Opciones</th>
                                        <th>Usuario</th>
                                        <th>Proveedor</th>
                                        <th>Tipo Comprobante</th>
                                        <th>Serie Comprobante</th>
                                        <th>Fecha Hora</th>
                                        <th>Total</th>
                                        <th>Impuesto</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody><!-- recorremos el metodo usando la directiva v-for el arrayIngreso cada valor que se recorre se mostrara en el objeto ingreso -->
                                    <tr v-for="ingreso in arrayIngreso" :key="ingreso.id">
                                        <td>
                                            <button type="button" @click="verIngreso(ingreso.id)" class="btn btn-success btn-sm"><!-- <button type="button" @click="abrirModal('ingreso','actualizar',ingreso)" class="btn btn-success btn-sm">//boton de abrir modal que nos permitira visualizar el ingreso-->
                                            <i class="icon-eye"></i><!--Hago referencia al metodo VerIngreso, se le va enviar como unico parametro el objeto ingreso con la propiedad id-->
                                            </button> &nbsp;<!--mediante la etiqueta template valido que el objeto es un ingreso su campo de estado es igual a registrado nos mostrara el boton que nos permite desactivar el ingreso -->
                                            <template v-if="ingreso.tipo_comprobante=='TICKET'">
                                                <button type="button" @click="pdfTicket(ingreso.id)" class="btn btn-warning btn-sm">
                                                <i class="icon-doc"></i>
                                                </button> &nbsp;
                                            </template>
                                            <template v-else>
                                                <button type="button" @click="pdfIngreso(ingreso.id)" class="btn btn-info btn-sm">
                                                    <i class="icon-doc"></i>
                                                </button> &nbsp;
                                             </template>
                                        </td><!-- se mostrara en las celdas los valores respectivos que tiene el objeto ingreso -->
                                        <td v-text="ingreso.usuario"></td>
                                        <td v-text="ingreso.nombre"></td>
                                        <td v-text="ingreso.tipo_comprobante"></td>
                                        <td v-text="ingreso.serie_comprobante"></td>
                                        <td v-text="ingreso.fecha_hora"></td>
                                        <td v-text="ingreso.total"></td>
                                        <td v-text="ingreso.impuesto"></td>
                                        <td v-text="ingreso.estado"></td>
                                    </tr>                                
                                </tbody>
                            </table>
                        </div>
                        <nav>
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    </template>  
                    <!-- FIN Listado -->
                    <!-- Por el contrario si el listado no es igual a 1, vamos a visualizar el div que me muestra el formulario para agregar detalles al ingreso -->
                    <!-- VER INGRESO -->
                    <!-- Listado para visualizar un ingreso registrado previamente -->
                    <template v-else-if="listado==2"><!-- se usa la directiva v-else-if, cuando el listado sea igual a dos se visualiza -->
                    <div class="card-body">
                        <div class="form-group row border">
                            <div class="col-md-9">  
                                <div class="form-group">
                                    <label for="">Proveedor</label>
                                    <p v-text="proveedor"></p><!-- se usa etiqueta tipo parrafo, aqui voy a motrar el valor que tenemos en la propiedad proveedor -->
                                </div>   
                            </div>
                            <div class="col-md-3">     
                                <label for="">Impuesto</label><!-- etiquetas de impuesto -->
                                <p v-text="impuesto"></p><!-- se usa etiqueta tipo parrafo, aqui voy a motrar el valor que tenemos en la propiedad impuesto -->
                            </div>
                            <div class="col-md-4">
                                <div class="form-group"><!-- etiquetas de Tipo Comprobante -->
                                    <label>Tipo Comprobante</label>
                                    <p v-text="tipo_comprobante"></p><!-- se usa etiqueta tipo parrafo, aqui voy a motrar el valor que tenemos en la propiedad tipo_comprobante -->
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Serie Comprobante</label><!-- etiquetas de Serie Comprobante -->
                                    <p v-text="serie_comprobante"></p><!-- se usa etiqueta tipo parrafo, aqui voy a motrar el valor que tenemos en la propiedad serie_comprobante -->
                                </div>
                            </div>
                        </div>
                        <div class="form-group row border">
                            <div class="table-responsive col-md-12"><!-- hacemos la tabla reposive -->
                                <table class="table table-bordered table-striped table-sm">
                                    <thead><!-- creamos una tabla con las celdas siguientes -->
                                        <tr> 
                                            <th>Materia Prima</th>
                                            <th>Precio</th>
                                            <th>Cantidad</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead><!-- condicion -->
                                    <!-- recordemos que el tbody se mostrara siempre y cuando se tenga al menos un indice en el arrayDetalle -->
                                    <tbody v-if="arrayDetalle.length"><!-- la condicion si el arraydetalle tiene un largo de uno hacia arriba entonces mostrare este tbody -->
                                        <tr v-for="detalle in arrayDetalle" :key="detalle.id"><!-- al mostrarlo en el tr usando la directiva v-for -->
                                            <!-- Detalle recorriendo el arraydetalle en este caso le indico que el key sea el campo de datalle id -->
                                            <td v-text="detalle.materia"><!-- mostramos el objeto detalle el campo materia -->
                                            </td>
                                            <td v-text="detalle.precio"><!-- vamos a mostrar usando la directiva v-text, vamos a mostar el precio del detalle -->
                                            </td>
                                            <td v-text="detalle.cantidad"><!--vamos a mostrar usando la directiva v-text, vamos a mostar la cantidad del detalle -->
                                            </td>
                                            <td>
                                                {{detalle.precio*detalle.cantidad}}<!-- vamos a mostrar del objeto detalle  el precio * el objeto detalle cantidad y de esta manera tenemos el subtotal -->
                                            </td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;"><!-- con este style lo que indico es el color que le voy a dar al campo -->
                                            <td colspan="3" align="right"><strong>Total Parcial:</strong></td><!-- indico la cantidad de celdas que tendre, alineo el texto y mediante la etiqueta strong para que se vea en negrita -->
                                            <td>${{totalParcial=(total-totalImpuesto).toFixed(2)}}</td>
                                            <!--el total parcial va ser igual al total menos el total impuesto-->
                                        </tr>
                                        <tr style="background-color: #CEECF5;"><!-- con este style lo que indico es el color que le voy a dar al campo -->
                                            <td colspan="3" align="right"><strong>Total Impuesto:</strong></td><!-- indico la cantidad de celdas que tendre, alineo el texto y mediante la etiqueta strong para que se vea en negrita -->
                                            <td>${{totalImpuesto=((total*impuesto)).toFixed(2)}}</td><!-- totalimpuesto se va a calcular de un valor que ya tiene impuesto, en este caso el total impuesto es igual a total por el porcentaje impuesto -->
                                            <!--asi que vamos a redonder el resultado con el metodo de javascript ToFixed indicandole que lo haga con solo dos decimales-->
                                        </tr>
                                        <tr style="background-color: #CEECF5;"><!-- con este style lo que indico es el color que le voy a dar al campo -->
                                            <td colspan="3" align="right"><strong>Total Neto:</strong></td><!-- indico la cantidad de celdas que tendre, alineo el texto y mediante la etiqueta strong para que se vea en negrita -->
                                            <td>$ {{total}}</td><!--el total va ser igual a loque tenemos en la variable total-->
                                        </tr>
                                    </tbody> 
                                    <tbody v-else>  <!-- este tbody se mostrara cuando el array este vacio -->
                                        <tr>
                                            <td colspan="4">
                                                No hay materia prima agregada
                                            </td>
                                        </tr>
                                    </tbody>                              
                                </table>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12"><!-- Boton cerrar nos permite ocultar el detalle del ingreso, cuyo tendra la funcion ocultar -->
                                <button type="button" @click="ocultarDetalle()" class="btn btn-secondary">Cerrar</button>
                            </div>
                        </div>
                    </div>
                    </template>
                    <!-- FIN VER INGRESO -->
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
        </main>
</template>

<script>//importamos el componente v-select
    import vSelect from 'vue-select';
    import 'vue-select/dist/vue-select.css';//Importamos el componente v-Select
    export default {
        data (){
            return {//delcaracion de variables que vamos a usar
                ingreso_id: 0,
                idproveedor:0,
                proveedor:'',
                nombre : '',
                tipo_comprobante : 'BOLETA',
                serie_comprobante : '',
                //num_comprobante : '',
                impuesto: 0.13,
                total:0.0,
                totalImpuesto: 0.0,
                totalParcial: 0.0,
                arrayIngreso : [],
                arrayProveedor : [],
                arrayDetalle : [],
                listado:1,//en esta variable se visualizara si se ve o no el listado, si es 1 el listado se visualiza y el formulario de detalle de ingreso se ocultara sino viceversa
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorIngreso : 0,
                errorMostrarMsjIngreso : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'serie_comprobante',// para indicar el campo de busqueda
                buscar : '',//y buscar para indicar cual es el texto de busqueda para realizar el filtro de toda la lista
                criterioM : 'nombre',
                buscarM : '',
                arrayMateria: [],//declaro el array
                idmateria: 0,//declaro la variable idmateria la inicializo en cero
                codigo: '',//se declara codigo y se inicializa vacia
                materia: '',//tambien la variable materia donde almaceamos el nombre de la materia
                precio: 0,//se declara la varibale precio, para almacenar lo que cuesta la materia
                cantidad:0//se declara la varibale cantidad, para almacenar cantidad de la materia que voy a comprar
            }
        },
        components: {//agregamos la propiedad, agregando el vSelect
            vSelect//de esta manera importo por completo el vselect
        },
        computed:{
            isActived: function(){
                return this.pagination.current_page;
            },
            //Calcula los elementos de la paginación
            pagesNumber: function() {
                if(!this.pagination.to) {
                    return [];
                }
                
                var from = this.pagination.current_page - this.offset; 
                if(from < 1) {
                    from = 1;
                }

                var to = from + (this.offset * 2); 
                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }  

                var pagesArray = [];
                while(from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;             
            },
            calcularTotal: function(){//funcion para calcular total impuesto y neto y parcial
                var resultado=0.0;//variable de nombre resultado que se inicializa en cero
                for(var i=0; i<this.arrayDetalle.length;i++){//el bucle inicia en 0 y se ejecuta el codigo incluido en este bucle siempre y vuando el contado sea menor que en este caso el largo del arrayDetalle   
                    //es decir el contador se va a ejecutar desde el 0 hasta que sea menor que el largo del arrayDetalle 
                    resultado=resultado+(this.arrayDetalle[i].precio*this.arrayDetalle[i].cantidad)//a la variable resultado que se declaro va ser igual a lo que ya tengo en la varibale resultado mas
                    //mas de mi arrayDetalle en la posicion I lo que esta almacenada en la propiedad precio lo multiplico nuevamente por el arraydetalle en la posicion I por la cantidad
                    //es decir voy a multiplicar el precio por la cantidad y lo voy almacenado en la varibale resultado 
                }
                return resultado;
            }
        },
        methods : {
            listarIngreso (page,buscar,criterio){
                let me=this;//declaramos nuestra variable me, para hacer referencia a esta clase decir "this"
                var url= '/ingreso?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;//se hace la referencia a la url ingreso
                axios.get(url).then(function (response) {//mediante el metodo de axios obtengo todos los registros de la tabla ingresos
                    var respuesta= response.data;
                    me.arrayIngreso = respuesta.ingresos.data;// se va a llenar con lo que resiva en el objeto ingresos, este objeto lo estoy resiviendo desde el controladordesde la fuincion index
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            cambiarPagina(page,buscar,criterio){
                let me = this;//declaramos nuestra variable me, para hacer referencia a esta clase decir "this"
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarIngreso(page,buscar,criterio);
            },
            cargarPdf(){
                //window.open(this.ruta + '/ingreso/listarPdf','_blank');
                window.open('http://127.0.0.1:8000/ingreso/listarPdf','_blank');
            },
            pdfIngreso(id){
                //window.open(this.ruta + '/ingreso/pdf/'+ id + ',' + '_blank');
                window.open('http://127.0.0.1:8000/ingreso/pdf/' + id + ',' + '_blank');
            },
            pdfTicket(id){
                window.open('http://127.0.0.1:8000/ingreso/pdfTicket/'+ id + ',' + '_blank');
            },
            mostrarDetalle(){//cuando llame a este metodo le indico que el valor de la variable listado es igual a 0
                this.listado=0;
                let me=this;//declaramos nuestra variable me, para hacer referencia a esta clase decir "this"
                me.idproveedor=0;
               // me.descripcion='';
                me.tipo_comprobante='BOLETA';
                me.serie_comprobante='';
                //me.num_comprobante='';
                me.impuesto=0.13;
                me.total=0.0;
                me.idmateria=0;
                me.materia='';
                me.cantidad=0;
                me.precio=0;
                me.arrayDetalle=[];
            },
            ocultarDetalle(){//en este caso es lo contrario le decimos qie la variable listado sea igual a 1
                this.listado=1;
            },
            verIngreso(id){//metodo para ver el listado registrado, recibe el parametro id para identificar el modal que debe visualizar
                let me=this;//declaramos nuestra variable me, para hacer referencia a esta clase decir "this"
                this.listado=2;
                
                //Obtener los datos del ingreso
                var arrayIngresoT=[];//arrayTemporal vacio 
                var url= '/ingreso/obtenerCabecera?id=' + id;//se hace la referencia a la url ingreso, el unico parametro es el id, en este metodo estamos recibiendo como parametro la variable id
                axios.get(url).then(function (response) {//mediante el metodo de axios obtengo todos los registros de la tabla ingresos
                    var respuesta= response.data;//voy obtener un valor resultante en este caso, respuesta es igual a response.data
                    arrayIngresoT = respuesta.ingreso;//este array solo pertenece a este metodo, el arrayIngresoT es igual a respuesta punto ingreso porque solo estamos recibiendo este unico parametro
    
                    me.proveedor = arrayIngresoT[0]['nombre'];//nuestra varibale proveedor que declaramos en la propiedad data va ser igual a lo que tenemos en nuestro arrayIngresoT, en el indice 0, que seria la fila es decir su unico registro pero en su propiedad nombre
                    me.fecha_hora=arrayIngresoT[0]['fecha_hora'];////nuestra varibale fecha_hora que declaramos en la propiedad data va ser igual a lo que tenemos en nuestro arrayIngresoT, en el indice 0, que seria la fila es decir su unico registro pero en su propiedad fecha_hora
                    me.tipo_comprobante=arrayIngresoT[0]['tipo_comprobante'];////nuestra varibale tipo_comprobante que declaramos en la propiedad data va ser igual a lo que tenemos en nuestro arrayIngresoT, en el indice 0, que seria la fila es decir su unico registro pero en su propiedad tipo_comprobante
                    me.serie_comprobante=arrayIngresoT[0]['serie_comprobante'];////nuestra varibale serie_comprobante que declaramos en la propiedad data va ser igual a lo que tenemos en nuestro arrayIngresoT, en el indice 0, que seria la fila es decir su unico registro pero en su propiedad serie_comprobante
                    //me.num_comprobante=arrayIngresoT[0]['num_comprobante'];////nuestra varibale num_comprobante que declaramos en la propiedad data va ser igual a lo que tenemos en nuestro arrayIngresoT, en el indice 0, que seria la fila es decir su unico registro pero en su propiedad num_comprobante
                    me.impuesto=arrayIngresoT[0]['impuesto'];////nuestra varibale impuesto que declaramos en la propiedad data va ser igual a lo que tenemos en nuestro arrayIngresoT, en el indice 0, que seria la fila es decir su unico registro pero en su propiedad impuesto
                    me.total=arrayIngresoT[0]['total'];////nuestra varibale total que declaramos en la propiedad data va ser igual a lo que tenemos en nuestro arrayIngresoT, en el indice 0, que seria la fila es decir su unico registro pero en su propiedad total
                })
                .catch(function (error) {
                    console.log(error);
                });

                //Obtener los datos de los detalles 
                var url= '/ingreso/obtenerDetalles?id=' + id;//se hace la referencia a la url ingreso, el unico parametro es el id, en este metodo estamos recibiendo como parametro la variable id
                
                axios.get(url).then(function (response) {//mediante el metodo de axios obtengo todos los registros de la tabla ingresos
                    var respuesta= response.data;//voy obtener un valor resultante en este caso, respuesta es igual a response.data
                    me.arrayDetalle = respuesta.detalles;//este array solo pertenece a este metodo, el arrayDetalles es igual a respuesta punto detalles, ya que el controlador nos devuelve ese parametro
                   })
                .catch(function (error) {
                    console.log(error);
                });
            }
        },
        mounted() {
            this.listarIngreso(1,this.buscar,this.criterio);
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
    @media (min-width: 600px) {/*estilo del boton de agregar el margin top es para cuando las pantallas sean grandes el boton quedara en el centro*/
        .btnagregar {
            margin-top: 2rem;
        }
    }

</style>