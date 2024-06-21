<template>
    <div class="container">
      <div class="center-container">
        <br>
        <div class="sign-title">
          <h1 class="title-text"><strong>RESULTADOS DE VOTACIONES</strong></h1>
        </div>
      </div>

      <div class="mb-3" v-if="showDependenciaSelect === true">
          <label for="defaultSelect" class="form-label">Seleccione una Institución</label>
          <select id="showDependenciaSelect" v-model="id_depen" @change="cambiarDependencia" class="form-select">
              <option disabled selected>Seleccionar</option>
              <option v-for="dependencia in dependencias" :value="dependencia.id">{{ dependencia.descripcion }}</option>
          </select>
      </div>

            <div>

            <br>
            <div v-if="mensajeNoVotaciones">{{ mensajeNoVotaciones }}</div>
                <div v-if="ganadores.length > 0" class="card mb-4">
                    <div class="table-container">
                        <div class="nav-item d-flex align-items-center">
                            <button class="btn btn-excepcion mb-3" title="Caso Excepcional" @click="nuevo">
                                <i class="bi bi-person-add" style="font-size: 20px;"></i>
                                &nbsp;<p>Excepción</p>
                            </button>
                        </div>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>CURP</th>
                                    <th>Grupo</th>
                                    <th>Cargo</th>
                                    <th>Documento</th>
                                    <th>Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-for="(grupo, index) in ganadores" :key="index">
                                    <tr v-for="(ganador, ganadorIndex) in grupo.ganadores" :key="ganadorIndex">
                                        <td>{{ ganador.id }}</td>
                                        <td>{{ ganador.nombre }}</td>
                                        <td>{{ ganador.curp }}</td>
                                        <td>{{ `Grupo ${grupo.grupo}` }}</td>
                                        <td>{{ descripcionCargo(ganador.id_cargo) }}</td>
                                        <td>{{ ganador.documento }}</td>
                                        <td>
                                            <button  class="btn btn-roles btn-sm"  title="Asignar cargo" @click="detalleGanadores(ganador.id)" :disabled="isGanadorRechazado(ganador.estado)">
                                                <i class="bi bi-person-rolodex" style="font-size: 15px;"></i>
                                            </button> &nbsp;
                                            <button  class="btn btn-edit btn-sm"  title="Aceptar" @click="aprobarGanador(ganador.id)":disabled="isGanadorRechazado(ganador.estado)">
                                                <i class="bi bi-check-circle" style="font-size: 15px;"></i>
                                            </button> &nbsp;
                                            <button  class="btn btn-delete btn-sm"  title="Rechazar" @click="rechazarGanador(ganador.id)":disabled="isGanadorRechazado(ganador.estado)">
                                                <i class="bi bi-x-circle" style="font-size: 15px;"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>


            <!-- Resultados de rondas (1 y 2) del concurso -->
            <br>
            <div v-for="(resultadosRonda, ronda) in resultadosPorRonda" :key="ronda" class="card mb-4">

                    <div class="p-4 cursor-pointer bg-gray-100 hover:bg-gray-200 d-flex justify-content-between align-items-center" @click="toggleCollapse(`ronda-${ronda}`)">
                        <h4 class="pb-1 mb-4">Ronda {{ ronda }}</h4>
                        <i :class="isExpanded(`ronda-${ronda}`) ? 'bi bi-chevron-up' : 'bi bi-chevron-down'"></i>
                    </div>
                    <div v-show="isExpanded(`ronda-${ronda}`)">
                        <div class="content-wrapper">
                            <div class="container-xxl flex-grow-1 container-p-y">
                                <div class="row mb-5">
                                    <div v-for="(grupo, numeroGrupo) in resultadosRonda" :key="numeroGrupo" class="col-md-6 col-lg-4 mb-3">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <h5 class="card-title">Grupo {{ numeroGrupo }}</h5>
                                                <h6 class="card-subtitle text-muted"></h6>
                                                <p class="card-text"></p>
                                                <ul class="list-group card-text">
                                                    <li v-for="votado in grupo" :key="votado.id" class="list-group-item d-flex justify-content-between align-items-center card-text">
                                                        <span>{{ `${votado.numero}. ${votado.nombre} ${votado.apellido_paterno} ${votado.apellido_materno}` }}</span>
                                                        <span :class="{ 'badge': true, 'bg-primeros': grupo.indexOf(votado) < 5, 'bg-segundos': grupo.indexOf(votado) >= 5 }" class="rounded-pill">{{ `${votado.votos} votos` }}</span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p v-if="resultadosRonda.length === 0" class="text-center mt-4">{{ `No hay resultados para la Ronda ${ronda}.` }}</p>
                    </div>
            </div>

             <!-- Inicio Modal Agregar Caso excepcional -->
             <div class="container">
                    <div class="modal fade" id="largeModal" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title"><strong>Empleado</strong></h5>
                                    <button class="btn-close" data-bs-dismiss="modal" @click="cerrarModal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form>
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label>Nombre completo</label>
                                                <input v-model="nombreCompleto" class="form-control" placeholder="Nombre Completo" required/>
                                                <div v-if="!nombreCompleto" class="text-danger">Este campo es obligatorio.</div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label>CURP</label>
                                                    <input v-model="curp" class="form-control" placeholder="Curp" required/>
                                                <div v-if="!curp" class="text-danger">Este campo es obligatorio.</div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label>Grupo</label>
                                                    <select v-model="id_grup" class="form-control" id="grupo" required>
                                                        <option v-for="grupo in grupos" :key="grupo.id" :value="grupo.id">{{ grupo.grupo }}</option>
                                                    </select>
                                                <div v-if="!id_grup" class="text-danger">Este campo es obligatorio.</div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label>Cargo</label>
                                                <select v-model="id_cargo" class="form-control" id="cargo" required>
                                                        <option v-for="cargo in cargos" :key="cargo.id" :value="cargo.id">{{ cargo.descripcion }}</option>
                                                </select>
                                                <div v-if="!cargo" class="text-danger">Este campo es obligatorio.</div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label>Documento</label>
                                                <div class="modal-body">
                                                    <input type="file" @change="handleExcepcionFileUpload" accept=".pdf" class="form-control" id="inputGroupFile03" aria-describedby="inputGroupFileAddon03" aria-label="Upload">
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div class="modal-footer">
                                    <button v-if="bandera === 0" class="btn btn-primary" @click="agregarExcepcion">Guardar</button>
                                    <button class="btn btn-cerrar" @click="cerrarModal" data-bs-dismiss="modal">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Final Modal Agregar Caso excepcional -->

                <!-- Modal asignar cargos -->
                <div class="container">
                    <div class="modal fade" id="modalcargos" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title"><strong>Cargos</strong></h5>
                                    <button class="btn-close" data-bs-dismiss="modal" @click="cerrarModalCargos" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label>Nombre Completo</label>
                                                <input v-model="id_emp" class="form-control" placeholder="Nombre Completo" disabled/>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label>Selecciona el cargo a asignar:</label>
                                                <select v-model="id_cargo" class="form-control" id="cargo" required>
                                                    <option v-for="cargo in cargos" :key="cargo.id" :value="cargo.id">{{ cargo.descripcion }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label>Documento comprobatorio:</label>&nbsp;
                                                    <input type="file" @change="handleFileChange" ref="fileInput" accept=".pdf" class="form-control" id="inputGroupFile04" aria-describedby="inputGroupFileAddon03" aria-label="Upload">
                                             </div>
                                        </div>
                                </div>

                                <div class="modal-footer">
                                    <button v-if="bandera === 1" class="btn btn-primary" @click="editarGanador">Guardar Cambios</button>
                                    <button class="btn btn-cerrar" @click="cerrarModalCargos" data-bs-dismiss="modal">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Fin Modal asignar cargos -->
        </div>
        <div class="d-grid gap-2">
            <button  class="btn btn-edit btn-sm"  style="height: 40px;" title="Publicar Resultados" @click="publicarResultados">
                <strong>PUBLICAR RESULTADOS</strong>
            </button>
        </div>
    </div>
  </template>

  <script>
    import axios from "axios";
    import 'sweetalert2/dist/sweetalert2.min.css';
    import permisos from "../permisos/permisos.vue";

    export default {
      data() {
        return {
            mensajeNoVotaciones: "",
            dependencias: [],
            cargos: [],
            id_depen: null,
            resultadosPorRonda: {
                1: [],
                2: [],
            },
            ganadores: [],
            expandedId: null,
            showDependenciaSelect: false,
            bandera: "",
            lista_permisos:[],
            nombreCompleto: "",
            curp: "",
            id_cargo: "",
            id_grup: "",
            id_emp:"",
            documento: null,
            id_conc: this.idConcursoActual,
            selectedFile: null,
            selectedGanadorId: null,
            excepcionFile: null,
            idGanador: "",
            idGan: ""
        };
      },

      created() {
          this.obtenerDependencias();
          this.obtenerResultados();
          this.obtenerGanadoresV();
          this.cargarDependencias();
          this.obtenerGrupos();
          this.obtenerPermisos();
          this.obtenerCargos();
          this.calcularYGuardarGanadores();
      },

      methods: {

        obtenerPermisos(){
            axios
                .get("/Obtenerpermisos")
                .then((response) => {
                    this.lista_permisos  = response.data;

                })
                .catch((error) => {

                });
        },

        async obtenerGrupos() {
             try {
                 const response = await axios.get('/obtenerGrupos');
                 if (response.data.success) {
                 this.grupos = response.data.grupos;
                 } else {
                 console.error('Error al obtener los grupos');
                 }
             } catch (error) {
                 console.error('Error al obtener los grupos:', error);
             }
        },

        async obtenerCargos() {
             try {
                 const response = await axios.get('/obtenerCargos');
                 if (response.data.success) {
                 this.cargos = response.data.cargos;
                 } else {
                    console.error('Error al obtener los cargos');
                 }
             } catch (error) {
                 console.error('Error al obtener los cargos:', error);
             }
        },

        descripcionCargo(idCargo) {
            const cargo = this.cargos.find(cargo => cargo.id === idCargo);
            return cargo ? cargo.descripcion : 'Sin descripción';
        },

        cargarDependencias() {
            axios.get('/resultadosWithDependencia')
                .then(response => {
                    this.showDependenciaSelect = response.data.showDependenciaSelect;
                    this.userDependencia = response.data.userDependencia;
                    if (!this.showDependenciaSelect && this.userDependencia) {
                        this.id_depen = this.userDependencia;
                        this.obtenerResultados(this.id_depen);
                        this.obtenerGanadoresV(this.id_depen);
                    }
                })
                .catch(error => {
                    console.error('Error al obtener los datos:', error);
                });
        },

        obtenerDependencias(){
          axios.get('/obtenerDependencias')
              .then((response) => {
                  this.dependencias = response.data.user;
              })
              .catch((error) => {
                  console.error(error);
              });
        },

        cambiarDependencia() {
              this.obtenerResultados(this.id_depen);
              this.obtenerGanadoresV(this.id_depen);
        },

        calcularYGuardarGanadores() {
        axios
            .get("/calcular-y-guardar-ganadores")
            .then((response) => {
            })
            .catch((error) => {
            });
        },

         //Metodos para efecto acordeón
        toggleCollapse(id) {
            if (this.expandedId === id) {
                    this.expandedId = null;
            } else {
                    this.expandedId = id;
                }
        },

        isExpanded(id) {
            return this.expandedId === id;
        },

        //   obtenerResultados(idDependencia) {

        //       axios.get(`/obtenerResultados?ronda=1&idDependencia=${idDependencia}`)
        //       .then(response => {
        //           this.resultadosPorRonda[1] = this.agregarNumeracion(response.data);
        //       })
        //       .catch(error => {
        //           if (error.response && error.response.status === 404) {
        //           this.resultadosPorRonda[1] = [];
        //           } else {
        //           console.error('Error al obtener resultados de la ronda 1', error);
        //           }
        //       });

        //       axios.get(`/obtenerResultados?ronda=2&idDependencia=${idDependencia}`)
        //       .then(response => {
        //           this.resultadosPorRonda[2] = this.agregarNumeracion(response.data);
        //       })
        //       .catch(error => {
        //           if (error.response && error.response.status === 404) {
        //           this.resultadosPorRonda[2] = [];
        //           } else {
        //           console.error('Error al obtener resultados de la ronda 2', error);
        //           }
        //       });
        //   },

        obtenerResultados(idDependencia = null) {
            if (!idDependencia) {
                idDependencia = this.id_depen;
            }
            axios.get(`/obtenerResultados?ronda=1&idDependencia=${idDependencia}`)
                .then(response => {
                    this.resultadosPorRonda[1] = this.agregarNumeracion(response.data);
                    this.id_conc = response.data.id_conc; // Guardar id_conc
                })
                .catch(error => {
                    if (error.response && error.response.status === 404) {
                        this.resultadosPorRonda[1] = [];
                    } else {
                        console.error('Error al obtener resultados de la ronda 1', error);
                    }
                });

            axios.get(`/obtenerResultados?ronda=2&idDependencia=${idDependencia}`)
                .then(response => {
                    this.resultadosPorRonda[2] = this.agregarNumeracion(response.data);
                    this.id_conc = response.data.id_conc; // Guardar id_conc
                })
                .catch(error => {
                    if (error.response && error.response.status === 404) {
                        this.resultadosPorRonda[2] = [];
                    } else {
                        console.error('Error al obtener resultados de la ronda 2', error);
                    }
                });
        },

        agregarNumeracion(resultados) {
                  if (typeof resultados !== 'object' || resultados === null) {
                      return {};
                  }

                  let numeradosResultados = {};
                  for (let ronda in resultados) {
                      if (resultados.hasOwnProperty(ronda) && Array.isArray(resultados[ronda])) {
                          numeradosResultados[ronda] = resultados[ronda].map((votado, index) => ({
                              ...votado,
                              numero: index + 1
                          }));
                      } else {
                          numeradosResultados[ronda] = [];
                      }
                  }
                  return numeradosResultados;
        },

        //   obtenerGanadoresV(idDependencia) {
        //       axios.get(`/obtenerGanadoresV?idDependencia=${idDependencia}`)
        //           .then(response => {
        //           this.ganadores = [];

        //           for (let grupo in response.data.ganadores) {
        //               let ganadoresGrupo = response.data.ganadores[grupo].map((ganador, index) => ({
        //               numero: index + 1,
        //               nombre: ganador.id_emp
        //               }));

        //               this.ganadores.push({
        //               grupo: grupo,
        //               ganadores: ganadoresGrupo
        //               });
        //           }

        //           })
        //           .catch(error => {
        //           console.error('Error al obtener ganadores', error);
        //           });
        //   },

        obtenerGanadoresV(idDependencia = null) {
            if (!idDependencia) {
                idDependencia = this.id_depen;
            }
            axios.get(`/obtenerGanadoresV?idDependencia=${idDependencia}`)
                .then(response => {
                    console.log(response.data);
                    this.ganadores = [];
                    this.id_conc = response.data.id_conc;
                    if (response.data.ganadores && Object.keys(response.data.ganadores).length > 0) {
                    for (let grupo in response.data.ganadores) {
                        let ganadoresGrupo = response.data.ganadores[grupo].map /*(ganador => ({ */ ((ganador, index) => ({
                            numero: index + 1,
                            id:ganador.id,
                            nombre: ganador.id_emp,
                            curp: ganador.curp,
                            id_cargo: ganador.id_cargo,
                            documento: ganador.documento,
                            id_conc: ganador.id_conc,
                            estado: ganador.estado
                        }));

                        this.ganadores.push({
                            grupo: grupo,
                            ganadores: ganadoresGrupo
                        });
                    }
                 }
                })
                .catch(error => {
                    console.error('Error al obtener ganadores', error);
                });
        },

        //Metodos agregar Ganador o excepción
        handleExcepcionFileUpload(event) {
            this.excepcionFile = event.target.files[0];
        },

        async agregarExcepcion() {
            if (this.curp.length !== 18) {
                Swal.fire('Error', 'La CURP debe tener exactamente 18 caracteres', 'error');
                return;
            }

            const formData = new FormData();
            //formData.append('id_conc', this.id_conc); // Incluir id_conc
            formData.append('id_emp', this.nombreCompleto);
            formData.append('curp', this.curp.toUpperCase().substring(0, 18));
            formData.append('id_grup', this.id_grup);
            formData.append('id_cargo', this.id_cargo);
            formData.append('documento', this.excepcionFile);

            try {
                const response = await axios.post('/agregarExcepcion', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });

                if (response.data.success) {
                    this.cerrarModal();
                    this.obtenerGanadoresV();
                    Swal.fire('Éxito', response.data.message, 'success');
                } else {
                    Swal.fire('Error', response.data.error, 'error');
                }
            } catch (error) {
                console.error(error);
                Swal.fire('Error', 'No se pudo guardar al ganador.', 'error');
            }
        },

        //Metodos para editar Ganadores
        detalleGanadores(idGan) {

        this.idGanador = idGan;
        this.bandera = 1;
        this.abrirModalCargos();

        axios
            .get("/detalleGanadores/" + idGan)
            .then((response) => {
                const ganador = response.data[0];
                this.id_emp = ganador.id_emp;
                this.id_cargo = ganador.id_cargo;
                this.documento = ganador.documento;
            })
            .catch((error) => {
                console.error(error);
            });
        },

        async editarGanador() {

            const ganador = {
                id: this.idGanador,
                // id_conc: this.id_conc,
                // id_emp: this.id_emp,
                // curp: this.curp,
                // id_grup: this.id_grup,
                id_cargo: this.id_cargo,
                //documento: documentoUrl
            };

            const documentoUrl = await this.handleFileUpload(ganador);
            if (!documentoUrl) {
                return;
            }

            ganador.documento = documentoUrl;

            try {
                const response = await axios.post('/editarGanadores', ganador);

                if (response.data.success) {
                    this.obtenerGanadoresV();
                    Swal.fire('Éxito', response.data.message, 'success');
                } else {
                    Swal.fire('Error', response.data.message, 'error');
                }
            } catch (error) {
                console.error(error);
                Swal.fire('Error', 'Hubo un error al actualizar el ganador.', 'error');
            }
        },

        async handleFileUpload(ganador) {

        console.log('Ganador:', ganador);
        console.log('Ganador ID:', ganador.id);

        const formData = new FormData();
        formData.append('file', this.documento);
        //formData.append('ganador_id', ganador.id);
        formData.append('ganador_id', this.idGanador);

        try {
            const response = await axios.post('/uploadDocument', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            });

            if (response.data.success) {
                return response.data.url;
            } else {
                Swal.fire('Error', response.data.message, 'error');
                return null;
            }
        } catch (error) {
            console.error(error);
            Swal.fire('Error', 'Hubo un error al subir el documento.', 'error');
            return null;
        }
        },

        handleFileChange(event) {
            const file = event.target.files[0];
            this.documento = file; // Guarda el archivo seleccionado en la propiedad documento
        },

        //metodos modal asginar cargos
        abrirModalCargos() {
            $("#modalcargos").modal({ backdrop: "static", keyboard: false });
            $("#modalcargos").modal("toggle");
        },

        cerrarModalCargos() {
            $("#modalcargos").modal("hide");
        },

        //Metodos para publicar resultados

        aprobarGanador(id) {
            axios.post(`/actualizarEstadoGanador/${id}`, { estado: 1 })
                .then(response => {
                    if (response.data.success) {
                            Swal.fire({
                            icon: 'success',
                            title: 'Aprobado',
                            text: 'El ganador ha sido aprobado exitosamente.'
                        });
                        this.obtenerGanadoresV(); // Vuelve a cargar los ganadores
                    } else {
                        console.error('Error al aprobar ganador', error);
                    }
                })
                .catch(error => {
                    console.error('Error al aprobar ganador', error);
                    this.$swal("Error", "Hubo un problema al aprobar al ganador.", "error");
                });
        },

        rechazarGanador(id) {
            axios.post(`/actualizarEstadoGanador/${id}`, { estado: 2 })
                .then(response => {
                    if (response.data.success) {
                            Swal.fire({
                            icon: 'error',
                            title: 'Rechazado',
                            text: 'El ganador ha sido rechazado.'
                        });
                        this.obtenerGanadoresV(); // Vuelve a cargar los ganadores
                    } else {
                        this.$swal("Error", response.data.message, "error");
                    }
                })
                .catch(error => {
                    console.error('Error al rechazar ganador', error);
                    console.error('Error al rechazar ganador', error);
                });
        },

        actualizarEstadoGanador(idGanador, estado) {
            for (let grupo of this.ganadores) {
                for (let ganador of grupo.ganadores) {
                    if (ganador.id === idGanador) {
                        ganador.estado = estado;
                    }
                }
            }
        },

        isGanadorRechazado(estado) {
            return estado === 2;
        },

        //metodos del modal agregar caso excepcional
        nuevo() {
            this.limpiarvar();
            this.bandera = 0;
            this.abrirModal();
        },

        abrirModal() {
            $("#largeModal").modal({ backdrop: "static", keyboard: false });
            $("#largeModal").modal("toggle");
        },

        cerrarModal() {
            $("#largeModal").modal("hide");
        },

        limpiarvar() {
            this.nombreCompleto = null;
            this.curp = null;
            this.id_cargo = null;
            this.id_grup = null;
        },
      },
    };
  </script>

  <style scoped>
  .center-container {
    text-align: center;
}

  .round-container {
    margin-bottom: 10px;
}

  .announcement-box {
    background-color: #050505;
    padding: 20px;
    border-radius: 10px;
}

  .bg-primeros{
    background-color: #ab0a3d;
}

  .bg-segundos{
    background-color: #9c9312;
}
  .bg-gray-100 {
  background-color: #f7f7f7;
}
.bg-gray-200 {
  background-color: #e1e1e1;
}
.custom-input {
      width: 400px;
      height: 100px;
      resize: none;
      white-space: pre-line;
}
  </style>
