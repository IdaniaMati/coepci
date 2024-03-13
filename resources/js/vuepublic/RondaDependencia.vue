<template>
    <div class="container">
      <div class="center-container">
        <br>
        <div class="sign-title">
          <h1 class="title-text">RESULTADOS</h1>
        </div>
      </div>
  
      <div class="mb-3">
      </div>
  
      <!--Resultado final del concurso-->
      <br>
      <div v-if="mensajeNoVotaciones">{{ mensajeNoVotaciones }}</div>
      <div v-if="ganadores.length > 0" class="card">
          <div class="card-body">
              <h4 class="pb-1 mb-4">Ganadores del Concurso</h4>
              <div class="row">
              <div v-for="(grupo, index) in ganadores" :key="index" class="col-md-4 mb-3">
                  <h5>{{ `Grupo ${grupo.grupo}` }}</h5>
                  <ul class="list-group">
                  <li v-for="(ganador, ganadorIndex) in grupo.ganadores" :key="ganadorIndex" class="list-group-item d-flex justify-content-between align-items-center">
                      <span>{{ `${ganador.numero}. ${ganador.nombre}` }}</span>
                  </li>
                  </ul>
              </div>
              </div>
          </div>
      </div>
  
      <!--Resultados de rondas (1 y 2) del concurso-->
      <br>
      <div v-for="(resultadosRonda, ronda) in resultadosPorRonda" :key="ronda" class="card mb-4">
          <div class="card-body">
              <div class="content-wrapper">
                  <div class="container-xxl flex-grow-1 container-p-y">
                      <h4 class="pb-1 mb-4">Ronda {{ ronda }}</h4>
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
          </div>
          <p v-if="resultadosRonda.length === 0" class="text-center mt-4">{{ `No hay resultados para la Ronda ${ronda}.` }}</p>
      </div>
  
      <div class="row mb-5">
              <label class="col-sm-1 col-form-label"></label>
              <div class="col-sm-10 d-flex justify-content-center">
                <button type="button" class="btn btn-primary mx-auto fs-5" @click="regresar">Regresar</button>
              </div>
            </div>
  
    </div>
  </template>
  
  <script>
    import axios from "axios";
    import Swal from 'sweetalert2';
    import 'sweetalert2/dist/sweetalert2.min.css';
  
    export default {
      data() {
        return {
          mensajeNoVotaciones: "",
          dependencias: [],
          id_depen: null,
          resultadosPorRonda: {
            1: [],
            2: [],
          },
          ganadores: [],
        };
      },
  
      created() {
        this.obtenerDependecias();
        this.obtenerDependecias();
        this.cambiarDependencia(); 

        // Obtener el ID de la dependencia de la URL
        const urlParams = new URLSearchParams(window.location.search);
        const idDependencia = urlParams.get('idDependencia');

        if (idDependencia) {
            // Si hay un ID de dependencia en la URL, cargar automáticamente los datos
            this.id_depen = idDependencia;
            this.obtenerResultados(idDependencia);
            this.obtenerGanadoresV(idDependencia);
        } else {
            // Si no hay un ID de dependencia en la URL, cargar con la dependencia seleccionada
            this.obtenerResultados();
            this.obtenerGanadoresV();
        }
      },
  
      methods: {
  
          regresar() {
              window.location.href = '/nominaciones'+ this.dependencia;
          },
  
          obtenerDependecias(){
          axios.get('/obtenerDependencias')
              .then((response) => {
                  this.dependencias = response.data.user;
              })
              .catch((error) => {
                  console.error(error);
              });
          },
  
          cambiarDependencia() {
               // Obtener el ID de la dependencia de la URL
            const urlParams = new URLSearchParams(window.location.search);
            const idDependencia = urlParams.get('idDependencia');
            // Resto del código ...

            // Si hay un ID de dependencia en la URL, cargar automáticamente los datos
            if (idDependencia) {
                this.id_depen = idDependencia;
                this.obtenerResultados(idDependencia);
                this.obtenerGanadoresV(idDependencia);

            } else {
                // Si no hay un ID de dependencia en la URL, cargar con la dependencia seleccionada
                this.obtenerResultados();
                this.obtenerGanadoresV();

            }
          },
  
          obtenerResultados(idDependencia) {
  
              axios.get(`/obtenerResultados?ronda=1&idDependencia=${idDependencia}`)
              .then(response => {
                  this.resultadosPorRonda[1] = this.agregarNumeracion(response.data);
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
  
          /* calcularYGuardarGanadores() {
              axios
                  .get("/calcular-y-guardar-ganadores")
                  .then((response) => {
                  console.log(response.data.message);
                  })
                  .catch((error) => {
                  console.error("Error al calcular y guardar ganadores", error);
                  });
              }, */
  
          obtenerGanadoresV(idDependencia) {
              axios.get(`/obtenerGanadoresV?idDependencia=${idDependencia}`)
                  .then(response => {
                  this.ganadores = [];
  
                  /* if (response.data.message) {
                      this.mensajeNoVotaciones = "No hay resultados para la Ronda 1.";
                  return;
                  } */
  
                  for (let grupo in response.data.ganadores) {
                      let ganadoresGrupo = response.data.ganadores[grupo].map((ganador, index) => ({
                      numero: index + 1,
                      nombre: ganador.id_emp
                      }));
  
                      this.ganadores.push({
                      grupo: grupo,
                      ganadores: ganadoresGrupo
                      });
                  }
  
                  /* this.mensajeNoVotaciones = ""; */
                  })
                  .catch(error => {
                  console.error('Error al obtener ganadores', error);
                  });
              },
          },
    };
  
  </script>
  
  
  <style scoped>
  .container {}
  
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
  
  </style>