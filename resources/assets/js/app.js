
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 "bootstrap": "^4.0.0" esto va en package.json en lugar del sas*/

//Vue.component('example-component', require('./components/ExampleComponent.vue'));
Vue.component('categoria', require('./components/administrador/Categoria.vue'));
Vue.component('cliente', require('./components/administrador/Cliente.vue'));
Vue.component('proveedor', require('./components/administrador/Proveedor.vue'));
Vue.component('rol', require('./components/administrador/Rol.vue'));
Vue.component('user', require('./components/administrador/User.vue'));
Vue.component('perfil', require('./components/administrador/Perfil.vue'));
Vue.component('dashboard', require('./components/administrador/Dashboard.vue'));
Vue.component('persona', require('./components/administrador/Persona.vue'));
Vue.component('producto', require('./components/administrador/Producto.vue'));
Vue.component('materia', require('./components/administrador/Materia.vue'));
Vue.component('ingreso', require('./components/administrador/Ingreso.vue'));
Vue.component('venta', require('./components/administrador/Venta.vue'));

Vue.component('consultaingreso', require('./components/consultas/ConsultaIngreso.vue'));
Vue.component('consultaventa', require('./components/consultas/ConsultaVenta.vue'));
Vue.component('consultaproducto', require('./components/consultas/ConsultaProducto.vue'));
Vue.component('consultaproveedor', require('./components/consultas/ConsultaProveedor.vue'));
Vue.component('consultacategoria', require('./components/consultas/ConsultasCategoria.vue'));
Vue.component('consultamateria', require('./components/consultas/ConsultaMateria.vue'));
Vue.component('consultapersona', require('./components/consultas/ConsultaPersona.vue'));
Vue.component('consultacliente', require('./components/consultas/ConsultaCliente.vue'));

//Vue.component('articulo', require('./components/administrador/Articulo.vue'));
//Vue.component('articulo', require('./components/administrador/articulo/Articulo.vue'));
//Vue.component('empleado', require('./components/administrador/Empleado.vue'));
//Vue.component('consultaarticulo', require('./components/administrador/ConsultaArticulo.vue'));

//Vue.component('categoriae', require('./components/empleado/CategoriaE.vue'));
//Vue.component('articuloe', require('./components/empleado/ArticuloE.vue'));
//Vue.component('clientee', require('./components/empleado/ClienteE.vue'));
//Vue.component('proveedore', require('./components/empleado/ProveedorE.vue'));
//Vue.component('productoe', require('./components/empleado/ProductoE.vue'));

const app = new Vue({
    el: '#app',
    data :{
        menu : 0,
    },
});