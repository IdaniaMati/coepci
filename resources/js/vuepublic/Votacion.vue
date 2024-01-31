<template>
    <div>
      <div class="col-xxl">
        <div class="card mb-4">

          <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="mb-0">Formulario de votación</h5>
            <button @click="cerrarSesion" class="btn btn-secondary">Cerrar Sesión</button>
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
                  <button type="button" class="btn btn-primary" @click="limpiarvariables">Limpiar campos</button>
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
        };
    },

    created() {
      this.obtenerOpcionesVotacion();
    },

    methods: {

      cerrarSesion() {
        axios.post("/CerrarSesion").then(() => {

          window.location.href = '/';

            }).catch((error) => {
                console.error(error);
            });
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

      enviarVotacion() {
        console.log("Votos:", this.votos);
      },

    },
  };


  </script>

  <style scoped>

  </style>
