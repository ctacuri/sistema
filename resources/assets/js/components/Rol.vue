<template>
  <main class="main">
    <!-- Breadcrumb -->
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="/">Escritorio</a>
      </li>
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
                </select>
                <input
                  type="text"
                  v-model="buscar"
                  @keyup.enter="listarRol(1,buscar,criterio)"
                  class="form-control"
                  placeholder="Texto a buscar"
                >
                <button type="submit" @click="listarRol(1,buscar,criterio)" class="btn btn-primary">
                  <i class="fa fa-search"></i> Buscar
                </button>
              </div>
            </div>
          </div>
          <table class="table table-bordered table-striped table-sm">
            <thead>
              <tr>
                <th>Opciones</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Estado</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="rol in arrayRol" :key="rol.id">
                <td>
                  <template v-if="$parent.granted['ACTUALIZAR_ROLES']">
                    <button
                      type="button"
                      @click="abrirModal('rol','actualizar',rol)"
                      class="btn btn-warning btn-sm"
                    >
                      <i class="icon-pencil"></i>
                    </button> &nbsp;
                    <button
                      type="button"
                      @click="abrirModal('rol','permisos',rol)"
                      class="btn btn-warning btn-sm"
                    >
                      <i class="icon-user-following"></i>
                    </button>
                  </template>
                </td>
                <td v-text="rol.nombre"></td>
                <td v-text="rol.descripcion"></td>
                <td>
                  <div v-if="rol.condicion">
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
                <label class="col-md-3 form-control-label" for="text-input">Nombre (*)</label>
                <div class="col-md-9">
                  <input
                    type="text"
                    v-model="nombre"
                    class="form-control text-uppercase"
                    placeholder="Nombre del rol"
                  >
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="email-input">Descripción</label>
                <div class="col-md-9">
                  <input
                    type="text"
                    v-model="descripcion"
                    class="form-control text-uppercase"
                    placeholder="Descripción del rol"
                  >
                </div>
              </div>
              <div v-show="errorRol" class="form-group row div-error">
                <div class="text-center text-error">
                  <div v-for="error in errorMostrarMsjRol" :key="error" v-text="error"></div>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
            <button type="button" class="btn btn-primary" @click="actualizarRol()">Actualizar</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!--Fin del modal-->
    <!--Inicio del modal permisos-->
    <div
      class="modal fade"
      tabindex="-1"
      :class="{'mostrar' : modalPermisos}"
      role="dialog"
      aria-labelledby="myModalLabel"
      style="display: none;"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-primary modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" v-text="tituloModalPermisos"></h4>
            <button type="button" class="close" @click="cerrarModalPermisos()" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            <form action method="post" enctype="multipart/form-data" class="form-horizontal">
              <div class="form-group row">
                <div class="col-md-12">
                  <template v-if="permisos && permisos.length == 0">
                    <div>
                      <em>Cargando permisos</em>
                      <span class="spinner blue">
                        <span class="bounce1"></span>
                        <span class="bounce2"></span>
                        <span class="bounce3"></span>
                      </span>
                    </div>
                  </template>
                  <template v-else-if="permisos && permisos.length > 0">
                    <div class="checkbox-list-wrapper">
                      <ul class="checkbox-list">
                        <li v-for="permiso in permisos" v-bind:key="permiso.id">
                          <label v-bind:for="permiso.id">
                            <input
                              type="checkbox"
                              v-bind:id="permiso.id"
                              v-bind:value="permiso.id"
                              v-model="checkedPermisos"
                            >
                            <span v-text="permiso.descripcion"></span>
                          </label>
                        </li>
                      </ul>
                    </div>
                  </template>
                  <template v-else-if="permisos == false">
                    <div class="text-center text-error">
                      <p>No existen permisos</p>
                    </div>
                  </template>
                </div>
              </div>
              <div v-show="errorPermisos" class="form-group row div-error">
                <div class="text-center text-error">
                  <div v-for="error in errorMostrarMsjPermisos" :key="error" v-text="error"></div>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              v-bind:disabled="procesandoPermisos"
              class="btn btn-secondary"
              @click="cerrarModalPermisos()"
            >Cerrar</button>
            <button
              v-if="permisos && permisos.length > 0"
              type="button"
              v-bind:disabled="procesandoPermisos"
              class="btn btn-primary"
              @click="actualizarPermisos()"
            >
              <span v-if="!procesandoPermisos">Guardar</span>
              <span v-else class="spinner">
                <span class="bounce1"></span>
                <span class="bounce2"></span>
                <span class="bounce3"></span>
              </span>
            </button>
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
      rol_id: 0,

      nombre: "",
      descripcion: "",
      arrayRol: [],
      modal: 0,
      tituloModal: "",
      errorRol: 0,
      errorMostrarMsjRol: [],

      procesandoPermisos: false,
      permisos: [],
      checkedPermisos: [],
      modalPermisos: 0,
      tituloModalPermisos: "",
      errorPermisos: 0,
      errorMostrarMsjPermisos: [],

      pagination: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0
      },
      offset: 3,
      criterio: "nombre",
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
    listarRol(page, buscar, criterio) {
      let me = this;
      var url =
        "/rol?page=" + page + "&buscar=" + buscar + "&criterio=" + criterio;
      axios
        .get(url)
        .then(function(response) {
          var respuesta = response.data;
          me.arrayRol = respuesta.roles.data;
          me.pagination = respuesta.pagination;
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    cambiarPagina(page, buscar, criterio) {
      let me = this;
      //Actualiza la página actual
      me.pagination.current_page = page;
      //Envia la petición para visualizar la data de esa página
      me.listarRol(page, buscar, criterio);
    },
    actualizarRol() {
      if (this.validarRol()) {
        return;
      }

      let me = this;

      axios
        .put("/rol/actualizar", {
          id: this.rol_id,
          nombre: this.nombre,
          descripcion: this.descripcion
        })
        .then(function(response) {
          me.cerrarModal();
          me.listarRol(1, "", "nombre");
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    validarRol() {
      this.errorRol = 0;
      this.errorMostrarMsjRol = [];

      if (!this.nombre)
        this.errorMostrarMsjRol.push("El nombre del rol no puede estar vacío.");
      if (!this.descripcion)
        this.errorMostrarMsjRol.push("La descripción no puede estar vacía.");

      if (this.errorMostrarMsjRol.length) this.errorRol = 1;

      return this.errorRol;
    },
    actualizarPermisos() {
      let me = this;

      if (me.procesandoPermisos) {
        return;
      }

      me.procesandoPermisos = true;
      axios
        .put("/rol/actualizarPermisosDeRol", {
          id: this.rol_id,
          checked: this.checkedPermisos
        })
        .then(function(response) {
          me.cerrarModalPermisos();
          me.listarRol(1, "", "nombre");

          me.procesandoPermisos = false;
        })
        .catch(function(error) {
          console.log(error);
          me.procesandoPermisos = false;
        });
    },

    cerrarModal() {
      this.modal = 0;
      this.tituloModal = "";
      this.nombre = "";
      this.descripcion = "";
      this.rol_id = 0;
      this.errorRol = 0;
    },
    cerrarModalPermisos() {
      this.modalPermisos = 0;
      this.tituloModalPermisos = "";
      this.permisos = [];
      this.checkedPermisos = [];
      this.rol_id = 0;
      this.errorRolPermisos = 0;
    },
    abrirModal(modelo, accion, data = []) {
      switch (modelo) {
        case "rol": {
          switch (accion) {
            case "actualizar": {
              //console.log(data);
              this.modal = 1;
              this.tituloModal = "Actualizar Rol";
              this.rol_id = data["id"];
              this.nombre = data["nombre"];
              this.descripcion = data["descripcion"];
              break;
            }
            case "permisos": {
              //console.log(data);
              this.modalPermisos = 1;
              this.tituloModalPermisos =
                'Permisos del rol "' + data["nombre"] + '"';
              this.rol_id = data["id"];

              let me = this;
              axios
                .get("/rol/obtenerPermisosDeRol", {
                  params: {
                    id: me.rol_id
                  }
                })
                .then(function(response) {
                  console.log(response.data);
                  me.permisos = response.data.permisos;
                })
                .catch(function(error) {
                  console.log(error);
                })
                .then(function() {
                  if (me.permisos && me.permisos.length > 0) {
                    var initialChecked = me.permisos.filter(function(o) {
                      return o.valor == 1;
                    });
                    me.checkedPermisos = initialChecked.map(function(o) {
                      return o.id;
                    });
                    console.log(me.checkedPermisos);
                  } else {
                    me.permisos = false;
                  }
                });
              break;
            }
          }
        }
      }
    }
  },
  mounted() {
    this.listarRol(1, this.buscar, this.criterio);
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
</style>
