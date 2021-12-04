<template>
            <main class="main">
            <!-- Breadcrumb <li class="breadcrumb-item"><a href="/">Escritorio</a></li>-->
            <ol class="breadcrumb">
                <li class="breadcrumb-item"> <i class="fa fa-user" aria-hidden="true"></i> <strong>Roles</strong></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Roles
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                      <option value="nombre">Nombre</option>
                                      <option value="descripcion">Descripción</option>
                                    </select><!-- //cuando se enter vamos a llamar nuestro metodo -->
                                    <input type="text" v-model="buscar" @keyup.enter="listarRol(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarRol(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button> 
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="rol in arrayRol" :key="rol.id">       
                                    <td v-text="rol.nombre"></td><!-- esto nos mostrara el nombre de la rol -->
                                    <td v-text="rol.descripcion"></td>
                                    <td>
                                        <div v-if="rol.condicion"><!--  si la condicion es igual 1-->
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
        </main>
</template>

<script>
    export default {
        data (){
            return {
                rol_id: 0,
                nombre : '',
                descripcion : '',
                arrayRol : [],
                modal : 0,
                tituloModal :  '',
                tipoAccion : 0,
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
            listarRol (page,buscar,criterio){
                let me=this;//la cadena de texto la concateno con la siguiente cadena, con el parametro buscar que mediante get a nuestro controlador es igual a la varibale buscar 
                //de igual manera concatenamos con el oarametro criterio para enviarlo a travez del metodo get y este parametro va ser igual a lo que tenemos en nuestro criterio
                var url= '/rol?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                //axios.get('/categoria'.then(function (response) { recibimos los parametros desde la ruta categorias
                axios.get(url).then(function (response) { //mediante el metodo de axios obtengo todos los registros de la tabla categorias
                    var respuesta= response.data;
                    me.arrayRol = respuesta.roles.data;// almacene todos el contenido del objeto response
                    me.pagination= respuesta.pagination;
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
                me.listarRol(page,buscar,criterio);// le envia valores al metodo
            }
        },
        mounted() {
            this.listarRol(1,this.buscar,this.criterio);
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