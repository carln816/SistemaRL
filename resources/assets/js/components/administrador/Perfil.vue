<template>
  
  <main class="main">
    <br />
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card mb-3">
          <div class="card-body">
            <div class="row">
              <div class="col-sm-3">
                <h6 class="mb-0 text-primary">Detalles Personales</h6>
              </div>
            </div>
            <hr />
            <div class="row">
              <div class="col-sm-3">
                <h6 class="mb-0">Nombre</h6>
              </div>
              <div class="col-sm-9 text-secondary">
                <h6>
                  <span class="label label-default" v-text="nombre"></span>
                </h6>
              </div>
            </div>
            <hr />
            <div class="row">
              <div class="col-sm-3">
                <h6 class="mb-0">Tipo Documento</h6>
              </div>
              <div class="col-sm-9 text-secondary">
                <h6>
                  <span
                    class="label label-default"
                    v-text="tipo_documento"
                  ></span>
                </h6>
              </div>
            </div>
            <hr />
            <div class="row">
              <div class="col-sm-3">
                <h6 class="mb-0">Numero de documento</h6>
              </div>
              <div class="col-sm-9 text-secondary">
                <h6>
                  <span
                    class="label label-default"
                    v-text="num_documento"
                  ></span>
                </h6>
              </div>
            </div>
            <hr />
            <div class="row">
              <div class="col-sm-3">
                <h6 class="mb-0">Direccion</h6>
              </div>
              <div class="col-sm-9 text-secondary">
                <h6>
                  <span class="label label-default" v-text="direccion"></span>
                </h6>
              </div>
            </div>
            <hr />
            <div class="row">
              <div class="col-sm-3">
                <h6 class="mb-0">Telefono</h6>
              </div>
              <div class="col-sm-9 text-secondary">
                <h6>
                  <span class="label label-default" v-text="telefono"></span>
                </h6>
              </div>
            </div>
            <hr />
            <div class="row">
              <div class="col-sm-3">
                <h6 class="mb-0">Email</h6>
              </div>
              <div class="col-sm-9 text-secondary">
                <h6>
                  <span class="label label-default" v-text="email"></span>
                </h6>
              </div>
            </div>
            <hr />
            <div class="row">
              <div class="col-sm-3">
                <h6 class="mb-0">Usuario</h6>
              </div>
              <div class="col-sm-9 text-secondary">
                <h6>
                  <span class="label label-default" v-text="usuario"></span>
                </h6>
              </div>
            </div>
            <hr />
            <div class="row">
              <div class="col-sm-12">
                <!--<a class="btn btn-info" target="__blank" href="#">Edit</a>-->
                <input type="button" class="btn btn-danger" data-toggle="modal" data-target="#cambioPassword" value="Cambio de Contraseña">
              </div>
            </div>
          </div>
        </div>
      </div>
      <!--MODAL-->
  
      <div class="modal fade" id="cambioPassword" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="cambioPassword" aria-hidden="true">
          <div class="modal-dialog modal-primary">
            <div class="modal-content">
              <div class="modal-header d-block">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title text-center"  id="cambioPassword" text="center">Cambio de Contraseña</h4>
              </div>
              <div class="modal-body">
                <form>
                  <div class="form-group">
                    <label for="current_pass"><strong>Contraseña actual</strong></label>
                    <input type="password" class="form-control" id="current_pass" v-model="current_pass"  required >
                  </div>
                  <div class="form-group">
                    <label for="password"><strong>Contraseña Nueva</strong></label>
                    <input type="password" class="form-control" id="password" v-model="password"  required>
                  </div>
                  <div class="form-group">
                    <label for="password_confirmation"><strong>Repita la contraseña</strong></label>
                    <input type="password" class="form-control" id="password_confirmation" v-model="password_confirmation" required>
                  </div>
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-success" @click.prevent="cambioPassword()" data-dismiss="modal">Cambiar</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    

  </main>
</template>

<!--<label for="nombre">Nombres</label>
<h6><span class="label label-default" v-text="nombre"></span></h6>
<hr>
<label for="nombre">Tipo Documento</label>
<h6><span class="label label-default" v-text="tipo_documento"></span></h6>-->   
<script>
export default {
  data() {
    return {
      idpersona: App.idpersona,
      iduser: App.iduser,
      idrol: App.idrol,
      user: 0,
      persona_id: 0,
      nombre: "",
      tipo_documento: "",
      num_documento: "",
      direccion: "",
      telefono: "",
      email: "",
      usuario: "",
      password: "",
      old_pass: '',
      current_pass :'',
      new_pass:'',
      password_confirmation: '',
      idrol: 0,
      arrayPersona: [],
      modal: 0,
      tituloModal: "",
      tipoAccion: 0,
      errorPersona: 0,
      errorMostrarMsjPersona: [],
      offset: 3,
      criterio: "nombre", // para indicar el campo de busqueda
      buscar: "",
      userid: [],
      errors: [],
    };
  },

  methods: {
    getUserId() {
      var url = "/get-user/" + this.idpersona;//llamo  a la url de la ruta y me concatene el idpersona
      axios
        .get(url)//mediante axios le hago un get de la url
        .then((response) => {//donde le paso un then donde este me devuelve los datos que vengan desde el response data el cero es proque un arreglo inicia de esa posicion
          //this.idpersona = response.data;
          this.nombre = response.data[0].nombre;
          this.tipo_documento = response.data[0].tipo_documento;
          this.num_documento = response.data[0].num_documento;
          this.direccion = response.data[0].direccion;
          this.telefono = response.data[0].telefono;
          this.email = response.data[0].email;
          this.usuario = response.data[0].usuario;
          //this.password = response.data[0].password;
          console.log(this.idpersona);
        })
        .catch((error) => {
          this.errors.push(error);
        });
    },

    cambioPassword() {
      var url = "/user/cambioPassword";
      const data = new FormData();//mediante una constante le mado la informacion atrapando todo lo que venga del forulario
      data.append("idpersona", this.idpersona);//el data append es una forma de encapsular los datps del formulario
      data.append("password", this.password);
      data.append("current_pass", this.current_pass);
      data.append("password_confirmation", this.password_confirmation)
      data.append("_method","POST")
        axios.post(url,data).then(response =>{
          console.log(response.data)
          if(response.data.errors === "0"){
            this.limpiar();
            swal(
              'Contraseña Incorrecta!!!',
              'La contraseña no coincide',
              'error'
            )
          }else{
            this.limpiar();
            $("#cambioPassword").modal("hide")
            swal(
                'Cambio exitoso!!!',
                'Has cambiado tu contraseña',
                'success'
              )
            
            this.getUserId();
          }
            
        }).catch(error =>{
          if(error.response.status == 422){
            this.errors = error.response.data.errors;
          }
          this.limpiar();

          if(this.errors.password != null){
            swal(
              'Error',
              'Las contraseñas no coinciden',
              'error'
            )
          }
        })
    },
    limpiar(){
      this.password = "";
      this.current_pass = "";
      this.password_confirmation = "";
    },
  },
  mounted() {
    this.getUserId();
  },
};
/*if(this.new_pass === this.confirmar_pass){
          axios.post(url,data).then(response =>{
          console.log(error.data)
          
          this.limpiar();
          $("#cambioPassword").modal("hide")
          swal(
              'Cambio exitoso!!!',
              'Has cambiado tu contraseña',
              'success'
            )
          this.getUserId();
        })
      }else{
        swal(
              'Errors!!!',
              'Las contraseñas no coinciden',
              'error'
            )
      }*/ 
</script>

<style>

</style>