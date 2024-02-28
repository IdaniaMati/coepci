<template>
    <div>
      <div class="col-xxl">
        <div class="card mb-4">
          <br>
          <h3 class="mb-0 text-center fs-4">Historico de Integrantes de COEPCI</h3>
          <div class="card-body">
            <div>
              <ul class="nav nav-tabs" role="tablist">
                <li v-for="(concursoPorAnio, anio) in historico" :key="anio" class="nav-item">
                  <a :id="`tab-${anio}`" :href="`#pane-${anio}`" class="nav-link" :aria-controls="`pane-${anio}`" data-bs-toggle="tab" role="tab">
                    {{ anio }} <!-- Año de concurso -->
                  </a>
                </li>
              </ul>
              <div class="tab-content">
                <div v-for="(concursoPorAno, anio) in historico" :key="anio" :id="`pane-${anio}`" class="tab-pane" role="tabpanel">
                    <!-- <h5 class="mb-3">{{ anio }}</h5> -->
                    <div v-for="(concurso, concursoKey) in concursoPorAno" :key="concursoKey" class="row">
                        <h6 class="mb-3"><strong>{{ concurso.descripcion }}</strong></h6>
                        <div v-for="(grupo, grupoIndex) in concurso.grupos" :key="grupoIndex" class="col-md-6 col-lg-4 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h6 class="card-title">{{ `Grupo ${grupoIndex}` }}</h6>
                                    <ul class="list-group">
                                        <li v-for="(ganadores, ganadorIndex) in grupo" :key="ganadorIndex" class="list-group-item d-flex justify-content-between align-items-center">
                                            <span>{{ ganadores }}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
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
      border: 1px solid #ddd;
      border-radius: 5px;
      padding: 10px;
      margin-bottom: 20px;
    }
    .card {
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
  }

  .card-title {
    color: #B68400;
    font-weight: bold;
  }

  .list-group-item {
    border: 1px solid #ddd;
    margin-bottom: 5px;
  }

  .card-body {
    padding: 20px;
  }

  .fs-4 {
    margin-bottom: 20px;
  }

  .btn-primary {
    background-color: #AB0A3D;
    border: 1px solid #AB0A3D;
  }

  .btn-primary:hover {
    background-color: #440412;
    border: 1px solid #440412;
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
          console.log(response.data);
          this.historico = response.data.historico;
        })
        .catch(error => {
          console.error('Error al obtener historico', error);
        });
    },
  },
};
</script>
