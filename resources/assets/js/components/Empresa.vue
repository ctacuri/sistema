<template>
  <main class="main">
    <!-- Breadcrumb -->
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="#">Escritorio</a>
      </li>
    </ol>
    <div class="container-fluid">
      <!-- Ejemplo de tabla Listado -->
      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Empresas
        </div>
        <div class="card-body">
          <div class="form-group row">
            <div class="col-md-6">
              <div class="input-group">
                <select class="form-control col-md-3" v-model="criterio">
                  <option value="ruc">Ruc</option>
                  <option value="nombre">nombre</option>
                </select>
                <input
                  type="text"
                  v-model="buscar"
                  @keyup.enter="listarEmpresa(1,buscar,criterio)"
                  class="form-control"
                  placeholder="Texto a buscar"
                >
                <button
                  type="submit"
                  @click="listarEmpresa(1,buscar,criterio)"
                  class="btn btn-primary"
                >
                  <i class="fa fa-search"></i> Buscar
                </button>
              </div>
            </div>
          </div>
          <table class="table table-bordered table-striped table-sm">
            <thead>
              <tr>
                <th>Opciones</th>
                <th>Ruc</th>
                <th>Nombre</th>
                <th>Direccion</th>
                <th>Estado</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="empresa in arrayEmpresa" :key="empresa.id">
                <td>
                  <template v-if="$parent.granted['ACTUALIZAR_EMPRESAS']">
                    <button
                      type="button"
                      @click="abrirModal('empresa','actualizar',empresa)"
                      class="btn btn-warning btn-sm"
                    >
                      <i class="icon-pencil"></i>
                    </button> &nbsp;
                  </template>
                </td>
                <td v-text="empresa.ruc"></td>
                <td v-text="empresa.nombre"></td>
                <td v-text="empresa.direccion"></td>
                <td>
                  <div v-if="empresa.condicion">
                    <span class="badge badge-success">Activo</span>
                  </div>
                  <div v-else>
                    <span class="badge badge-danger">Desactivado</span>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
          <nav>
            <ul class="pagination">
              <li class="page-item" v-if="pagination.current_page > 1">
                <a
                  class="page-link"
                  href="#"
                  @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)"
                >Ant</a>
              </li>
              <li
                class="page-item"
                v-for="page in pagesNumber"
                :key="page"
                :class="[page == isActived ? 'active' : '']"
              >
                <a
                  class="page-link"
                  href="#"
                  @click.prevent="cambiarPagina(page,buscar,criterio)"
                  v-text="page"
                ></a>
              </li>
              <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                <a
                  class="page-link"
                  href="#"
                  @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)"
                >Sig</a>
              </li>
            </ul>
          </nav>
        </div>
      </div>
      <!-- Fin ejemplo de tabla Listado -->
    </div>
    <!--Inicio del modal agregar/actualizar-->
    <div
      class="modal fade"
      tabindex="-1"
      :class="{'mostrar' : modal}"
      role="dialog"
      aria-labelledby="myModalLabel"
      style="display: none;"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-primary modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" v-text="tituloModal"></h4>
            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            <form action method="post" enctype="multipart/form-data" class="form-horizontal">
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">Ruc</label>
                <div class="col-md-9">
                  <input
                    type="text"
                    v-model="ruc"
                    class="form-control"
                    placeholder="Ruc de empresa"
                  >
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="email-input">Nombre</label>
                <div class="col-md-9">
                  <input
                    type="text"
                    v-model="nombre"
                    class="form-control text-uppercase"
                    placeholder="Nombre de empresa"
                  >
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="email-input">Departamento</label>
                <div class="col-md-9">
                  <select v-model="departamento" class="form-control" required="required">
                    <option disabled value="0">Departamento</option>
                    <option
                      v-for="optDep in fillDepartamentos"
                      v-bind:key="optDep.id"
                      v-bind:value="optDep.id"
                    >{{ optDep.descripcion }}</option>
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="email-input">Provincia</label>
                <div class="col-md-9">
                  <select v-model="provincia" class="form-control" required="required">
                    <option disabled value="0">Provincia</option>
                    <option
                      v-for="optProv in fillProvincias"
                      v-bind:key="optProv.iddepartamento + optProv.id"
                      v-bind:value="optProv.id"
                    >{{ optProv.descripcion }}</option>
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="email-input">Distrito</label>
                <div class="col-md-9">
                  <select v-model="distrito" class="form-control" required="required">
                    <option disabled value="0">Distrito</option>
                    <option
                      v-for="optDis in fillDistritos"
                      v-bind:key="optDis.iddepartamento + optDis.idprovincia + optDis.id"
                      v-bind:value="optDis.id"
                    >{{ optDis.descripcion }}</option>
                  </select>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="email-input">Direccion</label>
                <div class="col-md-9">
                  <input
                    type="text"
                    v-model="direccion"
                    class="form-control text-uppercase"
                    placeholder="Direccion"
                  >
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="email-input">Telefono</label>
                <div class="col-md-9">
                  <input
                    type="text"
                    v-model="telefono"
                    class="form-control text-uppercase"
                    placeholder="Telefono"
                  >
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="email-input">Email</label>
                <div class="col-md-9">
                  <input
                    type="text"
                    v-model="email"
                    class="form-control text-lowercase"
                    placeholder="Email"
                  >
                </div>
              </div>
              <div v-show="errorEmpresa" class="form-group row div-error">
                <div class="text-center text-error">
                  <div v-for="error in errorMostrarMsjEmpresa" :key="error" v-text="error"></div>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
            <button
              type="button"
              v-if="tipoAccion==1"
              class="btn btn-primary"
              @click="registrarEmpresa()"
            >Guardar</button>
            <button
              type="button"
              v-if="tipoAccion==2"
              class="btn btn-primary"
              @click="actualizarEmpresa()"
            >Actualizar</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!--Fin del modal-->
  </main>
</template>
<script>
export default {
  data() {
    return {
      empresa_id: 0,
      ruc: "",
      nombre: "",
      direccion: "",
      telefono: "",
      email: "",
      departamento: 0,
      provincia: 0,
      distrito: 0,
      arrayEmpresa: [],
      fillDepartamentos: [],
      fillProvincias: [],
      fillDistritos: [],
      ubigeo: {
        departamentos: [],
        provincias: [],
        distritos: [],
        allLoaded: false
      },
      modal: 0,
      loadingModal: false,
      tituloModal: "",
      tipoAccion: 0,
      errorEmpresa: 0,
      errorMostrarMsjEmpresa: [],
      pagination: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0
      },
      offset: 3,
      criterio: "ruc",
      buscar: ""
    };
  },
  computed: {
    isActived: function() {
      return this.pagination.current_page;
    },
    //Calcula los elementos de la paginación
    pagesNumber: function() {
      if (!this.pagination.to) {
        return [];
      }

      var from = this.pagination.current_page - this.offset;
      if (from < 1) {
        from = 1;
      }

      var to = from + this.offset * 2;
      if (to >= this.pagination.last_page) {
        to = this.pagination.last_page;
      }

      var pagesArray = [];
      while (from <= to) {
        pagesArray.push(from);
        from++;
      }
      return pagesArray;
    }
  },
  methods: {
    listarEmpresa(page, buscar, criterio) {
      let me = this;
      var url =
        "/empresa?page=" + page + "&buscar=" + buscar + "&criterio=" + criterio;
      axios
        .get(url)
        .then(function(response) {
          var respuesta = response.data;
          me.arrayEmpresa = respuesta.empresas.data;

          me.pagination = respuesta.pagination;
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    actualizarEmpresa() {
      if (this.validarEmpresa()) {
        return;
      }

      let me = this;

      axios
        .put("/empresa/actualizar", {
          ruc: this.ruc,
          nombre: this.nombre,
          direccion: this.direccion,
          iddepartamento: this.departamento,
          idprovincia: this.provincia,
          iddistrito: this.distrito,
          telefono: this.telefono,
          email: this.email,
          id: this.empresa_id
        })
        .then(function(response) {
          me.cerrarModal();
          me.listarEmpresa(1, "", "nombre");
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    validarEmpresa() {
      this.errorEmpresa = 0;
      this.errorMostrarMsjEmpresa = [];

      if (!this.nombre)
        this.errorMostrarMsjEmpresa.push(
          "El nombre de la empresa no puede estar vacío."
        );

      if (this.errorMostrarMsjEmpresa.length) this.errorEmpresa = 1;

      return this.errorEmpresa;
    },
    obtenerUbigeo() {
      let me = this;

      axios
        .get("/departamento/all", {})
        .then(function(response) {
          me.ubigeo.departamentos = response.data.departamentos || [];

          axios
            .get("/provincia/all", {})
            .then(function(response) {
              me.ubigeo.provincias = response.data.provincias || [];

              axios
                .get("/distrito/all", {})
                .then(function(response) {
                  me.ubigeo.distritos = response.data.distritos || [];

                  me.ubigeo.allLoaded = true;
                  me.fillDepartamentos = me.ubigeo.departamentos;
                })
                .catch(function(error) {
                  console.log(error);
                });
            })
            .catch(function(error) {
              console.log(error);
            });
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    cerrarModal() {
      let me = this;

      me.modal = 0;
      me.tituloModal = "";
      me.ruc = "";
      me.nombre = "";
      me.direccion = "";
      me.departamento = 0;
      me.provincia = 0;
      me.distrito = 0;
      me.telefono = "";
      me.email = "";
    },
    abrirModal(modelo, accion, data = []) {
      let me = this;

      if (!me.ubigeo.allLoaded) {
        return;
      }

      switch (modelo) {
        case "empresa": {
          me.loadingModal = true;
          switch (accion) {
            case "actualizar": {
              //console.log(data);
              me.modal = 1;
              me.tituloModal = "Actualizar empresa";
              me.tipoAccion = 2;
              me.empresa_id = data["id"];
              me.ruc = data["ruc"];
              me.nombre = data["nombre"];
              me.direccion = data["direccion"];
              me.departamento = data["iddepartamento"];
              me.provincia = data["idprovincia"];
              me.distrito = data["iddistrito"];
              me.telefono = data["telefono"];
              me.email = data["email"];
              break;
            }
          }
          setTimeout(
            function() {
              me.loadingModal = false;
            }.bind(me),
            500
          );
        }
      }
    }
  },
  watch: {
    departamento: function() {
      let me = this;

      if (me.loadingModal == false) {
        me.provincia = 0;
        me.distrito = 0;
      }

      me.fillProvincias = [];
      me.fillDistritos = [];

      if (me.departamento != 0) {
        me.ubigeo.provincias.map(function(obj, ind) {
          if (me.departamento == obj.iddepartamento) {
            me.fillProvincias.push(obj);
          }
        });
      }
    },
    provincia: function() {
      let me = this;

      if (me.loadingModal == false) {
        me.distrito = 0;
      }

      me.fillDistritos = [];

      if (me.provincia != 0) {
        me.ubigeo.distritos.map(function(obj, ind) {
          if (
            me.departamento == obj.iddepartamento &&
            me.provincia == obj.idprovincia
          ) {
            me.fillDistritos.push(obj);
          }
        });
      }
    }
  },
  mounted() {
    this.obtenerUbigeo();
    this.listarEmpresa(1, this.buscar, this.criterio);
  }
};
</script>
<style>
.modal-content {
  width: 100% !important;
  position: absolute !important;
}
.mostrar {
  display: list-item !important;
  opacity: 1 !important;
  position: absolute !important;
  background-color: #3c29297a !important;
}
.div-error {
  display: flex;
  justify-content: center;
}
.text-error {
  color: red !important;
  font-weight: bold;
}
@media (min-width: 600px) {
  .btnagregar {
    margin-top: 2rem;
  }
}
</style>
