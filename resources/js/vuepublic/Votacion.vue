<template>
    <div>
      <div class="col-xxl">
        <div class="card mb-4">

            <div class="card-header d-flex justify-content-center align-items-center">
                <h3 class="mb-0 text-center fs-4">Formulario de votación</h3>
            </div>

          <div class="card-body">
            <form>
              <div class="col-xxl">

                <div class="card mb-4" v-for="(grupo, index) in grupos" :key="index">
                  <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Grupo {{ grupo.numero }}</h5>
                  </div>
                  <div class="card-body col-xxl">
                    <div class="col-sm-10" v-for="i in grupo.numCandidatos">
                      <div class="row mb-3">
                        <label class="col-sm-2 col-form-label" :for="`candidato-${grupo.numero}-${i}`">Candidato {{ i }}</label>

                        <div class="col-sm-10">
                          <select :id="`candidato-${grupo.numero}-${i}`" class="form-select" aria-label="Default select example" v-model="votos[grupo.numero - 1][i - 1]">
                            <option disabled selected>Seleccionar</option>
                            <option v-for="opcion in grupo.opciones" :value="opcion.id">
                                {{ opcion.nombre+' '+opcion.apellido_paterno+' '+opcion.apellido_materno }}
                            </option>
                          </select>
                          <div v-if="!votos[grupo.numero - 1][i - 1]" class="text-danger">Este campo es obligatorio.</div>
                        </div>

                      </div>
                    </div>
                  </div>
                </div>

              </div>
              <div class="row mb-3">
                <label class="col-sm-2 col-form-label"></label>
                <div class="col-sm-10">
                  <button type="button" class="btn btn-primary" @click="enviarVotacion">Enviar votación</button> &nbsp;&nbsp;&nbsp;&nbsp;
                  <button type="button" class="btn btn-primary" @click="limpiarCampos">Limpiar campos</button>
                </div>
                <div class="card-header d-flex align-items-center justify-content-between">
                    <button type="button" class="btn btn-secondary ms-auto" @click="regresar" >Regresar</button>
                </div>
              </div>
            </form>
          </div>

        </div>
      </div>
    </div>
  </template>

  <script>
  import axios from "axios";
  import Swal from 'sweetalert2'
  import 'sweetalert2/dist/sweetalert2.min.css'

  export default {
    data() {
        return {
            grupos: [],
            votos: [],
            idUsuarioAutenticado: null,
        };
    },

    created() {
    const urlParams = new URLSearchParams(window.location.search);
    const ronda = urlParams.get('ronda');

    if (ronda === '2') {
        axios.get('/obtenerSegundaFechaConcurso')
            .then((response) => {
                const fechaSegunda = response.data.fechaSegundo;

                const fechaActual = new Date();
                if (new Date(fechaSegunda) <= fechaActual) {
                    this.obtenerOpcionesVotacion();
                    this.obtenerIdUsuarioAutenticado();
                } else {
                    Swal.fire({
                        icon: 'info',
                        title: 'Fecha aún no habilitada',
                        text: 'La votación de la segunda ronda aún no está habilitada.',
                    }).then(() => {
                        window.location.href = '/Principal';
                    });
                }
            })
            .catch((error) => {
                console.error(error);
            });
    } else {
        this.obtenerOpcionesVotacion();
        this.obtenerIdUsuarioAutenticado();
    }
},

    methods: {

        regresar() {
            window.location.href = '/Principal';
        },

        limpiarCampos() {
        this.votos = this.grupos.map((grupo) => Array.from({ length: grupo.numCandidatos }, () => null));
        },

        obtenerOpcionesVotacion() {
        axios.get("/obtenerOpcionesVotacion")
            .then((response) => {
                this.grupos = response.data.reduce((accumulator, opcion) => {
                    const existingGroup = accumulator.find((group) => group.numero === opcion.id_grup);

                    if (existingGroup) {
                    existingGroup.opciones.push(opcion);
                    existingGroup.numCandidatos = Math.max(existingGroup.numCandidatos, 2); // # de candidatos base

                    } else {
                        accumulator.push({
                            numero: opcion.id_grup,
                            numCandidatos: opcion.id_grup === 3 /* grupo */ ? 3 : 2, // # de candidatos
                            opciones: [opcion],
                        });
                    }

                    return accumulator;
                },
                    []);

                this.votos = this.grupos.map((grupo) => Array.from({ length: grupo.numCandidatos }, () => null));
            })

            .catch((error) => {
                console.error('Error al obtener opciones de votación', error);
            });
        },

        obtenerIdUsuarioAutenticado() {
        axios.get("/obtenerIdUsuarioAutenticado")
            .then((response) => {
            this.idUsuarioAutenticado = response.data.id;
            })
            .catch((error) => {
            console.error('Error al obtener el ID del usuario autenticado', error);
            });
        },

        enviarVotacion() {
            this.obtenerIdUsuarioAutenticado();

            const urlParams = new URLSearchParams(window.location.search);
            const ronda = urlParams.get('ronda');

            const candidatosPorGrupo = new Set();

            for (let i = 0; i < this.grupos.length; i++) {
                for (let j = 0; j < this.votos[i].length; j++) {
                const candidatoId = this.votos[i][j];

                if (candidatoId === null) {
                    Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Debes seleccionar un candidato en cada grupo.',
                    });
                    return;
                }

                if (candidatosPorGrupo.has(candidatoId)) {
                    Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No puedes seleccionar al mismo candidato en un grupo.',
                    });
                    return;
                }
                    candidatosPorGrupo.add(candidatoId);
                }
            }


            const promises = [];

            for (let i = 0; i < this.grupos.length; i++) {
                for (let j = 0; j < this.votos[i].length; j++) {
                const votanteId = this.idUsuarioAutenticado;
                const candidatoId = this.votos[i][j];
                const grupoId = 1;
                const concursoId = 4;


                promises.push(
                    axios.post('/enviarVotacion', {
                    votante_id: votanteId,
                    candidato_id: candidatoId,
                    grupo_id: grupoId,
                    concurso_id: concursoId,
                    ronda: ronda,
                    })
                );
                }
            }

            Promise.all(promises)
            .then(responses => {
                console.log('Votos enviados con éxito', responses);

                Swal.fire({
                    icon: 'success',
                    title: 'Voto registrado',
                    text: 'Se han registrado su voto exitosamente',
                    timer: 2000,
                    showConfirmButton: false
                });

                setTimeout(() => {
                    this.limpiarCampos();
                    window.location.href = '/FinVotacion';
                }, 2000);
            })
            .catch(error => {
                console.error('Error al enviar los votos', error);
            });
        },
    },
  };


  </script>

  <style scoped>

  </style>
