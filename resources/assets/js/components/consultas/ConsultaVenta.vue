<template>
            <main class="main">
            <!-- Breadcrumb <li class="breadcrumb-item"><a href="/">Escritorio</a></li>-->
            <ol class="breadcrumb">
               <li class="breadcrumb-item"> <i class="fa fa-bar-chart" aria-hidden="true"></i> <strong>Reportes Ventas</strong></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Ventas
                        <button type="button" @click="cargarPdf()" class="btn btn-danger">
                            <i class="fa fa-file-pdf-o" aria-hidden="true"> PDF</i>
                        </button>
                    </div>
                    <!-- Listado -->
                    <!-- Si la variable listado es igual a 1, se visualiza el listado de ventas y ocultamos el formulario que agrega detalles al venta -->
                    <template v-if="listado==1"><!-- le condiciono si el listado es igual a uno visualizo el div que me muestra el listado de los ventas y se va ocultar el div que muestra  el formulario para agrear detalles a la venta-->
                    <div class="card-body"><!-- se visualiza cuando el listado es igual 1 -->
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group"><!-- Formas de realizar la busqueda -->
                                    <select class="form-control col-md-3" v-model="criterio">
                                      <option value="tipo_comprobante">Tipo Comprobante</option>
                                      <option value="serie_comprobante">Serie Comprobante</option>
                                    </select><!-- Hacemos referencia al escribir al metodo listar -->
                                    <input type="text" v-model="buscar" @keyup.enter="listarVenta(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarVenta(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr><!-- Describimos las celdas ue ocupamos -->
                                        <th>Opciones</th>
                                        <th>Usuario</th>
                                        <th>Cliente</th>
                                        <th>Tipo Comprobante</th>
                                        <th>Serie Comprobante</th>
                                        <th>Fecha Hora</th>
                                        <th>Total</th>
                                        <th>Impuesto</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody><!-- recorremos el metodo usando la directiva v-for el arrayVenta cada valor que se recorre se mostrara en el objeto venta -->
                                    <tr v-for="venta in arrayVenta" :key="venta.id">
                                        <td>
                                            <button type="button" @click="verVenta(venta.id)" class="btn btn-success btn-sm"><!-- <button type="button" @click="abrirModal('venta','actualizar',ingreso)" class="btn btn-success btn-sm">//boton de abrir modal que nos permitira visualizar el ingreso-->
                                            <i class="icon-eye"></i><!--Hago referencia al metodo verVenta, se le va enviar como unico parametro el objeto venta con la propiedad id-->
                                            </button> &nbsp;<!--mediante la etiqueta template valido que el objeto es un venta su campo de estado es igual a registrado nos mostrara el boton que nos permite desactivar el ingreso -->
                                            <template v-if="venta.tipo_comprobante=='TICKET'">
                                                <button type="button" @click="pdfTicket(venta.id)" class="btn btn-warning btn-sm">
                                                <i class="icon-doc"></i>
                                                </button> &nbsp;
                                            </template>
                                            <template v-else>
                                                <button type="button" @click="pdfVenta(venta.id)" class="btn btn-info btn-sm">
                                                <i class="icon-doc"></i>
                                                </button> &nbsp;
                                            </template>  
                                        </td><!-- se mostrara en las celdas los valores respectivos que tiene el objeto venta -->
                                        <td v-text="venta.usuario"></td>
                                        <td v-text="venta.nombre"></td>
                                        <td v-text="venta.tipo_comprobante"></td>
                                        <td v-text="venta.serie_comprobante"></td>
                                        <td v-text="venta.fecha_hora"></td>
                                        <td v-text="venta.total"></td>
                                        <td v-text="venta.impuesto"></td>
                                        <td v-text="venta.estado"></td>
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
                                    <label for="">Cliente</label>
                                    <p v-text="cliente"></p><!-- se usa etiqueta tipo parrafo, aqui voy a motrar el valor que tenemos en la propiedad cliente -->
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
                                            <th>Producto</th>
                                            <th>Precio</th>
                                            <th>Peso</th>
                                            <th>Cantidad</th>
                                            <th>Descuento</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead><!-- condicion -->
                                    <!-- recordemos que el tbody se mostrara siempre y cuando se tenga al menos un indice en el arrayDetalle -->
                                    <tbody v-if="arrayDetalle.length"><!-- la condicion si el arraydetalle tiene un largo de uno hacia arriba entonces mostrare este tbody -->
                                        <tr v-for="detalle in arrayDetalle" :key="detalle.id"><!-- al mostrarlo en el tr usando la directiva v-for -->
                                            <!-- Detalle recorriendo el arraydetalle en este caso le indico que el key sea el campo de datalle id -->
                                            <td v-text="detalle.producto"><!-- mostramos el objeto detalle el campo producto -->
                                            </td>
                                            <td v-text="detalle.precio"><!-- vamos a mostrar usando la directiva v-text, vamos a mostar el precio del detalle -->
                                            </td>
                                             <td v-text="detalle.peso"><!-- vamos a mostrar usando la directiva v-text, vamos a mostar el precio del detalle -->
                                            </td>
                                            <td v-text="detalle.cantidad"><!--vamos a mostrar usando la directiva v-text, vamos a mostar la cantidad del detalle -->
                                            </td>
                                            <td v-text="detalle.descuento"><!--vamos a mostrar usando la directiva v-text, vamos a mostar la cantidad del detalle -->
                                            </td>
                                            <td>
                                                {{detalle.precio*detalle.cantidad-detalle.descuento}}<!-- vamos a mostrar del objeto detalle  el precio * el objeto detalle cantidad y ademas de eso le restamos el descuento y de esta manera tenemos el subtotal -->
                                            </td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;"><!-- con este style lo que indico es el color que le voy a dar al campo -->
                                            <td colspan="5" align="right"><strong>Total Parcial:</strong></td><!-- indico la cantidad de celdas que tendre, alineo el texto y mediante la etiqueta strong para que se vea en negrita -->
                                            <td>${{totalParcial=(total-totalImpuesto).toFixed(2)}}</td>
                                            <!--el total parcial va ser igual al total menos el total impuesto-->
                                        </tr>
                                        <tr style="background-color: #CEECF5;"><!-- con este style lo que indico es el color que le voy a dar al campo -->
                                            <td colspan="5" align="right"><strong>Total Impuesto:</strong></td><!-- indico la cantidad de celdas que tendre, alineo el texto y mediante la etiqueta strong para que se vea en negrita -->
                                            <td>${{totalImpuesto=((total*impuesto)).toFixed(2)}}</td><!-- totalimpuesto se va a calcular de un valor que ya tiene impuesto, en este caso el total impuesto es igual a total por el porcentaje impuesto -->
                                            <!--asi que vamos a redonder el resultado con el metodo de javascript ToFixed indicandole que lo haga con solo dos decimales-->
                                        </tr>
                                        <tr style="background-color: #CEECF5;"><!-- con este style lo que indico es el color que le voy a dar al campo -->
                                            <td colspan="5" align="right"><strong>Total Neto:</strong></td><!-- indico la cantidad de celdas que tendre, alineo el texto y mediante la etiqueta strong para que se vea en negrita -->
                                            <td>$ {{total}}</td><!--el total va ser igual a loque tenemos en la variable total-->
                                        </tr>
                                    </tbody> 
                                    <tbody v-else>  <!-- este tbody se mostrara cuando el array este vacio -->
                                        <tr>
                                            <td colspan="6">
                                                No hay productos agregadas
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
                venta_id: 0,
                idcliente:0,
                cliente:'',
                tipo_comprobante : 'BOLETA',
                serie_comprobante : '',
                impuesto: 0.13,
                total:0.0,
                totalImpuesto: 0.0,
                totalParcial: 0.0,
                arrayVenta : [],
                arrayCliente : [],
                arrayDetalle : [],
                listado:1,//en esta variable se visualizara si se ve o no el listado, si es 1 el listado se visualiza y el formulario de detalle de ingreso se ocultara sino viceversa
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorVenta : 0,
                errorMostrarMsjVenta : [],
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
                arrayProducto: [],//declaro el array
                idproducto: 0,//declaro la variable idproducto la inicializo en cero
                codigo: '',//se declara codigo y se inicializa vacia
                producto: '',//tambien la variable producto donde almaceamos el nombre de la producto
                precio: 0,//se declara la varibale precio, para almacenar lo que cuesta la producto
                cantidad:0,//se declara la varibale cantidad, para almacenar cantidad de la producto que voy a comprar
                descuento:0,//se declara la varibale descuento, para almacenar descuento del producto
                peso:'',//se declara peso y se inicializa vacia
                stock:0
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
                    resultado=resultado+(this.arrayDetalle[i].precio*this.arrayDetalle[i].cantidad-this.arrayDetalle[i].descuento)//a la variable resultado que se declaro va ser igual a lo que ya tengo en la varibale resultado mas
                    //mas de mi arrayDetalle en la posicion I lo que esta almacenada en la propiedad precio lo multiplico nuevamente por el arraydetalle en la posicion I por la cantidad
                    //es decir voy a multiplicar el precio por la cantidad y ademas le resto un descuento determiando y lo voy almacenado en la varibale resultado 
                }
                return resultado;
            }
        },
        methods : {
            listarVenta (page,buscar,criterio){
                let me=this;//declaramos nuestra variable me, para hacer referencia a esta clase decir "this"
                var url= '/venta?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;//se hace la referencia a la url venta
                axios.get(url).then(function (response) {//mediante el metodo de axios obtengo todos los registros de la tabla ventas
                    var respuesta= response.data;
                    me.arrayVenta = respuesta.ventas.data;// se va a llenar con lo que resiva en el objeto ventas, este objeto lo estoy resiviendo desde el controlador desde la funcion index
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
           cargarPdf(){//este es primero segun entendi
                window.open('http://127.0.0.1:8000/venta/listarPdf','_blank');
            },
            pdfVenta(id){//hace referencia a la url que hace referencia nuestro pdf con el metodo open.
                window.open('http://127.0.0.1:8000/venta/pdf/' + id + ',' + '_blank');//hacemos refrencia ala url mas el id que seria el id venta que vamos a imprimir
                //window.open(this.ruta + '/venta/pdf/'+ id + ',' + '_blank');
                //window.open('http://127.0.0.1:8000/venta/pdf','_blank');     
                //window.open('http://localhost:8000/producto/listarPdf','_blank');
            },
            pdfTicket(id){
                window.open('http://127.0.0.1:8000/venta/pdfTicket/'+ id + ',' + '_blank');
            },
            cambiarPagina(page,buscar,criterio){
                let me = this;//declaramos nuestra variable me, para hacer referencia a esta clase decir "this"
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarVenta(page,buscar,criterio);
            },           
            mostrarDetalle(){//cuando llame a este metodo le indico que el valor de la variable listado es igual a 0
                this.listado=0;
                let me=this;//declaramos nuestra variable me, para hacer referencia a esta clase decir "this"
                me.idcliente=0;
               // me.descripcion='';
                me.tipo_comprobante='BOLETA';
                me.serie_comprobante='';
                me.impuesto=0.13;
                me.total=0.0;
                me.idproducto=0;
                me.producto='';
                me.cantidad=0;
                me.precio=0;
                me.arrayDetalle=[];
            },
            ocultarDetalle(){//en este caso es lo contrario le decimos qie la variable listado sea igual a 1
                this.listado=1;
            },
            verVenta(id){//metodo para ver el listado registrado, recibe el parametro id para identificar el modal que debe visualizar
                let me=this;//declaramos nuestra variable me, para hacer referencia a esta clase decir "this"
                this.listado=2;
                
                //Obtener los datos del venta
                var arrayVentaT=[];//arrayTemporal vacio 
                var url= '/venta/obtenerCabecera?id=' + id;//se hace la referencia a la url venta, el unico parametro es el id, en este metodo estamos recibiendo como parametro la variable id
                axios.get(url).then(function (response) {//mediante el metodo de axios obtengo todos los registros de la tabla ventas
                    var respuesta= response.data;//voy obtener un valor resultante en este caso, respuesta es igual a response.data
                    arrayVentaT = respuesta.venta;//este array solo pertenece a este metodo, el arrayVentaT es igual a respuesta punto venta porque solo estamos recibiendo este unico parametro
                    
                    me.cliente = arrayVentaT[0]['nombre'];//nuestra varibale cliente que declaramos en la propiedad data va ser igual a lo que tenemos en nuestro arrayVentaT, en el indice 0, que seria la fila es decir su unico registro pero en su propiedad nombre
                    me.fecha_hora=arrayVentaT[0]['fecha_hora'];////nuestra varibale fecha_hora que declaramos en la propiedad data va ser igual a lo que tenemos en nuestro arrayVentaT, en el indice 0, que seria la fila es decir su unico registro pero en su propiedad fecha_hora
                    me.tipo_comprobante=arrayVentaT[0]['tipo_comprobante'];////nuestra varibale tipo_comprobante que declaramos en la propiedad data va ser igual a lo que tenemos en nuestro arrayVentaT, en el indice 0, que seria la fila es decir su unico registro pero en su propiedad tipo_comprobante
                    me.serie_comprobante=arrayVentaT[0]['serie_comprobante'];////nuestra varibale serie_comprobante que declaramos en la propiedad data va ser igual a lo que tenemos en nuestro arrayVentaT, en el indice 0, que seria la fila es decir su unico registro pero en su propiedad serie_comprobante
                    //me.num_comprobante=arrayVentaT[0]['num_comprobante'];////nuestra varibale num_comprobante que declaramos en la propiedad data va ser igual a lo que tenemos en nuestro arrayVentaT, en el indice 0, que seria la fila es decir su unico registro pero en su propiedad num_comprobante
                    me.impuesto=arrayVentaT[0]['impuesto'];////nuestra varibale impuesto que declaramos en la propiedad data va ser igual a lo que tenemos en nuestro arrayVentaT, en el indice 0, que seria la fila es decir su unico registro pero en su propiedad impuesto
                    me.total=arrayVentaT[0]['total'];////nuestra varibale total que declaramos en la propiedad data va ser igual a lo que tenemos en nuestro arrayVentaT, en el indice 0, que seria la fila es decir su unico registro pero en su propiedad total
                })
                .catch(function (error) {
                    console.log(error);
                });

                //Obtener los datos de los detalles 
                var url= '/venta/obtenerDetalles?id=' + id;//se hace la referencia a la url Venta, el unico parametro es el id, en este metodo estamos recibiendo como parametro la variable id
                
                axios.get(url).then(function (response) {//mediante el metodo de axios obtengo todos los registros de la tabla Ventas
                    var respuesta= response.data;//voy obtener un valor resultante en este caso, respuesta es igual a response.data
                    me.arrayDetalle = respuesta.detalles;//este array solo pertenece a este metodo, el arrayDetalles es igual a respuesta punto detalles, ya que el controlador nos devuelve ese parametro
                   })
                .catch(function (error) {
                    console.log(error);
                });
            }
        },
        mounted() {
            this.listarVenta(1,this.buscar,this.criterio);
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