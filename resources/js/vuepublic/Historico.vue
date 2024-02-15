<template>
    <div>
      <div class="col-xxl">
        <div class="card mb-4"> <br>
          <h3 class="mb-0 text-center fs-4">Historico de Integrantes de COEPCI</h3>
          <div class="card-body">
            <div v-for="(concurso, concursoNombre) in historico" :key="concursoNombre" class="concurso-card">
              <h4>{{ concursoNombre }}</h4>
              <div class="row">
                <div v-for="(grupos, grupoIndex) in concurso" :key="grupoIndex" class="col-md-6 col-lg-4 mb-3">
                  <div class="card h-100">
                    <div class="card-body">
                      <h5 class="card-title">{{ `Grupo ${grupoIndex}` }}</h5>
                      <ul class="list-group">
                        <li v-for="(ganador, ganadorIndex) in grupos" :key="ganadorIndex" class="list-group-item d-flex justify-content-between align-items-center">
                          <span>{{ ganador }}</span>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row mb-5">
            <label class="col-sm-1 col-form-label"></label>
            <div class="col-sm-10 d-flex justify-content-center">
              <button type="button" class="btn btn-primary mx-auto fs-5" @click="regresar">Regresar</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>

  <style>
  .concurso-card {
    border: 1px solid #ddd; /* Añade un borde ligero */
    border-radius: 5px; /* Añade bordes redondeados */
    padding: 10px; /* Añade un espacio interno */
    margin-bottom: 20px; /* Añade espacio entre los concursos */
  }
  </style>

  <script>
  import axios from 'axios';

  export default {
    data() {
      return {
        historico: {},
      };
    },

    created() {
      this.obtenerHistorico();
    },

    methods: {
      regresar() {
        window.location.href = '/nominaciones';
      },

      obtenerHistorico() {
        axios.get('/obtenerHistorico')
          .then(response => {
            this.historico = response.data.historico;
          })
          .catch(error => {
            console.error('Error al obtener historico', error);
          });
      },
    },
  };
  </script>
