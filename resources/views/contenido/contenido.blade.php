@extends('principal')
    @section('contenido')

        @if (Auth::check())<!-- si el usuario autenticado tiene el rol 1 significa que es admistrador-->
            @if (Auth::user()->idrol == 1)<!-- como es administrador se le realiza un include a la plantilla sidebaradministrador-->
                @include('plantilla.sidebaradministrador')<!-- muestre todo esto-->
                <template v-if="menu==0">
                    <dashboard></dashboard>
                </template>

                <template v-if="menu==1">
                    <categoria></categoria>
                </template>

                <template v-if="menu==2">
                    <perfil></perfil>
                </template>

                <template v-if="menu==3">
                    <producto></producto>
                </template>

                <template v-if="menu==4">
                    <proveedor></Proveedor>
                </template>

                <template v-if="menu==5">
                    <cliente></cliente>
                </template>

                <template v-if="menu==6">
                    <ingreso></ingreso>
                </template>

                <template v-if="menu==7">
                    <materia></materia>
                </template>

                <template v-if="menu==8">
                    <venta></venta>
                </template>
                
                <template v-if="menu==9">
                    <rol></rol>
                </template>

                <template v-if="menu==10">
                    <user></user>
                </template>

                <template v-if="menu==11">
                   <persona></persona>
                </template>
                

                <template v-if="menu==12">
                    <consultaingreso></consultaingreso>
                </template>

                <template v-if="menu==13">
                    <consultaventa></consultaventa>
                </template>

                <template v-if="menu==14">
                    <consultacategoria></consultacategoria>
                </template>

                <template v-if="menu==15">
                    <consultaproducto></consultaproducto>
                </template>

                <template v-if="menu==16">
                    <consultaproveedor></consultaproveedor>
                </template>

                <template v-if="menu==17">
                    <consultamateria></consultamateria>
                </template>

                <template v-if="menu==18">
                    <consultapersona></consultapersona>
                </template>

                <template v-if="menu==19">
                    <consultacliente></consultacliente>
                </template>

            @elseif (Auth::user()->idrol == 2)<!-- si el usuario autenticado tiene el rol  significa que es empleado-->
                @include('plantilla.sidebarempleado')<!-- muestre el sidebarEmpleado-->
                <template v-if="menu==0">
                    <dashboard></dashboard>
                </template>

                <template v-if="menu==2">
                    <perfil></perfil>
                </template>

                <template v-if="menu==1">
                    <categoria></categoria>
                </template>

                <template v-if="menu==3">
                    <producto></producto>
                </template>

                <template v-if="menu==4">
                    <proveedor></proveedor>
                </template>

                <template v-if="menu==5">
                    <cliente></cliente>
                </template>

                <template v-if="menu==14">
                    <consultacategoria></consultacategoria>
                </template>

                <template v-if="menu==15">
                    <consultaproducto></consultaproducto>
                </template>

                <template v-if="menu==16">
                    <consultaproveedor></consultaproveedor>
                </template>

                <template v-if="menu==19">
                    <consultacliente></consultacliente>
                </template>

                @else

                @endif
        @endif
        
    @endsection