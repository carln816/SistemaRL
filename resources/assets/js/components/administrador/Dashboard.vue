<template>
<main class="main">
    <!-- Breadcrumb -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/"><br></a></li>
    </ol>
    <div class="container-fluid"><!-- Breadcrumb -->
        <div class="card">
            <div class="card-header">
                
            </div>
            <div class="car-body">
                <div class="row">
                    <div class="col-md-6"><!-- Se agrega una columna que ocupa 6 espacios -->
                        <div class="card card-chart"><!--div para el grafico con la clase card card -chart -->
                            <div class="card-header">
                                <h4>Ingresos</h4><!-- dentro de la clase header se agrega un titulo -->
                            </div>
                            <div class="card-content"><!--div para el grafico con la clase card-content -->
                                <div class="ct-chart"><!-- dentro de la clase content agrego otro div con la etiqueta canvas que tiene como identificador ingresos -->
                                    <canvas id="ingresos"><!--Con la etiqueta canvas se representa el grafico estadistico de los ingresos en este caso de los ultimos meses -->                                              
                                    </canvas>
                                </div>
                            </div>
                            <div class="card-footer"><!--div para el grafico con la clasecard-footer -->
                                <p>Compras de los últimos meses.</p><!--Aqui solo muestro un texto  -->
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6"><!-- Se agrega una columna que ocupa 6 espacios -->
                        <div class="card card-chart"><!--div para el grafico con la clase card card -chart -->
                            <div class="card-header">
                                <h4>Ventas</h4><!-- dentro de la clase header se agrega un titulo -->
                            </div>
                            <div class="card-content"><!--div para el grafico con la clase card-content -->
                                <div class="ct-chart"><!-- dentro de la clase content agrego otro div con la etiqueta canvas que tiene como identificador ventas -->
                                    <canvas id="ventas"><!--Con la etiqueta canvas se representa el grafico estadistico de las ventas en este caso de los ultimos meses -->                                               
                                    </canvas>
                                </div>
                            </div>
                            <div class="card-footer"><!--div para el grafico con la clasecard-footer -->
                                <p>Ventas de los últimos meses.</p><!--Aqui solo muestro un texto  -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</main>
</template>
<script>
    export default {
        data (){
            return {
                varIngreso:null,//Declaro la variable varIngreso donde voy almacenar el valor del id donde vamos a mostrar el grafico
                charIngreso:null,//Variable que crea el grafico char, alimentado por valores vinculados con el objeto canvas 
                ingresos:[],//Es un arreglo de datos que contendra el listado de ingresos con la informacion necesaria 
                varTotalIngreso:[],//Se declara el arreglo donde se almacenaran los datos del total de los ingresos de cada mes
                varMesIngreso:[],//Y el arreglo mesIngreso, almacenara los nombres de los meses que vamos a  mostrar en el grafico
                
                varVenta:null,
                charVenta:null,
                ventas:[],
                varTotalVenta:[],
                varMesVenta:[],
            }
        },
        methods : {
            getIngresos(){
                let me=this;
                var url= '/dashboard';//declaro la variable url que sera la url donde voy a obtener los datos, en este caso dashboard 
                axios.get(url).then(function (response) {//mediante axios hago una peticion ajax a la url, que esta apuntando a nuestra funcion invoke de nuestro controlador dashboard
                    var respuesta= response.data;
                    me.ingresos = respuesta.ingresos;//es decir de nuestro dashboard controller tengo que retornar un parametro ingresos la lista de todos los ingresos
                    //una vez que se obtiene todos los ingresos y los almacenamos en nuestro arreglo llamado ingresos que hempos declarado en la funcion data 
                    //cargamos los datos del chart
                    me.loadIngresos();//se llama a una nueva funcion llamada loadIngresos.
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getVentas(){
                let me=this;
                var url= '/dashboard';
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.ventas = respuesta.ventas;
                    //cargamos los datos del chart
                    me.loadVentas();
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            loadIngresos(){
                let me=this;
                let meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"]; 
                me.ingresos.map(function(x){
                    //me.varMesProducto.push(x.mes);
                    me.varMesIngreso.push(meses[x.mes-1]);
                    me.varTotalIngreso.push(x.total);
                

                /*let me=this;
                me.ingresos.map(function(x){//recorro todo el arreglo ingresos usando el metodo map 
                    me.varMesIngreso.push(x.mes);//Al recorrer el arreglo va almacenando en el arreglo varMesIngreso de la 
                    //propiedad data lo que tenemos en el arreglo ingresos  en su propiedad mes
                    me.varTotalIngreso.push(x.total);//Igualmente en el arreglo varTotalIngreso voy almacenado todos los totales
                    //que tengo en el arreglo ingresos*/
                });
                //Hago referencia  a la variable varIngreso que establece el lugar donde se va a mostrar en este caso el grafico 
                //estadistico de ingresos
                //Usando document document.getElementById le indico que el id del elemento que voy a mostrar el grafico de ingresos
                //se llama ingresos
                me.varIngreso=document.getElementById('ingresos').getContext('2d');
            
                me.charIngreso = new Chart(me.varIngreso, {//aqui es lo que tenemos en la variable charingreso y luego representamos 
                //me.varIngreso que hace referencia al canvas con nuestro id ingresos 
                    type: 'bar',
                    data: {
                        labels: me.varMesIngreso,//se muestra el arreglo de las etiquetas, que esta almacenado en la variable me.varMesIngreso
                        datasets: [{
                            label: 'Ingresos',//se muestra el texto ingresos
                            data: me.varTotalIngreso,//se muestra el arreglo con todos los totales correspondientes a cada uno de los meses
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 0.2)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero:true
                                }
                            }]
                        }
                    }
                });
            },
            loadVentas(){
                let me=this;
                let meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"]; 
                me.ventas.map(function(x){//recorro todo el arreglo ventas usando el metodo map 
                me.varMesVenta.push(meses[x.mes-1]);
                    //me.varMesVenta.push(x.mes);//Al recorrer el arreglo va almacenando en el arreglo varMesVentas de la 
                    //propiedad data lo que tenemos en el arreglo ventas  en su propiedad mes
                    me.varTotalVenta.push(x.total);//Igualmente en el arreglo varTotalVentas voy almacenado todos los totales
                    //que tengo en el arreglo ventas
                });
                //Hago referencia  a la variable varVentas que establece el lugar donde se va a mostrar en este caso el grafico 
                //estadistico de ventas
                //Usando document document.getElementById le indico que el id del elemento que voy a mostrar el grafico de ventas
                //se llama ventas
                me.varVenta=document.getElementById('ventas').getContext('2d');
            
                me.charVenta = new Chart(me.varVenta, {//aqui es lo que tenemos en la variable charVentas y luego representamos 
                //me.varVentas que hace referencia al canvas con nuestro id Ventas
                    type: 'bar',
                    data: {
                        labels: me.varMesVenta,//se muestra el arreglo de las etiquetas, que esta almacenado en la variable me.varMesVenta
                        datasets: [{
                            label: 'Ventas',//se muestra el texto Ventas
                            data: me.varTotalVenta,//se muestra el arreglo con todos los totales correspondientes a cada uno de los meses
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 0.2)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero:true
                                }
                            }]
                        }
                    }
                });
            }
        },
        mounted() {
            this.getIngresos();
            this.getVentas();
        }
    }
</script>
