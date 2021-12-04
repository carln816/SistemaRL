<!DOCTYPE>
<html>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de Ingreso</title>
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
        margin-left: 60%;
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

        #facarticulo{
        width: 100%;
        text-align: center;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 15px;
        }

        #facarticulo thead{
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
        @foreach ($ingreso as $i)
        <header>
        <div id="logo">
                <img src="img/logonuevo.png" alt="RL" id="imagen">
            </div>
        <br>
        <br>
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
                <p>{{$i->tipo_comprobante}}<br>
                {{$i->serie_comprobante}}</p>
            </div>
        </header>
        <br>
        <section>
            <div>
                <table id="facliente">
                    <thead>                        
                        <tr>
                            <th id="fac">Proveedor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th><p id="cliente">Sr(a). {{$i->nombre}}<br>
                            {{$i->tipo_documento}}: {{$i->num_documento}}<br>
                            Dirección: {{$i->direccion}}<br>
                            Teléfono: {{$i->telefono}}<br>
                            Email: {{$i->email}}</</p></th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
        @endforeach
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
                            <td>{{$i->usuario}}</td>
                            <td>{{$i->created_at}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
        <br>
        <section>
            <div>
                <table id="facarticulo">
                    <thead>
                        <tr id="fa">
                            <th>CANT</th>
                            <th>DESCRIPCION</th>
                            <th>PRECIO UNIT</th>
                            <th>PRECIO TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($detalles as $det)
                        <tr>
                            <td>{{$det->cantidad}}</td>
                            <td>{{$det->materia}}</td>
                            <td>{{$det->precio}}</td>
                            <td>{{$det->cantidad*$det->precio}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        @foreach ($ingreso as $i)
                        <tr>
                            <th></th>
                            <th></th>
                            <th>SUBTOTAL</th>
                            <td>&cent; {{$i->total-($i->total*$i->impuesto)}}</td>
                        </tr>
                        <tr>
                            <th></th>
                            <th></th>
                            <th>Impuesto</th>
                            <td>&cent; {{$i->total*$i->impuesto}}</td>
                        </tr>
                        <tr>
                            <th></th>
                            <th></th>
                            <th>TOTAL</th>
                            <td>&cent; {{$i->total,2}}</td>
                        </tr>
                        @endforeach
                    </tfoot>
                </table>
            </div>
        </section>
        <br>
        <br>
        <footer>
            <div id="gracias">
                <p><b>Este comprobante de ingreso almacén ha sido generado por el sistema, no es un comprobante emitipo por el proveedor.</b></p>
            </div>
        </footer>
    </body>
</html>