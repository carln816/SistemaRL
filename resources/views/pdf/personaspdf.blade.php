<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de proveedores</title>
    <style>
        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            font-size: 0.875rem;
            font-weight: normal;
            line-height: 1.5;
            color: #151b1e;           
        }

        #logo{
        /*float: left;
        margin-top: 1%;
        margin-left: 2%;
        margin-right: 2%;*/

        float: right;
        margin-top: 4%;
        margin-left: 15%;
        margin-right: 0%;
        font-size: 20px;
        }

        #imagen{
        width: 300px;
        }

        #datos{
        float: center;
        margin-top: 0%;
        margin-left: 2%;
        margin-right: 2%;
        /*text-align: justify;*/
        }

        #encabezado{
        text-align: center;
        margin-left: 10%;
        margin-right: 15%;
        font-size: 15px;
        }

        #cliente{
        text-align: left;
        }

        #facliente{
        width: 40%;
        text-align: center;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 15px;
        }

        #fac, #fv, #fa{
        color: #FFFFFF;
        font-size: 15px;
        }

        .table {
            display: table;
            width: 100%;
            max-width: 100%;
            margin-bottom: 1rem;
            background-color: transparent;
            border-collapse: collapse;
        }
        .table-bordered {
            border: 2px solid #151b1e;/*border: 1px solid #c2cfd6;*/ 
        }
        thead {
            display: table-header-group;
            vertical-align: middle;
            border-color: inherit;
        }
        tr {
            display: table-row;
            vertical-align: inherit;
            border-color: inherit;
            
        }
        .table th, .table td {
            padding: 0.75rem;
            vertical-align: top;
            border-top: 2px solid #151b1e;
        }
        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #151b1e;
        }
        .table-bordered thead th, .table-bordered thead td {
            border-bottom-width: 2px;
            
        }
        .table-bordered th, .table-bordered td {
            border: 2px solid #151b1e;
        }
        th, td {
            display: table-cell;
            vertical-align: inherit;
            
        }
        th {
            font-weight: bold;
            text-align: -internal-center;
            text-align: left;
            
        }
        tbody {
            display: table-row-group;
            vertical-align: middle;
            border-color: inherit;
            
        }
        tr {
            display: table-row;
            vertical-align: inherit;
            border-color: inherit;
            
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: transparent;/*background-color: rgba(0, 0, 0, 0.05); 
            /// background-color:rgba(192,192,192,0.3); */
            
        }
        .izquierda{
            float:left;
        }
        .derecha{
            float:right;
        }
    </style>
</head>

    <body>
    <span class="derecha">{{now()}}</span>

    <br>
    <br>
        <header>
            <div id="logo">
                <img src="img/logonuevo.png" alt="RL" id="imagen">
            </div>
        
        <table id="facliente">
                    <tbody>
                        <tr>
                        <th><p id="cliente">COOPERATIVA DE PRODUCCIÓN, INDUSTRIALIZACIÓN Y<br>
                        COMERCIALIZACIÓN DE LÁCTEOS Y SERVICIOS MÚLTIPLES <br>
                        DE LA ZONA NORTE-NORTE, R.L.<br>
                        Cédula Jurídica: 3-004-662371<br>
                        Fundada el 29 de octubre de 2011<br>
                        Upala, Alajuela, Costa Rica.<br>
                        Tel.: 2470-3000<br>
                        Correo: coopelacteos@gmail.com</</p></th>
                        </tr>
                    </tbody>
                </table>
        </header> 
        <div>
            <h3>Lista de Personas </h3><!-- metodo now para mostrar la fecha actual -->
        </div>      
        <br>     
        <section>
            <div>
                <table class="table table-bordered table-striped table-sm">

                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Número</th>
                            <th>Dirección</th>
                            <th>Teléfono</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($personas as $a)<!-- bucle del arreglo para alamcenar en el objeto a -->
                        <tr>
                            <td>{{$a->nombre}}</td>
                            <td>{{$a->num_documento}}</td>
                            <td>{{$a->direccion}}</td>
                            <td>{{$a->telefono}}</td>
                            <td>{{$a->email}}</td>
                        </tr>
                        @endforeach                                
                    </tbody>
                </table>
            </div>
        </section>
        <br>
        <footer>
            <div class="izquierda">
               <p><strong>Total de registros: </strong>{{$cont}}</p><!--se muestra el total de registros -->
            </div> 
        </footer>
    </body>
</html>