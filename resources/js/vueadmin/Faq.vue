<template>
    <div>
      <div class="text-center mb-4">
        <h2><strong></strong></h2>
      </div>

      <div class="card-container">
        <div class="card">
          <div class="nav-item d-flex align-items-center">
            <!-- v-if="hab_permisos()" Modificar permisos al finalizar -->
            <h5 class="card-header"><strong>Importar Manual</strong></h5>
            <input type="file" @change="handleFileUpload" accept=".pdf" class="form-control" id="inputGroupFile03" aria-describedby="inputGroupFileAddon03" aria-label="Upload">
            <button class="btn btn-upload" title="Subir Formato" @click="importarManuales">
              <i class="bi bi-file-arrow-up" style="font-size: 20px;"></i>
            </button>
          </div>
        </div>
      </div>

      <br>

      <div class="card-container">
        <div class="card">
          <div class="table-container">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Nombre de manual</th>
                  <th>Fecha de actualización</th>
                  <th>Opciones</th>
                  <!-- v-if="hab_permisos()" Modificar permisos al finalizar -->
                </tr>
              </thead>
              <tbody>
                <tr>
                  <!-- v-for="evento in paginatedEventos" :key="evento.id" -->
                  <td></td>
                  <!-- {{ evento.nombre }} -->
                  <th></th>
                  <td>
                    <!-- v-if="hab_permisos()" Modificar permisos al finalizar -->
                    <button class="btn btn-delete btn-sm" title="Eliminar" @click="eliminarArchivo(id)">
                      <!-- v-if="hab_permisos()" Modificar permisos al finalizar -->
                      <i class="bi bi-trash3-fill" style="font-size: 15px;"></i>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <br>
          <!-- Agregamos el paginador -->
          <div class="row justify-content-center">
            <div class="col-md-auto">
              <button @click="paginaAnterior" :disabled="pagina === 1" class="btn btn-primary mr-2">
                Anterior
              </button>
            </div>
            <div class="col-md-auto">
              <ul class="pagination">
                <li v-for="numero in totalPaginas" :key="numero" class="page-item" :class="{ active: numero === pagina }">
                  <a class="page-link" @click="cambiarPagina(numero)">{{ numero }}</a>
                </li>
              </ul>
            </div>
            <div class="col-md-auto">
              <button @click="paginaSiguiente" :disabled="pagina === totalPaginas" class="btn btn-primary ml-2">
                Siguiente
              </button>
            </div>
          </div>
          <!-- Fin del paginador -->
          <br>
        </div>
      </div>
    </div>
  </template>

  <style>
    body.modal-open .modal-backdrop {
      opacity: 0.5;
    }
    .custom-input {
      width: 400px;
      height: 100px;
      resize: none;
      white-space: pre-line;
    }
    .swal2-container.swal2-backdrop-show, .swal2-container.swal2-noanimation {
      z-index: 11000;
    }
  </style>

  <script>
    import permisos from "../permisos/permisos.vue";
    import axios from 'axios';

    export default {
        components: {
            permisos,
        },
        extends: permisos,

        data() {
            return {
                pagina: 1,
                totalPaginas: 0,
                registrosPorPagina: 5,
                bandera: "",
                id_depen: "",
                ideve: "",
                lista_permisos: [],
                eventos: [],
                dependencias: [],
                archivo: null,
                descripcion: null,
            };
        },

        mounted() {
            this.calcularTotalPaginas();
            this.obtenerPermisos();
            this.obtenerDependencias();
        },

        computed: {
            paginatedEventos() {
                const startIndex = (this.pagina - 1) * this.registrosPorPagina;
                const endIndex = startIndex + this.registrosPorPagina;
                return this.eventos.slice(startIndex, endIndex);
            },
        },

        methods: {
            async importarManuales() {
                const formData = new FormData();
                formData.append('file', this.archivo);

                try {
                    const response = await axios.post('/importar-manual', formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    });
                    console.log(response.data.message);
                } catch (error) {
                    console.error('Hubo un error al subir el archivo:', error);
                }
            },

        obtenerPermisos() {
            axios
            .get("/Obtenerpermisos")
            .then((response) => {
                this.lista_permisos = response.data;
            })
            .catch((error) => {
                console.error(error);
            });
        },

        handleFileUpload(event) {
            this.archivo = event.target.files[0];
        },

        obtenerEvento() {
            axios.get('/obtenerEvento')
            .then((response) => {
                if (response.data.eventos) {
                this.eventos = response.data.eventos;
                this.calcularTotalPaginas();
                } else {
                console.log(response.data.message);
                }
            })
            .catch((error) => {
                console.error(error);
            });
        },

        obtenerDependencias() {
            axios.get('/obtenerDependencias')
            .then((response) => {
                this.dependencias = response.data.user;
            })
            .catch((error) => {
                console.error(error);
            });
        },

        descripcionDepen(id_depen) {
            const dependencia = this.dependencias.find(dep => dep.id === id_depen)
            return dependencia ? dependencia.descripcion : 'Sin descripción';
        },

        async eliminarArchivo() {
            // Implementar la lógica para eliminar archivo
        },

        abrirModal() {
            $("#largeModal").modal({ backdrop: "static", keyboard: false });
            $("#largeModal").modal("toggle");
        },

        cerrarModal() {
            $("#largeModal").modal("hide");
        },

        limpiarvar() {
            this.descripcion = null;
        },

        calcularTotalPaginas() {
            this.totalPaginas = Math.ceil(this.eventos.length / this.registrosPorPagina);
        },

        paginaAnterior() {
            if (this.pagina > 1) {
            this.pagina--;
            }
        },

        paginaSiguiente() {
            if (this.pagina < this.totalPaginas) {
            this.pagina++;
            }
        },

        cambiarPagina(numero) {
            this.pagina = numero;
        },
        }
    };
  </script>
