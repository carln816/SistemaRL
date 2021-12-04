<template>
            <main class="main">
            <!-- Breadcrumb <li class="breadcrumb-item"><a href="/">Escritorio</a></li>-->
            <ol class="breadcrumb">
                <li class="breadcrumb-item"> <i class="fa fa-cart-plus" aria-hidden="true"></i> <strong>Ventas</strong></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Ventas
                        <button type="button" @click="mostrarDetalle()" class="btn btn-success"><!-- este boton llama al metodo para que muestre el formulario -->
                            <i class="fa fa-plus-circle" aria-hidden="true"> Nuevo</i>
                        </button>
                        <button type="button" @click="cargarPdf()" class="btn btn-danger">
                            <i class="fa fa-file-pdf-o" aria-hidden="true"> Reporte</i>&nbsp;
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
                                            </button><!--mediante la etiqueta template valido que el objeto es un venta su campo de estado es igual a registrado nos mostrara el boton que nos permite desactivar el ingreso -->
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
                                            <!--<button type="button" @click="pdfVenta(venta.id)" class="btn btn-info btn-sm">
                                            <i class="fa fa-file-pdf-o"></i>
                                            </button>-->
                                            <template v-if="venta.estado=='Registrado'"><!-- Es decir si el estado esta registrado pueda permitir anuelar ese estado, ya que si se encuentra anulado, no se puede anular -->
                                                <button type="button" class="btn btn-danger btn-sm" @click="desactivarVenta(venta.id)"><!-- se envia como parametro el objeto venta -->
                                                    <i class="icon-trash"></i>
                                                </button>
                                            </template>
                                        </td><!-- se mostrara en las celdas los valores respectivos que tiene el objeto venta -->
                                        <td v-text="venta.usuario"></td>
                                        <td v-text="venta.nombre"></td>
                                        <td v-text="venta.tipo_comprobante"></td>
                                        <td v-text="venta.serie_comprobante"></td>
                                        <!--<td v-text="venta.num_comprobante"></td>-->
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
                    <!-- Detalle -->
                    <template v-else-if="listado==0"><!-- se usa la directiva v-else-if, cuando el listado sea igual a cero se visualiza -->
                    <div class="card-body">
                        <div class="form-group row border">
                            <div class="col-md-9">  
                                <div class="form-group">
                                    <label for="">Cliente(*)</label>
                                    <!-- COMENTARIOS DE ESTE QUE SI FUNCIONA -->
                                    <!-- Usamos la etiqueta v-Select -->
                                    <!-- primero le muestro el metodo que me permitira obtener los registros que voy a visualizar en la etiqueta v-select -->
                                    <!-- atributo label le indico que voy a mostrar en las opciones el valor que tengo en la propiedad nombre  -->
                                    <!-- se declara el atributo opciones donde mostrare la opciones que se pueden mostrar en este caso el arraycliete -->
                                    <!-- se declara el atributo opciones donde mostrare la opciones que se pueden mostrar en este caso el arraycliete -->
                                    <!-- se agrega un placepholder con el texto buscarclietes  -->
                                    <!-- un ultimo atributo el input para indicar que caga vez que cambiemos una opcion del v-select nos permita obtener los datos del cliente -->
                                    <v-select
                                        @search="selectCliente"
                                        label="nombre"
                                        :filterable="false"
                                        :options="arrayCliente"
                                        placeholder="Buscar Clientes..."
                                        @input="getDatosCliente"                                        
                                    >
                                    <template slot="no-options">
                                        Lo sentimos, no hay opciones que coincidan...
                                    </template>
                                    </v-select>
                                </div>   
                            </div>
                            <div class="col-md-3">     
                                <label for="">Impuesto(*)</label><!-- etiquetas de impuesto -->
                                <input type="text" class="form-control" v-model="impuesto" placeholder="Indique con un 0 luego .mas el porcentaje"><!-- con la directiva v-model hace refrencia a la variable impuesto de la propiedad data  -->
                            </div>
                            <div class="col-md-4">
                                <div class="form-group"><!-- etiquetas de Tipo Comprobante -->
                                    <label>Tipo Comprobante(*)</label>
                                    <select class="form-control" v-model="tipo_comprobante"><!-- en la etiqueta de tipo de select que tiene la directiva v-model la variable tipo de comprobante voy a mostrar las siguientes opciones -->
                                        <option value="0">Seleccione</option><!-- la opcion vacia sera SELECCIONE se le pondra el valor de cero -->
                                        <option value="BOLETA">Boleta</option><!-- la opcion con el valor boleta, muestra el texto boleta-->
                                        <option value="FACTURA">Factura</option><!-- la opcion con el valor Factura, muestra el texto Factura-->
                                        <option value="TICKET">Ticket</option><!-- la opcion con el valor Ticket, muestra el texto Ticket-->
                                    </select><!-- el valor que se seleccione se almacenara en la variable tipo de comprobante -->
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Serie Comprobante</label><!-- etiquetas de Serie Comprobante -->
                                    <input type="text" class="form-control" v-model="serie_comprobante" placeholder="000x"><!-- con un input de tipo texto voy a mostrar un objeto  de formulario input para que el usuario pueda ingresar la serie del comprobante, relacionadolo con la directiva v-model con la variable serie del comprobante de nuestra propiedad data-->
                                </div>
                            </div>
                            <!-- <div class="col-md-4">
                                <div class="form-group">
                                    <label>Número Comprobante(*)</label>etiquetas de Número Comprobante 
                                    <input type="text" class="form-control" v-model="num_comprobante" placeholder="000xx">< se implementa un input que se ha relacionado mediante la directiva v-model con la variable numero de comprobante de nuestra propiedad data
                                </div>
                            </div>-->
                            <div class="col-md-12"><!-- si el errorIngreso es igual se va a visualizar el div -->
                                <div v-show="errorVenta" class="form-group row div-error">
                                    <div class="text-center text-error"><!-- con el objeto error se va a recorrer todos los valores que tengo en el errorMostrarMsjVenta-->
                                        <div v-for="error in errorMostrarMsjVenta" :key="error" v-text="error">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row border">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Producto <span style="color:red;" v-show="idproducto==0">(*Seleccione)</span></label><!-- se agrega una etiqueta con el texto producto,la directiva v-show solo se muestre cuando el idproducto sea igual a cero -->
                                    <div class="form-inline"><!--se agrega el div que me permite tener la clase formInline para mostrar una caja de texto y al costado un boton -->
                                        <input type="text" class="form-control" v-model="codigo" @keyup.enter="buscarProducto()" placeholder="Ingrese el producto"><!--en la caja de texto en este caso el input con type text, muestro la caja de texto con el placeholder ingrese un producto, relacionadolo con el v-model codigo   -->
                                        <button @click="abrirModal()" class="btn btn-primary">...</button><!-- muestro un boton que al darle click se mostrara una ventana con la lista de todos las ventas para cargarlos de 1 en 1 al detalle -->
                                        <input type="text" readonly class="form-control" v-model="producto">
                                    </div>                                    
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group"><!-- se agrega una etiqueta con el texto Precio -->
                                    <label>Precio <span style="color:red;" v-show="precio==0">(*Ingrese )</span></label><!-- la directiva v-show solo se muestre cuando el precio sea igual a cero -->
                                    <input type="number" value="0" step="any" class="form-control" v-model="precio"><!-- mostramos un input de tipo numero que tiene dentro de la directiva v-model la variable precio para hacer referencia a nuestra varibale precio de nuestra propiedad data -->
                                </div><!-- step many aumenta valores decimales, el value es para darle un inicio a la cantidad lo cual le indico que es cero -->
                            </div>
                            <div class="col-md-2">
                                <div class="form-group"><!-- se agrega una etiqueta con el texto Precio -->
                                    <label>Peso <span style="color:red;" v-show="peso==0">(*Ingrese )</span></label><!-- la directiva v-show solo se muestre cuando el precio sea igual a cero -->
                                    <input type="text"  class="form-control" v-model="peso"><!-- mostramos un input de tipo numero que tiene dentro de la directiva v-model la variable precio para hacer referencia a nuestra varibale precio de nuestra propiedad data -->
                                </div><!-- step many aumenta valores decimales, el value es para darle un inicio a la cantidad lo cual le indico que es cero -->
                            </div>
                            <div class="col-md-2">
                                <div class="form-group"><!-- se agrega una etiqueta con el texto Precio -->
                                <!--dentro de la directiva v-model la variable precio para hacer referencia a nuestra variable cantidad de nuestra propiedad data -->
                                    <label>Cantidad <span style="color:red;" v-show="cantidad==0">(*Ingrese)</span></label><!-- la directiva v-show solo se muestre cuando el cantidad sea igual a cero -->
                                    <input type="number" value="0" class="form-control" v-model="cantidad">
                                </div><!-- el value es para darle un inicio a la cantidad lo cual le indico que es cero-->
                            </div>
                            <div class="col-md-2">
                                <div class="form-group"><!-- se agrega una etiqueta con el texto Precio -->
                                <!--dentro de la directiva v-model la variable precio para hacer referencia a nuestra variable descuento de nuestra propiedad data -->
                                    <label>Descuento </label>
                                    <input type="number" value="0" class="form-control" v-model="descuento">
                                </div><!-- el value es para darle un inicio al descuento lo cual le indico que es cero-->
                            </div>
                            <div class="col-md-2">
                                <div class="form-group"><!-- clase tendra un boton que contara un btn agregar-->
                                    <button @click="agregarDetalle()" class="btn btn-success form-control btnagregar"><i class="icon-plus"></i></button>
                                </div><!-- se da el evento click,hacemos referencia el metodo agregarDetalle -->
                            </div>
                        </div>
                        <div class="form-group row border">
                            <div class="table-responsive col-md-12"><!-- hacemos la tabla reposive -->
                                <table class="table table-bordered table-striped table-sm">
                                    <thead><!-- creamos una tabla con las celdas siguientes -->
                                        <tr>
                                            <th>Opciones</th>
                                            <th>Producto</th>
                                            <th>Peso</th><!--ESTA-->
                                            <th>Precio</th>
                                            <th>Cantidad</th>
                                            <th>Descuento</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead><!-- condicion -->
                                    <!-- recordemos que el tbody se mostrara siempre y cuando se tenga al menos un indice en el arrayDetalle -->
                                    <tbody v-if="arrayDetalle.length"><!-- la condicion si el arraydetalle tiene un largo de uno hacia arriba entonces mostrare este tbody -->
                                    <!-- es decir si existe un indice mostrare el tbody -->
                                        <tr v-for="(detalle,index) in arrayDetalle" :key="detalle.id"><!-- al mostrarlo en el tr usando la directiva v-for -->
                                            <!-- Detalle recorriendo el arraydetalle en este caso le indico que el key sea el campo de datalle id -->
                                            <td><!--el detalle tendra un indice y ese indice lo indicaremos en el boton de eliminar con el metodo eliminarDetalle-->
                                                <button @click="eliminarDetalle(index)" type="button" class="btn btn-danger btn-sm">
                                                    <i class="icon-close"></i>
                                                </button>
                                            </td>
                                            <td v-text="detalle.producto"><!-- mostramos el objeto detalle el campo producto -->
                                            </td>
                                            <td>
                                            <input v-model="detalle.peso" type="text"  class="form-control"><!--ESTA- mostramos el objeto detalle el campo peso-->
                                            </td>
                                            <td>
                                                <input v-model="detalle.precio" type="number"  class="form-control"><!-- vamos a mostrar usando la directiva v-model, mostrando del objeto detalle el campo precio -->
                                            </td>
                                            <td>
                                                <span style="color:red;" v-show="detalle.cantidad>detalle.stock">Stock: {{detalle.stock}}</span><!-- vamos a mostrar usando la directiva v-show,para que se visualiza cuando la cantidad sea mayor que el stock -->
                                                <input v-model="detalle.cantidad" type="number"  class="form-control"><!-- vamos a mostrar usando la directiva v-model, mostrando del objeto detalle el campo cantidad -->
                                            </td>
                                            <td>
                                                <span style="color:red;" v-show="detalle.descuento>(detalle.precio*detalle.cantidad)">Descuento Superior: {{detalle.stock}}</span><!-- vamos a mostrar usando la directiva v-show,para que se visualiza cuando el descuento sea mayor que el subtotal, es decir el precio por la cantidad -->
                                                <input v-model="detalle.descuento" type="number"  class="form-control"><!-- vamos a mostrar usando la directiva v-model, mostrando del objeto detalle el campo descuento -->
                                            </td>
                                            <td>
                                                {{detalle.precio*detalle.cantidad-detalle.descuento}}<!-- vamos a mostrar del objeto detalle  el precio * el objeto detalle cantidad y menos el descuento y de esta manera tenemos el subtotal -->
                                            </td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;"><!-- con este style lo que indico es el color que le voy a dar al campo -->
                                            <td colspan="6" align="right"><strong>Total Parcial:</strong></td><!-- indico la cantidad de celdas que tendre, alineo el texto y mediante la etiqueta strong para que se vea en negrita -->
                                            <td>₡{{totalParcial=(total-totalImpuesto).toFixed(2)}}</td>
                                            <!--el total parcial va ser igual al total menos el total impuesto-->
                                        </tr>
                                        <tr style="background-color: #CEECF5;"><!-- con este style lo que indico es el color que le voy a dar al campo -->
                                            <td colspan="6" align="right"><strong>Total Impuesto:</strong></td><!-- indico la cantidad de celdas que tendre, alineo el texto y mediante la etiqueta strong para que se vea en negrita -->
                                            <td>₡{{totalImpuesto=((total*impuesto)).toFixed(2)}}</td>
                                            <!--aqui decimos que el metodo totalImpuesto  es igual al total que lo multiplico por el porcentaje de impuesto-->
                                            <!--y lo voy a dividir entre uno mas el porcentaje de impuesto pero si lo hago asi apareceran muchos decimales-->
                                            <!--asi que vamos a redonder el resultado con el metodo de javascript ToFixed indicandole que lo haga con solo decimales-->
                                        </tr>
                                        <tr style="background-color: #CEECF5;"><!-- con este style lo que indico es el color que le voy a dar al campo -->
                                            <td colspan="6" align="right"><strong>Total Neto:</strong></td><!-- indico la cantidad de celdas que tendre, alineo el texto y mediante la etiqueta strong para que se vea en negrita -->
                                            <td>₡{{total=calcularTotal}}</td><!--aqui decimos que el total es igual al metodo donde hacemos refrencia al metodo calcularTotal en todo momento -->
                                        </tr>
                                    </tbody> 
                                    <tbody v-else>  <!-- este tbody se mostrara cuando el array este vacio -->
                                        <tr>
                                            <td colspan="7">
                                                No hay productos agregados
                                            </td>
                                        </tr>
                                    </tbody>                              
                                </table>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12"><!-- Boton cerrar nos permite ocultar el detalle del ingreso, cuyo tendra la funcion ocultar -->
                                <button type="button" @click="ocultarDetalle()" class="btn btn-secondary">Cerrar</button>
                                <button type="button" class="btn btn-primary" @click="registrarVenta()">Registrar Venta</button><!-- Boton registar nos permite realizar la compra del ingreso, cuyo tendra la funcion resgistrar ingreso -->
                            </div>
                        </div>
                    </div>
                    </template>
                    <!-- FIN Detalle -->
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
                                    <label>Serie Comprobante</label><!--etiquetas de Serie Comprobante -->
                                    <p v-text="serie_comprobante"></p><!-- se usa etiqueta tipo parrafo, aqui voy a motrar el valor que tenemos en la propiedad serie_comprobante -->
                                </div>
                            </div>
                            <!--<div class="col-md-4">
                                <div class="form-group">
                                    <label>Número Comprobante</label> etiquetas de Número Comprobante 
                                    <p v-text="num_comprobante"></p> se usa etiqueta tipo parrafo, aqui voy a motrar el valor que tenemos en la propiedad num_comprobante 
                                </div>
                            </div>-->
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
                                            <td>₡{{totalParcial=(total-totalImpuesto).toFixed(2)}}</td>
                                            <!--el total parcial va ser igual al total menos el total impuesto-->
                                        </tr>
                                        <tr style="background-color: #CEECF5;"><!-- con este style lo que indico es el color que le voy a dar al campo -->
                                            <td colspan="5" align="right"><strong>Total Impuesto:</strong></td><!-- indico la cantidad de celdas que tendre, alineo el texto y mediante la etiqueta strong para que se vea en negrita -->
                                            <td>₡{{totalImpuesto=((total*impuesto)).toFixed(2)}}</td><!-- totalimpuesto se va a calcular de un valor que ya tiene impuesto, en este caso el total impuesto es igual a total por el porcentaje impuesto -->
                                            <!--asi que vamos a redonder el resultado con el metodo de javascript ToFixed indicandole que lo haga con solo dos decimales-->
                                        </tr>
                                        <tr style="background-color: #CEECF5;"><!-- con este style lo que indico es el color que le voy a dar al campo -->
                                            <td colspan="5" align="right"><strong>Total Neto:</strong></td><!-- indico la cantidad de celdas que tendre, alineo el texto y mediante la etiqueta strong para que se vea en negrita -->
                                            <td>₡{{total}}</td><!--el total va ser igual a loque tenemos en la variable total-->
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
            <!--Inicio del modal agregar/actualizar-->
            <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <select class="form-control col-md-3" v-model="criterioM"><!-- //le enlazamos mediante el v-model el select con criterioM significa por el producto -->
                                        <option value="nombre">Nombre</option>
                                        <option value="descripcion">Descripción</option>
                                        <option value="codigo">Codigo</option>
                                        </select><!-- //cuando se da enter vamos a llamar nuestro metodo -->
                                        <input type="text" v-model="buscarM" @keyup.enter="listarProducto(buscarM,criterioM)" class="form-control" placeholder="Texto a buscar">
                                        <button type="submit" @click="listarProducto(buscarM,criterioM)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button> 
                                    </div>
                                </div>
                            </div>
                            <div class="table responsive">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Opciones</th>
                                            <th>Codigo</th>
                                            <th>Nombre</th>
                                            <th>Categoria</th>
                                            <th>Stock</th>
                                            <th>Precio</th>
                                            <th>Peso</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="producto in arrayProducto" :key="producto.id">
                                            <td>
                                                <button type="button" @click="agregarDetalleModal(producto)" class="btn btn-success btn-sm"><!--cuando le damos click llamaremos al metodo agregardetallemodal y ademas le vamos a enviar solamente el objeto producto-->
                                                <i class="icon-check"></i>
                                                </button>
                                            </td>
                                            <td v-text="producto.codigo"></td>
                                            <td v-text="producto.nombre"></td>
                                            <td v-text="producto.nombre_categoria"></td>
                                            <td v-text="producto.stock"></td>
                                            <td v-text="producto.precio_venta"></td>
                                            <td v-text="producto.peso"></td>
                                            <td>
                                                <div v-if="producto.condicion"><!--  si la condicion es igual 1-->
                                                    <span class="badge badge-success">Activo</span>
                                                </div>
                                                <div v-else><!-- si no -->
                                                    <span class="badge badge-danger">Desactivado</span>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarProducto()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarProducto()">Actualizar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->
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
                //num_comprobante : '',
                impuesto:'',
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
            selectCliente(search,loading){//le damos dos parametros el search que es propio que lo envia el objeto v-select y el otro loading  
                let me=this;//declaramos nuestra variable me, para hacer referencia a esta clase decir "this"
                loading(true)//le indico al parametro que este en true  

                var url= '/cliente/selectCliente?filtro='+search;//se le pasa el parametro llamado filtro que es igual a lo que estoy recibiendo en el parametro search de nuestro metodo select cliene, reocordando que me lo envia v-select
                axios.get(url).then(function (response) {//le hago la peticion con axios
                //relleno todo el array de cliente con lo que tengo en el array respuesta punto clientes
                    let respuesta = response.data;//declaro una variable llamda respuesta es igual a lo que tiene el objeto response en la propiedad data
                    //recorremos que el objeto response es lo que me esta devolviendo nuestra funcion select cliente de nuestro controlador ingreso controler
                    q: search//Es un parametro del select que indica que permita la búsqueda.
                    me.arrayCliente=respuesta.clientes;//llenamos nuestro objeto arraycliente con lo que tenemos en nuestra variable respuesta en la propiedad clientes
                    loading(false)//
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            
            getDatosCliente(val1){//recibe un parametro que se llama val uno, donde recibo la opcion que es selecionada por el objeto select
                let me = this;//declaramos nuestra variable me, para hacer referencia a esta clase decir "this"
                me.loading = true;//digo que la variable loadin es verdadero
                me.idcliente = val1.id;// el id cliente va ser igual al id que estoy recibiendo en este parametro val uno.
                //en este caso es un objeto que almacena toda la opcion del cliente selecionado en nuestro v-select
            },
            buscarProducto(){
                let me=this;//declaramos nuestra variable me, para hacer referencia a esta clase decir "this"
                //se declara una varia url que contiene dicha url
                var url= '/producto/buscarProductoVenta?filtro=' + me.codigo;//el valor que le damos a filtro es codigo que esta enlazada con el input donde estoy ingresando el codigo de barras para poder filtrar y obtener ese producto que coincide con ese codigo de barras   

                axios.get(url).then(function (response) {//se usa axios para hacer una peticion ajax, a la siguiente url
                    var respuesta= response.data;//le indico a la variable respuesta que es igual a response.data
                    me.arrayProducto = respuesta.productos;//lleno mi array producto todos los productos que se encuentran en response
                    //Estructura condicional, determinando si existe elementos en el array
                    if (me.arrayProducto.length>0){//valido que si el largo de mi array es mayor que cero, encontre un registro
                    //si lo encuentro
                        me.producto=me.arrayProducto[0]['nombre'];//nuestra varibale producto es igual al arrayproducto, en este caso en el indice cero nombre
                        me.idproducto=me.arrayProducto[0]['id'];//y en la variable id producto vamos almacenar lo que tenemos arrayproducto en este caso en el indice cero id
                        me.precio=me.arrayProducto[0]['precio_venta'];//en la variable precio_venta vamos almacenar lo que tenemos arrayproducto en este caso en el indice cero id
                        me.stock=me.arrayProducto[0]['stock'];//en la variable stock vamos almacenar lo que tenemos arrayproducto en este caso en el indice cero id
                        me.peso=me.arrayProducto[0]['peso'];//en la variable peso vamos almacenar lo que tenemos arrayproducto en este caso en el indice cero id
                        
                    }
                    else{//sino lo encuentro, el largo del array es menor que cero
                        me.producto='No existe el producto';//en la variable producto no existe
                        me.idproducto=0;//y en la variable id es igual a cero
                    }
                })
                .catch(function (error) {//capturador de excepciones
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
            encuentra(id){//parametro el id del producto
                var sw=0;//sw es una variable con valor de cero
                for(var i=0;i<this.arrayDetalle.length;i++){//bucle que se incia en cero y recorrera todos los indices del detalle y mas mas por que se actualizara de uno en uno, el contador del bucle
                    if(this.arrayDetalle[i].idproducto==id){//si del arraydetalle en el indice i que es el contador del bucle en la propiedad idproducto es igual al id que se recibe como parametro
                        sw=true;//si coincide el valor de la varibale es verdadero o 1
                    }
                }
                return sw;//retorno la variable
            },
            eliminarDetalle(index){//se recibe el indice del detalle que queremos elminar
                let me = this;//declaramos nuestra variable me, para hacer referencia a esta clase decir "this"
                me.arrayDetalle.splice(index, 1);//de nuestro arraydetalle usando el metodo splice voy a eliminar el objeto que tenga el indice en este caso index, y voy a eliminar 1 solo objeto 
            },
            agregarDetalle(){
                let me=this;//declaramos nuestra variable me, para hacer referencia a esta clase decir "this" //Declaro la varia me que es igual this
                if(me.idproducto==0 || me.cantidad==0 || me.precio==0) {//Estructura condicional
                    //si de mi propiedad data el producto es igual a cero o la cantidad de mi propiedad es igual a 0 o el precio es igual a cero
                }
                else{//s
                    if(me.encuentra(me.idproducto)){//metodo encuentra va a revisar si el producto se encuentra agregado en el detalle y recibi un parametro que es idproducto
                        //verifica si se encuentra agregado en el detalle o no, si lo es muestre el mensaje
                        swal({
                            type: 'error',
                            title: 'Error...',
                            text: 'Ese producto ya se encuentra agregado!',
                            })
                    }
                    else{//sino 
                       if(me.cantidad>me.stock){//si la cantidad es mayor que el stock muestre un mensaje de error
                        //verifica si hay stock o no, si lo es muestre el mensaje
                        swal({
                            type: 'error',
                            title: 'Error...',
                            text: 'No hay stock disponible!',
                            })
                       }
                       else{// si no se encuentra, que me lo agregue al detalle
                                me.arrayDetalle.push({//se declara arrayDetalle. push, para agregar valores al array
                                idproducto: me.idproducto,// en el indice idproducto del array voy agregar lo que tengo en la variable idproducto en la propiedad data, que esta enlazada con el input donde estoy obteniendo la llave primaria del producto que estoy buscando por su codigo de barras
                                producto: me.producto,//en el indice producto de nuestro array, voy almacenar lo que tengo en la variable producto de la propiedad data
                                cantidad: me.cantidad,//lo mismo
                                precio: me.precio,// lo mismo
                                descuento: me.descuento,
                                stock: me.stock,
                                peso: me.peso//aqui
                            }); 
                            me.codigo="";
                            me.idproducto=0;
                            me.producto="";
                            me.cantidad=0;
                            me.precio=0; 
                            me.descuento=0; 
                            me.stock=0;
                            me.peso='';//aqui
                        }
                    }
                    
                }

            },
            agregarDetalleModal(data=[]){//delcaramos el parametro data y se inicializa en un array
                let me=this;//declaramos nuestra variable me, para hacer referencia a esta clase decir "this"//Declaro la varia me que es igual this
                if(me.encuentra(data['id'])){//hago referencia al metodo me encuentra, en este caso desde nuestro objeto data el indice id le enviamos
                        swal({// si lo encuentra mostrata el mensaje de agregado 
                            type: 'error',
                            title: 'Error...',
                            text: 'Ese producto ya se encuentra agregado!',
                            })
                    }
                    else{//si no mediante el metodo push agregamos ese objeto al arrayDetalle
                       me.arrayDetalle.push({///mediante el metodo push agregaremos el objeto al array detalle
                            idproducto: data['id'],//al idproducto le enviaremos lo que tenemos en el data en su propiedad id 
                            producto: data['nombre'],//enviamos el nombre
                            cantidad: 1,//en la cantidad por defecto le enviamos
                            precio: data['precio_venta'],//le enviamos el precio que tenemos en el objeto data en el indice precio venta
                            descuento:0,//el descuento por defecto tendra 0
                            stock: data['stock'],//le agrego un valor al stock que va ser igual al objeto data, en el indice stock
                            peso: data['peso']
                        }); 
                    }
            },
            listarProducto (buscar,criterio){
                let me=this;//declaramos nuestra variable me, para hacer referencia a esta clase decir "this"
                //la cadena de texto la concateno con la siguiente cadena, con el parametro buscar que mediante get a nuestro controlador es igual a la varibale buscar 
                //de igual manera concatenamos con el parametro criterio para enviarlo a travez del metodo get y este parametro va ser igual a lo que tenemos en nuestro criterio
                var url= '/producto/listarProductoVenta?buscar='+ buscar + '&criterio='+ criterio;
                //axios.get('/categoria'.then(function (response) { recibimos los parametros desde la ruta categorias
                axios.get(url).then(function (response) { //mediante el metodo de axios obtengo todos los registros de la tabla categorias
                    var respuesta= response.data;
                    me.arrayProducto = respuesta.productos.data;// almacene todos el contenido del objeto response
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            registrarVenta(){
                //realizamos la verificacion con una estructura condicioanl
                //si el metodo validar Venta devuelve un 1 sifnifica que tengo un error simplemente ejecutamos un return
                if (this.validarVenta()){
                    return;
                }//declaramos nuestra variable me, para hacer referencia a esta clase decir "this"
                let me = this;//declaramos la varibale para hacer refencia a este mismo archivo
                //utilizaremos el verbo axios.post 
                // le vamos a enviar dos parametros primero la ruta
                // y como segundo vamos enviar los valores del controlador
                //donde los recibe el metodo store
               // metodo axios que recibe dos parametros primero la ruta registrar en la tabla categorias de la base de datos
               // y se le envia en la siguiente data, nombre y descripcion
                axios.post('/venta/registrar',{//mediante axios hacemos referencia a la url Venta registrar
                    'idcliente': this.idcliente,//se le enviaran los siguientes valores
                    //'descripcion': this.descripcion,//al idcliente le enviaremos lo que tenemos en la variable idcliente
                    'tipo_comprobante': this.tipo_comprobante,
                    'serie_comprobante' : this.serie_comprobante,
                    //'num_comprobante' : this.num_comprobante,
                    'impuesto' : this.impuesto,
                    'total' : this.total,
                    'data': this.arrayDetalle//en el parametro data lo que tenemos en el arrayDetalle 

                }).then(response => {
                    me.listado=1;//le indico que el listado sea 1, es decir que me muestre el listado
                    me.listarVenta(1,'','serie_comprobante');//llamamos al metodo listarVenta, le enviamos como criterio de busqueda lo que tenemos en la varibale num_comprobante
                    me.idcliente=0;//al cliente se inicializa en cero
                    //me.descripcion='';
                    me.tipo_comprobante='BOLETA';//indico que me de por defecto Boleta como tipo de comprobante
                    me.serie_comprobante='';//la serie la dejare vacio
                    //me.num_comprobante='';//la serie la dejare vacio
                    me.impuesto='';// el valor por defecto de impuesto es de 13
                    me.total=0.0;// el total en cero
                    me.idproducto=0;//el idproducto de igual manera en cero
                    me.producto='';//la variable producto la inicializare vacia
                    me.cantidad=0;// el cantidad en cero
                    me.precio=0;// el precio en cero
                    me.stock=0;
                    me.codigo='';
                    me.peso='';
                    me.descuento=0;
                    me.arrayDetalle=[];//le indico a mi array que se inicialice
                    //hacemos refrencia ala url mas el id que seria el id venta que vamos a imprimir
                    window.open('http://127.0.0.1:8000/venta/pdf/'+ response.data.id + ',' + '_blank');//Concatenamos con el objeto response punto data punto id, ese id dia lo envio desde el controlador
                    //y asi nos imprima la venta
                }).catch(error => {
                    console.log(error);
                    swal(
                        'Duplicado!!!',
                        'Ya existe un numero de serie igual',
                        'error'
                    )
                });
            },
            validarVenta(){
                //Me es igual a this, me=this;
                //Eso porque dentro de la estructura de validación ya no hará referencia a la clase como this sino a la estructura, 
                //por eso me hace referencia a la clase
                let me=this;//declaramos nuestra variable me, para hacer referencia a esta clase decir "this"
                me.errorVenta=0;//inicializamos la variable errorVenta en cero
                me.errorMostrarMsjVenta =[];//inicializamos la variable errorMostrarMsjVenta en vacio
                var pro;//declarao la varibale haciendo referencia a producto
                me.arrayDetalle.map(function(x){//se recorre el arraydetalle usandp map y los valores se guardaran en el objeto x
                    if(x.cantidad>x.stock){//se valida con una estructura condicional, que dice si la cantidad de venta es mayor que el stock
                        pro=x.producto + "Stock insuficiente"; //le indico que mi variable producto es igual al nombre del producto que lo tengo en el objeto x de la propiedad producto y lo concateno con el texto "stock insufiente"
                        //arriba, de esa manera muestro el nombre del producto y que tiene insufience stock
                        me.errorMostrarMsjVenta.push(pro);//agrego el error al arrayerrorMostrarMsjVenta y le indico el error que tengo en la variable pro
                    }
                });
                //validamos si el nombre esta vacio, entonces de esa clase la variable error usando su metodo push le agregamos el sigiente mensaje 
                if (me.idcliente==0) me.errorMostrarMsjVenta.push("Seleccione un Cliente");////si el idcliente es igual a 0 significa que no he selecionado ningun cliente en el objeto select, entocnes se agrega el array errorMostrarMsjVenta, selecione un cliente
                if (me.tipo_comprobante==0) me.errorMostrarMsjVenta.push("Seleccione el comprobante");//si el tipo_comprobante es igual a 0 significa que no he selecionado ningun tipo_comprobante , entocnes se agrega el array errorMostrarMsjVenta, selecione un tipo_comprobante
                if (!me.serie_comprobante) me.errorMostrarMsjVenta.push("Ingrese el número de serie");//si el num_comprobante esta vacio el error que le voy a dar es, errorMostrarMsjVenta, selecione un num_comprobante
                if (!me.impuesto) me.errorMostrarMsjVenta.push("Ingrese el impuesto de compra");//si el impuesto esta vacio, entocnes se agrega el array errorMostrarMsjVenta, selecione un impuesto de compra
                if (me.arrayDetalle.length<=0) me.errorMostrarMsjVenta.push("Ingrese detalles");//si el arrayDetalle no tenemos ningun objeto, entonces nos debe mostrar el errorMostrarMsjIngreso, selecione un Ingrese detalles

                //si tengo un error evaluamos nuestro array y nuestra variable error venta va a pasar 1 cuando tenga al menos 1 error.
                if (me.errorMostrarMsjVenta.length) me.errorVenta = 1;//si mi variable errorVenta es 1, queire decir que hay un error

                return me.errorVenta;
            },
            mostrarDetalle(){//cuando llame a este metodo le indico que el valor de la variable listado es igual a 0
                this.listado=0;
                let me=this;//declaramos nuestra variable me, para hacer referencia a esta clase decir "this"
                me.idcliente=0;
               // me.descripcion='';
                me.tipo_comprobante='BOLETA';
                me.serie_comprobante='';
                //me.num_comprobante='';
                me.impuesto='';
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
            },
            cerrarModal(){//cuando llamamos el metodo cerrar model
                this.modal=0;//la variable modal pasa hacer 0
                this.tituloModal='';//Y el titulo en blanco 
                
            },
            abrirModal(){
                this.arrayProducto=[];//le indico ami arrayProducto me muestre los datos vacios
                this.modal = 1;//la variable modal pasa hacer 1
                this.tituloModal = 'Seleccione uno o varios Productos';//
            },
            desactivarVenta(id){//este esperando un parametro que es el id de la venta que va a eliminar
               swal({
                title: 'Esta seguro de anular este Venta?',
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

                    axios.put('/venta/desactivar',{
                        'id': id
                    }).then(function (response) {
                        me.listarVenta(1,'','serie_comprobante');
                        swal(
                        'Anulado!',
                        'La venta ha sido anulado con éxito.',
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