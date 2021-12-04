<!DOCTYPE>
<html>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de venta</title>
    <style>
        body {
        /*position: relative;*/
        /*width: 16cm;  */
        /*height: 29.7cm; */
        /*margin: 0 auto; */
        /*color: #555555;*/
        /*background: #FFFFFF; */
        font-family: Arial, sans-serif; 
        font-size: 14px;
        /*font-family: SourceSansPro;*/
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
        margin-left: 60;
        margin-right: 15%;
        font-size: 15px;
        }

        #fact{
        /*position: relative;*/
        float: right;
        margin-top: 2%;
        margin-left: 2%;
        margin-right: 2%;
        font-size: 20px;
        }

        section{
        clear: left;
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

        #facliente thead{
        padding: 20px;
        background: #2183E3;
        text-align: left;
        border-bottom: 1px solid #FFFFFF;  
        }

        #facvendedor{
        text-align: center;
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 15px;
        }

        #facvendedor thead{
        padding: 20px;
        background: #2183E3;
        text-align: center;
        border-bottom: 1px solid #FFFFFF;  
        }

        #facproducto{
        width: 100%;
        text-align: center;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 15px;
        }

        #facproducto thead{
        padding: 20px;
        background: #2183E3;
        text-align: center;
        border-bottom: 1px solid #FFFFFF;  
        }

        #gracias{
        text-align: center; 
        }
    </style>
    <body>
        
        <header>
        <div id="logo">
                <img src="img/logonuevo.png" alt="RL" id="imagen">
            </div>
        <br>
        <br>
        @foreach ($venta as $v)<!-- metodo foreach que indica que recorremos el paramtro que recibimos  desde el controlador venta en el objeto v -->
           <!-- <div id="datos">
                <p id="encabezado">
                <b>Cooperativa Coopelácteos RL</b><br>San Fernando, Upala<br>Telefono : (+506)86300701<br>Telefono : 24601052<br>Email:cooperl@gmail.com</p>
            </div>-->
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
            <div id="fact">
                <p>{{$v->tipo_comprobante}}<br><!-- indicamos el tipo del comprobante -->
                {{$v->serie_comprobante}}</p><!-- indicamos la serie comprobante y el numero de comprobante -->
            </div>
        </header>
        <br>
        <section>
            <div>
                <table id="facliente">
                    <thead>                        
                        <tr>
                            <th id="fac">Cliente</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                        <th><p id="cliente">Sr(a). {{$v->nombre}}<br>
                            {{$v->tipo_documento}}: {{$v->num_documento}}<br>
                            Dirección: {{$v->direccion}}<br>
                            Teléfono: {{$v->telefono}}<br>
                            Email: {{$v->email}}</</p></th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
        <br>
        <section>
            <div>
                <table id="facvendedor">
                    <thead>
                        <tr id="fv">
                            <th>USUARIO</th>
                            <th>FECHA</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                        <td>{{$v->usuario}}</td>
                        <td>{{$v->created_at}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
        @endforeach
        <br>
        <section>
            <div>
                <table id="facproducto">
                    <thead>
                        <tr id="fa">
                            <th>CANT</th>
                            <th>DESCRIPCION</th>
                            <th>PRECIO UNIT</th>
                            <th>PESO</th>
                            <th>DESC.</th>
                            <th>PRECIO TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($detalles as $det)<!-- metodo foreach que indica que recorremos el paramtro que recibimos  desde el controlador venta en el objeto det -->
                        <tr>
                            <td align="right" >{{$det->cantidad}}</td>
                            <td>{{$det->producto}}</td>
                            <td>{{$det->peso}}</td>
                            <td>{{$det->precio}}</td>
                            <td>{{$det->descuento}}</td>
                            <td align="right">{{$det->cantidad*$det->precio-$det->descuento}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                    <br>
                    <tbody>
                    @foreach ($venta as $v)<!-- metodo foreach que indica que recorremos el paramtro que recibimos  desde el controlador venta en el objeto det -->
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>SUBTOTAL</th>
                            <td align="right">&cent; {{round($v->total-($v->total*$v->impuesto),2)}}</td>
                        </tr>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>Impuesto</th>
                            <td align="right"> &cent; {{round($v->total*$v->impuesto,2)}}</td><!-- se usa el metodo de php round, todo ese valor lo redondeo para obtener solamente 2 decimales-->
                        </tr>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>TOTAL</th>
                            <td align="right"> &cent;{{$v->total}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </section>
        <br>
        <br>
        <footer>
            <div id="gracias">
                <p><b>Gracias por su compra!</b></p>
            </div>
        </footer>
    </body>
</html>